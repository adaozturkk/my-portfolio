<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

include 'db_connect.php';

$feedback_message = "";
$project = null;

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM projects WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $project = $result->fetch_assoc();
    $stmt->close();

    if (!$project) {
        die("Project not found!");
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_project'])) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Invalid request.");
    }

    $id = $_POST['id'];
    $title = trim($_POST['title']);
    $badge = trim($_POST['badge']);
    $description = trim($_POST['description']);
    $github_link = trim($_POST['github_link']);

    $stmt = $conn->prepare("UPDATE projects SET title=?, badge=?, description=?, github_link=? WHERE id=?");
    $stmt->bind_param("ssssi", $title, $badge, $description, $github_link, $id);

    if ($stmt->execute()) {
        header("Location: admin_dashboard.php");
        exit();
    } else {
        $feedback_message = "<p class='feedback-error'>Error updating project: " . $stmt->error . "</p>";
    }
    $stmt->close();
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ada Öztürk | Edit Project</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar">
        <div class="logo">Admin Dashboard</div>
        <ul class="nav-links">
            <li><a href="admin_dashboard.php">← Back to Dashboard</a></li>
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
        <section class="admin-section" style="padding-top: 4rem;">
            <h2 class="section-title">Edit Project</h2>
            
            <div class="feedback-container">
                <?php echo $feedback_message; ?>
            </div>

            <form method="POST" action="edit_project.php?id=<?php echo $project['id']; ?>" class="card contact-form">
                <input type="hidden" name="id" value="<?php echo $project['id']; ?>">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                <div class="form-group">
                    <label for="title">Project Title</label>
                    <input type="text" id="title" name="title" required value="<?php echo htmlspecialchars($project['title']); ?>">
                </div>
                <div class="form-group">
                    <label for="badge">Technology Badge</label>
                    <input type="text" id="badge" name="badge" required value="<?php echo htmlspecialchars($project['badge']); ?>">
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" rows="5" required><?php echo htmlspecialchars($project['description']); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="github_link">GitHub Link</label>
                    <input type="url" id="github_link" name="github_link" required value="<?php echo htmlspecialchars($project['github_link']); ?>">
                </div>
                
                <button type="submit" name="update_project" class="submit-btn" style="background-color: #3b82f6;">Update Project</button>
            </form>
        </section>
    </main>
    <script src="script.js"></script>
</body>
</html>