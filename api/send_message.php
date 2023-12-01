<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Handle incoming messages
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['content'])) {
    $user_id = $_SESSION['user_id'];
    $content = htmlspecialchars($_POST['content']); // Sanitize input
    $message = "<strong>{$_SESSION['username']}:</strong> $content";
    
    // Store message in the session
    $_SESSION['chat_messages'][] = $message;
}
?>
