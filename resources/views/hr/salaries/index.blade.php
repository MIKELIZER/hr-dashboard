@extends('layouts.hr')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Manage Salaries</h1>
    <a href="{{ route('hr.salaries.create') }}" class="btn btn-primary">Add Salary Record</a>
</div>

@if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
@if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th>Period</th>
                <th>Employee</th>
                <th>Base</th>
                <th>Allowance</th>
                <th>Deduction</th>
                <th>Total</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($salaries as $sal)
            <tr>
                <td>{{ $sal->year }}-{{ str_pad($sal->month, 2, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $sal->employee->user->name ?? 'N/A' }}</td>
                <td>Rp {{ number_format($sal->base_salary, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($sal->allowance, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($sal->deduction, 0, ',', '.') }}</td>
                <td><strong>Rp {{ number_format($sal->total_salary, 0, ',', '.') }}</strong></td>
                <td>
                    <span class="badge bg-{{ $sal->status == 'released' ? 'success' : 'secondary' }}">{{ ucfirst($sal->status) }}</span>
                </td>
                <td>
                    @if($sal->status == 'draft')
                        <a href="{{ route('hr.salaries.edit', $sal->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('hr.salaries.release', $sal->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Release this salary? The employee will be able to see it.')">
                            @csrf <button type="submit" class="btn btn-sm btn-info">Release</button>
                        </form>
                        <form action="{{ route('hr.salaries.destroy', $sal->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this record?')">
                            @csrf @method('DELETE') <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
