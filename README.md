# XapiVerse - API Gateway & Rotation Platform

Fast & Affordable APIs for Developers with intelligent key rotation system.

## Tech Stack

| Component | Technology |
|-----------|-----------|
| Backend | Laravel 12 (PHP 8.2+) |
| Frontend | Tailwind CSS (CDN) + Alpine.js |
| Database | MySQL / MariaDB |
| Fonts | Plus Jakarta Sans + Inter (Google Fonts) |

## Features

- **Multi-Role System**: Admin, Developer, User
- **API Key Rotation Engine**: Round Robin, Priority, Least Used, Weighted, Fill & Rotate
- **Bulk Key Import**: Add thousands of source keys at once
- **Credit System**: Usage-based pricing
- **Installation Wizard**: Web-based setup (Requirements → Permissions → Database → Admin Setup)
- **Auto-Failover**: Automatically switches to next key on failure
- **Daily Reset Scheduler**: Resets usage counters automatically

---

## Local Installation (XAMPP - Windows)

### Prerequisites

- XAMPP with PHP 8.2+ and MySQL
- Composer installed globally
- Git (optional)

### Step-by-Step Setup

#### 1. Download & Place Files

```bash
# Option A: Clone from GitHub
cd C:\xampp\htdocs
git clone https://github.com/walok790/XapiVerse.git
cd XapiVerse

# Option B: Download ZIP and extract to C:\xampp\htdocs\XapiVerse
```

#### 2. Install Dependencies

```bash
cd C:\xampp\htdocs\XapiVerse
composer install
```

#### 3. Environment Setup

```bash
copy .env.example .env
php artisan key:generate
```

#### 4. Create Database

1. Open XAMPP Control Panel → Start **Apache** and **MySQL**
2. Open browser → go to `http://localhost/phpmyadmin`
3. Click "New" → Create database named `xapiverse_db`
4. Collation: `utf8mb4_unicode_ci`

#### 5. Configure `.env`

Open `C:\xampp\htdocs\XapiVerse\.env` in any text editor:

```env
APP_NAME=XapiVerse
APP_URL=http://localhost/XapiVerse/public

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=xapiverse_db
DB_USERNAME=root
DB_PASSWORD=
```

#### 6. Run Migrations & Seed

```bash
php artisan migrate
php artisan db:seed
```

#### 7. Access the Application

**Option A: Using PHP built-in server (Recommended for testing)**
```bash
php artisan serve
```
Then open: `http://localhost:8000`

**Option B: Direct XAMPP access**
Open: `http://localhost/XapiVerse/public`

#### 8. OR Use the Web Installer

If you skip steps 4-6, you can use the built-in installer:
Open: `http://localhost:8000/install`

The wizard will guide you through:
1. ✅ Requirements check
2. ✅ Folder permissions
3. ✅ Database configuration
4. ✅ Admin account setup
5. ✅ Complete!

---

### Default Login Credentials (after seeding)

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@xapiverse.com | password |
| Developer | dev@xapiverse.com | password |
| User | user@xapiverse.com | password |

---

## VPS / Hosting Deployment (Hostinger, Namecheap, etc.)

### For Shared Hosting

1. Upload files via File Manager or FTP
2. Point domain to `/public` folder
3. Open `https://yourdomain.com/install` for web installer
4. Set up cron job:
   ```
   * * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
   ```

### For VPS (Ubuntu)

```bash
# Clone project
cd /var/www
git clone https://github.com/walok790/XapiVerse.git
cd XapiVerse

# Install dependencies
composer install --optimize-autoloader --no-dev

# Setup
cp .env.example .env
php artisan key:generate

# Configure .env with your database credentials

# Run migrations
php artisan migrate --force
php artisan db:seed --force

# Permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Cron job
crontab -e
# Add: * * * * * cd /var/www/XapiVerse && php artisan schedule:run >> /dev/null 2>&1
```

---

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/         → Admin panel controllers (Phase 2)
│   │   ├── Api/V1/        → Public API controllers (Phase 4)
│   │   ├── Auth/          → Authentication
│   │   ├── Developer/     → Developer dashboard (Phase 4)
│   │   ├── Install/       → Installation wizard
│   │   └── User/          → User platform (Phase 5)
│   └── Middleware/
│       ├── ApiAuthenticate.php  → API key validation
│       ├── CheckInstalled.php   → Installer guard
│       └── RoleMiddleware.php   → Role-based access
├── Models/
│   ├── ApiKeyImportBatch.php
│   ├── ApiRequestLog.php
│   ├── ApiService.php
│   ├── ApiSourceKey.php
│   ├── CreditPackage.php
│   ├── Setting.php
│   ├── Transaction.php
│   ├── User.php
│   └── UserApiKey.php
└── Services/               → (Phase 3: Rotation Engine)

database/
├── migrations/             → All table schemas
└── seeders/
    ├── DatabaseSeeder.php
    └── DefaultSettingsSeeder.php
```

---

## Development Phases

- [x] **Phase 1**: Foundation (Auth, DB, Layout, Installer)
- [ ] **Phase 2**: Admin Panel (Services, Keys, Users, Bulk Import)
- [ ] **Phase 3**: Key Rotation Engine
- [ ] **Phase 4**: Developer Platform (API Docs, Keys, Credits)
- [ ] **Phase 5**: User Platform (Iteraplay)

---

## License

Proprietary - All rights reserved.
