# Ada Öztürk | Full-Stack Web Portfolio

**Live Demo:** [https://adaozturk.wuaze.com](https://adaozturk.wuaze.com)

## About This Project

This is my personal portfolio website, built from scratch to showcase my skills as a web developer. It is a complete full-stack application featuring a responsive frontend and a secure, custom-built PHP backend.

## Key Features

- **Dynamic UI:** Includes a smooth typing animation and a global light/dark mode toggle that remembers your preference using local storage.
- **Interactive Contact Form:** Uses the JavaScript Fetch API (AJAX) to send messages smoothly without reloading the page.
- **Custom Admin Dashboard:** A hidden, secure login portal built with PHP Sessions and Cookies.
- **Full Database Control:** The admin panel has full CRUD (Create, Read, Update, Delete) functionality to manage my projects directly from the browser.
- **High Security:** Protected against SQL Injection (using MySQLi Prepared Statements), XSS (using htmlspecialchars), and CSRF attacks (using cryptographically secure tokens).

## Technology Stack

- **Frontend:** HTML5, Custom CSS3, Vanilla JavaScript
- **Backend:** PHP
- **Database:** MySQL
- **Version Control:** Git & GitHub

## How to Run Locally

If you are reviewing this code and want to run it on your own machine:

1. Clone this repository to your local server environment (e.g., the `htdocs` folder in XAMPP).
2. Open phpMyAdmin and create a new database named `portfolio_db`.
3. Import the provided `.sql` file to set up the tables and data.
4. Create a new file in the root directory called `db_connect.php` and add the following code:

   ```php
   <?php
   $host = "localhost";
   $username = "root";
   $password = "";
   $database = "portfolio_db";

   $conn = new mysqli($host, $username, $password, $database);
   if ($conn->connect_error) {
       die("Connection Failed: " . $conn->connect_error);
   }
   ?>
   ```

   _> Note: Update the username and password in this file if your local database uses different credentials._

5. Open the project in your browser (e.g., `http://localhost/portfolio`).
