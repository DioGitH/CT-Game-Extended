@extends('lecturer.layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Dashboard</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Rank Distribution -->
            <div class="bg-white p-4 rounded-lg shadow-md">
                <h2 class="text-lg font-semibold mb-3 text-blue-700">📊 Rank Distribution</h2>
                <canvas id="rankChart" style="height: 220px;"></canvas>
            </div>

            <!-- Streak Distribution -->
            <div class="bg-white p-4 rounded-lg shadow-md">
                <h2 class="text-lg font-semibold mb-3 text-yellow-700">🎯 Streak Distribution</h2>
                <div class="h-[220px] flex justify-center items-center relative">
                    <canvas id="streakChart" class="max-h-[200px] w-[200px]"></canvas>
                </div>
            </div>

            <!-- Top 5 Students -->
            <div class="md:col-span-2 bg-white p-4 rounded-lg shadow-md">
                <h2 class="text-lg font-semibold mb-3 text-green-700">🏆 Top 5 Students</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left text-gray-800 divide-y divide-gray-200">
                        <thead class="bg-green-500 text-white text-xs uppercase font-semibold tracking-wider">
                            <tr>
                                <th class="px-4 py-2">Name</th>
                                <th class="px-4 py-2">Weekly Score</th>
                                <th class="px-4 py-2">Rank</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach ($topStudents as $student)
                                <tr>
                                    <td class="px-4 py-2">{{ $student->user->name }}</td>
                                    <td class="px-4 py-2">{{ $student->weekly_score }}</td>
                                    <td class="px-4 py-2">{{ $student->ranks->last()?->name ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    <script>
        const rankCtx = document.getElementById('rankChart').getContext('2d');
        new Chart(rankCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($rankStats->pluck('name')->values()) !!},
                datasets: [{
                    label: 'Students',
                    data: {!! json_encode($rankStats->pluck('students_count')->values()) !!},
                    backgroundColor: '#3B82F6',
                    borderRadius: 4
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
                        title: {
                            display: true,
                            text: 'Number of Students'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Rank'
                        }
                    }
                }
            }
        });

        const streakCtx = document.getElementById('streakChart').getContext('2d');

        new Chart(streakCtx, {
            type: 'pie',
            data: {
                labels: {!! json_encode($streakStats->pluck('streak')->values()) !!},
                datasets: [{
                    data: {!! json_encode($streakStats->pluck('total')->values()) !!},
                    backgroundColor: ['#FBBF24', '#FCD34D', '#FDE68A', '#FEF3C7', '#F97316'],
                    borderColor: '#ffffff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false // hide built-in legend
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                return `${label} days: ${value} students`;
                            }
                        }
                    },
                    datalabels: {
                        color: '#111827',
                        font: {
                            weight: 'bold'
                        },
                        formatter: (value, ctx) => {
                            const label = ctx.chart.data.labels[ctx.dataIndex];
                            return `${label} (${value})`;
                        }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });
    </script>
@endsection
