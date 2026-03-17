<!-- resources/views/dashboard/index.blade.php -->
@extends('layouts.app')

@section('title', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('hide_header', true)

@section('content')
    <!-- Redesigned Stats Cards -->
    <div class="row mb-5">
        <div class="col-lg-3 col-md-6 mb-4">
            <a href="{{ route('action-items.index') }}" class="text-decoration-none">
                <div class="card h-100 border-0 stats-card" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                    <div class="card-body p-4 text-white">
                        <div class="d-flex align-items-center mb-3">
                            <div class="glass-icon-wrapper mr-3">
                                <i class="fas fa-tasks"></i>
                            </div>
                            <span class="text-xs font-weight-bold text-uppercase opacity-75 letter-spacing-1">Tugas</span>
                        </div>
                        <div class="d-flex align-items-baseline">
                            <h2 class="font-weight-bold mb-0 mr-2 display-4">{{ $totalActions }}</h2>
                            <span class="text-xs opacity-75">Total item</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <a href="{{ route('meetings.index') }}" class="text-decoration-none">
                <div class="card h-100 border-0 stats-card" style="background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%);">
                    <div class="card-body p-4 text-white">
                        <div class="d-flex align-items-center mb-3">
                            <div class="glass-icon-wrapper mr-3">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <span class="text-xs font-weight-bold text-uppercase opacity-75 letter-spacing-1">Rapat</span>
                        </div>
                        <div class="d-flex align-items-baseline">
                            <h2 class="font-weight-bold mb-0 mr-2 display-4">{{ $totalMeetings }}</h2>
                            <span class="text-xs opacity-75">Dilaksanakan</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <a href="{{ route('meetings.index') }}" class="text-decoration-none">
                <div class="card h-100 border-0 stats-card" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                    <div class="card-body p-4 text-white">
                        <div class="d-flex align-items-center mb-3">
                            <div class="glass-icon-wrapper mr-3">
                                <i class="fas fa-clock"></i>
                            </div>
                            <span class="text-xs font-weight-bold text-uppercase opacity-75 letter-spacing-1">Dijadwalkan</span>
                        </div>
                        <div class="d-flex align-items-baseline">
                            <h2 class="font-weight-bold mb-0 mr-2 display-4">{{ $scheduledMeetings }}</h2>
                            <span class="text-xs opacity-75">Mendatang</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <a href="{{ route('action-items.index') }}" class="text-decoration-none">
                <div class="card h-100 border-0 stats-card" style="background: linear-gradient(135deg, #f43f5e 0%, #e11d48 100%);">
                    <div class="card-body p-4 text-white">
                        <div class="d-flex align-items-center mb-3">
                            <div class="glass-icon-wrapper mr-3">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <span class="text-xs font-weight-bold text-uppercase opacity-75 letter-spacing-1">Terlambat</span>
                        </div>
                        <div class="d-flex align-items-baseline">
                            <h2 class="font-weight-bold mb-0 mr-2 display-4">{{ $overdueActions }}</h2>
                            <span class="text-xs opacity-75">Perlu tindakan</span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Redesigned Trend Section -->
    <div class="row mb-5">
        <div class="col-lg-8 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Tren Performa Tugas</h5>
                    <div class="badge badge-light-indigo px-3 py-2">30 Hari Terakhir</div>
                </div>
                <div class="card-body">
                    <div style="height: 350px;">
                        <canvas id="actionTrendChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-header">
                    <h5 class="card-title mb-0">Aktivitas Rapat</h5>
                </div>
                <div class="card-body">
                    <div style="height: 200px;" class="mb-4">
                        <canvas id="meetingTrendChart"></canvas>
                    </div>
                    <hr class="my-4 opacity-50">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="text-sm text-muted mb-1 text-uppercase font-weight-bold">Tingkat Keberhasilan</div>
                            <div class="h4 font-weight-bold text-success mb-0">{{ $totalActions > 0 ? number_format(($completedActions/$totalActions)*100, 0) : 0 }}%</div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="text-sm text-muted mb-1 text-uppercase font-weight-bold">Mendatang</div>
                            <div class="h4 font-weight-bold text-primary mb-0">{{ $scheduledMeetings }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Tugas per User -->
    @if(auth()->user()->isAdmin() || auth()->user()->isManager())
    <div class="card shadow-sm border-0 rounded-lg mb-5 stats-card-section">
        <div class="card-header bg-white py-4 px-4 border-0 d-flex align-items-center justify-content-between">
            <div style="flex: 1;">
                <h5 class="card-title mb-0 font-weight-bold text-dark">
                    <span class="title-accent mr-2"></span>Statistik Tugas per Penugasan
                </h5>
            </div>
            
            <div class="display-toggle-pill mx-auto">
                <button type="button" class="toggle-btn active" data-display-type="chart">
                    <i class="fas fa-chart-bar mr-1"></i> Grafik
                </button>
                <button type="button" class="toggle-btn" data-display-type="table">
                    <i class="fas fa-table mr-1"></i> Tabel
                </button>
            </div>

            <div style="flex: 1;"></div>
        </div>
        <div class="card-body px-4 pb-4">
            <!-- Diagram Batang -->
            <div id="user-chart-container">
                <div class="chart-container" style="height: 350px;">
                    <canvas id="userAssignmentChart"></canvas>
                </div>
            </div>
            <!-- Tabel Statistik -->
            <div id="user-table-container" style="display: none;">
                <div class="table-responsive">
                    <table class="table table-hover border-0 mb-0">
                        <thead class="bg-light text-muted text-sm text-uppercase">
                            <tr>
                                <th class="border-0 px-3 py-3">Nama Anggota</th>
                                <th class="border-0 px-3 py-3 text-center">Selesai</th>
                                <th class="border-0 px-3 py-3 text-center">Proses</th>
                                <th class="border-0 px-3 py-3 text-center">Menunggu</th>
                                <th class="border-0 px-3 py-3 text-center">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($userAssignmentStats as $stat)
                            <tr class="align-middle">
                                <td class="px-3 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm mr-3 bg-light text-primary font-weight-bold rounded-circle d-flex align-items-center justify-content-center">
                                            {{ strtoupper(substr($stat->name, 0, 1)) }}
                                        </div>
                                        <span class="font-weight-bold text-dark">{{ $stat->name }}</span>
                                    </div>
                                </td>
                                <td class="px-3 py-3 text-center"><span class="badge badge-soft-success">{{ $stat->completed_actions }}</span></td>
                                <td class="px-3 py-3 text-center"><span class="badge badge-soft-warning">{{ $stat->in_progress_actions }}</span></td>
                                <td class="px-3 py-3 text-center"><span class="badge badge-soft-secondary">{{ $stat->pending_actions }}</span></td>
                                <td class="px-3 py-3 text-center font-weight-bold text-dark">{{ $stat->completed_actions + $stat->in_progress_actions + $stat->pending_actions }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Lower Dashboard Sections -->
    <div class="row">
        <!-- Recent Tasks -->
        <div class="col-lg-8 mb-5">
            <div class="card h-100 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Tindak Lanjut Terbaru</h5>
                    <a href="{{ route('action-items.index') }}" class="btn btn-sm btn-link text-primary font-weight-bold p-0">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 border-0">
                            <thead class="bg-light text-muted text-sm text-uppercase">
                                <tr>
                                    <th class="border-0 px-4 py-3">Tugas & Rapat</th>
                                    <th class="border-0 px-4 py-3">Penerima Tugas</th>
                                    <th class="border-0 px-4 py-3">Status</th>
                                    <th class="border-0 px-4 py-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentActionItems as $item)
                                <tr class="align-middle">
                                    <td class="px-4 py-3">
                                        <div class="font-weight-bold text-dark mb-1">{{ Str::limit($item->title, 40) }}</div>
                                        <div class="text-sm text-muted">
                                            <i class="fas fa-link mr-1 opacity-50"></i>
                                            @if($item->meeting) {{ Str::limit($item->meeting->title, 30) }} @else No Meeting @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded-circle text-sm font-weight-bold d-flex align-items-center justify-content-center mr-2" style="width: 32px; height: 32px;">
                                                {{ strtoupper(substr($item->assignedTo->name, 0, 1)) }}
                                            </div>
                                            <span class="text-sm text-dark">{{ $item->assignedTo->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        @php
                                            $badgeClass = match($item->status) {
                                                'completed' => 'bg-emerald-soft text-emerald',
                                                'in_progress' => 'bg-amber-soft text-amber',
                                                default => 'bg-slate-soft text-slate'
                                            };
                                            $badgeColor = match($item->status) {
                                                'completed' => '#10b981',
                                                'in_progress' => '#f59e0b',
                                                default => '#64748b'
                                            };
                                            $badgeBg = match($item->status) {
                                                'completed' => 'rgba(16, 185, 129, 0.1)',
                                                'in_progress' => 'rgba(245, 158, 11, 0.1)',
                                                default => 'rgba(100, 116, 139, 0.1)'
                                            };
                                        @endphp
                                        <span class="badge" style="background: {{ $badgeBg }}; color: {{ $badgeColor }}; border-radius: 6px; font-weight: 600; font-size: 11px;">
                                            {{ strtoupper($item->status_label) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <a href="{{ route('action-items.show', $item) }}" class="btn btn-sm btn-light rounded-circle">
                                            <i class="fas fa-chevron-right text-xs text-muted"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">Tidak ada aktivitas terbaru.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Notifications & Upcoming -->
        <div class="col-lg-4">
            <!-- Critical Notifications -->
            <div class="card h-100 shadow-sm border-0 mb-4" style="background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%)">
                <div class="card-body p-4 text-white">
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-white rounded-lg p-2 mr-3" style="background: rgba(255, 255, 255, 0.2) !important">
                            <i class="fas fa-bell"></i>
                        </div>
                        <h5 class="mb-0 font-weight-bold">Acara Mendatang</h5>
                    </div>
                    
                    @forelse($upcomingMeetings->take(3) as $meeting)
                    <div class="mb-4 bg-white bg-opacity-10 p-3 rounded-lg border border-white border-opacity-10" style="background: rgba(255, 255, 255, 0.05)">
                        <div class="text-sm text-white opacity-75 font-weight-bold mb-1">{{ $meeting->start_time->format('D, d M • H:i') }}</div>
                        <a href="{{ route('meetings.show', $meeting) }}" class="text-white font-weight-bold d-block mb-2">{{ Str::limit($meeting->title, 35) }}</a>
                        <div class="text-sm text-white opacity-50"><i class="fas fa-map-marker-alt mr-1"></i> {{ $meeting->location ?? 'Online' }}</div>
                    </div>
                    @empty
                    <div class="text-center py-4 opacity-50">
                        <i class="far fa-calendar-check fa-2x mb-2"></i>
                        <p class="text-sm mb-0">Tidak ada rapat mendatang</p>
                    </div>
                    @endforelse

                    <a href="{{ route('meetings.index') }}" class="btn btn-white w-100 rounded-lg font-weight-bold mt-2" style="background: white; color: #4f46e5; border: none;">
                        Buka Kalender
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- User Productivity Section -->
    @if(auth()->user()->isAdmin() || auth()->user()->isManager())
    <div class="row mb-5">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="card-title mb-0">Produktivitas Tim</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 border-0">
                            <thead class="bg-light text-muted text-xs text-uppercase">
                                <tr>
                                    <th class="border-0 px-4 py-3">Anggota Tim</th>
                                    <th class="border-0 px-4 py-3 text-center">Ditugaskan</th>
                                    <th class="border-0 px-4 py-3 text-center">Selesai</th>
                                    <th class="border-0 px-4 py-3">Tingkat Keberhasilan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($userAssignmentStats->take(5) as $user)
                                @php
                                    $progress = $user->total_assigned > 0 ? ($user->completed_actions / $user->total_assigned) * 100 : 0;
                                @endphp
                                <tr class="align-middle">
                                    <td class="px-4 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded-circle font-weight-bold d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px; background: rgba(79, 70, 229, 0.05); color: #4f46e5">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="font-weight-bold text-dark">{{ $user->name }}</div>
                                                <div class="text-xs text-muted">{{ $user->department->name ?? 'Global' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center font-weight-bold">{{ $user->total_assigned }}</td>
                                    <td class="px-4 py-3 text-center text-success font-weight-bold">{{ $user->completed_actions }}</td>
                                    <td class="px-4 py-3" style="width: 250px;">
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-grow-1 rounded-pill" style="height: 6px; background: #f1f5f9">
                                                <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $progress }}%"></div>
                                            </div>
                                            <span class="text-xs font-weight-bold text-muted ml-3">{{ number_format($progress, 0) }}%</span>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Initialize Data from PHP
    const actionTrendData = @json($actionTrendData);
    const meetingTrendData = @json($meetingTrendData);
    const userAssignmentStats = @json($userAssignmentStats);

    document.addEventListener('DOMContentLoaded', function() {
        // 1. Chart Trend Tindak Lanjut
        const actionTrendCtx = document.getElementById('actionTrendChart');
        if (actionTrendCtx) {
            const ctx = actionTrendCtx.getContext('2d');
            const accentColor = '#4f46e5';
            const successColor = '#10b981';

            const blueGrad = ctx.createLinearGradient(0, 0, 0, 400);
            blueGrad.addColorStop(0, 'rgba(79, 70, 229, 0.4)');
            blueGrad.addColorStop(1, 'rgba(79, 70, 229, 0)');

            const greenGrad = ctx.createLinearGradient(0, 0, 0, 400);
            greenGrad.addColorStop(0, 'rgba(16, 185, 129, 0.4)');
            greenGrad.addColorStop(1, 'rgba(16, 185, 129, 0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: actionTrendData.labels || [],
                    datasets: [
                        {
                            label: 'Dibuat',
                            data: actionTrendData.created || [],
                            borderColor: accentColor,
                            backgroundColor: blueGrad,
                            borderWidth: 3,
                            borderWidth: 3,
                            pointRadius: 3,
                            pointHoverRadius: 6,
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Selesai',
                            data: actionTrendData.completed || [],
                            borderColor: successColor,
                            backgroundColor: greenGrad,
                            borderWidth: 3,
                            pointRadius: 3,
                            pointHoverRadius: 6,
                            fill: true,
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            padding: 12,
                            cornerRadius: 8,
                            titleFont: { size: 13, weight: '700' },
                            mode: 'index',
                            intersect: false
                        }
                    },
                    scales: {
                        y: { 
                            beginAtZero: true,
                            grid: { color: '#f1f5f9', drawBorder: false }, 
                            ticks: { font: { size: 12 }, precision: 0 } 
                        },
                        x: { 
                            grid: { display: false }, 
                            ticks: { 
                                font: { size: 12 },
                                maxRotation: 0,
                                autoSkip: true,
                                maxTicksLimit: 10
                            } 
                        }
                    }
                }
            });
        }

        // 2. Chart Trend Meeting
        const meetingTrendCtx = document.getElementById('meetingTrendChart');
        if (meetingTrendCtx) {
            const ctx = meetingTrendCtx.getContext('2d');
            const barGrad = ctx.createLinearGradient(0, 0, 0, 200);
            barGrad.addColorStop(0, '#4f46e5');
            barGrad.addColorStop(1, 'rgba(79, 70, 229, 0.1)');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: meetingTrendData.labels || [],
                    datasets: [{
                        data: meetingTrendData.created || [],
                        backgroundColor: barGrad,
                        hoverBackgroundColor: '#4f46e5',
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false }, tooltip: { enabled: true } },
                    scales: {
                        y: { display: false, beginAtZero: true },
                        x: { grid: { display: false }, ticks: { display: false } }
                    }
                }
            });
        }

        const userAssignmentCtx = document.getElementById('userAssignmentChart');
        if (userAssignmentCtx && userAssignmentStats && userAssignmentStats.length > 0) {
            const ctx = userAssignmentCtx.getContext('2d');
            
            const successGrad = ctx.createLinearGradient(0, 0, 0, 400);
            successGrad.addColorStop(0, '#10b981');
            successGrad.addColorStop(1, '#059669');

            const warningGrad = ctx.createLinearGradient(0, 0, 0, 400);
            warningGrad.addColorStop(0, '#f59e0b');
            warningGrad.addColorStop(1, '#d97706');

            const secondaryGrad = ctx.createLinearGradient(0, 0, 0, 400);
            secondaryGrad.addColorStop(0, '#94a3b8');
            secondaryGrad.addColorStop(1, '#64748b');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: userAssignmentStats.map(u => u.name),
                    datasets: [
                        {
                            label: 'Selesai',
                            data: userAssignmentStats.map(u => u.completed_actions),
                            backgroundColor: successGrad,
                            borderRadius: 6,
                            barThickness: 20
                        },
                        {
                            label: 'Proses',
                            data: userAssignmentStats.map(u => u.in_progress_actions),
                            backgroundColor: warningGrad,
                            borderRadius: 6,
                            barThickness: 20
                        },
                        {
                            label: 'Menunggu',
                            data: userAssignmentStats.map(u => u.pending_actions),
                            backgroundColor: secondaryGrad,
                            borderRadius: 6,
                            barThickness: 20
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { boxWidth: 10, padding: 20, font: { family: "'Inter'", size: 12 } } },
                        tooltip: { 
                            backgroundColor: '#1e293b',
                            padding: 12,
                            cornerRadius: 8,
                            mode: 'index', 
                            intersect: false 
                        }
                    },
                    scales: {
                        x: { stacked: true, grid: { display: false }, ticks: { font: { size: 11 } } },
                        y: { stacked: true, beginAtZero: true, grid: { color: 'rgba(0,0,0,0.03)', drawBorder: false }, ticks: { font: { size: 11 }, precision: 0 } }
                    },
                    animation: {
                        duration: 1500,
                        easing: 'easeOutQuart'
                    }
                }
            });
        }

        // Toggle Display Type
        $('[data-display-type]').on('click', function() {
            const type = $(this).data('display-type');
            const container = $(this).closest('.stats-card-section');
            
            // UI Toggle
            container.find('.toggle-btn').removeClass('active');
            $(this).addClass('active');
            
            // Content Toggle with smooth transition
            if (type === 'chart') {
                $('#user-table-container').fadeOut(200, function() {
                    $('#user-chart-container').fadeIn(300);
                });
            } else {
                $('#user-chart-container').fadeOut(200, function() {
                    $('#user-table-container').fadeIn(300);
                });
            }
        });
    });
