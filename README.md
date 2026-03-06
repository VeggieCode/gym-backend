# Gym Backend (Laravel 12)

Backend API for a gym management system, built with Laravel 12. It provides endpoints for authentication, routines, and subscription plan management.

## 🚀 Tech Stack

- **Language:** PHP ^8.2
- **Framework:** [Laravel 12](https://laravel.com)
- **Authentication:** Laravel Sanctum
- **Database:** MySQL / SQLite
- **Environment:** Laravel Sail (Docker)
- **Frontend Tooling:** Vite + Tailwind CSS
- **Package Manager:** Composer & NPM
- **Testing:** PHPUnit

## 📋 Requirements

Before starting, make sure you have the following installed:

- [Docker Desktop](https://www.docker.com/products/docker-desktop)
- WSL2 with an Ubuntu distribution if you are on Windows
- [Composer](https://getcomposer.org/) *(optional if you install PHP dependencies with Docker only)*
- [Node.js & NPM](https://nodejs.org/) *(recommended in WSL for Vite/frontend tooling)*
- PhpStorm *(recommended IDE)*

> **Recommended setup on Windows:** use **WSL2 + Ubuntu + Docker Desktop + PhpStorm**, and keep the project inside the Linux filesystem, for example `~/projects/gym-backend`.

---

## ⚠️ Important Notes for Windows + WSL Users

If you are using Windows, **do not place the project under** paths such as:
```bash
/mnt/c/Users/...
```
This often causes:

- poor performance
- file permission issues
- unstable file watchers
- problems with Vite hot reload
- inconsistent ownership between Windows, WSL, and Docker

Use a WSL-native path instead:
```bash
mkdir -p ~/projects
cd ~/projects
git clone <repository-url> gym-backend
cd gym-backend
```
---

## 🛠️ Setup & Installation

The recommended way to run this project is with **Laravel Sail**.

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
mkdir -p ~/projects
cd ~/projects
git clone <repository-url> gym-backend
cd gym-backend
```
### 3. Create the environment file
```bash
cp .env.example .env
```
### 4. Add WSL/Sail environment variables to `.env`

This project's `compose.yaml` uses `WWWUSER`, `WWWGROUP`, and `PWD`.  
To avoid warnings and permission mismatches, add these values to your `.env`.

Recommended values for WSL/Ubuntu:
```dotenv
WWWUSER=1000
WWWGROUP=1000
PWD=/var/www/html
```
> If your Linux user does not use UID/GID `1000`, check them with:
>
> ```bash
> id -u
> id -g
> ```

Then use those values in `.env`.

### 5. Install PHP dependencies

#### Option A — Composer installed in WSL

If you already have PHP and Composer available in your WSL environment:
```bash
composer install
```
#### Option B — Without local Composer

If you do not want to install Composer locally, use the official Composer container:
```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php84-composer:latest \
    composer install --ignore-platform-reqs
```
### 6. Start Sail
```bash
./vendor/bin/sail up -d
```
Check that the containers are running:
```bash
./vendor/bin/sail ps
```
### 7. Generate the application key
```bash
./vendor/bin/sail artisan key:generate
```
### 8. Configure the database and run migrations

Review your `.env` file before migrating.

#### Recommended MySQL configuration for Sail
```dotenv
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=sail
DB_PASSWORD=password
```
Then run:
```bash
./vendor/bin/sail artisan migrate
```
If you need seed data:
```bash
./vendor/bin/sail artisan migrate --seed
```
### 9. Install frontend dependencies

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
> **Note:** this project includes Vite/Tailwind tooling. If `npm` is not available in WSL, frontend commands will fail.

---

## ▶️ Daily Development Workflow

### Start containers
```bash
./vendor/bin/sail up -d
```
### Stop containers
```bash
./vendor/bin/sail down
```
### Rebuild containers
```bash
./vendor/bin/sail build --no-cache
./vendor/bin/sail up -d
```
### Run Artisan commands
```bash
./vendor/bin/sail artisan <command>
```
Example:
```bash
./vendor/bin/sail artisan migrate
```
### Run tests
```bash
./vendor/bin/sail artisan test
```
### Run Vite
```bash
npm run dev
```
---

## 📜 Available Scripts

Managed via `composer.json`:

| Script | Description |
| :--- | :--- |
| `composer setup` | Installs dependencies, copies `.env`, generates app key, runs migrations, installs npm packages, and builds assets. |
| `composer dev` | Starts Laravel development server, queue listener, logs, and Vite. |
| `composer test` | Clears config and runs tests. |
| `./vendor/bin/sail artisan ...` | Runs Artisan commands inside the Sail container. |

> **Important:** `composer setup` and `composer dev` use `php`, `artisan`, `npm`, and `vite` from your local/WSL environment.  
> If you are working **Sail-first**, prefer `./vendor/bin/sail artisan ...` for backend commands and use `npm` from WSL for frontend assets.

---

## 💻 PhpStorm Recommendations

If you use PhpStorm on Windows with a project running in WSL:

### Open the project from WSL

Open the project located in your WSL filesystem, for example:
```bash
~/projects/gym-backend
```
Avoid opening the project from `C:\...` or `/mnt/c/...`.

### Configure the terminal

Set PhpStorm's integrated terminal to use **WSL / Ubuntu** so project commands run in the correct environment.

### Configure PHP

Use a PHP interpreter that matches your workflow:

- **Recommended for Sail users:** configure a **Docker-based PHP interpreter**
- or use PHP from **WSL** if your team works that way

### Configure Composer

Make sure Composer uses the same PHP interpreter/environment as the project. Avoid mixing:

- project files in WSL
- Composer from Windows
- PHP from another environment

### Configure Node.js

If you run Vite from WSL, make sure PhpStorm uses the same WSL Node.js installation, or run frontend commands from the WSL terminal.

### Configure line endings

Use **LF** line endings for shell-related files when possible. Mixed line endings can break scripts and generate confusing behavior in WSL.

### If file watching is unreliable

If Vite changes are not detected:

- confirm the project is inside the WSL filesystem
- do not use `/mnt/c/...`
- run `npm run dev` from WSL
- verify Docker, WSL, and PhpStorm all point to the same environment

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

This section focuses on the most common installation and recovery problems seen when working with **Windows + WSL + Docker Desktop + Sail + PhpStorm**.

### 1. `./vendor/bin/sail: No such file or directory`

This means dependencies have not been installed yet, so Sail is not available.

#### Recovery

Run one of these:
```bash
composer install
```
or:
```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php84-composer:latest \
    composer install --ignore-platform-reqs
```
Then try again:
```bash
./vendor/bin/sail up -d
```
---

### 2. Docker commands fail inside WSL

Examples:
```bash
docker version
docker ps
```
If they fail, the problem is usually Docker Desktop or WSL integration, not Laravel.

#### Recovery checklist

- make sure **Docker Desktop is open**
- make sure **WSL integration** is enabled for Ubuntu
- restart Docker Desktop
- restart the WSL terminal
- if necessary, restart WSL completely:
```bash
wsl --shutdown
```
Then reopen Ubuntu and run:
```bash
docker version
docker ps
```
---

### 3. Error building Sail image: `docker-credential-desktop.exe: exec format error`

Example:
```text
failed to solve: error getting credentials - err: fork/exec /usr/bin/docker-credential-desktop.exe: exec format error
```
This usually happens when Docker credential helper integration is broken between Docker Desktop and WSL, or when Docker CLI inside WSL is trying to execute a Windows credential helper incorrectly.

#### Recovery steps

First, inspect Docker config inside WSL:
```bash
cat ~/.docker/config.json
```
If you see something like this:
```json
{
  "credsStore": "desktop.exe"
}
```
or another Docker Desktop credential helper that is failing, back up the file:
```bash
cp ~/.docker/config.json ~/.docker/config.json.bak
```
Then remove the credential helper entry manually from `~/.docker/config.json`, or temporarily replace the file with a minimal config like:
```json
{}
```
After that:
```bash
docker logout
docker pull ubuntu:24.04
```
Then retry:
```bash
./vendor/bin/sail build --no-cache
./vendor/bin/sail up -d
```
#### If it still fails

Try the following recovery sequence:
```bash
wsl --shutdown
```
Then:

1. reopen Docker Desktop
2. wait until Docker is fully started
3. reopen Ubuntu
4. run:
```bash
docker version
docker pull ubuntu:24.04
```
If the pull works, try Sail again.

#### Team recommendation

If several developers hit this error, document a known-good WSL Docker configuration and standardize:

- Ubuntu distro version
- Docker Desktop version
- whether `~/.docker/config.json` should contain `credsStore` or not

This error is annoying because it looks like a Laravel problem, but it is really a Docker Desktop + WSL credential integration problem wearing a fake moustache.

---

### 4. Warning: `The PWD variable is not set`
### 5. Warning: `The WWWGROUP variable is not set`
### 6. Warning: `The WWWUSER variable is not set`

Example:
```text
WARNING: The PWD variable is not set. Defaulting to a blank string.
WARNING: The WWWGROUP variable is not set. Defaulting to a blank string.
WARNING: The WWWUSER variable is not set. Defaulting to a blank string.
```
This happens because `compose.yaml` expects those variables and they are missing from `.env` or the shell environment.

#### Recovery

Add these values to `.env`:
```dotenv
WWWUSER=1000
WWWGROUP=1000
PWD=/var/www/html
```
If your WSL user has different UID/GID, verify them:
```bash
id -u
id -g
```
Then restart containers:
```bash
./vendor/bin/sail down
./vendor/bin/sail up -d
```
#### Why this matters

Missing `WWWUSER` and `WWWGROUP` can lead to ownership mismatches. That often becomes a permissions problem later in:

- `storage/`
- `bootstrap/cache/`
- generated files
- logs

---

### 7. Permission denied in `storage/logs/laravel.log`

Example:
```text
The stream or file "/var/www/html/storage/logs/laravel.log" could not be opened in append mode: Failed to open stream: Permission denied
```
This means the container user cannot write to Laravel's writable directories.

#### Recovery steps

From the project root, run:
```bash
./vendor/bin/sail artisan optimize:clear
./vendor/bin/sail shell
```
Inside the container:
```bash
chown -R sail:sail storage bootstrap/cache
chmod -R ug+rwx storage bootstrap/cache
exit
```
Then retry your request.

#### If the issue persists

From WSL, also check who owns the files:
```bash
ls -la storage
ls -la storage/logs
ls -la bootstrap/cache
```
If files were created by a mismatched user because commands were run from another environment, rebuild ownership from WSL:
```bash
sudo chown -R $(id -u):$(id -g) storage bootstrap/cache
```
Then restart Sail:
```bash
./vendor/bin/sail down
./vendor/bin/sail up -d
```
#### Prevention

- keep the project inside WSL
- avoid mixing Windows terminal, PowerShell, Git Bash, and WSL for the same project
- avoid generating project files from multiple environments with different users
- make sure `WWWUSER` and `WWWGROUP` are set

---

### 8. SQL error: `Table 'laravel.users' doesn't exist`

Example:
```text
SQLSTATE[42S02]: Base table or view not found: 1146 Table 'laravel.users' doesn't exist
```
This means the application is connected to MySQL, but the required tables were not created yet.

#### Recovery steps

First, confirm your `.env` uses the Sail MySQL service:
```dotenv
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=sail
DB_PASSWORD=password
```
Then run migrations:
```bash
./vendor/bin/sail artisan migrate
```
If your app also needs seed data:
```bash
./vendor/bin/sail artisan migrate --seed
```
#### If migrations fail or database state is inconsistent

Use:
```bash
./vendor/bin/sail artisan migrate:fresh --seed
```
> Warning: `migrate:fresh` drops all tables first. Use it only for local development.

#### If MySQL container is up but tables still do not exist

Check whether the app container can reach MySQL:
```bash
./vendor/bin/sail artisan db:show
```
Also check the running containers:
```bash
./vendor/bin/sail ps
```
If MySQL was created with old/bad values, you may need to reset volumes:
```bash
./vendor/bin/sail down -v
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate
```
> Warning: `down -v` removes Docker volumes and deletes local container data for services like MySQL.

---

### 9. App is still using SQLite or wrong DB settings

Your `.env.example` may default to SQLite, while your Sail environment is using MySQL.

Symptoms include:

- migrations running against a different database than expected
- app connecting to the wrong driver
- login or API failing even though MySQL is running

#### Recovery

Open `.env` and confirm you are using the intended database driver. For Sail + MySQL, use:
```dotenv
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=sail
DB_PASSWORD=password
```
Then clear cached configuration:
```bash
./vendor/bin/sail artisan config:clear
./vendor/bin/sail artisan cache:clear
./vendor/bin/sail artisan optimize:clear
```
After that, run migrations again:
```bash
./vendor/bin/sail artisan migrate
```
---

### 10. Database container starts, but connection still fails

Common causes:

- MySQL container is still initializing
- wrong credentials in `.env`
- stale volume from a previous setup
- app config cache contains old values

#### Recovery sequence

Wait a few seconds and then run:
```bash
./vendor/bin/sail ps
./vendor/bin/sail artisan config:clear
./vendor/bin/sail artisan optimize:clear
./vendor/bin/sail artisan migrate
```
If it still fails, reset volumes:
```bash
./vendor/bin/sail down -v
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate
```
---

### 11. `composer dev` fails with `php: command not found` or `npm: command not found`

Those scripts use your **local/WSL tools**, not Sail.

#### Recovery

Choose one approach:

- install PHP, Composer, Node.js, and NPM in WSL
- or use Sail for backend commands and WSL Node/NPM for frontend commands

Recommended Sail-first workflow:
```bash
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate
npm install
npm run dev
```
---

### 12. `npm install` or `npm run dev` fails in WSL

Common causes:

- Node.js is not installed in WSL
- wrong Node version
- running commands from Windows instead of WSL
- broken `node_modules`

#### Recovery

Verify versions:
```bash
node -v
npm -v
```
If Node is missing, install it in WSL and retry.

If dependencies are corrupted:
```bash
rm -rf node_modules package-lock.json
npm install
```
Then:
```bash
npm run dev
```
---

### 13. Vite does not detect file changes

Most common cause on Windows: the project is located under `/mnt/c/...`.

#### Recovery

Move the project to a WSL-native directory such as:
```bash
~/projects/gym-backend
```
Then reinstall dependencies if needed and run:
```bash
npm run dev
```
Also make sure PhpStorm is opened against the WSL project path, not the Windows path.

---

### 14. Changes in `.env` do not take effect

Laravel may still be using cached configuration.

#### Recovery

Run:
```bash
./vendor/bin/sail artisan optimize:clear
./vendor/bin/sail artisan config:clear
```
If necessary, restart containers:
```bash
./vendor/bin/sail down
./vendor/bin/sail up -d
```
---

### 15. Port already in use (`80`, `3306`, `5173`, etc.)

Symptoms:

- Sail does not start
- browser opens wrong app
- Vite or MySQL ports conflict with another service

#### Recovery

Check which service is already using the port, or override the project ports in `.env`, for example:
```dotenv
APP_PORT=8080
FORWARD_DB_PORT=3307
VITE_PORT=5174
FORWARD_MAILPIT_DASHBOARD_PORT=8026
```
Then restart:
```bash
./vendor/bin/sail down
./vendor/bin/sail up -d
```
---

### 16. Containers are running, but the application fails with stale state

Sometimes the easiest fix is a clean local reset.

#### Recovery checklist
```bash
./vendor/bin/sail down
./vendor/bin/sail up -d
./vendor/bin/sail artisan optimize:clear
./vendor/bin/sail artisan migrate
```
If the environment is still broken and you are okay resetting local DB/container data:
```bash
./vendor/bin/sail down -v
rm -rf vendor
composer install
./vendor/bin/sail up -d
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate
npm install
npm run build
```
---

### 17. A teammate can run the project, but you cannot

If the same branch works for others and not for you, the issue is usually environmental.

#### Recovery checklist

Compare:

- project location: WSL path vs `/mnt/c/...`
- Docker Desktop version
- Ubuntu/WSL distro
- `~/.docker/config.json`
- `.env` values
- local ports already in use
- Node / NPM versions
- whether PhpStorm terminal is running in WSL
- file ownership in `storage/` and `bootstrap/cache/`

When in doubt, ask for these checks first instead of randomly rebuilding half the planet.

---

## 📂 Project Structure

- `app/Http/Controllers`: API controllers
- `app/Models`: Eloquent models
- `app/Domain`: Domain logic and custom exceptions
- `database/migrations`: Database schema definitions
- `routes/api.php`: Main API route definitions
- `tests/`: Feature and Unit tests

---

## 🔑 Environment Variables

Important variables in `.env`:

- `APP_KEY`: Application encryption key
- `APP_URL`: Base URL, usually `http://localhost`
- `DB_CONNECTION`: `mysql` or `sqlite`
- `DB_HOST`: database host
- `DB_PORT`: database port
- `DB_DATABASE`: database name
- `DB_USERNAME`: database username
- `DB_PASSWORD`: database password
- `WWWUSER`: Linux user ID used by Sail
- `WWWGROUP`: Linux group ID used by Sail
- `PWD`: working directory hint used by Docker Compose integration in this setup

---

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
