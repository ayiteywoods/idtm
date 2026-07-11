# University School Management System

Laravel 13 + MySQL university website and student management portal for the **Institute of Development & Technology Management (IDTM)**.

## Features (Foundation)

### Public Website
- Homepage with CMS-managed content
- Static pages (About, Admissions, Terms, Privacy)

### Student Portal (`/portal`)
- Dashboard, Profile, Wallet, Course Registration
- Learning Materials, Help Desk (FAQs)
- Change Requests, Online Library

### Faculty Portal (`/faculty`)
- View assigned courses and registered students
- Learning materials, grades & resit exams (UI ready)
- Online library book uploads (UI ready)

### Admin Dashboard (`/admin`)
- Overview stats
- Students, Faculty, Courses management (list views)
- Change request review
- Website CMS (list view)

## Tech Stack

- **Laravel 13** with PHP 8.4
- **MySQL** database
- **Tailwind CSS 4** via Vite
- Role-based authentication (Admin, Student, Faculty)

## Setup

### 1. Create MySQL database

```bash
mysql -u root -e "CREATE DATABASE school CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

Or via a MySQL client:

```sql
CREATE DATABASE school CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 2. Configure environment

Copy `.env.example` to `.env` and set your MySQL credentials:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=school
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 3. Install dependencies

```bash
composer install
npm install
```

### 4. Run migrations and seed demo data

```bash
php artisan migrate:fresh --seed
```

### 5. Build assets and start server

```bash
npm run build
php artisan serve
```

Visit `http://localhost:8000`

## Demo Login Credentials

| Role    | Username / Email      | Password  |
|---------|----------------------|-----------|
| Admin   | `admin` or `admin@idtm.edu.gh` | `password` |
| Student | `dankrah` or `niiaankrah@live.com` | `password` |
| Faculty | `kmensah` or `kmensah@idtm.edu.gh` | `password` |

Select the matching account type on the login page.

## Project Structure

```
app/
  Http/Controllers/
    Admin/          # Admin dashboard
    Student/        # Student portal
    Faculty/        # Faculty portal
    Auth/           # Login/logout
  Models/           # Eloquent models
  UserRole.php      # Admin, Student, Faculty enum
database/migrations/ # MySQL schema
resources/views/
  layouts/          # Website & portal layouts
  website/          # Public pages
  student/          # Student portal views
  faculty/          # Faculty portal views
  admin/            # Admin dashboard views
```

## Next Steps

- CRUD forms for admin (students, faculty, course assignment)
- File uploads for learning materials and library books
- Grade entry and resit exam forms for faculty
- Change request submission and approval workflow
- Payment deposit and receipt upload
- Full website CMS editing
