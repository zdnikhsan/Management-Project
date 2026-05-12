<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Task Detail') }}: {{ $task->title }}
            </h2>
            <a href="{{ route('projects.tasks.index', $project) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 transition duration-150">
                Back to Board
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Task Info Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg border border-gray-200 dark:border-gray-700 mb-8">
                <div class="p-8">
                    <div class="flex items-center justify-between mb-6">
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider 
                            {{ $task->status === 'done' ? 'bg-green-100 text-green-800' : ($task->status === 'doing' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                            {{ $task->status }}
                        </span>
                        <div class="flex flex-col">
                            <span class="text-sm text-gray-500 dark:text-gray-400">Assigned to: <strong>{{ $task->user->name }}</strong></span>
                            @if($task->due_date)
                                <span class="text-sm text-orange-600 dark:text-orange-400 mt-1 flex items-center font-medium">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    Deadline: {{ $task->due_date->format('F d, Y') }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">{{ $task->title }}</h3>
                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed">{{ $task->description ?: 'No description provided.' }}</p>
                </div>
            </div>

            <!-- Comments Section -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-8">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white uppercase tracking-tight mb-8">Discussion</h3>

                    <!-- Comments List -->
                    <div class="space-y-6 mb-10">
                        @forelse($task->comments as $comment)
                            <div class="flex space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="h-10 w-10 rounded-full bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-bold">
                                        {{ substr($comment->user->name, 0, 1) }}
                                    </div>
                                </div>
                                <div class="flex-grow bg-gray-50 dark:bg-gray-700/50 p-4 rounded-2xl relative">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="font-bold text-sm text-gray-900 dark:text-white">{{ $comment->user->name }}</span>
                                        <div class="flex items-center space-x-2">
                                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                                            @if($comment->user_id === Auth::id() || Auth::user()->hasRole('admin'))
                                                <form action="{{ route('comments.destroy', $comment) }}" method="POST" onsubmit="return confirm('Delete this comment?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-red-400 hover:text-red-600 transition-colors">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">{{ $comment->content }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-gray-500 dark:text-gray-400 py-4">No comments yet. Start the discussion!</p>
                        @endforelse
                    </div>

                    <!-- New Comment Form -->
                    <form action="{{ route('comments.store', $task) }}" method="POST" class="mt-8 pt-8 border-t border-gray-100 dark:border-gray-700">
                        @csrf
                        <div class="mb-4">
                            <label for="content" class="sr-only">Your Comment</label>
                            <textarea name="content" id="content" rows="3" class="w-full px-4 py-3 rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 shadow-sm" placeholder="Write a comment..." required></textarea>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center px-6 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 shadow-lg shadow-indigo-500/30 transition duration-150">
                                Post Comment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
