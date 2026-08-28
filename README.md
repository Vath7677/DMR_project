# DMR Hospital Management System (Digital Medical Records)

A modern, high-performance, and secure healthcare management web application designed for hospitals and clinics to manage patient demographics, clinical health records, consultations, financial analytics, and user access control.

## URL Web : 

https://dmr-project.vercel.app/

---

## Architecture & Technology Stack

| Layer | Technologies Used | Details |
|---|---|---|
| **Frontend** | Vue 3, Vite, TypeScript, Tailwind CSS, Lucide Icons, Chart.js / Vue-Chartjs | SPA architecture with responsive modern UI and role-based views. |
| **Backend** | PHP 8.2+, Slim 4 Framework, Eloquent ORM (Illuminate Database) | Lightweight RESTful API with CORS preflight and session security. |
| **Database** | MySQL 8.0 / TiDB Cloud (AWS Singapore) | Cloud database with SSL/TLS encryption and persistent connection pooling. |

---

## Database Schemas

The database consists of 6 primary tables:

```
┌─────────────────────────────────────────────────────────────┐
│                       DATABASE: test                        │
├─────────────────┬──────────────────┬────────────────────────┤
│ users           │ patients         │ health_records         │
│ system_activ... │ login_activities │ payments               │
└─────────────────┴──────────────────┴────────────────────────┘
```

1. **`users`**: System accounts (`superadmin`, `doctor`, `nurse`, `staff`).
2. **`patients`**: Patient demographics (`PID-XXXX`, name, gender, DOB, phone, address, status).
3. **`health_records`**: Clinical consultations (`REC-XXXX`, patient linkage, vitals, doctor, fee, notes, attachments).
4. **`system_activities`**: Audit trail and real-time activity log.
5. **`login_activities`**: Session and device tracking for user security.
6. **`payments`**: Consultation billing and payment status logs.

---

## API Endpoints Summary

### 1. Authentication (`/api/auth`)
- `POST /api/auth/login` - Authenticate user credentials with brute-force rate-limiting.
- `POST /api/auth/logout` - Invalidate user session.
- `GET /api/user/profile` - Fetch current authenticated user profile.
- `POST /api/user/profile` - Update username, email, and password.
- `POST /api/user/avatar` - Upload and persist user avatar image.
- `DELETE /api/user/avatar` - Remove user avatar image.
- `GET /api/user/login-activities` - Get recent login sessions and devices.

### 2. Patients Management (`/api/patients`)
- `GET /api/patients` - Retrieve all registered patients.
- `POST /api/patients` - Register a new patient (auto-generates `PID-100X`).
- `GET /api/patients/{id}` - Get patient profile by ID.
- `PUT /api/patients/{id}` - Update patient demographics.
- `DELETE /api/patients/{id}` - Remove patient profile.

### 3. Health Records (`/api/health-records`)
- `GET /api/health-records` - Fetch all medical consultation records.
- `POST /api/health-records` - Log a new clinical encounter with attachments and vitals.
- `GET /api/health-records/{id}` - Fetch encounter details.
- `POST /api/health-records/{id}` - Update existing encounter.
- `DELETE /api/health-records/{id}` - Delete encounter and attached files.

### 4. Dashboard & Analytics (`/api/dashboard` & `/api/reports`)
- `GET /api/dashboard/stats` - **High-Speed Unified Endpoint** (Returns total patients, active patients, new patients, encounter counts, and recent activities in 1 single round-trip).
- `GET /api/reports/financial?deviceMonth=YYYY-MM` - Dynamic 12-month analytics, consecutive 1–31 day consultation trends, department distributions, and payroll metrics.

### 5. Administrative Controls (`/api/admin`)
- `GET /api/admin/users` - List all staff accounts (Superadmin only).
- `POST /api/admin/users` - Create new staff account.
- `PUT /api/admin/users/{id}` - Modify user account and permissions.
- `DELETE /api/admin/users/{id}` - Delete user account (Protected: original superadmin cannot be deleted).
- `GET /api/admin/seed-realistic-data` - Populate realistic 20 patients & 29 health records across multiple months with returning visits.
- `GET /api/admin/clean-all-data` - Safely truncate clinical records while keeping `users` table 100% intact.

---

## Comprehensive Code Audit & Bug Fixes Report

During the full codebase audit, several issues and performance bottlenecks were identified and resolved:

| # | Component | Issue Identified | Status / Fix Applied |
|---|---|---|---|
| **1** | `backend/config/database.php` & `AuthController.php` | **Severe Latency Bottleneck**: 5 separate `ALTER TABLE` DDL queries were running on *every single API request*, causing 3–5 second freezes over TiDB Cloud. | **RESOLVED**: Removed all runtime DDL queries. API response latency dropped from ~4,000ms to **< 50ms**. |
| **2** | `frontend/src/views/DashboardView.vue` | **Multiple Sequential Roundtrips**: Dashboard was making 3 separate HTTP requests to Singapore, tripling latency on slow connections. | **RESOLVED**: Created `GET /api/dashboard/stats` unified endpoint to deliver all stats in **1 single fast response**. |
| **3** | `backend/src/UserController.php` | **Logical Bug in Revenue Fallback**: When the current active month had $0 revenue, it was overriding the month's metric with all-time revenue sum. | **RESOLVED**: Removed incorrect fallback so months with $0 revenue report true and accurate financial numbers. |
| **4** | `frontend/src/components/Login.vue` | **Template Syntax Typo**: Stray trailing backslash `\` in root `<div class="...">\`. | **RESOLVED**: Fixed template tag syntax. |
| **5** | `frontend/src/views/ReportsView.vue` | **Obsolete State & Unused Imports**: Unused chart options and dropdown refs from older iterations were lingering in the script block. | **RESOLVED**: Cleaned all unused imports, chart options, and event listeners. |
| **6** | `frontend/src/views/HealthRecordsView.vue` | **Fee String Formatting**: Consultation fees were being passed as string decimals (`"35.00"`). | **RESOLVED**: Standardized to pure integer fees (`35`, `$35`, `$50`, `$100`). |
| **7** | `backend/src/PatientController.php` & `HealthRecordController.php` | **Auto-Increment Prefix Compatibility**: Regex ID extractors only supported `P-` and `HR-`. | **RESOLVED**: Enhanced regex to support both `PID-` / `P-` and `REC-` / `HR-`. |
| **8** | `backend/public/index.php` | **Table Truncation Mismatch**: `clean-all-data` looked for `activities` instead of `system_activities`. | **RESOLVED**: Added `system_activities` check and truncation. |

---

## Local Development Setup

### 1. Backend Setup
1. Place the project inside `/Applications/XAMPP/xamppfiles/htdocs/DMR_project/` (or your Apache web directory).
2. Start Apache and MySQL via XAMPP or Docker.
3. Configure `backend/config/database.php` with your database credentials.
4. Access API at `http://localhost/DMR_project/backend/public`.

### 2. Frontend Setup
1. Navigate to frontend directory:
   ```bash
   cd frontend
   npm install
   npm run dev
   ```
2. Open browser at `http://localhost:5175`.
---

## 🔒 Security & Privacy Practices
- **Never push code to GitHub without explicit user permission.**
- Password hashing uses `PASSWORD_BCRYPT`.
- XSS prevention uses strict input sanitization on all POST/PUT endpoints.
- Cross-origin credentials and session protection use `SameSite` and `HttpOnly` cookie flags.
