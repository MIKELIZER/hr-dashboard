@extends('layouts.employee')

@section('content')
{{-- Welcome --}}
<div class="d-flex justify-content-between align-items-center page-heading">
    <div>
        <h2 class="mb-1"><i class="bi bi-emoji-smile me-2"></i>Halo, {{ $employee->user->name }}!</h2>
        <p class="text-muted mb-0 small">
            <i class="bi bi-briefcase me-1"></i>{{ $employee->position->name ?? '-' }} &bull;
            <i class="bi bi-building me-1"></i>{{ $employee->department->name ?? '-' }}
        </p>
    </div>
    <span class="text-muted small d-none d-md-block"><i class="bi bi-calendar3 me-1"></i>{{ now()->format('l, d M Y') }}</span>
</div>

@if(session('success'))
    <div class="alert alert-success fade-in-up"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger fade-in-up"><i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}</div>
@endif

<div class="row">
    {{-- Attendance Card --}}
    <div class="col-md-6 mb-4 fade-in-up fade-in-up-1">
        <div class="card h-100">
            <div class="card-header bg-white border-0 pt-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-calendar-check me-2 text-primary"></i>Absensi Hari Ini</h6>
            </div>
            <div class="card-body text-center">
                <p class="mb-2 text-muted small">{{ date('Y-m-d') }}</p>
                @if(!$attendanceToday)
                    <div class="mb-3">
                        <span class="badge bg-light text-danger border px-3 py-2"><i class="bi bi-x-circle me-1"></i>Belum Check In</span>
                    </div>
                    <form action="{{ route('employee.attendances.check-in') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-lg px-5 shadow-sm">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Check In
                        </button>
                    </form>
                @else
                    <p class="text-success mb-1"><i class="bi bi-clock me-1"></i>Check In: <strong>{{ $attendanceToday->check_in }}</strong></p>
                    <p class="mb-3">
                        Status: <span class="badge bg-{{ $attendanceToday->status == 'hadir' ? 'success' : 'warning' }} px-3">{{ ucfirst($attendanceToday->status) }}</span>
                        @if($attendanceToday->late_minutes > 0)
                            <span class="text-muted small ms-1">({{ $attendanceToday->late_minutes }} menit)</span>
                        @endif
                    </p>
                    
                    @if(!$attendanceToday->check_out)
                        <form action="{{ route('employee.attendances.check-out') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-lg px-5 shadow-sm">
                                <i class="bi bi-box-arrow-right me-2"></i>Check Out
                            </button>
                        </form>
                    @else
                        <p class="text-secondary mb-0"><i class="bi bi-clock me-1"></i>Check Out: <strong>{{ $attendanceToday->check_out }}</strong></p>
                        <span class="badge bg-light text-dark border mt-2 px-3 py-2">
                            <i class="bi bi-hourglass-split me-1"></i>Durasi: {{ $attendanceToday->work_duration_minutes }} menit
                        </span>
                    @endif
                @endif
            </div>
        </div>
    </div>

    {{-- Leave Balance Card --}}
    <div class="col-md-6 mb-4 fade-in-up fade-in-up-2">
        <div class="card h-100">
            <div class="card-header bg-white border-0 pt-3">
                <h6 class="fw-bold mb-0"><i class="bi bi-calendar-heart me-2 text-success"></i>Saldo Cuti ({{ date('Y') }})</h6>
            </div>
            <div class="card-body d-flex flex-column justify-content-center align-items-center">
                @if($leaveBalance)
                    <h1 class="fw-bold" style="font-size:3.5rem;color:var(--primary);">{{ $leaveBalance->remaining_quota }}</h1>
                    <p class="text-muted mb-3">Hari Tersisa</p>
                    <div class="d-flex gap-4 text-center">
                        <div>
                            <span class="fw-bold text-dark">{{ $leaveBalance->total_quota }}</span>
                            <br><small class="text-muted">Total</small>
                        </div>
                        <div>
                            <span class="fw-bold text-danger">{{ $leaveBalance->used_quota }}</span>
                            <br><small class="text-muted">Terpakai</small>
                        </div>
                    </div>
                @else
                    <p class="text-muted"><i class="bi bi-info-circle me-1"></i>Saldo belum diinisialisasi.</p>
                @endif
                <a href="{{ route('employee.leave-requests.create') }}" class="btn btn-outline-primary mt-4">
                    <i class="bi bi-plus-circle me-1"></i>Ajukan Cuti
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Attendance Chart --}}
<div class="row">
    <div class="col-md-12 mb-4 fade-in-up fade-in-up-3">
        <div class="card">
            <div class="card-header bg-white border-0 pt-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0"><i class="bi bi-bar-chart me-2 text-info"></i>Ringkasan Kehadiran</h6>
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('employee.dashboard', ['period' => 3]) }}" class="btn {{ $period == 3 ? 'btn-primary' : 'btn-outline-primary' }}">3 Bulan</a>
                    <a href="{{ route('employee.dashboard', ['period' => 6]) }}" class="btn {{ $period == 6 ? 'btn-primary' : 'btn-outline-primary' }}">6 Bulan</a>
                    <a href="{{ route('employee.dashboard', ['period' => 12]) }}" class="btn {{ $period == 12 ? 'btn-primary' : 'btn-outline-primary' }}">12 Bulan</a>
                </div>
            </div>
            <div class="card-body">
                <canvas id="attendanceSummaryChart" height="100"></canvas>
            </div>
            {{-- Stats Summary Row --}}
            <div class="card-footer bg-white border-0 pb-3">
                @php
                    $totalHadir = collect($attendanceSummary)->sum('hadir');
                    $totalTerlambat = collect($attendanceSummary)->sum('terlambat');
                    $totalTidakHadir = collect($attendanceSummary)->sum('tidak_hadir');
                    $totalHariKerja = collect($attendanceSummary)->sum('working_days');
                    $totalAbsensi = $totalHadir + $totalTerlambat;
                    $percentage = $totalHariKerja > 0 ? round(($totalAbsensi / $totalHariKerja) * 100, 1) : 0;
                @endphp
                <div class="row text-center">
                    <div class="col">
                        <h5 class="fw-bold text-success mb-0">{{ $totalHadir }}</h5>
                        <small class="text-muted">Hadir</small>
                    </div>
                    <div class="col">
                        <h5 class="fw-bold text-warning mb-0">{{ $totalTerlambat }}</h5>
                        <small class="text-muted">Terlambat</small>
                    </div>
                    <div class="col">
                        <h5 class="fw-bold text-danger mb-0">{{ $totalTidakHadir }}</h5>
                        <small class="text-muted">Tidak Hadir</small>
                    </div>
                    <div class="col">
                        <h5 class="fw-bold text-dark mb-0">{{ $totalHariKerja }}</h5>
                        <small class="text-muted">Hari Kerja</small>
                    </div>
                    <div class="col">
                        <h5 class="fw-bold mb-0" style="color:var(--primary);">{{ $percentage }}%</h5>
                        <small class="text-muted">Tingkat Kehadiran</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    Chart.defaults.font.family = "'Inter', sans-serif";
    const data = @json($attendanceSummary);
    new Chart(document.getElementById('attendanceSummaryChart'), {
        type: 'bar',
        data: {
            labels: data.map(d => d.month),
            datasets: [
                {
                    label: 'Hadir',
                    data: data.map(d => d.hadir),
                    backgroundColor: 'rgba(6, 214, 160, 0.8)',
                    borderRadius: 4,
                    order: 2
                },
                {
                    label: 'Terlambat',
                    data: data.map(d => d.terlambat),
                    backgroundColor: 'rgba(255, 209, 102, 0.8)',
                    borderRadius: 4,
                    order: 3
                },
                {
                    label: 'Tidak Hadir',
                    data: data.map(d => d.tidak_hadir),
                    backgroundColor: 'rgba(239, 71, 111, 0.4)',
                    borderRadius: 4,
                    order: 4
                },
                {
                    label: 'Total Hari Kerja',
                    data: data.map(d => d.working_days),
                    type: 'line',
                    borderColor: '#4361ee',
                    backgroundColor: 'rgba(67, 97, 238, 0.1)',
                    borderWidth: 2,
                    pointRadius: 4,
                    pointBackgroundColor: '#4361ee',
                    tension: 0.3,
                    fill: false,
                    order: 1
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom', labels: { usePointStyle: true, padding: 16 } },
                tooltip: {
                    callbacks: {
                        afterBody: function(context) {
                            const idx = context[0].dataIndex;
                            const d = data[idx];
                            return 'Total Kehadiran: ' + d.total + '/' + d.working_days + ' hari';
                        }
                    }
                }
            },
            scales: {
                x: { stacked: true, grid: { display: false } },
                y: { stacked: true, beginAtZero: true, ticks: { stepSize: 1 } }
            }
        }
    });
});
</script>
@endsection
