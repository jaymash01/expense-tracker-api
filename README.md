# Expense Tracker API

A professional Laravel-based RESTful API for managing personal finances, tracking expenses, and organizing budgets. Built with security and performance in mind using JWT authentication.

---

## 🚀 Getting Started

Follow these instructions to get the project up and running on your local machine.

### Prerequisites

Ensure you have the following installed:

- **PHP** (>= 8.2)
- **Composer**
- **Node.js & NPM**
- **MySQL**

---

## 🛠️ Installation & Setup

### 1. Clone the Repository

```bash
git clone [https://github.com/jaymash01/expense-tracker-api.git](https://github.com/jaymash01/expense-tracker-api.git)
cd expense-tracker-api
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Environment Configuration

Copy the template to create your local environment file:

```bash
cp .env.example .env
```

### 4. Database Setup

Open the .env file in your editor and update the database access credentials:

```plaintext
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=expense_tracker
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 5. Generate Security Keys

Generate the application key and the JWT secret for authentication:

```bash
php artisan key:generate
php artisan jwt:secret
```

### 6. Database Migrations & Seeding

Set up your database tables and populate them with initial data:

```bash
php artisan migrate
php artisan db:seed
```

### 7. Compile Frontend Assets

Install Node dependencies and build the assets:

```bash
npm install
npm run build
```

## 🏃 Running the Application

Start the Laravel development server:

```bash
php artisan serve
```

The project homepage will now be accessible at: 👉 http://localhost:8000

## 🔧 Troubleshooting & Permissions

If you encounter errors related to file uploads or directory access, follow these steps:

### 1. Public Uploads Permissions

Since this project uses a custom public/uploads directory for files, ensure it is writable by the server:

```bash
chmod -R 775 public/uploads
```

### 2. Internal Cache Permissions

Laravel still requires write access to internal folders for logging and caching:

```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### 3. Clear Configuration Cache

If your .env changes are not being detected:

```bash
php artisan config:clear
php artisan cache:clear
```

## 🔑 Key Features

- JWT Authentication: Secure API access via JSON Web Tokens.
- Expense Tracking: Comprehensive CRUD functionality for expenses.
- Data Seeding: Pre-configured sample data for immediate testing.
- Asset Management: Custom upload handling via the uploads disk.