</script>

<style>
    .letter-spacing-1 { letter-spacing: 0.05em; }
    .bg-emerald-soft { background-color: rgba(16, 185, 129, 0.1); }
    .bg-amber-soft { background-color: rgba(245, 158, 11, 0.1); }
    .bg-slate-soft { background-color: rgba(100, 116, 139, 0.1); }
    .text-emerald { color: #10b981; }
    .text-amber { color: #f59e0b; }
    .text-slate { color: #64748b; }

    /* Premium Dashboard Extensions */
    .stats-card {
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        box-shadow: 0 10px 20px -5px rgba(0, 0, 0, 0.1);
        position: relative;
        overflow: hidden;
    }
    .stats-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 25px 40px -10px rgba(0, 0, 0, 0.2);
    }
    .stats-card::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 150px;
        height: 150px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        pointer-events: none;
    }
    .glass-icon-wrapper {
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(8px);
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.3);
        font-size: 1.25rem;
    }
    .card-header {
        background: transparent !important;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05) !important;
        padding: 1.25rem !important;
    }
    .badge-light-indigo {
        background-color: rgba(79, 70, 229, 0.1);
        color: #4f46e5;
        border-radius: 30px;
        font-weight: 600;
    }
    .progress {
        background-color: #f1f5f9;
        overflow: visible;
    }
    .progress-bar {
        border-radius: 30px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    /* Animation entries */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .row > div {
        animation: fadeInUp 0.6s ease-out forwards;
    }
    .row > div:nth-child(1) { animation-delay: 0.1s; }
    .row > div:nth-child(2) { animation-delay: 0.2s; }
    .row > div:nth-child(3) { animation-delay: 0.3s; }
    .row > div:nth-child(4) { animation-delay: 0.4s; }

    /* User Stats Section Extensions */
    .title-accent {
        display: inline-block;
        width: 4px;
        height: 18px;
        background: #4f46e5;
        border-radius: 4px;
        vertical-align: middle;
    }
    .display-toggle-pill {
        background: #f1f5f9;
        padding: 4px;
        border-radius: 50px;
        display: flex;
        gap: 4px;
    }
    .toggle-btn {
        border: none;
        background: transparent;
        padding: 6px 16px;
        border-radius: 50px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #64748b;
        transition: all 0.3s ease;
    }
    .toggle-btn.active {
        background: white;
        color: #4f46e5;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }
    .avatar-sm {
        width: 32px;
        height: 32px;
        font-size: 0.8rem;
    }
    .badge-soft-success { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .badge-soft-warning { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .badge-soft-secondary { background: rgba(148, 163, 184, 0.1); color: #475569; }
    .table-hover tbody tr:hover {
        background-color: rgba(79, 70, 229, 0.02) !important;
    }
</style>
@endpush