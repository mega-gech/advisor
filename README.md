# AdvisorHub – AAU Academic Advising System

Production-style PHP MVC application connecting students, academic advisors, and registrars.

## Project structure

```
advisor/
├── public/                 # Web document root (point Apache/Nginx here)
│   ├── index.php           # Front controller – only public entry point
│   ├── .htaccess
│   └── assets/             # CSS, JS, images (static files)
│       ├── css/
│       ├── js/
│       └── img/
├── app/                    # Application code (not web-accessible)
│   ├── Core/               # Router, BaseController, BaseModel
│   ├── Controllers/
│   ├── Models/
│   ├── Services/
│   ├── Support/            # helpers.php
│   └── Views/
├── bootstrap/
│   └── app.php             # Autoload, session, config load
├── config/
│   ├── app.php             # App & database settings
│   └── Database.php        # PDO singleton
├── database/
│   └── seed.sql            # Schema + demo data
├── storage/
│   └── logs/               # Writable logs (future use)
├── .env.example
├── .gitignore
└── index.php               # Redirects to public/ (legacy/dev)
```

## Requirements

- PHP 8.0+
- MySQL / MariaDB
- Apache with `mod_rewrite` (optional) or PHP built-in server

## Installation

1. Clone or copy the project to your web server.
2. **Recommended:** set the virtual host **document root** to `advisor/public`.
3. Import the database:
   ```bash
   mysql -u root -p < database/seed.sql
   ```
4. Copy environment file (optional):
   ```bash
   copy .env.example .env
   ```
5. Edit `config/app.php` or `.env` for database credentials.

### XAMPP (quick start)

- URL: `http://localhost/advisor/public/`
- Or: `http://localhost/advisor/` (root redirect sends you to `public/`)

### PHP built-in server

```bash
cd advisor
php -S localhost:8080 -t public
```


## Architecture

- **MVC** – Controllers handle requests, Models handle data, Views render HTML.
- **Router** – Maps `?action=` to controller methods.
- **AuthService** – Login, registration, sessions.
- **Autoloading** – Classes in `app/` and `config/` load automatically.
- **Security** – App code lives outside `public/`; only assets and `index.php` are exposed.

## License

Academic / internal use – Addis Ababa University (AAU).
