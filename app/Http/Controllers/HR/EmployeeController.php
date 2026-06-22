<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\User;
use App\Models\Department;
use App\Models\Position;
use App\Models\WorkSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with(['user', 'department', 'position', 'schedule'])->get();
        return view('hr.employees.index', compact('employees'));
    }

    public function create()
    {
        $departments = Department::all();
        $positions = Position::all();
        $schedules = WorkSchedule::all();
        return view('hr.employees.create', compact('departments', 'positions', 'schedules'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'employee_code' => 'required|unique:employees',
            'department_id' => 'required',
            'position_id' => 'required',
            'schedule_id' => 'required',
            'hire_date' => 'required|date'
        ]);

        DB::transaction(function() use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make('password'),
                'role' => 'employee'
            ]);
            
            Employee::create([
                'user_id' => $user->id,
                'department_id' => $request->department_id,
                'position_id' => $request->position_id,
                'schedule_id' => $request->schedule_id,
                'employee_code' => $request->employee_code,
                'phone' => $request->phone,
                'address' => $request->address,
                'hire_date' => $request->hire_date,
                'status' => 'active'
            ]);
        });

        return redirect()->route('hr.employees.index')->with('success', 'Employee created successfully. Default login password is "password".');
    }

    public function edit(Employee $employee)
    {
        $departments = Department::all();
        $positions = Position::all();
        $schedules = WorkSchedule::all();
        return view('hr.employees.edit', compact('employee', 'departments', 'positions', 'schedules'));
    }

    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $employee->user_id,
            'employee_code' => 'required|unique:employees,employee_code,' . $employee->id,
            'department_id' => 'required',
            'position_id' => 'required',
            'schedule_id' => 'required',
            'hire_date' => 'required|date'
        ]);

        DB::transaction(function() use ($request, $employee) {
            $employee->user->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);
            
            $employee->update([
                'department_id' => $request->department_id,
                'position_id' => $request->position_id,
                'schedule_id' => $request->schedule_id,
                'employee_code' => $request->employee_code,
                'phone' => $request->phone,
                'address' => $request->address,
                'hire_date' => $request->hire_date,
                'status' => $request->status ?? 'active'
            ]);
        });

        return redirect()->route('hr.employees.index')->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        DB::transaction(function() use ($employee) {
            $userId = $employee->user_id;
            $employee->delete();
            User::destroy($userId); // Cascade is not strictly set for User -> Employee in schema, so manual delete is safe.
        });
        return redirect()->route('hr.employees.index')->with('success', 'Employee deleted successfully.');
    }
}
