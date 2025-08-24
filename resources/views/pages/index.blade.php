@extends('layout.application')

@section('content')
    <div class="container">
        <h2 class="mb-4 fw-bold">📊 Dashboard Overview</h2>
        <div class="row g-4">
            <!-- Total Users -->
            @if (auth()->user()->is_admin == 1)
                <div class="col-md-3 col-sm-6">
                    <div class="card dashboard-card text-center shadow-sm border-0">
                        <div class="card-body">
                            <div class="icon-wrapper bg-primary text-white mb-3">
                                <i class="fa fa-users fs-2"></i>
                            </div>
                            <h6 class="fw-semibold text-secondary">Total Users</h6>
                            <h3 class="fw-bold text-dark">{{ $totalUsers }}</h3>
                        </div>
                    </div>
                </div>
            @endif
            <!-- Total Files -->
            <div class="col-md-3 col-sm-6">
                <div class="card dashboard-card text-center shadow-sm border-0">
                    <div class="card-body">
                        <div class="icon-wrapper bg-success text-white mb-3">
                            <i class="fa fa-file fs-2"></i>
                        </div>
                        <h6 class="fw-semibold text-secondary">Total Files</h6>
                        <h3 class="fw-bold text-dark">{{ $totalFiles }}</h3>
                    </div>
                </div>
            </div>

            <!-- Total Activities -->
            <div class="col-md-3 col-sm-6">
                <div class="card dashboard-card text-center shadow-sm border-0">
                    <div class="card-body">
                        <div class="icon-wrapper bg-info text-white mb-3">
                            <i class="fa fa-chart-line fs-2"></i>
                        </div>
                        <h6 class="fw-semibold text-secondary">Total Activities</h6>
                        <h3 class="fw-bold text-dark">{{ $totalActivities }}</h3>
                    </div>
                </div>
            </div>

            <!-- Total Views -->
            <div class="col-md-3 col-sm-6">
                <div class="card dashboard-card text-center shadow-sm border-0">
                    <div class="card-body">
                        <div class="icon-wrapper bg-warning text-white mb-3">
                            <i class="fa fa-eye fs-2"></i>
                        </div>
                        <h6 class="fw-semibold text-secondary">Total Views</h6>
                        <h3 class="fw-bold text-dark">{{ $totalViews }}</h3>
                    </div>
                </div>
            </div>

            <!-- Total Downloads -->
            <div class="col-md-3 col-sm-6">
                <div class="card dashboard-card text-center shadow-sm border-0">
                    <div class="card-body">
                        <div class="icon-wrapper bg-danger text-white mb-3">
                            <i class="fa fa-download fs-2"></i>
                        </div>
                        <h6 class="fw-semibold text-secondary">Total Downloads</h6>
                        <h3 class="fw-bold text-dark">{{ $totalDownloads }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title">Aktivitas Harian (7 Hari Terakhir)</h5>
            <canvas id="activityChart" height="100"></canvas>
        </div>
    </div>

    <style>
        .dashboard-card {
            border-radius: 15px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .dashboard-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }

        .icon-wrapper {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
        }
    </style>
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const ctx = document.getElementById('activityChart').getContext('2d');
            const activityChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($activityLabels),
                    datasets: [{
                        label: 'Total Aktivitas',
                        data: @json($activityTotals),
                        borderColor: 'rgba(54, 162, 235, 1)',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        tension: 0.3,
                        fill: true,
                        pointRadius: 5,
                        pointBackgroundColor: 'rgba(54, 162, 235, 1)'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
    @endpush
@endsection
