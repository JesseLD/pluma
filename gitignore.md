# Pluma Framework - .gitignore Guidelines

This file explains which folders and files you should ignore in Git for a clean and safe Pluma project.

---

## ✅ Recommended .gitignore content

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

---

## 🧠 Notes

- Keep `storage/` in the project structure, but **ignore its dynamic contents**.
- Use `.gitkeep` to preserve folder structure when needed.
- Never commit `.env` files or secret keys.
- If you use SQLite, you may also want to add `database.sqlite` to `.gitignore`.

---

## 🧹 Optional extras

- If you use `phpunit`, add `tests/_output/`, `coverage/` to your ignore list.
- If you're compiling assets, ignore `/public/build/`, `/public/css/`, etc.

---

> Always test your `.gitignore` after modifying it:
> Use `git status` to ensure nothing sensitive or dynamic is being committed.
