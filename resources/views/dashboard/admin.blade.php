<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-3xl text-gray-800 dark:text-white leading-tight">
            {{ __('Global System Overview') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Top Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-all duration-300">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-blue-100 text-sm font-semibold uppercase tracking-wider">Total Users</p>
                            <h3 class="text-4xl font-extrabold mt-1">{{ $totalUsers }}</h3>
                        </div>
                        <div class="bg-white bg-opacity-20 p-3 rounded-xl">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                    </div>
                    <p class="mt-4 text-blue-100 text-sm flex items-center">
                        <span class="bg-green-400 bg-opacity-30 text-green-100 text-xs px-2 py-0.5 rounded-full mr-2">Active</span>
                        Platform-wide reach
                    </p>
                </div>

                <div class="bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-all duration-300">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-purple-100 text-sm font-semibold uppercase tracking-wider">Total Projects</p>
                            <h3 class="text-4xl font-extrabold mt-1">{{ $totalProjects }}</h3>
                        </div>
                        <div class="bg-white bg-opacity-20 p-3 rounded-xl">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        </div>
                    </div>
                    <p class="mt-4 text-purple-100 text-sm">All time created projects</p>
                </div>

                <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-all duration-300">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-emerald-100 text-sm font-semibold uppercase tracking-wider">Total Tasks</p>
                            <h3 class="text-4xl font-extrabold mt-1">{{ $totalTasks }}</h3>
                        </div>
                        <div class="bg-white bg-opacity-20 p-3 rounded-xl">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                    </div>
                    <p class="mt-4 text-emerald-100 text-sm">Accumulated across all projects</p>
                </div>
            </div>

            <!-- Secondary Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- New Sign-ups -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                        <h4 class="text-lg font-bold text-gray-800 dark:text-white">New Sign-ups</h4>
                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-widest">Last 5 Users</span>
                    </div>
                    <div class="p-0">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">User</th>
                                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase">Role</th>
                                    <th class="px-6 py-3 text-xs font-semibold text-gray-500 dark:text-gray-300 uppercase text-right">Joined</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @foreach($recentUsers as $user)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                                    <td class="px-6 py-4 flex items-center">
                                        <div class="h-8 w-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold mr-3">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <span class="font-medium text-gray-700 dark:text-gray-200">{{ $user->name }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-xs rounded-full bg-blue-50 text-blue-600 font-semibold border border-blue-100">
                                            {{ $user->getRoleNames()->first() ?? 'Staff' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm text-gray-500 dark:text-gray-400">
                                        {{ $user->created_at->diffForHumans() }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Role Distribution -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
                    <h4 class="text-lg font-bold text-gray-800 dark:text-white mb-6">Role Distribution</h4>
                    <div class="h-64 relative">
                        <canvas id="roleChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Activity Section -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                    <h4 class="text-lg font-bold text-gray-800 dark:text-white">Recent Actions</h4>
                </div>
                <div class="p-0">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-300 uppercase text-xs">
                            <tr>
                                <th class="px-6 py-3">User</th>
                                <th class="px-6 py-3">Action</th>
                                <th class="px-6 py-3 text-right">Timestamp</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse($recentActions as $action)
                            <tr>
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ $action->user->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                    {{ $action->action }}
                                    @if($action->project)
                                        <span class="block text-[10px] text-indigo-500 font-bold uppercase mt-1">Project: {{ $action->project->name }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 text-right">{{ $action->created_at->format('M d, H:i') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-6 py-10 text-center text-gray-400">No recent actions recorded</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('roleChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Admin', 'Leader', 'Staff'],
                datasets: [{
                    data: [{{ $roleDistribution['Admin'] }}, {{ $roleDistribution['Leader'] }}, {{ $roleDistribution['Staff'] }}],
                    backgroundColor: ['#6366f1', '#a855f7', '#10b981'],
                    hoverOffset: 4,
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            color: window.matchMedia('(prefers-color-scheme: dark)').matches ? '#fff' : '#000'
                        }
                    }
                },
                cutout: '70%'
            }
        });
    </script>
</x-app-layout>
