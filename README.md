# Task Manager (Version 2.0)

**Task Manager - Based on Filesystem - No SQL**

A modern PHP task manager with JSON-based storage, featuring user authentication, a glassmorphism UI, smooth animations, and mobile-responsive design. This is the second version of the project, upgraded with a modular multi-page structure, a dynamic landing page, and enhanced security features, all implemented on **April 29, 2025**.
![Task Manager Screenshot](https://github.com/maharshikushwaha/TaskManagerPHP/blob/main/image.png?raw=true)

---

## Features
- **Dynamic Landing Page**: `index.php` displays Login/Register buttons for guests or My Tasks/Logout for logged-in users, adapting based on session status.
- **Secure Authentication**: User registration and login with password hashing and session management.
- **Task Management**: Create, edit, toggle completion, and delete tasks with validation to prevent duplicates and empty inputs.
- **Glassmorphism UI**: Built with Tailwind CSS, featuring gradients, scale effects, and animations (task slide-in, modal zoom-in, loading spinners).
- **Mobile-Responsive**: Seamless experience on mobile and desktop devices.
- **JSON Storage**: Persistent data in `users.json` (credentials) and `tasks_<username>.json` (user tasks), no SQL database required.
- **Modular Structure**: Separate pages for landing (`index.php`), login (`login.php`), registration (`register.php`), tasks (`tasks.php`), logout (`logout.php`), and utilities (`utils.php`).

---

## Changelog

### Version 2.0 (April 29, 2025)
- **Landing Page**: Implemented `index.php` to check user login status, showing Login/Register or My Tasks/Logout.
- **Multi-Page Structure**: Split functionality into modular PHP files for maintainability.
- **Enhanced Authentication**: Password hashing, session regeneration, and username sanitization.
- **Task Management**: Full task creation, editing, toggling completion, and deletion with robust validation.
- **Glassmorphism UI**: Tailwind CSS with modern responsive design and interactive animations.
- **Bug Fixes**: Session persistence, file operation stability, and TypeError resolution.
- **Security Improvements**: Error logging, secure session management, and input sanitization.

---

## Setup

### Place Files
- Copy `index.php`, `login.php`, `register.php`, `tasks.php`, `logout.php`, and `utils.php` to a PHP-enabled server (e.g., `/home/zuptektestnet/public_html/msk/` or `http://localhost/msk/`).
- Ensure the directory is writable for JSON file creation.

### Set Permissions
```bash
chmod 775 /path/to/msk/
