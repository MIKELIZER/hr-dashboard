<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 12">
  <img src="https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white" alt="Bootstrap 5">
  <img src="https://img.shields.io/badge/PostgreSQL-16-4169E1?style=for-the-badge&logo=postgresql&logoColor=white" alt="PostgreSQL">
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.2+">
  <img src="https://img.shields.io/badge/Chart.js-4-FF6384?style=for-the-badge&logo=chartdotjs&logoColor=white" alt="Chart.js">
</p>

# 🏢 HR Dashboard

**Sistem Manajemen Sumber Daya Manusia (HRM)** berbasis web yang dirancang untuk mengelola data karyawan, absensi harian, pengajuan cuti, dan penggajian dasar secara efisien dan aman.

Dibangun menggunakan **Laravel 12**, **Bootstrap 5**, dan **PostgreSQL** dengan arsitektur *Role-Based Access Control* yang memisahkan panel **HR Admin** dan **Employee** secara ketat.

---

## ✨ Fitur Utama

### 🔐 Autentikasi & Keamanan
- Login dengan role-based redirect (HR → Panel Admin, Employee → Portal Karyawan)
- Role Middleware untuk proteksi akses antar panel
- Password terenkripsi menggunakan Bcrypt

### 👨‍💼 Panel HR Admin
| Modul | Fitur |
|-------|-------|
| **Dashboard** | 6 kartu statistik real-time, 4 grafik interaktif (Chart.js) |
| **Master Departemen** | CRUD dengan proteksi Foreign Key |
| **Master Jabatan** | CRUD dengan validasi kode unik |
| **Jadwal Kerja** | Konfigurasi jam masuk, pulang, dan toleransi keterlambatan |
| **Data Karyawan** | Registrasi karyawan + pembuatan akun login otomatis |
| **Monitoring Absensi** | Rekap harian dengan filter tanggal |
| **Persetujuan Cuti** | Approval flow dengan pemotongan saldo otomatis + audit trail |
| **Manajemen Gaji** | Input gaji dengan sistem Draft → Release |

### 👤 Portal Employee
| Modul | Fitur |
|-------|-------|
| **Dashboard** | Status absensi hari ini, saldo cuti, grafik kehadiran bulanan |
| **Check-In / Check-Out** | Absensi satu-klik dengan deteksi keterlambatan otomatis |
| **Riwayat Absensi** | Tabel lengkap dengan status, menit terlambat, durasi kerja |
| **Pengajuan Cuti** | Form pengajuan + riwayat status (Pending/Approved/Rejected) |
| **Riwayat Gaji** | Slip gaji bulanan (hanya yang sudah di-release HR) |

### 📊 Dashboard Analytics
- **Tren Kehadiran** — Stacked bar chart (7 hari terakhir)
- **Distribusi Departemen** — Doughnut chart
- **Status Cuti** — Doughnut chart (Pending/Approved/Rejected)
- **Pengeluaran Gaji** — Bar chart (6 bulan terakhir)
- **Grafik Kehadiran Employee** — Bar + Line chart dengan pemilih periode (3/6/12 bulan)

---

## 🗃️ Database Schema

```
users ─────────────┐
                    │ 1:1
departments ───┐    ▼
positions ─────┤  employees ──┬── attendances
work_schedules ┘    │         ├── leave_requests ── leave_histories
                    │         ├── leave_balances ──┘
                    │         └── salaries
                    │
                    └── (FK: department_id, position_id, schedule_id)
```

**10 tabel utama** dengan Foreign Key constraints, unique indexes, dan cascade/restrict rules yang ketat.

---

## 🚀 Instalasi & Setup

### Prasyarat
- PHP ≥ 8.2
- Composer
- Node.js ≥ 18 & npm
- PostgreSQL ≥ 14

### Langkah Instalasi

```bash
# 1. Clone repository
git clone https://github.com/MIKELIZER/hr-dashboard.git
cd hr-dashboard

# 2. Install dependensi PHP
composer install

# 3. Install dependensi frontend
npm install

# 4. Salin file environment
cp .env.example .env

# 5. Generate application key
php artisan key:generate

# 6. Konfigurasi database di file .env
#    Ubah bagian berikut sesuai pengaturan PostgreSQL Anda:
#    DB_CONNECTION=pgsql
#    DB_HOST=127.0.0.1
#    DB_PORT=5432
#    DB_DATABASE=hr_dashboard
#    DB_USERNAME=postgres
#    DB_PASSWORD=your_password

# 7. Buat database PostgreSQL
#    Jalankan di psql: CREATE DATABASE hr_dashboard;

# 8. Jalankan migrasi & seeder
php artisan migrate:fresh --seed

# 9. Build assets frontend
npm run build

# 10. Jalankan server lokal
php artisan serve
```

Buka **http://127.0.0.1:8000** di browser Anda.

---

## 🔑 Akun Demo

| Role | Email | Password |
|------|-------|----------|
| **HR Admin** | `admin@hr.com` | `password` |
| Employee | `budi@company.com` | `password` |
| Employee | `siti@company.com` | `password` |
| Employee | `ahmad@company.com` | `password` |
| Employee | `dewi@company.com` | `password` |

> Total 15 akun employee tersedia. Semua menggunakan password: `password`

---

## 🏗️ Arsitektur Proyek

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── HR/                    # Panel HR Admin
│   │   │   ├── DashboardController
│   │   │   ├── DepartmentController
│   │   │   ├── PositionController
│   │   │   ├── WorkScheduleController
│   │   │   ├── EmployeeController
│   │   │   ├── AttendanceController
│   │   │   ├── LeaveRequestController
│   │   │   └── SalaryController
│   │   └── Employee/              # Portal Employee
│   │       ├── DashboardController
│   │       ├── AttendanceController
│   │       ├── LeaveRequestController
│   │       └── SalaryController
│   └── Middleware/
│       └── RoleMiddleware          # Proteksi akses berbasis role
├── Models/                         # 10 Eloquent Models
resources/
├── css/app.css                     # Custom theme (CSS variables, animations)
├── views/
│   ├── layouts/                    # HR sidebar & Employee navbar
│   ├── auth/                       # Login page
│   ├── hr/                         # 16 views HR Admin
│   └── employee/                   # 7 views Employee
```

---

## 🛡️ Business Rules

| Rule | Implementasi |
|------|-------------|
| Akses panel sesuai role | `RoleMiddleware` memblokir akses silang |
| Satu absensi per hari | Constraint `UNIQUE(employee_id, attendance_date)` |
| Deteksi keterlambatan | Otomatis berdasarkan `work_schedules.start_time + late_tolerance` |
| Pemotongan cuti saat approve | `DB::transaction()` → update status + potong saldo + catat history |
| Gaji Draft → Release | Gaji yang sudah di-release tidak bisa diedit/dihapus |
| Total gaji otomatis | Model Observer: `total_salary = base + allowance - deduction` |
| Employee hanya lihat gajinya | Query filter `employee_id` + status `released` |

---

## 🛠️ Tech Stack

| Layer | Teknologi |
|-------|-----------|
| **Backend** | Laravel 12 (PHP 8.2+) |
| **Frontend** | Bootstrap 5.3 + Bootstrap Icons |
| **Database** | PostgreSQL 16 |
| **Charting** | Chart.js 4 |
| **Build Tool** | Vite |
| **Auth** | Laravel Breeze (authentication only) |
| **Font** | Inter (Google Fonts) |

---

## 📄 Lisensi

Proyek ini dibuat untuk keperluan akademik mata kuliah **Basis Data 2**.

---

<p align="center">
  Built with ❤️ using Laravel 12
</p>
