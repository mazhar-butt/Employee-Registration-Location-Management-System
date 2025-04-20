# Employee Registration & Location Management System

This is a web application built using **Laravel** (backend) and **Vue.js** (frontend), designed to manage employee registrations along with dynamic country, state, and city selection. The application also supports **multilingual translations**, including support for **Urdu and English**.

## 🛠️ Features

- 👨‍💼 Employee registration with step-by-step forms
- 🌐 Country → State → City selection flow
- 💬 Multilingual support (English & Urdu)
- 🔐 Login system
- 🧾 Admin dashboard to manage employee details
- 🗺️ Location management module (Add/Edit/Delete Countries, States, Cities)
- ✨ Vue.js frontend with Laravel Blade integration
- 📬 SweetAlert notifications for user feedback

## 📁 Project Structure

### Laravel Backend
- `routes/web.php` — All web routes
- `app/Http/Controllers/` — Controllers for handling logic
- `resources/views/` — Blade views for login/success
- `app/Models/` — Eloquent models for `Country`, `State`, `City`, `Employee`

### Vue.js Frontend
- Vue components are rendered through the main `app.blade.php` file
- Form steps include:
  - Register
  - Select Country → State → City
  - Employee Details
- Multilingual support using language files (published via `php artisan lang:publish`)

## 🚀 Installation

### Prerequisites
- PHP 8.x
- Composer
- Node.js and npm
- MySQL or another database
- Laravel CLI


