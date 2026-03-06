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
- WSL2 with an Ubuntu distribution (recommended for Windows users)
- [Composer](https://getcomposer.org/) *(optional if you install PHP dependencies using Docker only)*
- [Node.js & NPM](https://nodejs.org/) *(recommended in WSL for frontend assets and Vite)*
- PhpStorm *(recommended IDE)*

> **Recommended setup on Windows:** use **WSL2 + Ubuntu + Docker Desktop + PhpStorm** and keep the project inside the Linux filesystem, for example `~/projects/gym-backend`.

## ⚠️ Important Notes for Windows + WSL Users

If you are using Windows, **do not place the project under** paths such as:

```bash
/mnt/c/Users/...
```

This often causes:

- slower performance
- file permission issues
- unreliable file watching for Vite
- problems with hot reload

Use a WSL-native path instead:

```bash
mkdir -p ~/projects               
cd ~/projects                             #(NOT "cd virgulilla")
git clone <repository-url> gym-backend
cd gym-backend
```

## 🛠️ Setup & Installation

The recommended way to run this project is using **Laravel Sail**.

### 1. Configure Docker Desktop and WSL integration

On Windows:

1. Open **Docker Desktop**
2. Go to **Settings > Resources > WSL Integration**
3. Enable integration for your Ubuntu distribution
4. Make sure Docker Desktop is running before starting the project

From your Ubuntu terminal in WSL, verify Docker is available:

```bash
docker version
docker ps
```

If these commands fail, fix Docker/WSL integration first before continuing.

### 2. Clone the repository inside WSL

```bash
# Make a directory for your repositories and change directory. (Opcional)
mkdir -p ~/projects
cd ~/projects

# Clone the repository into the projects directory.
git clone <repository-url> gym-backend

# Change directory to the cloned repository.
cd gym-backend
```

### 3. Install Dependencies

#### Option A — Composer installed in WSL
If you already have PHP and Composer available in your WSL environment:

```bash
composer install
npm install
```

#### Option B — Without local Composer
If you do not want to install Composer locally, use the official Composer container:
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

Check that the containers are running:

```bash
./vendor/bin/sail ps
```

### 6. Generate the application key and run migrations
Run migrations and generate the application key:
```bash
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate
```

### 7. Install frontend dependencies

If Node.js and NPM are installed in WSL:

```bash
npm install
```

To build frontend assets once:

```bash
 npm run build
```

To run Vite in development mode:

```bash
npm run dev
```
> **Note:** this project includes Vite/Tailwind tooling. If `npm` is not available in your WSL environment, frontend commands will fail.

---
## ▶️ Daily Development Workflow

### Start containers

```bash
./vendor/bin/sail up -d
```

### Run Artisan commands

```bash
./vendor/bin/sail artisan
```

Example:

```bash
./vendor/bin/sail artisan migrate
```

### Run Tests

```bash
./vendor/bin/sail test
```

### Run Vite
```bash
npm run dev
```

## 📜 Available Scripts

Managed via `composer.json`:

| Script | Description |
| :--- | :--- |
| `composer setup` | Install dependencies, copy `.env`, generate key, and run migrations. |
| `composer dev` | Start the development server with Pail, Queue, and Vite. |
| `composer test` | Clear configuration and run PHPUnit tests. |
| `./vendor/bin/sail artisan ...` | Run any Laravel Artisan command inside the Docker container. |

> **Important:** `composer setup` and `composer dev` use `php`, `artisan`, `npm`, and `vite` from your local/WSL environment.  
> If you are working with **Sail-only**, prefer `./vendor/bin/sail artisan ...` for backend commands and use `npm` from WSL for frontend assets.
---
## 💻 PhpStorm Recommendations

If you use PhpStorm on Windows with a project running in WSL:

### Open the project from WSL
Open the project located in your WSL filesystem, for example:
```bash
~/projects/gym-backend
```
Avoid opening the project from `C:\` or `/mnt/c/...`.

### Configure the terminal
Set PhpStorm's integrated terminal to use **WSL / Ubuntu** so project commands run in the correct environment.

### Configure PHP
Use a PHP interpreter that matches your workflow:

- **Recommended for Sail users:** configure a **Docker-based PHP interpreter**
- or use PHP from **WSL** if your team works that way

### Configure Composer
Make sure Composer uses the same PHP interpreter/environment as the project.  
Avoid mixing:

- project files in WSL
- Composer from Windows
- PHP from another environment

### Configure Node.js
If you run Vite from WSL, make sure your Node.js setup in PhpStorm points to the same WSL environment or run frontend commands from the WSL terminal.

### If file watching is unreliable
If Vite changes are not detected:

- confirm the project is inside WSL filesystem
- do not use `/mnt/c/...`
- run `npm run dev` from WSL
- verify Docker, WSL, and PhpStorm are all pointed to the same environment

---
## 🧪 Testing

Run the test suite:

```bash
./vendor/bin/sail artisan test
```

If you also have a local PHP environment configured correctly, you may use:

```bash
composer test
```

---

## 🧯 Troubleshooting

### `./vendor/bin/sail: No such file or directory`
You have not installed PHP dependencies yet.

Run:

```bash
composer install
```


or use the Docker Composer command shown above.

### Docker commands fail inside WSL
Examples:

```bash
docker version docker ps
```

If they fail:

- ensure Docker Desktop is open
- enable WSL integration for Ubuntu
- restart Docker Desktop
- restart your WSL terminal

### `composer dev` fails with `php: command not found` or `npm: command not found`
Those scripts use your **local/WSL tools**, not Sail.

Use one of these approaches:

- install PHP/Composer/Node/NPM in WSL
- or run backend commands with Sail and frontend commands with NPM from WSL

### Vite does not detect file changes
Most common cause on Windows: the project is located under `/mnt/c/...`.

Move the project to a WSL-native directory, for example:

```bash 
~/projects/gym-backend
```
### Permission issues on generated files
This usually happens when commands are run from mixed environments or with mismatched users.

Recommended:

- keep the project in WSL
- run commands consistently from WSL
- avoid mixing Windows terminal, WSL terminal, and Docker commands without a clear workflow

---

## 📂 Project Structure

- `app/Http/Controllers`: API Controllers
- `app/Models`: Eloquent Models
- `app/Domain`: Domain logic and custom exceptions
- `database/migrations`: Database schema definitions
- `routes/api.php`: Main API route definitions
- `tests/`: Feature and Unit tests

---

## 🔑 Environment Variables

Key variables in `.env`:

- `APP_KEY`: Application encryption key
- `DB_CONNECTION`: `sqlite` or `mysql`
- `DB_DATABASE`: Database name
- `APP_URL`: Base URL, usually `http://localhost`

---

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
