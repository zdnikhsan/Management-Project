<div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow">
    <div class="flex justify-between items-start mb-2">
        <a href="{{ route('projects.tasks.show', [$project, $task]) }}" class="hover:text-indigo-600 transition-colors">
            <h4 class="font-semibold text-gray-900 dark:text-white text-sm">{{ $task->title }}</h4>
        </a>
    </div>
    <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">{{ Str::limit($task->description, 100) }}</p>

    @if($task->due_date)
        <div class="flex items-center text-[10px] text-orange-600 dark:text-orange-400 font-medium mb-4">
            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            Deadline: {{ $task->due_date->format('M d, Y') }}
        </div>
    @endif
    
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <div class="h-6 w-6 rounded-full bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center text-[10px] font-bold text-indigo-600 dark:text-indigo-400" title="{{ $task->user->name }}">
                {{ substr($task->user->name, 0, 1) }}
            </div>
            <span class="ml-2 text-[10px] text-gray-500 dark:text-gray-400 truncate max-w-[80px]">{{ $task->user->name }}</span>
        </div>

        <div class="flex space-x-1">
            @if($task->status === 'review')
                @if(Auth::id() === $project->leader_id || Auth::user()->hasRole('admin'))
                    <form action="{{ route('projects.tasks.approve', [$project, $task]) }}" method="POST">
                        @csrf
                        <button type="submit" class="p-1 text-green-500 hover:text-green-700" title="Approve Task">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </button>
                    </form>
                    <form action="{{ route('projects.tasks.reject', [$project, $task]) }}" method="POST">
                        @csrf
                        <button type="submit" class="p-1 text-red-500 hover:text-red-700" title="Reject Task (Back to Doing)">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </form>
                @else
                    <span class="text-[9px] font-bold text-amber-600 uppercase tracking-tighter">Reviewing...</span>
                @endif
            @elseif($task->status !== 'done' && ($task->user_id === Auth::id() || Auth::id() === $project->leader_id || Auth::user()->hasRole('admin')))
                @if($task->status !== 'todo')
                    <form action="{{ route('projects.tasks.update', [$project, $task]) }}" method="POST">
                        @csrf @method('PUT')
                        <input type="hidden" name="status" value="todo">
                        <button type="submit" class="p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200" title="Move to Todo">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path></svg>
                        </button>
                    </form>
                @endif
                
                @if($task->status !== 'doing')
                    <form action="{{ route('projects.tasks.update', [$project, $task]) }}" method="POST">
                        @csrf @method('PUT')
                        <input type="hidden" name="status" value="doing">
                        <button type="submit" class="p-1 text-blue-400 hover:text-blue-600" title="Move to Doing">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path></svg>
                        </button>
                    </form>
                @endif

                <form action="{{ route('projects.tasks.update', [$project, $task]) }}" method="POST">
                    @csrf @method('PUT')
                    <input type="hidden" name="status" value="done">
                    <button type="submit" class="p-1 text-green-400 hover:text-green-600" title="Mark as Done">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
