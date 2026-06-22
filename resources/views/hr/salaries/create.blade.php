@extends('layouts.hr')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Add Salary Record</h1>
    <a href="{{ route('hr.salaries.index') }}" class="btn btn-secondary">Back</a>
</div>

<form action="{{ route('hr.salaries.store') }}" method="POST" style="max-width: 600px;">
    @csrf
    <div class="mb-3">
        <label class="form-label">Employee</label>
        <select class="form-select" name="employee_id" required>
            <option value="">Select...</option>
            @foreach($employees as $emp)
                <option value="{{ $emp->id }}" {{ old('employee_id') == $emp->id ? 'selected' : '' }}>{{ $emp->user->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Month</label>
            <input type="number" class="form-control" name="month" min="1" max="12" value="{{ old('month', date('n')) }}" required>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Year</label>
            <input type="number" class="form-control" name="year" value="{{ old('year', date('Y')) }}" required>
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label">Base Salary (Rp)</label>
        <input type="number" class="form-control" name="base_salary" value="{{ old('base_salary', 0) }}" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Allowance (Rp)</label>
        <input type="number" class="form-control" name="allowance" value="{{ old('allowance', 0) }}" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Deduction (Rp)</label>
        <input type="number" class="form-control" name="deduction" value="{{ old('deduction', 0) }}" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Notes</label>
        <textarea class="form-control" name="notes">{{ old('notes') }}</textarea>
    </div>
    <button type="submit" class="btn btn-primary">Save as Draft</button>
</form>
@endsection
