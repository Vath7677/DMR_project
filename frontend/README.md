# DMR Hospital Portal 
A modern Healthcare Management Dashboard designed for doctors to manage patient records, daily appointments, and securely oversee hospital resources.

## Technology Stack
- **Frontend:** Vue.js 3, Vite, Tailwind CSS, TypeScript
- **Backend:** Core PHP (OOP Architecture - API Gateway Pattern)
- **Database:** MySQL (Hosted on Aiven)
- **Version Control:** Git (Private Repository)
## Setup Instructions (How to run locally)
Follow these steps to set up the project on your local machine after cloning the repository.
### 1. Clone the Repository
```bash
git clone <your-repository-url>
cd DMR_project
```
### 2. Frontend Setup
Make sure you have [Node.js](https://nodejs.org/) installed on your computer.
```bash
# Navigate to the frontend directory
cd frontend
# Install dependencies
npm install
# Run the development server
npm run dev
```
*The frontend application will be accessible at `http://localhost:5175`*
### 3. Backend & Database Setup
Make sure you have XAMPP installed for the PHP local server.
1. Place the `DMR_project` folder inside your XAMPP `htdocs` directory.
2. Start the **Apache** service in your XAMPP control panel.
3. Navigate to `backend/config/db.php` and update the database credentials to match your local database or your Aiven Cloud Database.
4. The backend API is strictly routed through the public entry point at: `http://localhost/DMR_project/backend/public/`

## Security Note
- This portal is built for internal hospital use only. 
- All API requests are processed through a secure Front Controller (`index.php`), and cross-origin requests (CORS) are strictly monitored.

## Copyright & License
&copy; 2026 **Vath**. All Rights Reserved.
This project is proprietary and confidential. Unauthorized copying of this project or its files, via any medium, is strictly prohibited.