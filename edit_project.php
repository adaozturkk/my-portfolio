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
    $title = trim(htmlspecialchars($_POST['title']));
    $badge = trim(htmlspecialchars($_POST['badge']));
    $description = trim(htmlspecialchars($_POST['description']));
    $github_link = trim(htmlspecialchars($_POST['github_link']));

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
    <title>Ada Öztürk - Edit Project</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar">
        <div class="logo">Admin Dashboard</div>
        <ul class="nav-links">
            <li><a href="admin_dashboard.php">← Back to Dashboard</a></li>
        </ul>
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
</body>
</html>