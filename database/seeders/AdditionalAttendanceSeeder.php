<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\WorkSchedule;
use Carbon\Carbon;

class AdditionalAttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $employees = Employee::all();
        $statuses = ['hadir', 'terlambat'];

        foreach ($employees as $emp) {
            $schedule = WorkSchedule::find($emp->schedule_id);
            if (!$schedule) continue;

            // Kita tambahkan data untuk 10 hari terakhir agar menutupi jeda waktu
            for ($day = 9; $day >= 0; $day--) {
                $date = Carbon::today()->subDays($day);
                
                // Skip weekends
                if ($date->isWeekend()) continue;

                // Cek apakah data absensi di tanggal ini sudah ada
                $exists = Attendance::where('employee_id', $emp->id)
                                    ->where('attendance_date', $date->format('Y-m-d'))
                                    ->exists();
                if ($exists) continue; // Jangan buat duplikat

                // 90% chance to attend
                if (rand(1, 100) > 90) continue;

                $isLate = rand(1, 100) <= 20; // 20% chance late
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
    }
}
