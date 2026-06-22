@extends('layouts.hr')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit Employee</h1>
    <a href="{{ route('hr.employees.index') }}" class="btn btn-secondary">Back</a>
</div>

<form action="{{ route('hr.employees.update', $employee->id) }}" method="POST" style="max-width: 800px;">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="employee_code" class="form-label">Employee Code</label>
            <input type="text" class="form-control" id="employee_code" name="employee_code" value="{{ old('employee_code', $employee->employee_code) }}" required>
            @error('employee_code')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6 mb-3">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $employee->user->name) }}" required>
            @error('name')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6 mb-3">
            <label for="email" class="form-label">Email (For Login)</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $employee->user->email) }}" required>
            @error('email')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6 mb-3">
            <label for="phone" class="form-label">Phone</label>
            <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $employee->phone) }}">
        </div>
        <div class="col-md-6 mb-3">
            <label for="department_id" class="form-label">Department</label>
            <select class="form-select" id="department_id" name="department_id" required>
                @foreach($departments as $d)
                    <option value="{{ $d->id }}" {{ (old('department_id', $employee->department_id) == $d->id) ? 'selected' : '' }}>{{ $d->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label for="position_id" class="form-label">Position</label>
            <select class="form-select" id="position_id" name="position_id" required>
                @foreach($positions as $p)
                    <option value="{{ $p->id }}" {{ (old('position_id', $employee->position_id) == $p->id) ? 'selected' : '' }}>{{ $p->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label for="schedule_id" class="form-label">Work Schedule</label>
            <select class="form-select" id="schedule_id" name="schedule_id" required>
                @foreach($schedules as $s)
                    <option value="{{ $s->id }}" {{ (old('schedule_id', $employee->schedule_id) == $s->id) ? 'selected' : '' }}>{{ $s->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label for="hire_date" class="form-label">Hire Date</label>
            <input type="date" class="form-control" id="hire_date" name="hire_date" value="{{ old('hire_date', $employee->hire_date ? $employee->hire_date->format('Y-m-d') : '') }}" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-select" id="status" name="status">
                <option value="active" {{ $employee->status === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ $employee->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
        <div class="col-md-12 mb-3">
            <label for="address" class="form-label">Address</label>
            <textarea class="form-control" id="address" name="address">{{ old('address', $employee->address) }}</textarea>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Update Employee</button>
</form>
@endsection
