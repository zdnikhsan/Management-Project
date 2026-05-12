<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-3xl text-gray-800 dark:text-white leading-tight">
                {{ __('Leadership Workspace') }}
            </h2>
            <a href="{{ route('projects.create') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 border border-transparent rounded-xl font-bold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-lg shadow-indigo-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Create New Project
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Top Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                    <p class="text-gray-500 dark:text-gray-400 text-sm font-semibold uppercase">Active Projects</p>
                    <h3 class="text-3xl font-bold mt-1 text-gray-800 dark:text-white">{{ $managedProjects->count() }}</h3>
                    <div class="mt-4 flex items-center text-green-500 text-sm">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        In Progress
                    </div>
                </div>

                <div class="bg-indigo-50 dark:bg-indigo-900/20 rounded-2xl shadow-sm border border-indigo-100 dark:border-indigo-800 p-6">
                    <p class="text-indigo-600 dark:text-indigo-400 text-sm font-semibold uppercase">Total Projects</p>
                    <h3 class="text-3xl font-bold mt-1 text-indigo-700 dark:text-indigo-300">{{ $allProjectsCount }}</h3>
                    <div class="mt-4 flex items-center text-indigo-600 dark:text-indigo-400 text-sm">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        Overall History
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                    <p class="text-gray-500 dark:text-gray-400 text-sm font-semibold uppercase">Team Members</p>
                    <h3 class="text-3xl font-bold mt-1 text-gray-800 dark:text-white">{{ $teamMembersCount }}</h3>
                    <div class="mt-4 flex items-center text-blue-500 text-sm">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        Project Staff
                    </div>
                </div>

                <div class="bg-red-50 dark:bg-red-900 dark:bg-opacity-20 rounded-2xl shadow-sm border border-red-100 dark:border-red-800 p-6">
                    <p class="text-red-600 dark:text-red-400 text-sm font-semibold uppercase">Tasks Review</p>
                    <h3 class="text-3xl font-bold mt-1 text-red-700 dark:text-red-300">{{ $urgentTasks->count() }}</h3>
                    <div class="mt-4 flex items-center text-red-600 dark:text-red-400 text-sm">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Attention needed
                    </div>
                </div>
            </div>

            <!-- Main View: Project Progress Tracker -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                    <h4 class="text-lg font-bold text-gray-800 dark:text-white">Project Progress Tracker</h4>
                </div>
                <div class="p-6 space-y-6">
                    @forelse($managedProjects as $project)
                        @php
                            $completedTasks = $project->tasks()->where('status', 'done')->count();
                            $totalTasks = $project->tasks_count;
                            $percent = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
                        @endphp
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="font-semibold text-gray-700 dark:text-gray-200">{{ $project->name }}</span>
                                <span class="text-sm font-medium text-indigo-600 dark:text-indigo-400">{{ $percent }}%</span>
                            </div>
                            <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-3 overflow-hidden">
                                <div class="bg-indigo-600 h-full transition-all duration-500 ease-out" style="width: {{ $percent }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-400 py-10">No projects managed yet.</p>
                    @endforelse
                </div>
            </div>

            <!-- Resource Management -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Team Workload -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                        <h4 class="text-lg font-bold text-gray-800 dark:text-white">Team Workload</h4>
                    </div>
                    <div class="p-0">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-300 uppercase text-xs">
                                <tr>
                                    <th class="px-6 py-3">Staff</th>
                                    <th class="px-6 py-3">Tasks</th>
                                    <th class="px-6 py-3">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @foreach($teamWorkload as $staff)
                                <tr>
                                    <td class="px-6 py-4 font-medium text-gray-700 dark:text-white">{{ $staff->name }}</td>
                                    <td class="px-6 py-4 text-sm">{{ $staff->tasks_count }} Active</td>
                                    <td class="px-6 py-4">
                                        @if($staff->tasks_count > 3)
                                            <span class="px-2 py-1 text-xs rounded-full bg-red-50 text-red-600 font-bold border border-red-100">Busy</span>
                                        @else
                                            <span class="px-2 py-1 text-xs rounded-full bg-green-50 text-green-600 font-bold border border-green-100">Available</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Task Review Requests -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                        <h4 class="text-lg font-bold text-gray-800 dark:text-white">Task Review Requests</h4>
                        <span class="bg-amber-100 text-amber-800 text-xs px-2.5 py-0.5 rounded-full font-bold uppercase tracking-wider">Revision Required?</span>
                    </div>
                    <div class="p-0">
                        @forelse($tasksToReview as $task)
                        <div class="p-6 border-b border-gray-50 dark:border-gray-750 last:border-0 hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h5 class="font-bold text-gray-900 dark:text-white">{{ $task->title }}</h5>
                                    <p class="text-xs text-gray-500 mt-1">Project: <span class="font-medium text-indigo-600">{{ $task->project->name }}</span></p>
                                    <p class="text-xs text-gray-500">Submitted by: <span class="font-medium text-gray-700 dark:text-gray-300">{{ $task->user->name }}</span></p>
                                </div>
                                <span class="text-xs text-gray-400">{{ $task->updated_at->diffForHumans() }}</span>
                            </div>
                            <div class="flex space-x-3">
                                <form action="{{ route('projects.tasks.approve', [$task->project, $task]) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-xs font-bold rounded-lg transition shadow-sm">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Approve
                                    </button>
                                </form>
                                <form action="{{ route('projects.tasks.reject', [$task->project, $task]) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-bold rounded-lg border border-red-200 transition">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        Reject
                                    </button>
                                </form>
                            </div>
                        </div>
                        @empty
                        <div class="p-10 text-center text-gray-400">
                            <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <p>No tasks pending review</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Activity Feed -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                    <h4 class="text-lg font-bold text-gray-800 dark:text-white">Recent Activity (My Projects)</h4>
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
                                <span class="text-xs text-gray-400 whitespace-nowrap ml-4">{{ $activity->created_at->diffForHumans() }}</span>
                            </div>
                            @if($activity->project)
                                <p class="text-xs text-indigo-500 font-medium mt-1">Project: {{ $activity->project->name }}</p>
                            @endif
                            <p class="text-xs text-gray-500 mt-1">{{ $activity->description }}</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-center text-gray-400 py-4">No recent activity in your projects.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
