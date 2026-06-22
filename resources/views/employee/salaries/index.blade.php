@extends('layouts.employee')

@section('content')
<div class="mb-4">
    <h2>My Salary History</h2>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Period</th>
                        <th>Base Salary</th>
                        <th>Allowance</th>
                        <th>Deduction</th>
                        <th>Total Received</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($salaries as $sal)
                    <tr>
                        <td>{{ $sal->year }}-{{ str_pad($sal->month, 2, '0', STR_PAD_LEFT) }}</td>
                        <td>Rp {{ number_format($sal->base_salary, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($sal->allowance, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($sal->deduction, 0, ',', '.') }}</td>
                        <td><strong class="text-success">Rp {{ number_format($sal->total_salary, 0, ',', '.') }}</strong></td>
                        <td>{{ $sal->notes }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-3">No salary history found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
