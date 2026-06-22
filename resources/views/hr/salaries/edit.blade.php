@extends('layouts.hr')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit Salary Record</h1>
    <a href="{{ route('hr.salaries.index') }}" class="btn btn-secondary">Back</a>
</div>

<form action="{{ route('hr.salaries.update', $salary->id) }}" method="POST" style="max-width: 600px;">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label class="form-label">Employee</label>
        <select class="form-select" name="employee_id" required>
            @foreach($employees as $emp)
                <option value="{{ $emp->id }}" {{ old('employee_id', $salary->employee_id) == $emp->id ? 'selected' : '' }}>{{ $emp->user->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Month</label>
            <input type="number" class="form-control" name="month" min="1" max="12" value="{{ old('month', $salary->month) }}" required>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Year</label>
            <input type="number" class="form-control" name="year" value="{{ old('year', $salary->year) }}" required>
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label">Base Salary (Rp)</label>
        <input type="number" class="form-control" name="base_salary" value="{{ old('base_salary', $salary->base_salary) }}" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Allowance (Rp)</label>
        <input type="number" class="form-control" name="allowance" value="{{ old('allowance', $salary->allowance) }}" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Deduction (Rp)</label>
        <input type="number" class="form-control" name="deduction" value="{{ old('deduction', $salary->deduction) }}" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Notes</label>
        <textarea class="form-control" name="notes">{{ old('notes', $salary->notes) }}</textarea>
    </div>
    <button type="submit" class="btn btn-primary">Update Record</button>
</form>
@endsection
