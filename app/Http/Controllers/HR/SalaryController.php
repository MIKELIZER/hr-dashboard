<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Salary;
use App\Models\Employee;

class SalaryController extends Controller
{
    public function index()
    {
        $salaries = Salary::with('employee.user')->orderBy('year', 'desc')->orderBy('month', 'desc')->get();
        return view('hr.salaries.index', compact('salaries'));
    }

    public function create()
    {
        $employees = Employee::with('user')->get();
        return view('hr.salaries.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer',
            'base_salary' => 'required|numeric',
            'allowance' => 'required|numeric',
            'deduction' => 'required|numeric',
        ]);
        
        $request->merge(['status' => 'draft']);
        Salary::create($request->all());
        
        return redirect()->route('hr.salaries.index')->with('success', 'Salary record created as Draft.');
    }

    public function edit(Salary $salary)
    {
        if ($salary->status == 'released') {
            return redirect()->route('hr.salaries.index')->with('error', 'Cannot edit a released salary.');
        }
        $employees = Employee::with('user')->get();
        return view('hr.salaries.edit', compact('salary', 'employees'));
    }

    public function update(Request $request, Salary $salary)
    {
        if ($salary->status == 'released') {
            return redirect()->route('hr.salaries.index')->with('error', 'Cannot update a released salary.');
        }
        $request->validate([
            'employee_id' => 'required',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer',
            'base_salary' => 'required|numeric',
            'allowance' => 'required|numeric',
            'deduction' => 'required|numeric',
        ]);
        $salary->update($request->all());
        return redirect()->route('hr.salaries.index')->with('success', 'Salary record updated.');
    }

    public function destroy(Salary $salary)
    {
        if ($salary->status == 'released') {
            return redirect()->route('hr.salaries.index')->with('error', 'Cannot delete a released salary.');
        }
        $salary->delete();
        return redirect()->route('hr.salaries.index')->with('success', 'Salary record deleted.');
    }

    public function release(Salary $salary)
    {
        $salary->update(['status' => 'released']);
        return redirect()->route('hr.salaries.index')->with('success', 'Salary released to employee.');
    }
}
