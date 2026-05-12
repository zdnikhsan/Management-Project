<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the tasks for a specific project.
     */
    public function index(Project $project)
    {
        // Security check: Only project participants (leader or members) or admin can view
        if (!$this->isProjectParticipant($project)) {
            abort(403, 'Unauthorized access to this project.');
        }

        $tasks = $project->tasks()->with('user')->get();
        
        $todo = $tasks->where('status', 'todo');
        $doing = $tasks->where('status', 'doing');
        $review = $tasks->where('status', 'review');
        $done = $tasks->where('status', 'done');

        return view('tasks.index', compact('project', 'todo', 'doing', 'review', 'done'));
    }

    /**
     * Display the specified task with comments.
     */
    public function show(Project $project, Task $task)
    {
        if (!$this->isProjectParticipant($project)) {
            abort(403);
        }

        $task->load('comments.user', 'user');

        return view('tasks.show', compact('project', 'task'));
    }

    /**
     * Show the form for creating a new task.
     */
    public function create(Project $project)
    {
        // Only leader or admin can create tasks
        if (!$this->isProjectLeader($project)) {
            abort(403, 'Only project leaders can create tasks.');
        }

        $members = $project->members;
        return view('tasks.create', compact('project', 'members'));
    }

    /**
     * Store a newly created task.
     */
    public function store(Request $request, Project $project)
    {
        if (!$this->isProjectLeader($project)) {
            abort(403);
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:todo,doing,done',
            'due_date' => 'nullable|date',
        ]);

        // Check if assigned user is actually a member of the project
        if (!$project->members()->where('user_id', $request->user_id)->exists()) {
            return back()->with('error', 'The assigned user must be a member of this project.');
        }

        $task = $project->tasks()->create($request->all());

        \App\Models\Activity::create([
            'user_id' => Auth::id(),
            'project_id' => $project->id,
            'action' => 'Created task: ' . $task->title,
            'description' => 'A new task was added to project ' . $project->name,
        ]);

        return redirect()->route('projects.tasks.index', $project)->with('success', 'Task created successfully.');
    }

    /**
     * Update the task status or details.
     */
    public function update(Request $request, Project $project, Task $task)
    {
        // Only assignee or leader can update
        if ($task->user_id !== Auth::id() && !$this->isProjectLeader($project)) {
            abort(403, 'You are not authorized to update this task.');
        }

        if ($request->has('status')) {
            $request->validate(['status' => 'required|in:todo,doing,done,review']);
            
            $newStatus = $request->status;
            $message = 'Task status updated to ' . $newStatus;

            // Intercept: If Staff marks as 'done', it goes to 'review' instead
            if ($newStatus === 'done' && !Auth::user()->hasAnyRole(['admin', 'leader'])) {
                $newStatus = 'review';
                $message = 'Task submitted for review.';
            }

            $task->update(['status' => $newStatus]);

            \App\Models\Activity::create([
                'user_id' => Auth::id(),
                'project_id' => $project->id,
                'action' => 'Updated task: ' . $task->title,
                'description' => 'Task status changed to ' . $newStatus,
            ]);

            return back()->with('success', $message);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
            'due_date' => 'nullable|date',
        ]);

        $task->update($request->all());

        return redirect()->route('projects.tasks.index', $project)->with('success', 'Task updated successfully.');
    }

    /**
     * Approve a task (Leader/Admin only).
     */
    public function approve(Project $project, Task $task)
    {
        if (!$this->isProjectLeader($project)) {
            abort(403);
        }

        $task->update(['status' => 'done']);

        \App\Models\Activity::create([
            'user_id' => Auth::id(),
            'project_id' => $project->id,
            'action' => 'Approved task: ' . $task->title,
            'description' => 'Leader approved the completion of task ' . $task->title,
        ]);

        return back()->with('success', 'Task approved and marked as done.');
    }

    /**
     * Reject a task (Leader/Admin only).
     */
    public function reject(Project $project, Task $task)
    {
        if (!$this->isProjectLeader($project)) {
            abort(403);
        }

        $task->update(['status' => 'doing']);

        \App\Models\Activity::create([
            'user_id' => Auth::id(),
            'project_id' => $project->id,
            'action' => 'Rejected task: ' . $task->title,
            'description' => 'Leader rejected task ' . $task->title . '. Task returned to Doing.',
        ]);

        return back()->with('success', 'Task rejected and returned to doing.');
    }

    /**
     * Remove the task.
     */
    public function destroy(Project $project, Task $task)
    {
        if (!$this->isProjectLeader($project)) {
            abort(403);
        }

        $task->delete();

        return redirect()->route('projects.tasks.index', $project)->with('success', 'Task deleted successfully.');
    }

    // Helper functions
    private function isProjectParticipant(Project $project)
    {
        $user = Auth::user();
        return $user->hasRole('admin') || 
               $project->leader_id === $user->id || 
               $project->members()->where('user_id', $user->id)->exists();
    }

    private function isProjectLeader(Project $project)
    {
        $user = Auth::user();
        return $user->hasRole('admin') || $project->leader_id === $user->id;
    }
}
