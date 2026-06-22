<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Department;
use App\Models\Position;
use App\Models\WorkSchedule;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\LeaveBalance;
use App\Models\LeaveHistory;
use App\Models\Salary;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ========================================
        // 1. MASTER DATA
        // ========================================

        // Departments (5)
        $deptIT = Department::create(['code' => 'IT', 'name' => 'Information Technology']);
        $deptHR = Department::create(['code' => 'HR', 'name' => 'Human Resources']);
        $deptFIN = Department::create(['code' => 'FIN', 'name' => 'Finance & Accounting']);
        $deptMKT = Department::create(['code' => 'MKT', 'name' => 'Marketing']);
        $deptOPS = Department::create(['code' => 'OPS', 'name' => 'Operations']);
        $departments = [$deptIT, $deptHR, $deptFIN, $deptMKT, $deptOPS];

        // Positions (5)
        $posDirector = Position::create(['code' => 'DIR', 'name' => 'Director']);
        $posManager = Position::create(['code' => 'MGR', 'name' => 'Manager']);
        $posSupervisor = Position::create(['code' => 'SPV', 'name' => 'Supervisor']);
        $posSeniorStaff = Position::create(['code' => 'SST', 'name' => 'Senior Staff']);
        $posStaff = Position::create(['code' => 'STF', 'name' => 'Staff']);
        $positions = [$posDirector, $posManager, $posSupervisor, $posSeniorStaff, $posStaff];

        // Work Schedules (3)
        $shiftPagi = WorkSchedule::create([
            'name' => 'Shift Pagi', 'start_time' => '08:00:00', 'end_time' => '17:00:00', 'late_tolerance' => 15
        ]);
        $shiftSiang = WorkSchedule::create([
            'name' => 'Shift Siang', 'start_time' => '10:00:00', 'end_time' => '19:00:00', 'late_tolerance' => 10
        ]);
        $shiftFlexible = WorkSchedule::create([
            'name' => 'Flexible', 'start_time' => '09:00:00', 'end_time' => '18:00:00', 'late_tolerance' => 30
        ]);
        $schedules = [$shiftPagi, $shiftSiang, $shiftFlexible];

        // ========================================
        // 2. USERS & EMPLOYEES
        // ========================================

        // HR Admin
        $adminUser = User::create([
            'name' => 'Rina Kusuma', 'email' => 'admin@hr.com',
            'password' => Hash::make('password'), 'role' => 'hr'
        ]);
        Employee::create([
            'user_id' => $adminUser->id, 'department_id' => $deptHR->id,
            'position_id' => $posDirector->id, 'schedule_id' => $shiftPagi->id,
            'employee_code' => 'EMP-001', 'phone' => '081200000001',
            'address' => 'Jl. Sudirman No. 1, Jakarta', 'hire_date' => '2019-03-15', 'status' => 'active'
        ]);

        // Employee data
        $employeeData = [
            ['name' => 'Budi Santoso',   'email' => 'budi@company.com',    'dept' => 0, 'pos' => 1, 'sch' => 0, 'hire' => '2021-06-01'],
            ['name' => 'Siti Aminah',    'email' => 'siti@company.com',     'dept' => 0, 'pos' => 3, 'sch' => 2, 'hire' => '2022-01-10'],
            ['name' => 'Ahmad Rizky',    'email' => 'ahmad@company.com',    'dept' => 2, 'pos' => 2, 'sch' => 0, 'hire' => '2020-08-20'],
            ['name' => 'Dewi Lestari',   'email' => 'dewi@company.com',     'dept' => 3, 'pos' => 1, 'sch' => 1, 'hire' => '2021-03-05'],
            ['name' => 'Eko Prasetyo',   'email' => 'eko@company.com',      'dept' => 4, 'pos' => 2, 'sch' => 0, 'hire' => '2022-07-15'],
            ['name' => 'Fitriani',       'email' => 'fitri@company.com',    'dept' => 1, 'pos' => 4, 'sch' => 0, 'hire' => '2023-02-01'],
            ['name' => 'Gunawan Wibowo', 'email' => 'gunawan@company.com',  'dept' => 0, 'pos' => 4, 'sch' => 2, 'hire' => '2023-04-12'],
            ['name' => 'Hana Safitri',   'email' => 'hana@company.com',     'dept' => 2, 'pos' => 4, 'sch' => 0, 'hire' => '2023-06-20'],
            ['name' => 'Irfan Hakim',    'email' => 'irfan@company.com',    'dept' => 3, 'pos' => 4, 'sch' => 1, 'hire' => '2024-01-08'],
            ['name' => 'Joko Widodo',    'email' => 'joko@company.com',     'dept' => 4, 'pos' => 4, 'sch' => 0, 'hire' => '2024-03-01'],
            ['name' => 'Kartini Putri',  'email' => 'kartini@company.com',  'dept' => 0, 'pos' => 4, 'sch' => 0, 'hire' => '2024-05-15'],
            ['name' => 'Lukman Hakim',   'email' => 'lukman@company.com',   'dept' => 2, 'pos' => 3, 'sch' => 0, 'hire' => '2022-09-01'],
            ['name' => 'Maya Sari',      'email' => 'maya@company.com',     'dept' => 3, 'pos' => 4, 'sch' => 1, 'hire' => '2023-11-10'],
            ['name' => 'Noval Ardiansyah','email' => 'noval@company.com',   'dept' => 0, 'pos' => 3, 'sch' => 2, 'hire' => '2023-08-01'],
        ];

        $employees = [];
        foreach ($employeeData as $i => $ed) {
            $user = User::create([
                'name' => $ed['name'], 'email' => $ed['email'],
                'password' => Hash::make('password'), 'role' => 'employee'
            ]);
            $emp = Employee::create([
                'user_id' => $user->id,
                'department_id' => $departments[$ed['dept']]->id,
                'position_id' => $positions[$ed['pos']]->id,
                'schedule_id' => $schedules[$ed['sch']]->id,
                'employee_code' => 'EMP-' . str_pad($i + 2, 3, '0', STR_PAD_LEFT),
                'phone' => '08120000' . str_pad($i + 2, 4, '0', STR_PAD_LEFT),
                'address' => 'Jl. Contoh No. ' . ($i + 2) . ', Jakarta',
                'hire_date' => $ed['hire'],
                'status' => 'active'
            ]);
            $employees[] = $emp;
        }

        // ========================================
        // 3. ATTENDANCE DATA (last 14 days for all employees)
        // ========================================
        $statuses = ['hadir', 'terlambat'];

        foreach ($employees as $emp) {
            $schedule = WorkSchedule::find($emp->schedule_id);
            for ($day = 13; $day >= 0; $day--) {
                $date = Carbon::today()->subDays($day);
                // Skip weekends
                if ($date->isWeekend()) continue;

                // 85% chance to attend
                if (rand(1, 100) > 85) continue;

                $isLate = rand(1, 100) <= 25; // 25% chance late
                $status = $isLate ? 'terlambat' : 'hadir';
                $lateMinutes = $isLate ? rand(5, 45) : 0;

                $checkInHour = (int) substr($schedule->start_time, 0, 2);
                $checkInMinute = $isLate ? rand(15, 55) : rand(0, 14);
                $checkIn = sprintf('%02d:%02d:00', $checkInHour, $checkInMinute);

                $checkOutHour = (int) substr($schedule->end_time, 0, 2);
                $checkOutMinute = rand(0, 30);
                $checkOut = sprintf('%02d:%02d:00', $checkOutHour, $checkOutMinute);

                $workDuration = ($checkOutHour * 60 + $checkOutMinute) - ($checkInHour * 60 + $checkInMinute);

                Attendance::create([
                    'employee_id' => $emp->id,
                    'attendance_date' => $date->format('Y-m-d'),
                    'check_in' => $checkIn,
                    'check_out' => $checkOut,
                    'status' => $status,
                    'late_minutes' => $lateMinutes,
                    'work_duration_minutes' => max($workDuration, 0),
                ]);
            }
        }

        // ========================================
        // 4. LEAVE BALANCES & REQUESTS
        // ========================================
        $leaveReasons = [
            'Keperluan keluarga', 'Sakit', 'Acara pernikahan saudara',
            'Liburan tahunan', 'Urusan pribadi', 'Pemeriksaan kesehatan',
            'Mengurus dokumen', 'Wisuda anak'
        ];

        foreach ($employees as $emp) {
            // Create leave balance
            $used = rand(0, 5);
            $balance = LeaveBalance::create([
                'employee_id' => $emp->id, 'year' => date('Y'),
                'total_quota' => 12, 'used_quota' => $used, 'remaining_quota' => 12 - $used
            ]);

            // Create 1-3 leave requests per employee
            $numRequests = rand(1, 3);
            $statusOptions = ['pending', 'approved', 'rejected'];
            for ($r = 0; $r < $numRequests; $r++) {
                $startOffset = rand(1, 60);
                $duration = rand(1, 3);
                $startDate = Carbon::today()->subDays($startOffset);
                $endDate = $startDate->copy()->addDays($duration - 1);
                $reqStatus = $statusOptions[array_rand($statusOptions)];

                $leaveReq = LeaveRequest::create([
                    'employee_id' => $emp->id,
                    'start_date' => $startDate->format('Y-m-d'),
                    'end_date' => $endDate->format('Y-m-d'),
                    'reason' => $leaveReasons[array_rand($leaveReasons)],
                    'status' => $reqStatus,
                    'approved_by' => $reqStatus !== 'pending' ? $adminUser->id : null,
                    'approved_at' => $reqStatus !== 'pending' ? now() : null,
                ]);

                // Create leave history for approved requests
                if ($reqStatus === 'approved') {
                    LeaveHistory::create([
                        'leave_balance_id' => $balance->id,
                        'leave_request_id' => $leaveReq->id,
                        'type' => 'deduct',
                        'amount' => $duration,
                        'description' => 'Leave approved: ' . $leaveReq->reason,
                    ]);
                }
            }
        }

        // ========================================
        // 5. SALARY DATA (last 6 months for all employees)
        // ========================================
        $baseSalaries = [5000000, 6500000, 7000000, 8000000, 10000000, 12000000, 15000000];

        foreach ($employees as $idx => $emp) {
            $baseSalary = $baseSalaries[$idx % count($baseSalaries)];
            for ($m = 5; $m >= 0; $m--) {
                $monthDate = Carbon::today()->subMonths($m);
                $allowance = rand(500000, 2000000);
                $deduction = rand(100000, 500000);

                Salary::create([
                    'employee_id' => $emp->id,
                    'month' => $monthDate->month,
                    'year' => $monthDate->year,
                    'base_salary' => $baseSalary,
                    'allowance' => $allowance,
                    'deduction' => $deduction,
                    'total_salary' => $baseSalary + $allowance - $deduction,
                    'notes' => 'Gaji bulan ' . $monthDate->format('F Y'),
                    'status' => $m >= 1 ? 'released' : 'draft', // current month = draft
                ]);
            }
        }
    }
}
