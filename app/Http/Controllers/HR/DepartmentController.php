<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::all();
        return view('hr.departments.index', compact('departments'));
    }

    public function create()
    {
        return view('hr.departments.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:departments',
            'name' => 'required'
        ]);
        Department::create($request->all());
        return redirect()->route('hr.departments.index')->with('success', 'Department created successfully.');
    }

    public function edit(Department $department)
    {
        return view('hr.departments.edit', compact('department'));
    }

    public function update(Request $request, Department $department)
    {
        $request->validate([
            'code' => 'required|unique:departments,code,' . $department->id,
            'name' => 'required'
        ]);
        $department->update($request->all());
        return redirect()->route('hr.departments.index')->with('success', 'Department updated successfully.');
    }

    public function destroy(Department $department)
    {
        try {
            $department->delete();
            return redirect()->route('hr.departments.index')->with('success', 'Department deleted successfully.');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('hr.departments.index')->with('error', 'Cannot delete department because it is assigned to employees.');
        }
    }
}
