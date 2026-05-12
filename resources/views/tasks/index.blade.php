<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Tasks') }}: {{ $project->name }}
            </h2>
            @if(Auth::user()->hasRole('admin') || Auth::id() === $project->leader_id)
                <a href="{{ route('projects.tasks.create', $project) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 shadow-lg shadow-indigo-500/30 transition duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    New Task
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 shadow-md" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- TODO COLUMN -->
                <div class="bg-gray-100 dark:bg-gray-900/50 p-4 rounded-xl border border-gray-200 dark:border-gray-800">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider text-sm">To Do</h3>
                        <span class="px-2 py-1 bg-gray-200 dark:bg-gray-800 rounded text-xs font-bold">{{ $todo->count() }}</span>
                    </div>
                    <div class="space-y-4">
                        @foreach($todo as $task)
                            @include('tasks._card', ['task' => $task])
                        @endforeach
                    </div>
                </div>

                <!-- DOING COLUMN -->
                <div class="bg-blue-50/50 dark:bg-blue-900/10 p-4 rounded-xl border border-blue-100 dark:border-blue-900/30">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-bold text-blue-700 dark:text-blue-400 uppercase tracking-wider text-sm">Doing</h3>
                        <span class="px-2 py-1 bg-blue-100 dark:bg-blue-900/40 rounded text-xs font-bold">{{ $doing->count() }}</span>
                    </div>
                    <div class="space-y-4">
                        @foreach($doing as $task)
                            @include('tasks._card', ['task' => $task])
                        @endforeach
                    </div>
                </div>

                <!-- REVIEW COLUMN -->
                <div class="bg-amber-50/50 dark:bg-amber-900/10 p-4 rounded-xl border border-amber-100 dark:border-amber-900/30">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-bold text-amber-700 dark:text-amber-400 uppercase tracking-wider text-sm">Review</h3>
                        <span class="px-2 py-1 bg-amber-100 dark:bg-amber-900/40 rounded text-xs font-bold">{{ $review->count() }}</span>
                    </div>
                    <div class="space-y-4">
                        @foreach($review as $task)
                            @include('tasks._card', ['task' => $task])
                        @endforeach
                    </div>
                </div>

                <!-- DONE COLUMN -->
                <div class="bg-green-50/50 dark:bg-green-900/10 p-4 rounded-xl border border-green-100 dark:border-green-900/30">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-bold text-green-700 dark:text-green-400 uppercase tracking-wider text-sm">Done</h3>
                        <span class="px-2 py-1 bg-green-100 dark:bg-green-900/40 rounded text-xs font-bold">{{ $done->count() }}</span>
                    </div>
                    <div class="space-y-4">
                        @foreach($done as $task)
                            @include('tasks._card', ['task' => $task])
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
