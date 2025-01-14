<div class="p-4 sm:p-6 lg:p-8">
    @livewire('update-user-data')
    <div class="p-4 sm:p-6 lg:p-8">
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center gap-2">
                <i class="fas fa-chart-line text-yellow-500"></i>
                Dashboard
            </h2>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <!-- Total Pekerjaan -->
                <div class="bg-white border border-gray-100 rounded-lg p-4 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Pekerjaan</p>
                            <h3 class="text-xl font-semibold text-gray-800 mt-1">{{ $totalJobs ?? 0 }}</h3>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center">
                            <i class="fas fa-briefcase text-blue-500"></i>
                        </div>
                    </div>
                </div>

                <!-- Pekerjaan Pending -->
                <div class="bg-white border border-gray-100 rounded-lg p-4 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Menunggu</p>
                            <h3 class="text-xl font-semibold text-gray-800 mt-1">{{ $pendingJobs ?? 0 }}</h3>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-yellow-50 flex items-center justify-center">
                            <i class="fas fa-clock text-yellow-500"></i>
                        </div>
                    </div>
                </div>

                <!-- Pekerjaan Disetujui -->
                <div class="bg-white border border-gray-100 rounded-lg p-4 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Disetujui</p>
                            <h3 class="text-xl font-semibold text-gray-800 mt-1">{{ $approvedJobs ?? 0 }}</h3>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-green-50 flex items-center justify-center">
                            <i class="fas fa-check text-green-500"></i>
                        </div>
                    </div>
                </div>

                <!-- Total Penghasilan -->
                <div class="bg-white border border-gray-100 rounded-lg p-4 hover:shadow-md transition-shadow duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Penghasilan</p>
                            <h3 class="text-xl font-semibold text-gray-800 mt-1">Rp
                                {{ number_format($totalEarnings ?? 0, 0, ',', '.') }}</h3>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-purple-50 flex items-center justify-center">
                            <i class="fas fa-wallet text-purple-500"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white border border-gray-100 rounded-lg">
                <div class="p-4 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-800">Aktivitas Terbaru</h3>
                </div>
                <div class="p-4">
                    @if (isset($recentActivities) && count($recentActivities) > 0)
                        <div class="space-y-4">
                            @foreach ($recentActivities as $activity)
                                <div class="flex items-center gap-4">
                                    <div class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center">
                                        @switch($activity->type ?? '')
                                            @case('apply')
                                                <i class="fas fa-paper-plane text-blue-500"></i>
                                            @break

                                            @case('approved')
                                                <i class="fas fa-check text-green-500"></i>
                                            @break

                                            @case('completed')
                                                <i class="fas fa-check-double text-purple-500"></i>
                                            @break

                                            @default
                                                <i class="fas fa-circle text-gray-400"></i>
                                        @endswitch
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm text-gray-600">{{ $activity->description ?? 'Aktivitas' }}</p>
                                        <p class="text-xs text-gray-400 mt-1">
                                            {{ isset($activity->created_at) ? $activity->created_at->diffForHumans() : '' }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6">
                            <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 mb-3">
                                <i class="fas fa-clock text-xl text-gray-400"></i>
                            </div>
                            <h3 class="text-sm font-medium text-gray-900">Belum Ada Aktivitas</h3>
                            <p class="text-sm text-gray-500 mt-1">Aktivitas Anda akan muncul di sini</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>