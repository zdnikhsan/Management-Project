<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $query = Project::with('leader')->withCount(['members', 'tasks']);
        
        if ($user->hasRole('admin')) {
            // Admin sees all active
        } elseif ($user->hasRole('leader')) {
            $query->where('leader_id', $user->id);
        } else {
            $query = $user->joinedProjects()->with('leader')->withCount(['members', 'tasks']);
        }

        // Filter: Only projects with todo/doing/review tasks OR projects with no tasks at all
        $projects = $query->where(function($q) {
            $q->whereDoesntHave('tasks')
              ->orWhereHas('tasks', function($sq) {
                  $sq->whereIn('status', ['todo', 'doing', 'review']);
              });
        })->get();

        $isHistory = false;
        return view('projects.index', compact('projects', 'isHistory'));
    }

    /**
     * Display a listing of completed projects (all tasks done).
     */
    public function history()
    {
        $user = Auth::user();
        $query = Project::with('leader')->withCount(['members', 'tasks']);
        
        if ($user->hasRole('admin')) {
            // Admin sees all history
        } elseif ($user->hasRole('leader')) {
            $query->where('leader_id', $user->id);
        } else {
            $query = $user->joinedProjects()->with('leader')->withCount(['members', 'tasks']);
        }

        // Filter: Projects that HAVE tasks AND all of them are 'done'
        $projects = $query->whereHas('tasks')
            ->whereDoesntHave('tasks', function($sq) {
                $sq->whereIn('status', ['todo', 'doing', 'review']);
            })->get();

        $isHistory = true;
        return view('projects.index', compact('projects', 'isHistory'));
    }

    public function show(Project $project)
    {
        // Redirect to the task board for this project
        return redirect()->route('projects.tasks.index', $project);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Auth::user()->hasAnyRole(['admin', 'leader'])) {
            abort(403, 'Hanya Admin atau Leader yang bisa membuat project.');
        }
        return view('projects.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::user()->hasAnyRole(['admin', 'leader'])) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
        ]);

        $project = Project::create([
            'name' => $request->name,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'leader_id' => Auth::id(),
        ]);

        \App\Models\Activity::create([
            'user_id' => Auth::id(),
            'project_id' => $project->id,
            'action' => 'Created project: ' . $request->name,
            'description' => 'A new project was initiated by ' . Auth::user()->name,
        ]);

        return redirect()->route('projects.index')->with('success', 'Project created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        if (!$this->isLeader($project)) {
            abort(403, 'Anda bukan leader dari project ini.');
        }

        // Ambil semua user kecuali leader project ini
        $users = User::where('id', '!=', $project->leader_id)->get();
        return view('projects.edit', compact('project', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        if (!$this->isLeader($project)) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
        ]);

        $project->update([
            'name' => $request->name,
            'description' => $request->description,
            'due_date' => $request->due_date,
        ]);

        return redirect()->route('projects.index')->with('success', 'Project updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        if (!$this->isLeader($project)) {
            abort(403);
        }

        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Project deleted successfully.');
    }

    /**
     * Fitur Invite Member (Add Member)
     */
    public function addMember(Request $request, Project $project)
    {
        if (!$this->isLeader($project)) {
            abort(403);
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        // Cek apakah user sudah jadi member
        if ($project->members()->where('user_id', $request->user_id)->exists()) {
            return back()->with('error', 'User tersebut sudah menjadi member di project ini.');
        }

        // Tambahkan user ke tabel pivot project_user
        $project->members()->attach($request->user_id);

        return back()->with('success', 'Member berhasil ditambahkan.');
    }

    /**
     * Remove Member (Optional but useful)
     */
    public function removeMember(Project $project, User $user)
    {
        if (!$this->isLeader($project)) {
            abort(403);
        }

        $project->members()->detach($user->id);

        return back()->with('success', 'Member berhasil dihapus dari project.');
    }

    /**
     * Helper to check if current user is the leader of the project or admin
     */
    private function isLeader(Project $project)
    {
        return Auth::user()->hasRole('admin') || $project->leader_id === Auth::id();
    }
}
