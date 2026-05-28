@props([
    'feedbackCount',
    'ratingsData',
    'affiliationData',
    'purposeData',
    'npsData',
    'npsScore',
    'responsesOverTime',
])
<div class="space-y-6">

    {{-- Row 1: Stats Cards --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 md:gap-6">

        {{-- Total Feedback --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
            <div class="flex items-center justify-center w-12 h-12 bg-lime-50 rounded-xl dark:bg-lime-500/10">
                <svg class="w-6 h-6 text-lime-600 dark:text-lime-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <div class="mt-5">
                <span class="text-sm text-gray-500 dark:text-gray-400">Total Feedback Responses</span>
                <h4 class="mt-2 font-bold text-gray-800 text-title-sm dark:text-white/90">{{ $feedbackCount }}</h4>
            </div>
        </div>

        {{-- NPS Score --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
            <div
                class="flex items-center justify-center w-12 h-12 rounded-xl
                {{ $npsScore >= 50 ? 'bg-green-50 dark:bg-green-500/10' : ($npsScore >= 0 ? 'bg-yellow-50 dark:bg-yellow-500/10' : 'bg-red-50 dark:bg-red-500/10') }}">
                <svg class="w-6 h-6 {{ $npsScore >= 50 ? 'text-green-600' : ($npsScore >= 0 ? 'text-yellow-600' : 'text-red-600') }}"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                </svg>
            </div>
            <div class="mt-5">
                <span class="text-sm text-gray-500 dark:text-gray-400">NPS Score</span>
                <h4
                    class="mt-2 font-bold text-title-sm
                    {{ $npsScore >= 50 ? 'text-green-600' : ($npsScore >= 0 ? 'text-yellow-600' : 'text-red-600') }}">
                    {{ $npsScore }}
                </h4>
                <div class="flex gap-3 mt-2 text-xs text-gray-500">
                    <span class="text-green-600">▲ {{ $npsData['promoters'] }} Promoters</span>
                    <span class="text-yellow-600">● {{ $npsData['passives'] }} Passives</span>
                    <span class="text-red-600">▼ {{ $npsData['detractors'] }} Detractors</span>
                </div>
            </div>
        </div>

        {{-- Overall Rating --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
            <div class="flex items-center justify-center w-12 h-12 bg-blue-50 rounded-xl dark:bg-blue-500/10">
                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
            </div>
            <div class="mt-5">
                <span class="text-sm text-gray-500 dark:text-gray-400">Overall Satisfaction</span>
                <h4 class="mt-2 font-bold text-gray-800 text-title-sm dark:text-white/90">
                    {{ $ratingsData['overall'] }}<span class="text-sm font-normal text-gray-500"> / 5</span>
                </h4>
                <div class="flex gap-1 mt-2">
                    @for ($i = 1; $i <= 5; $i++)
                        <svg class="w-4 h-4 {{ $i <= round($ratingsData['overall']) ? 'text-yellow-400' : 'text-gray-300' }}"
                            fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                    @endfor
                </div>
            </div>
        </div>
    </div>

    {{-- Row 2: Responses Over Time + Affiliation --}}
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 md:gap-6">

        {{-- Responses Over Time --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90 mb-4">
                Responses Over Time <span class="text-sm font-normal text-gray-500">(Last 30 days)</span>
            </h3>
            <canvas id="responsesTimeChart" height="200"></canvas>
        </div>

        {{-- Affiliation Donut --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90 mb-4">Visitor Affiliation</h3>
            <div class="flex items-center justify-center">
                <canvas id="affiliationChart" height="200"></canvas>
            </div>
        </div>
    </div>

    {{-- Row 3: Ratings Radar + Purpose Bar --}}
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 md:gap-6">

        {{-- Ratings Radar --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90 mb-4">Service Ratings</h3>
            <canvas id="ratingsRadarChart" height="250"></canvas>
        </div>

        {{-- Purpose Bar --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90 mb-4">Purpose of Visit</h3>
            <canvas id="purposeChart" height="250"></canvas>
        </div>
    </div>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        const isDark = document.documentElement.classList.contains('dark');
        const gridColor = isDark ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.05)';
        const textColor = isDark ? '#9ca3af' : '#6b7280';

        // 1. Responses Over Time - Line Chart
        const timeLabels = @json($responsesOverTime->pluck('date'));
        const timeCounts = @json($responsesOverTime->pluck('count'));

        new Chart(document.getElementById('responsesTimeChart'), {
            type: 'line',
            data: {
                labels: timeLabels,
                datasets: [{
                    label: 'Responses',
                    data: timeCounts,
                    borderColor: '#65a30d',
                    backgroundColor: 'rgba(101, 163, 13, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#65a30d',
                    pointRadius: 4,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: textColor,
                            stepSize: 1
                        },
                        grid: {
                            color: gridColor
                        }
                    },
                    x: {
                        ticks: {
                            color: textColor,
                            maxTicksLimit: 10
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // 2. Affiliation - Donut Chart
        const affiliationLabels = @json($affiliationData->keys());
        const affiliationCounts = @json($affiliationData->values());

        new Chart(document.getElementById('affiliationChart'), {
            type: 'doughnut',
            data: {
                labels: affiliationLabels,
                datasets: [{
                    data: affiliationCounts,
                    backgroundColor: ['#65a30d', '#3b82f6', '#f59e0b', '#ec4899', '#8b5cf6', '#06b6d4'],
                    borderWidth: 2,
                    borderColor: isDark ? '#1f2937' : '#ffffff',
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: textColor,
                            padding: 15,
                            font: {
                                size: 11
                            }
                        }
                    }
                }
            }
        });

        // 3. Ratings - Radar Chart
        const radarLabels = ['Responsiveness', 'Reliability', 'Access', 'Communication', 'Costs', 'Integrity', 'Assurance',
            'Outcome', 'Overall'
        ];
        const radarData = @json(array_values($ratingsData));

        new Chart(document.getElementById('ratingsRadarChart'), {
            type: 'radar',
            data: {
                labels: radarLabels,
                datasets: [{
                    label: 'Average Rating',
                    data: radarData,
                    borderColor: '#65a30d',
                    backgroundColor: 'rgba(101, 163, 13, 0.15)',
                    borderWidth: 2,
                    pointBackgroundColor: '#65a30d',
                    pointRadius: 4,
                }]
            },
            options: {
                responsive: true,
                scales: {
                    r: {
                        min: 0,
                        max: 5,
                        ticks: {
                            stepSize: 1,
                            color: textColor,
                            backdropColor: 'transparent'
                        },
                        grid: {
                            color: gridColor
                        },
                        pointLabels: {
                            color: textColor,
                            font: {
                                size: 11
                            }
                        },
                        angleLines: {
                            color: gridColor
                        }
                    }
                },
                plugins: {
                    legend: {
                        labels: {
                            color: textColor
                        }
                    }
                }
            }
        });

        // 4. Purpose - Horizontal Bar Chart
        const purposeLabels = @json($purposeData->keys());
        const purposeCounts = @json($purposeData->values());

        new Chart(document.getElementById('purposeChart'), {
            type: 'bar',
            data: {
                labels: purposeLabels,
                datasets: [{
                    label: 'Visitors',
                    data: purposeCounts,
                    backgroundColor: 'rgba(59, 130, 246, 0.7)',
                    borderColor: '#3b82f6',
                    borderWidth: 1,
                    borderRadius: 4,
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            color: textColor,
                            stepSize: 1
                        },
                        grid: {
                            color: gridColor
                        }
                    },
                    y: {
                        ticks: {
                            color: textColor,
                            font: {
                                size: 11
                            }
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    </script>
@endpush
