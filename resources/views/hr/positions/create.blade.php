@extends('layouts.hr')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Add Position</h1>
    <a href="{{ route('hr.positions.index') }}" class="btn btn-secondary">Back</a>
</div>

<form action="{{ route('hr.positions.store') }}" method="POST" style="max-width: 500px;">
    @csrf
    <div class="mb-3">
        <label for="code" class="form-label">Position Code</label>
        <input type="text" class="form-control" id="code" name="code" value="{{ old('code') }}" required>
        @error('code')<div class="text-danger">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
        <label for="name" class="form-label">Position Name</label>
        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
        @error('name')<div class="text-danger">{{ $message }}</div>@enderror
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
</form>
@endsection
