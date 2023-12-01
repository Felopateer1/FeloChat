<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Initialize chat_messages session variable if not set
if (!isset($_SESSION['chat_messages'])) {
    $_SESSION['chat_messages'] = [];
}

// Handle incoming messages
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['content'])) {
    $user_id = $_SESSION['user_id'];
    $content = htmlspecialchars($_POST['content']); // Sanitize input
    $message = "<strong>{$_SESSION['username']}:</strong> $content";
    
    // Store message in the session
    $_SESSION['chat_messages'][] = $message;
}

// Fetch users
$users = [];

if (isset($_SESSION['user_id'])) {
    $users[$_SESSION['user_id']] = $_SESSION['username'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Simple Chat App</title>
    <style>
        body {
    font-family: 'Arial', sans-serif;
    margin: 80;
    padding: 80;
    background-color: #0e0e0e;
    color: #ffffff;
}

#chat-container {
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
    box-sizing: border-box;
    background-color: #181818;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
}

.message {
    background-color: #1e1e1e;
    padding: 10px;
    margin: 10px 0;
    border-radius: 10px;
    word-wrap: break-word;
}

.message strong {
    color: #34b7f1;
}

#message-form {
    display: flex;
    margin-top: 20px;
}

#message-input {
    flex-grow: 1;
    padding: 10px;
    border: 1px solid #343434;
    border-radius: 5px;
    color: #ffffff;
    background-color: #343434;
}

#message-form button {
    padding: 10px;
    background-color: #34b7f1;
    color: #ffffff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

    </style>
</head>
<body>
    <div id="chat-container">
        <?php
        foreach ($_SESSION['chat_messages'] as $message) {
            echo "<div class='message'>$message</div>";
        }
        ?>
    </div>
    <form id="message-form" method="post" action="chat.php">
        <input type="text" id="message-input" name="content" placeholder="Type your message..." required>
        <button type="submit">Send</button>
    </form>
    <script src="script.js"></script>
</body>
</html>
