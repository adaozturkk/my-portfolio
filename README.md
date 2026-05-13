# Ada Öztürk | Full-Stack Web Portfolio

**Live Demo:** [https://adaozturk.wuaze.com](https://adaozturk.wuaze.com)

## About This Project

This is a personal portfolio website built from scratch to showcase web development skills. It uses a PHP backend with MySQL to load project cards dynamically, handle contact messages, and provide a secure admin portal for managing content.

## Key Features

- **Dynamic Portfolio Projects:** Projects are loaded from a MySQL database and displayed as cards on the homepage.
- **Admin Panel:** Secure admin area with login, session protection, and logout support.
- **Project Management:** Add, edit, and delete portfolio projects from the admin dashboard.
- **Message Inbox:** View incoming contact messages, read full message details, and delete messages from the admin dashboard.
- **AJAX Contact Form:** Contact form submits via JavaScript Fetch API and returns JSON responses without page refresh.
- **Theme Toggle:** Light/dark mode toggle that remembers the user preference in `localStorage`.
- **Typing Animation:** Smooth typewriter effect on the homepage hero heading.
- **Back to Top Button:** Floating button appears after scrolling to quickly return to the top.
- **Security Practices:** Uses MySQLi prepared statements, `htmlspecialchars()` sanitization, and CSRF tokens for admin form actions.

## Technology Stack

- **Frontend:** HTML5, CSS3, Vanilla JavaScript
- **Backend:** PHP
- **Database:** MySQL
- **Tools:** XAMPP / phpMyAdmin

## Admin Section

- `admin_login.php` — login page for admin access.
- `admin_dashboard.php` — dashboard to manage projects and messages.
- `edit_project.php` — edit existing project entries.
- `view_message.php` — read full message details.
- `logout.php` — safely end the admin session.
- `admin_credentials.php` — stores admin username/password for local development.

## Database Setup

This project expects a MySQL database named `portfolio_db`.

Create a `db_connect.php` file in the root directory with your database credentials. Example:

```php
<?php
$host = "127.0.0.1";
$port = "3307";
$username = "root";
$password = "";
$database = "portfolio_db";

$conn = new mysqli($host, $username, $password, $database, $port);
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
date_default_timezone_set('Europe/Istanbul');
$conn->query("SET time_zone = '+03:00'");
?>
```

> Note: Adjust `host`, `port`, `username`, `password`, and `database` values to match your local environment.

## How to Run Locally

1. Copy the project into your local web server folder (for XAMPP, use `htdocs\portfolio`).
2. Create the `portfolio_db` database in phpMyAdmin.
3. Create the required tables for `projects` and `messages` if they do not already exist.
4. Add `db_connect.php` with your local database settings.
5. Open `http://localhost/portfolio` in your browser.

## Files Included

- `index.php` — homepage with dynamic project cards and contact form.
- `style.css` — site styling for light/dark theme and admin pages.
- `script.js` — frontend logic for form validation, AJAX submit, theme toggle, typing animation, and back-to-top button.
- `submit_form.php` — backend handler for contact form submissions.
- `db_connect.php` — database connection settings.
- `admin_login.php` — admin login form.
- `admin_dashboard.php` — portfolio and message management panel.
- `edit_project.php` — edit project details.
- `view_message.php` — show full contact messages.
- `logout.php` — log out admin user.
- `admin_credentials.php` — admin username and password storage.
