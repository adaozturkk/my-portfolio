<?php
session_start();

// Require admin login to view messages
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

include 'db_connect.php';

// Require a message ID to load the detail page
if (!isset($_GET['id'])) {
    header("Location: admin_dashboard.php#manage-messages");
    exit();
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM messages WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$message_data = $result->fetch_assoc();
$stmt->close();

if (!$message_data) {
    die("Message not found!");
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ada Öztürk | View Message</title>
    <link rel="stylesheet" href="style.css?v=6">
</head>
<body>
    <nav class="navbar">
        <div class="logo">Admin Dashboard</div>
        <ul class="nav-links">
            <li><a href="admin_dashboard.php#manage-messages">← Back to Inbox</a></li>
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
        <section class="admin-section manage-section">
            <h2 class="section-title">Message Details</h2>
            
            <div class="card message-detail-card">
                <div class="message-header">
                    <h3>From: <?php echo htmlspecialchars($message_data['name']); ?></h3>
                    <p>
                        <strong>Email:</strong> 
                        <a href="mailto:<?php echo htmlspecialchars($message_data['email']); ?>" class="email-link">
                            <?php echo htmlspecialchars($message_data['email']); ?>
                        </a>
                    </p>
                </div>
                
                <div class="message-body"><?php echo htmlspecialchars($message_data['message']); ?></div>

                <div class="button-group">
                    <a href="admin_dashboard.php#manage-messages" class="submit-btn btn-secondary">← Back</a>
                    
                    <form method="POST" action="admin_dashboard.php" onsubmit="return confirm('Are you sure you want to delete this message?');" style="flex: 1;">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        <input type="hidden" name="delete_message" value="1">
                        <input type="hidden" name="message_id" value="<?php echo $message_data['id']; ?>">
                        <button type="submit" class="submit-btn btn-danger">Delete Message</button>
                    </form>
                </div>
            </div>
        </section>
    </main>
    <script src="script.js"></script>
</body>
</html>