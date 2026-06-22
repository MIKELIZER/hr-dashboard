@extends('layouts.hr')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center page-heading">
    <h1 class="h2"><i class="bi bi-speedometer2 me-2"></i>Dashboard</h1>
    <span class="text-muted small"><i class="bi bi-calendar3 me-1"></i>{{ now()->format('l, d F Y') }}</span>
</div>

{{-- Summary Cards --}}
<div class="row mb-4">
    <div class="col-md-2 mb-3 fade-in-up fade-in-up-1">
        <div class="stat-card text-white" style="background: linear-gradient(135deg, #4361ee, #3a56d4);">
            <div class="card-body py-3 px-3">
                <i class="bi bi-people-fill stat-icon"></i>
                <h2 class="display-5 mb-0 fw-bold">{{ $totalEmployees }}</h2>
                <small class="text-white-50">Karyawan</small>
            </div>
        </div>
    </div>
    <div class="col-md-2 mb-3 fade-in-up fade-in-up-2">
        <div class="stat-card text-white" style="background: linear-gradient(135deg, #06d6a0, #05b588);">
            <div class="card-body py-3 px-3">
                <i class="bi bi-building stat-icon"></i>
                <h2 class="display-5 mb-0 fw-bold">{{ $totalDepartments }}</h2>
                <small class="text-white-50">Departemen</small>
            </div>
        </div>
    </div>
    <div class="col-md-2 mb-3 fade-in-up fade-in-up-3">
        <div class="stat-card text-white" style="background: linear-gradient(135deg, #118ab2, #0e7a9e);">
            <div class="card-body py-3 px-3">
                <i class="bi bi-briefcase-fill stat-icon"></i>
                <h2 class="display-5 mb-0 fw-bold">{{ $totalPositions }}</h2>
                <small class="text-white-50">Jabatan</small>
            </div>
        </div>
    </div>
    <div class="col-md-2 mb-3 fade-in-up fade-in-up-4">
        <div class="stat-card text-white" style="background: linear-gradient(135deg, #7209b7, #620aa0);">
            <div class="card-body py-3 px-3">
                <i class="bi bi-check-circle-fill stat-icon"></i>
                <h2 class="display-5 mb-0 fw-bold">{{ $attendanceToday }}</h2>
                <small class="text-white-50">Hadir Hari Ini</small>
            </div>
        </div>
    </div>
    <div class="col-md-2 mb-3 fade-in-up fade-in-up-5">
        <div class="stat-card text-white" style="background: linear-gradient(135deg, #ffd166, #e6b84d);">
            <div class="card-body py-3 px-3">
                <i class="bi bi-exclamation-triangle-fill stat-icon"></i>
                <h2 class="display-5 mb-0 fw-bold">{{ $lateToday }}</h2>
                <small class="text-white-50">Terlambat</small>
            </div>
        </div>
    </div>
    <div class="col-md-2 mb-3 fade-in-up fade-in-up-6">
        <div class="stat-card text-white" style="background: linear-gradient(135deg, #ef476f, #d93d5f);">
            <div class="card-body py-3 px-3">
                <i class="bi bi-hourglass-split stat-icon"></i>
                <h2 class="display-5 mb-0 fw-bold">{{ $pendingLeaves }}</h2>
                <small class="text-white-50">Cuti Pending</small>
            </div>
        </div>
    </div>
</div>

{{-- Row 1: Attendance Trend + Department Distribution --}}
<div class="row mb-4">
    <div class="col-md-8 mb-3">
        <div class="card">
            <div class="card-header bg-white border-0 pt-3 pb-0">
                <h6 class="fw-bold text-dark mb-0"><i class="bi bi-graph-up me-2 text-primary"></i>Tren Kehadiran (7 Hari Terakhir)</h6>
            </div>
            <div class="card-body">
                <canvas id="attendanceTrendChart" height="120"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-header bg-white border-0 pt-3 pb-0">
                <h6 class="fw-bold text-dark mb-0"><i class="bi bi-pie-chart me-2 text-success"></i>Per Departemen</h6>
            </div>
            <div class="card-body d-flex justify-content-center align-items-center">
                <canvas id="deptChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- Row 2: Leave Status + Salary Trend --}}
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-header bg-white border-0 pt-3 pb-0">
                <h6 class="fw-bold text-dark mb-0"><i class="bi bi-envelope-paper me-2 text-warning"></i>Status Cuti</h6>
            </div>
            <div class="card-body d-flex justify-content-center align-items-center">
                <canvas id="leaveChart" height="200"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-8 mb-3">
        <div class="card">
            <div class="card-header bg-white border-0 pt-3 pb-0">
                <h6 class="fw-bold text-dark mb-0"><i class="bi bi-cash-stack me-2 text-info"></i>Pengeluaran Gaji (6 Bulan)</h6>
            </div>
            <div class="card-body">
                <canvas id="salaryTrendChart" height="120"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.font.size = 12;

    // 1. Attendance Trend
    const attData = @json($attendanceTrend);
    new Chart(document.getElementById('attendanceTrendChart'), {
        type: 'bar',
        data: {
            labels: attData.map(d => d.date),
            datasets: [
                { label: 'Hadir', data: attData.map(d => d.hadir), backgroundColor: 'rgba(6, 214, 160, 0.8)', borderRadius: 6 },
                { label: 'Terlambat', data: attData.map(d => d.terlambat), backgroundColor: 'rgba(255, 209, 102, 0.8)', borderRadius: 6 }
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, padding: 16 } } },
            scales: { x: { stacked: true, grid: { display: false } }, y: { stacked: true, beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });

    // 2. Department Doughnut
    const deptData = @json($deptDistribution);
    const deptColors = ['#4361ee','#06d6a0','#118ab2','#ffd166','#ef476f','#7209b7','#f72585','#20c997'];
    new Chart(document.getElementById('deptChart'), {
        type: 'doughnut',
        data: { labels: deptData.map(d => d.name), datasets: [{ data: deptData.map(d => d.count), backgroundColor: deptColors.slice(0, deptData.length), borderWidth: 3, borderColor: '#fff' }] },
        options: { responsive: true, cutout: '68%', plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, padding: 12, boxWidth: 10 } } } }
    });

    // 3. Leave Status Doughnut
    const leaveData = @json($leaveStats);
    new Chart(document.getElementById('leaveChart'), {
        type: 'doughnut',
        data: { labels: ['Pending', 'Approved', 'Rejected'], datasets: [{ data: [leaveData.pending, leaveData.approved, leaveData.rejected], backgroundColor: ['#ffd166', '#06d6a0', '#ef476f'], borderWidth: 3, borderColor: '#fff' }] },
        options: { responsive: true, cutout: '68%', plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, padding: 12, boxWidth: 10 } } } }
    });

    // 4. Salary Trend
    const salData = @json($salaryTrend);
    new Chart(document.getElementById('salaryTrendChart'), {
        type: 'bar',
        data: { labels: salData.map(d => d.period), datasets: [{ label: 'Total Gaji', data: salData.map(d => d.total), backgroundColor: 'rgba(67, 97, 238, 0.7)', borderRadius: 8 }] },
        options: { responsive: true, plugins: { legend: { display: false } }, scales: { x: { grid: { display: false } }, y: { beginAtZero: true, ticks: { callback: v => 'Rp ' + v.toLocaleString('id-ID') } } } }
    });
});
</script>
@endsection
