<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\LoginHistory;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            return $this->adminDashboard();
        } elseif ($user->hasRole('leader')) {
            return $this->leaderDashboard($user);
        } else {
            return $this->staffDashboard($user);
        }
    }

    private function adminDashboard()
    {
        $data = [
            'totalUsers' => User::count(),
            'totalProjects' => Project::count(),
            'totalTasks' => Task::count(),
            'recentUsers' => User::latest()->take(5)->get(),
            'roleDistribution' => [
                'Admin' => User::role('admin')->count(),
                'Leader' => User::role('leader')->count(),
                'Staff' => User::role('staff')->count(),
            ],
            'recentActions' => Activity::with(['user', 'project'])->latest()->take(10)->get(),
        ];

        return view('dashboard.admin', $data);
    }

    private function leaderDashboard($user)
    {
        // Active Projects only (same logic as Project index)
        $managedProjects = Project::where('leader_id', $user->id)
            ->where(function($q) {
                $q->whereDoesntHave('tasks')
                  ->orWhereHas('tasks', function($sq) {
                      $sq->whereIn('status', ['todo', 'doing', 'review']);
                  });
            })
            ->withCount('tasks')->get();

        $allProjectsCount = Project::where('leader_id', $user->id)->count();
        
        $teamMembersCount = User::whereHas('joinedProjects', function($q) use ($user) {
            $q->where('leader_id', $user->id);
        })->count();

        $urgentTasks = Task::whereHas('project', function($q) use ($user) {
            $q->where('leader_id', $user->id);
        })->where('status', '!=', 'done')
          ->whereNotNull('due_date')
          ->where('due_date', '<=', now()->addDays(3))
          ->get();

        $teamWorkload = User::role('staff')
            ->whereHas('joinedProjects', function($q) use ($user) {
                $q->where('leader_id', $user->id);
            })
            ->withCount(['tasks' => function($q) {
                // Count tasks that are not done (todo, doing, review)
                $q->whereIn('status', ['todo', 'doing', 'review']);
            }])
            ->get();

        $tasksToReview = Task::whereHas('project', function($q) use ($user) {
            $q->where('leader_id', $user->id);
        })->where('status', 'review')->with(['project', 'user'])->get();

        // Activity Feed: Only related to projects they lead or are members of
        $projectIds = Project::where('leader_id', $user->id)
            ->pluck('id')
            ->merge($user->joinedProjects->pluck('id'))
            ->unique();

        $activityFeed = Activity::whereIn('project_id', $projectIds)
            ->with(['user', 'project'])
            ->latest()
            ->take(10)
            ->get();

        $data = [
            'managedProjects' => $managedProjects,
            'allProjectsCount' => $allProjectsCount,
            'teamMembersCount' => $teamMembersCount,
            'urgentTasks' => $urgentTasks,
            'teamWorkload' => $teamWorkload,
            'tasksToReview' => $tasksToReview,
            'activityFeed' => $activityFeed,
        ];

        return view('dashboard.leader', $data);
    }

    private function staffDashboard($user)
    {
        $assignedTasks = Task::where('user_id', $user->id)->count();
        $dueToday = Task::where('user_id', $user->id)
            ->where('status', '!=', 'done')
            ->whereDate('due_date', now())
            ->count();
        $dueTomorrow = Task::where('user_id', $user->id)
            ->where('status', '!=', 'done')
            ->whereDate('due_date', now()->addDay())
            ->count();

        $myProjects = $user->joinedProjects()->withCount('tasks')->get();

        $upcomingDeadlines = Task::where('user_id', $user->id)
            ->where('status', '!=', 'done')
            ->whereBetween('due_date', [now(), now()->addDays(7)])
            ->orderBy('due_date')
            ->get();

        $activityFeed = Activity::whereIn('project_id', $user->joinedProjects->pluck('id'))
            ->with(['user', 'project'])
            ->latest()
            ->take(10)
            ->get();

        $pendingReviewCount = Task::where('user_id', $user->id)
            ->where('status', 'review')
            ->count();

        // Heatmap Data: Task completions in last 30 days
        $completions = Task::where('user_id', $user->id)
            ->where('status', 'done')
            ->where('updated_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(updated_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->pluck('count', 'date');

        $data = [
            'assignedTasksCount' => $assignedTasks,
            'dueTodayCount' => $dueToday,
            'dueTomorrowCount' => $dueTomorrow,
            'pendingReviewCount' => $pendingReviewCount,
            'myProjects' => $myProjects,
            'upcomingDeadlines' => $upcomingDeadlines,
            'activityFeed' => $activityFeed,
            'completions' => $completions,
        ];

        return view('dashboard.staff', $data);
    }
}
