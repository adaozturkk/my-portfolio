<?php
session_start();

// Protect admin dashboard and require login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

// Generate CSRF token once per session for form safety
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

include 'db_connect.php';

$feedback_message = "";

// Add project
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_project'])) {

    // Verify the CSRF token before changing database data
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Invalid request.");
    }

    $title = trim($_POST['title']);
    $badge = trim($_POST['badge']);
    $description = trim($_POST['description']);
    $github_link = trim($_POST['github_link']);
    
    $stmt = $conn->prepare("INSERT INTO projects (title, badge, description, github_link) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $title, $badge, $description, $github_link);

    if ($stmt->execute()) {
        $feedback_message = "<p class='feedback-success'>Project added successfully!</p>";
    } else {
        $feedback_message = "<p class='feedback-error'>Error adding project: " . $stmt->error . "</p>";
    }
    $stmt->close();
}

// Delete project
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_project'])) {

    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Invalid request.");
    }

    $delete_id = $_POST['delete_id'];
    
    $stmt = $conn->prepare("DELETE FROM projects WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    
    if ($stmt->execute()) {
        $feedback_message = "<p class='feedback-success'>Project deleted successfully!</p>";
    } else {
        $feedback_message = "<p class='feedback-error'>Error deleting project: " . $stmt->error . "</p>";
    }
    $stmt->close();
}

// Delete message
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_message'])) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Invalid request.");
    }
    $delete_id = $_POST['message_id'];
    
    $stmt = $conn->prepare("DELETE FROM messages WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    
    if ($stmt->execute()) {
        $feedback_message = "<p class='feedback-success'>Message deleted successfully!</p>";
    } else {
        $feedback_message = "<p class='feedback-error'>Error deleting message: " . $stmt->error . "</p>";
    }
    $stmt->close();
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ada Öztürk | Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar">
        <div class="logo">Admin Dashboard</div>
        <ul class="nav-links">
            <li><a href="index.php" target="_blank">View Live Site ↗</a></li>
            <li><a href="logout.php" class="logout-link">Logout</a></li>
        </ul>
        <button id="theme-toggle" class="theme-btn" aria-label="Toggle light/dark mode">
            <svg id="icon-sun" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 3V4M12 20V21M4 12H3M6.31412 6.31412L5.5 5.5M17.6859 6.31412L18.5 5.5M6.31412 17.69L5.5 18.5001M17.6859 17.69L18.5 18.5001M21 12H20M16 12C16 14.2091 14.2091 16 12 16C9.79086 16 8 14.2091 8 12C8 9.79086 9.79086 8 12 8C14.2091 8 16 9.79086 16 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <svg id="icon-moon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M3.32031 11.6835C3.32031 16.6541 7.34975 20.6835 12.3203 20.6835C16.1075 20.6835 19.3483 18.3443 20.6768 15.032C19.6402 15.4486 18.5059 15.6834 17.3203 15.6834C12.3497 15.6834 8.32031 11.654 8.32031 6.68342C8.32031 5.50338 8.55165 4.36259 8.96453 3.32996C5.65605 4.66028 3.32031 7.89912 3.32031 11.6835Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
    </nav>

    <main>
        <section class="hero admin-hero">
            <h1>Welcome back, <?php echo isset($_COOKIE['admin_username']) ? htmlspecialchars($_COOKIE['admin_username']) : 'Admin'; ?>!</h1>
            <p>Use this panel to manage your portfolio content.</p>
        </section>

        <div class="feedback-container">
           <?php echo $feedback_message; ?>
        </div>

        <section id="add-project" class="admin-section">
            <h2 class="section-title">Add New Project</h2>

            <form method="POST" action="admin_dashboard.php" class="card contact-form">
                <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                <div class="form-group">
                    <label for="title">Project Title</label>
                    <input type="text" id="title" name="title" required placeholder="e.g., Python AI Chatbot">
                </div>
                <div class="form-group">
                    <label for="badge">Technology Badge</label>
                    <input type="text" id="badge" name="badge" required placeholder="e.g., Python & TensorFlow">
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="4" required placeholder="Describe what the project does..."></textarea>
                </div>
                <div class="form-group">
                    <label for="github_link">GitHub Link</label>
                    <input type="url" id="github_link" name="github_link" required placeholder="https://github.com/adaozturkk/...">
                </div>
                <button type="submit" name="add_project" class="submit-btn">Publish Project</button>
            </form>
        </section>

        <section id="manage-projects" class="admin-section manage-section">
            <h2 class="section-title">Manage Projects</h2>
            <div class="card">
                <table class="projects-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Badge</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT id, title, badge FROM projects ORDER BY id DESC";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                        ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($row['title']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($row['badge']); ?></td>
                                    <td class="action-buttons">
                                        <a href="edit_project.php?id=<?php echo $row['id']; ?>" class="edit-btn">Edit</a>
                                        
                                        <form method="POST" action="admin_dashboard.php" onsubmit="return confirm('Are you sure you want to delete this project?');">
                                            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                                            <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                                            <button type="submit" name="delete_project" class="delete-btn">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                        <?php
                            }
                        } else {
                            echo "<tr><td colspan='3'>No projects found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </section>

        <section id="manage-messages" class="admin-section manage-section">
            <h2 class="section-title">Inbox (Messages)</h2>
            <div class="card">
                <table class="projects-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT id, name, email, created_at FROM messages ORDER BY id DESC";
                        $result = $conn->query($sql);

                        if ($result && $result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                        ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($row['name']); ?></strong></td>
                                    <td>
                                        <a href="mailto:<?php echo htmlspecialchars($row['email']); ?>" class="email-link">
                                            <?php echo htmlspecialchars($row['email']); ?>
                                        </a>
                                    </td>
                                    <td class="date">
                                        <?php echo date('d M Y, H:i', strtotime($row['created_at'])); ?>
                                    </td>
                                    <td class="action-buttons">
                                        <a href="view_message.php?id=<?php echo $row['id']; ?>" class="view-btn">Read</a>
                                        
                                        <form method="POST" action="admin_dashboard.php" onsubmit="return confirm('Are you sure you want to delete this message?');">
                                            <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                                            <input type="hidden" name="message_id" value="<?php echo $row['id']; ?>">
                                            <button type="submit" name="delete_message" class="delete-btn">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                        <?php
                            }
                        } else {
                            echo "<tr><td colspan='3'>No new messages.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
    <script src="script.js"></script>
</body>
</html>