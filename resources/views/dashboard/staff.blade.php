<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-3xl text-gray-800 dark:text-white leading-tight">
            {{ __('My Daily Tasks') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Top Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 flex items-center">
                    <div class="bg-indigo-100 p-4 rounded-xl mr-6">
                        <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm font-semibold uppercase">Assigned Tasks</p>
                        <h3 class="text-3xl font-bold text-gray-800 dark:text-white">{{ $assignedTasksCount }}</h3>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 flex items-center">
                    <div class="bg-amber-100 p-4 rounded-xl mr-6">
                        <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm font-semibold uppercase">Pending Review</p>
                        <h3 class="text-3xl font-bold text-gray-800 dark:text-white">{{ $pendingReviewCount }}</h3>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 flex items-center">
                    <div class="bg-orange-100 p-4 rounded-xl mr-6">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm font-semibold uppercase">Urgent</p>
                        <h3 class="text-3xl font-bold text-gray-800 dark:text-white">{{ $dueTodayCount + $dueTomorrowCount }}</h3>
                    </div>
                </div>
            </div>

            <!-- Project List -->
            <div>
                <h4 class="text-xl font-bold text-gray-800 dark:text-white mb-6">Active Projects</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @forelse($myProjects as $project)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start mb-4">
                            <h5 class="font-bold text-gray-800 dark:text-white">{{ $project->name }}</h5>
                            <span class="px-2 py-1 text-xs font-bold rounded-lg bg-blue-50 text-blue-600 uppercase">{{ $project->status }}</span>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6 line-clamp-2">{{ $project->description }}</p>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-600 dark:text-gray-300 font-medium">{{ $project->tasks_count }} Tasks</span>
                            <a href="{{ route('projects.show', $project) }}" class="text-indigo-600 font-bold hover:underline">View Board →</a>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-3 bg-white dark:bg-gray-800 rounded-2xl p-10 text-center text-gray-400 border border-dashed border-gray-300">
                        No projects assigned to you yet.
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Upcoming Deadlines Timeline -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                    <h4 class="text-lg font-bold text-gray-800 dark:text-white">Upcoming Deadlines (Next 7 Days)</h4>
                </div>
                <div class="p-6 overflow-x-auto">
                    <div class="flex space-x-6 min-w-max">
                        @forelse($upcomingDeadlines as $task)
                        <div class="w-64 flex-shrink-0 border-l-4 border-indigo-500 bg-indigo-50 dark:bg-indigo-900 dark:bg-opacity-20 p-4 rounded-r-xl">
                            <p class="text-xs font-bold text-indigo-600 uppercase mb-1">{{ \Carbon\Carbon::parse($task->due_date)->format('D, M d') }}</p>
                            <h6 class="font-bold text-gray-800 dark:text-white truncate">{{ $task->title }}</h6>
                            <p class="text-xs text-gray-500 mt-2">{{ $task->project->name }}</p>
                        </div>
                        @empty
                        <p class="text-gray-400 italic">No deadlines in the next 7 days.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Activity Feed -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                        <h4 class="text-lg font-bold text-gray-800 dark:text-white">Activity Feed</h4>
                    </div>
                    <div class="p-6 space-y-6">
                        @forelse($activityFeed as $activity)
                        <div class="flex">
                            <div class="h-10 w-10 rounded-full bg-indigo-50 dark:bg-indigo-900/50 flex items-center justify-center flex-shrink-0 mr-4">
                                <span class="font-bold text-indigo-600 dark:text-indigo-400 uppercase">{{ substr($activity->user->name, 0, 1) }}</span>
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-start">
                                    <p class="text-sm">
                                        <span class="font-bold text-gray-800 dark:text-white">{{ $activity->user->name }}</span>
                                        <span class="text-gray-600 dark:text-gray-400">{{ $activity->action }}</span>
                                    </p>
                                    <span class="text-xs text-gray-400 ml-4">{{ $activity->created_at->diffForHumans() }}</span>
                                </div>
                                @if($activity->project)
                                    <p class="text-[10px] text-indigo-500 font-bold uppercase mt-1">Project: {{ $activity->project->name }}</p>
                                @endif
                                <p class="text-xs text-gray-500 mt-1">{{ $activity->description }}</p>
                            </div>
                        </div>
                        @empty
                        <p class="text-center text-gray-400 py-4">No recent activity.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Contribution Chart (Heatmap) -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                    <h4 class="text-lg font-bold text-gray-800 dark:text-white mb-6">Performance Heatmap</h4>
                    <div class="flex flex-wrap gap-2">
                        @for($i = 30; $i >= 0; $i--)
                            @php 
                                $date = now()->subDays($i)->format('Y-m-d'); 
                                $count = $completions->get($date, 0);
                                $colorClass = 'bg-gray-100 dark:bg-gray-700';
                                if($count > 0 && $count <= 1) $colorClass = 'bg-emerald-200 dark:bg-emerald-900';
                                elseif($count > 1 && $count <= 3) $colorClass = 'bg-emerald-400 dark:bg-emerald-700';
                                elseif($count > 3) $colorClass = 'bg-emerald-600 dark:bg-emerald-500';
                            @endphp
                            <div class="w-6 h-6 rounded-md {{ $colorClass }} hover:ring-2 hover:ring-indigo-500 cursor-help transition-all duration-200" title="{{ $date }}: {{ $count }} tasks done"></div>
                        @endfor
                    </div>
                    <div class="mt-4 flex items-center justify-between">
                        <p class="text-xs text-gray-400 italic">Last 30 days task completion activity</p>
                        <div class="flex items-center space-x-1">
                            <span class="text-[10px] text-gray-400 mr-1">Less</span>
                            <div class="w-3 h-3 rounded bg-gray-100 dark:bg-gray-700"></div>
                            <div class="w-3 h-3 rounded bg-emerald-200"></div>
                            <div class="w-3 h-3 rounded bg-emerald-400"></div>
                            <div class="w-3 h-3 rounded bg-emerald-600"></div>
                            <span class="text-[10px] text-gray-400 ml-1">More</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
