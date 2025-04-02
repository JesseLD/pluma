# Pluma Framework

Pluma is a lightweight, elegant PHP mini-framework designed for small to medium web applications.
It features clean routing, session-based authentication, simple MVC structure, and a custom view engine.

---

## 🚀 Features

- Minimal and fast setup
- MVC pattern with routing
- Session-based authentication
- Blade-inspired custom view engine (`.pluma.php`)
- Secure by default (CSRF, XSS, prepared SQL)
- Modular helper functions
- Artisan-like command structure (e.g., `make:module`)

---

## 📂 Directory Structure

```
app/
  Controllers/
  Models/
core/
  Router.php
  Controller.php
  View.php
  Auth.php
  ...
public/
  index.php
  views/
    layouts/
    partials/
    welcome.pluma.php
  images/
    pluma.svg
storage/
  views/
  logs/
routes/
  web.php
```

---

## ✅ Getting Started

1. Clone the project
2. Run `composer install`
3. Configure `.env`
4. Point your server to `public/index.php`
5. Access `/welcome` to see the welcome page

---

## 🔐 Security Defaults

- All output is escaped with `{{ }}` → `e()`
- CSRF tokens in forms (`csrf_field()`)
- Authentication with hashed passwords (`Auth::attempt()`)
- Prepared SQL with `PDO`

---

## 💻 Development Tips

- Use `dd()` to debug
- Add views as `.pluma.php` inside `public/views/`
- Use `render_component()` and `include_partial()` to build UI

---

## 🧾 License

This project is open-source and free to use for personal or commercial projects.

---

## 📁 .gitignore Guide

This file explains which folders and files you should ignore in Git for a clean and safe Pluma project.

### Recommended .gitignore content

```
# Node (optional frontend stuff)
node_modules/

# PHP dependencies
/vendor/

/.env
.env.*

# IDEs and editors
.vscode/
.idea/
.DS_Store

# System files
Thumbs.db

# Composer artifacts
composer.lock

# Logs
/storage/logs/*
!/storage/logs/.gitkeep

# View cache (precompiled .pluma.php files)
storage/views/*
!storage/views/.gitkeep

# Temp and runtime files
/storage/tmp/*
!/storage/tmp/.gitkeep

# File uploads
/storage/uploads/*
!/storage/uploads/.gitkeep
```

### Notes

- Keep `storage/` in the project structure, but **ignore its dynamic contents**.
- Use `.gitkeep` to preserve folder structure when needed.
- Never commit `.env` files or secret keys.
- If you use SQLite, you may also want to add `database.sqlite` to `.gitignore`.

### Optional extras

- If you use `phpunit`, add `tests/_output/`, `coverage/` to your ignore list.
- If you're compiling assets, ignore `/public/build/`, `/public/css/`, etc.

> Always test your `.gitignore` after modifying it:
> Use `git status` to ensure nothing sensitive or dynamic is being committed.