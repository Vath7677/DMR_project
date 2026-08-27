# DMR (Hospital Management System)

A comprehensive, secure, and modern healthcare management dashboard designed to streamline clinic and hospital operations. Built with a Vue.js 3 frontend and a robust PHP backend.

## Key Features
- **🔐 Secure Authentication:** Encrypted passwords (Bcrypt) and secure session management.
- **👥 Patient Management:** Complete CRUD operations for patient profiles and demographics.
- **📂 Health Records:** Track diagnoses, treatments, vitals (BMI, Blood Pressure), and upload medical attachments.
- **📊 Interactive Dashboard:** Visual statistics and real-time data using Chart.js.

## 💻 Technologies Used
**Frontend:**
- Vue 3 (Composition API) + TypeScript
- Vite (Build Tool)
- Tailwind CSS v4 (Styling)
- Vue Router (Navigation Guards)
- Axios (API Communication)
- Lucide Vue Next (Icons)

**Backend:**
- PHP 8.2 + Slim Framework 4 (RESTful API)
- Eloquent ORM (Database Management)
- Phinx (Database Migrations & Seeding)

**Infrastructure:**
- Docker & Docker Compose
- MySQL 8.0
- phpMyAdmin

---

## Quick Start (Recommended: Docker)

The easiest way to run the backend and database is using Docker. You do not need to install PHP, MySQL, or XAMPP on your host machine.

### 1. Start the Backend & Database
1. Ensure **Docker** and **Docker Compose** are installed and running.
2. Open your terminal, navigate to the root of the project (`DMR_project`), and run:
   ```bash
   docker-compose up -d
   ```
*(This will start the PHP API on port 80, MySQL on port 3306, and phpMyAdmin on port 8081)*

### 2. Start the Frontend
1. Open a new terminal window and navigate to the `frontend` folder:
   ```bash
   cd frontend
   ```
2. Install dependencies and start the Vite development server:
   ```bash
   npm install
   npm run dev
   ```
3. Open the provided local link in your browser (usually `http://localhost:5173` or `http://localhost:5174`).

---

## Security Highlights
This project has been heavily reviewed for security best practices:
- **SQL Injection Prevention:** Utilizes Eloquent ORM Parameterized Queries to prevent payload execution.
- **Data Encryption:** User passwords are not stored in plain text. They are securely hashed using PHP's native `password_hash()` (Bcrypt algorithm) and verified via `password_verify()`.
- **CORS Protection:** Configured to strictly allow communication between the allowed frontend origins and the backend API.
