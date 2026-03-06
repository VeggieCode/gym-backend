# Gym Backend (Laravel 12)

A robust backend API for a gym management system, built with Laravel 12. It provides endpoints for authentication, routines, and subscription plans management.

## 🚀 Tech Stack

- **Language:** PHP ^8.2
- **Framework:** [Laravel 12](https://laravel.com)
- **Authentication:** Laravel Sanctum
- **Database:** MySQL / SQLite
- **Environment:** Laravel Sail (Docker)
- **Package Manager:** Composer & NPM
- **Testing:** PHPUnit

## 📋 Requirements

Before starting, ensure you have the following installed:
- [Docker Desktop](https://www.docker.com/products/docker-desktop)
- WSL2 (If using Windows)
- [Composer](https://getcomposer.org/) (optional, if running locally without Docker)
- [Node.js & NPM](https://nodejs.org/)

## 🛠️ Setup & Installation

The recommended way to run this project is using **Laravel Sail**.

### 1. Clone the repository
```bash
git clone <repository-url> gym-backend
cd gym-backend
```

### 2. Configure Docker & WSL (Windows Users)
- Open Docker Desktop.
- Go to **Settings > Resources > WSL Integration**.
- Enable integration with your default WSL distro (e.g., Ubuntu).

### 3. Install Dependencies
If you have PHP and Composer installed locally:
```bash
composer install
npm install
```
Otherwise, you can use a temporary container to install dependencies:
```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php84-composer:latest \
    composer install --ignore-platform-reqs
```

### 4. Environment Configuration
Copy the `.env.example` file to `.env`:
```bash
cp .env.example .env
```
Update your `.env` file with your database credentials and other configurations.

### 5. Start the Application (Sail)
```bash
./vendor/bin/sail up -d
```
Your application will be available at: [http://localhost](http://localhost)

### 6. Finalize Setup
Run migrations and generate the application key:
```bash
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate
```

---

## 📜 Available Scripts

Managed via `composer.json`:

| Script | Description |
| :--- | :--- |
| `composer setup` | Install dependencies, copy `.env`, generate key, and run migrations. |
| `composer dev` | Start the development server with Pail, Queue, and Vite. |
| `composer test` | Clear configuration and run PHPUnit tests. |
| `./vendor/bin/sail artisan ...` | Run any Laravel Artisan command inside the Docker container. |

---

## 📂 Project Structure

- `app/Http/Controllers`: API Controllers (Auth, Plans, Rutinas).
- `app/Models`: Eloquent Models (User, etc.).
- `app/Domain`: Domain logic and custom exceptions.
- `database/migrations`: Database schema definitions.
- `routes/api.php`: Main API route definitions.
- `tests/`: Feature and Unit tests.

---

## 🔑 Environment Variables

Key variables in `.env`:
- `APP_KEY`: Application encryption key.
- `DB_CONNECTION`: `sqlite` (default) or `mysql`.
- `DB_DATABASE`: Database name.
- `APP_URL`: Base URL (default: `http://localhost`).

---

## 🧪 Testing

Run the test suite using PHPUnit:
```bash
./vendor/bin/sail artisan test
# OR
composer test
```

---

## 📄 License
This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
