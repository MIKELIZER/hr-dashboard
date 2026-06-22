<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Salary;

class SalaryController extends Controller
{
    public function index()
    {
        $employee = Auth::user()->employee;
        $salaries = Salary::where('employee_id', $employee->id)
                          ->where('status', 'released')
                          ->orderBy('year', 'desc')
                          ->orderBy('month', 'desc')
                          ->get();
                          
        return view('employee.salaries.index', compact('salaries'));
    }
}
