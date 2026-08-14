# DMR Hospital Portal

A comprehensive healthcare management dashboard with a Vue.js (Vite) frontend and a Custom PHP Backend utilizing Eloquent ORM and Phinx migrations.

## 🛠️ Prerequisites
Before starting, ensure you have the following installed on your laptop:
- **XAMPP** (Apache & MySQL) with **PHP 8.2** or higher.
- **Node.js** (v18 or higher) and **npm**.
- **Composer** (PHP Package Manager).

---

## 🚀 Setup Instructions

### Step 1: Project Placement
1. Copy or clone the `DMR_project` folder into your XAMPP `htdocs` directory:
   - **Mac:** `/Applications/XAMPP/xamppfiles/htdocs/DMR_project`
   - **Windows:** `C:\xampp\htdocs\DMR_project`

### Step 2: Database Setup
1. Open the **XAMPP Control Panel** and Start both **Apache** and **MySQL**.
2. Open your web browser and go to [http://localhost/phpmyadmin](http://localhost/phpmyadmin).
3. Click "New" and create an empty database named exactly: **`dmr_db`** (leave the collation as default).

### Step 3: Backend Setup
1. Open your Terminal (Mac) or Command Prompt / PowerShell (Windows).
2. Navigate to the `backend` directory of the project:
   ```bash
   cd /Applications/XAMPP/xamppfiles/htdocs/DMR_project/backend
   ```
   *(Note for Windows users: `cd C:\xampp\htdocs\DMR_project\backend`)*
3. Install all required PHP dependencies:
   ```bash
   composer install
   ```
4. Run the database migrations (this creates the `users` table):
   ```bash
   vendor/bin/phinx migrate
   ```
5. Run the database seeder (this inserts the default admin account):
   ```bash
   vendor/bin/phinx seed:run
   ```

### Step 4: Frontend Setup
1. Open a **New** Terminal window (keep the old one open if you like).
2. Navigate to the `frontend` directory:
   ```bash
   cd /Applications/XAMPP/xamppfiles/htdocs/DMR_project/frontend
   ```
   *(Note for Windows users: `cd C:\xampp\htdocs\DMR_project\frontend`)*
3. Install all required Node dependencies:
   ```bash
   npm install
   ```
4. Start the Vue.js development server:
   ```bash
   npm run dev
   ```

### Step 5: Test the Application
1. After running `npm run dev`, the terminal will display a local link (usually `http://localhost:5173` or `http://localhost:5174`).
2. Open that link in your web browser.
3. You should see the DMR Hospital Portal login page.
4. Log in using the default admin credentials seeded in Step 3:
   - **Email:** `admin@gmail.com`
   - **Password:** `pass1234`

---

## 📂 Project Architecture
* **Frontend:** Vue 3, Vite, TailwindCSS, Lucide Icons, Vue Router.
* **Backend:** Plain PHP (MVC Architecture), Illuminate Database (Eloquent ORM), Phinx (Migrations/Seeding).
* **Communication:** REST API via standard HTTP POST/GET requests (JSON encoded).
