<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

include 'db_connect.php';

$feedback_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_project'])) {

    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Invalid request.");
    }

    $title = trim(htmlspecialchars($_POST['title']));
    $badge = trim(htmlspecialchars($_POST['badge']));
    $description = trim(htmlspecialchars($_POST['description']));
    $github_link = trim(htmlspecialchars($_POST['github_link']));

    $stmt = $conn->prepare("INSERT INTO projects (title, badge, description, github_link) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $title, $badge, $description, $github_link);

    if ($stmt->execute()) {
        $feedback_message = "<p class='feedback-success'>Project added successfully!</p>";
    } else {
        $feedback_message = "<p class='feedback-error'>Error adding project: " . $stmt->error . "</p>";
    }
    $stmt->close();
}

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
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar">
        <div class="logo">Admin Dashboard</div>
        <ul class="nav-links">
            <li><a href="index.php" target="_blank">View Live Site ↗</a></li>
            <li><a href="logout.php" class="logout-link">Logout</a></li>
        </ul>
    </nav>

    <main>
        <section class="hero admin-hero">
            <h1>Welcome back, <?php echo isset($_COOKIE['admin_username']) ? htmlspecialchars($_COOKIE['admin_username']) : 'Admin'; ?>!</h1>
            <p>Use this panel to manage your portfolio content.</p>
        </section>

        <section id="add-project" class="admin-section">
            <h2 class="section-title">Add New Project</h2>
            
            <div class="feedback-container">
                <?php echo $feedback_message; ?>
            </div>

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

        <section id="manage-projects" class="admin-section" style="padding-top: 3rem;">
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
                                        
                                        <form method="POST" action="admin_dashboard.php" onsubmit="return confirm('Are you sure you want to delete this project?');" style="margin:0;">
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
    </main>
</body>
</html>