# FoundIT - Campus Lost & Found Portal (BRAC University)

> **Team Base Skeleton**  
> Initialized for our 4-person engineering team to maintain a single unified codebase on GitHub without merge conflicts.

---

## 🏗️ Architecture & Tech Stack
* **Backend:** PHP 8 with Laravel (Strict MVC: Eloquent Models, Blade Views, Artisan Controllers).
* **Frontend:** Laravel Blade templates styled with Tailwind CSS and our custom design system.
* **Database:** MySQL.
* **AI Microservice:** Python FastAPI (handles Image Similarity Matching and OCR-based PII Redaction via HTTP requests).

---

## 🚀 Setup Instructions for the Team

### For Team Member Initializing & Pushing to GitHub (Prantik):
```bash
git init
git add .
git commit -m "chore: initialize clean Laravel skeleton with login blade template and design system"
git branch -M main
git remote add origin <YOUR_GITHUB_REPO_URL>
git push -u origin main
```
*Notify the group once pushed so everyone pulls from the same base!*

### For the Other 3 Teammates (Cloning & Running):
```bash
# 1. Clone the repository
git clone <YOUR_GITHUB_REPO_URL>
cd Project-F

# 2. Install PHP backend dependencies
composer install

# 3. Install frontend dependencies
npm install

# 4. Set up environment configuration
cp .env.example .env

# 5. Generate application key
php artisan key:generate

# 6. Configure your local MySQL database in .env
# DB_DATABASE=foundit_db
# DB_USERNAME=root
# DB_PASSWORD=

# 7. Run database migrations
php artisan migrate

# 8. Start local development servers
php artisan serve
npm run dev
```

---

## 📁 Repository Structure

```text
Project-F/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Controller.php
│   │   │   └── Auth/
│   │   │       └── LoginController.php    <-- Returns login view
│   │   └── Middleware/
│   ├── Models/
│   │   └── User.php                       <-- User model with role taxonomy
│   └── Providers/
├── bootstrap/
├── config/                                <-- Database (MySQL), app, auth configs
├── database/
│   ├── migrations/                        <-- Base users migration with roles
│   └── seeders/
├── public/
│   ├── css/
│   │   └── style.css                      <-- Official FoundIT custom design system
│   └── index.php
├── resources/
│   ├── css/
│   │   ├── app.css                        <-- Tailwind + design system imports
│   │   └── style.css
│   ├── js/
│   │   └── app.js
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php              <-- Global layout (FontAwesome + Inter + CSS)
│       └── auth/
│           └── login.blade.php            <-- FoundIT Login view (exact prototype design)
├── routes/
│   ├── web.php                            <-- Root route '/' rendering login
│   └── api.php
├── prototype/                             <-- Safe backup of previous HTML/CSS/JS mock
├── artisan
├── composer.json
├── package.json
└── vite.config.js
```

---

## 📌 Status
* **Login View:** Kept exactly as designed, integrated with Blade and CSRF protection.
* **Features:** Intentionally clean with no business logic yet, ready for Sprint 1 module assignments.
