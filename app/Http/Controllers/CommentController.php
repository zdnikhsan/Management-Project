<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Comment;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Store a newly created comment in storage.
     */
    public function store(Request $request, Task $task)
    {
        $project = $task->project;

        // Security check: Only project participants can comment
        if (!$this->isProjectParticipant($project)) {
            abort(403, 'Anda bukan member dari project ini.');
        }

        $request->validate([
            'content' => 'required|string',
        ]);

        $task->comments()->create([
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);

        return back()->with('success', 'Komentar berhasil ditambahkan.');
    }

    /**
     * Remove the specified comment from storage.
     */
    public function destroy(Comment $comment)
    {
        // Only owner or admin can delete
        if ($comment->user_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
            abort(403, 'Hanya pemilik komentar atau admin yang bisa menghapus ini.');
        }

        $comment->delete();

        return back()->with('success', 'Komentar berhasil dihapus.');
    }

    private function isProjectParticipant(Project $project)
    {
        $user = Auth::user();
        return $user->hasRole('admin') || 
               $project->leader_id === $user->id || 
               $project->members()->where('user_id', $user->id)->exists();
    }
}
