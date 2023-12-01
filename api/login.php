<?php
session_start();

// Function to read user data from the file
function readUsersFromFile($filename) {
    if (file_exists($filename)) {
        $content = file_get_contents($filename);
        return json_decode($content, true) ?: [];
    }
    return [];
}

// Function to write user data to the file
function writeUsersToFile($filename, $users) {
    $content = json_encode($users);
    file_put_contents($filename, $content);
}

$usersFilename = 'acc.txt';

// Read existing users from the file
$users = readUsersFromFile($usersFilename);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {
        // Login form submitted
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Check if the user exists and the password is correct
        if (isset($users[$username]) && password_verify($password, $users[$username])) {
            // Set session variables
            $_SESSION['user_id'] = $username;
            $_SESSION['username'] = $username;

            // Redirect to the chat page
            header("Location: chat.php");
            exit();
        } else {
            $error_message = "Invalid username or password";
        }
    } elseif (isset($_POST['register'])) {
        // Registration form submitted
        $newUsername = $_POST['new_username'];
        $newPassword = $_POST['new_password'];

        // Check if the username is already taken
        if (isset($users[$newUsername])) {
            $error_message = "Username already taken. Please choose a different one.";
        } else {
            // Hash the password
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            // Add the new user
            $users[$newUsername] = $hashedPassword;

            // Write updated user data to the file
            writeUsersToFile($usersFilename, $users);

            $success_message = "Registration successful! You can now log in with your new credentials.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Login</title>
    <style>
        body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #060606;
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
}

#login-container {
    background-color: #1E1E1E;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    padding: 20px;
    width: 300px;
    text-align: center;
}

h2 {
    color: #34B7F1;
}

form {
    display: flex;
    flex-direction: column;
    margin-top: 20px;
}

label {
    margin-bottom: 5px;
    color: #34B7F1;
}

input {
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #343434;
    border-radius: 5px;
    background-color: #343434;
    color: #ffffff;
}

button {
    padding: 10px;
    background-color: #34B7F1;
    color: #ffffff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

    </style>
</head>
<body>

    <div id="login-container" style="margin: 20px;">
        <h2>Login</h2>
        <?php if (isset($error_message)): ?>
            <p class="error"><?= $error_message ?></p>
        <?php elseif (isset($success_message)): ?>
            <p class="success"><?= $success_message ?></p>
        <?php endif; ?>
        <form method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit" name="login">Login</button>
        </form>

        <h2>Register</h2>
        <form method="post">
            <label for="new_username">New Username:</label>
            <input type="text" id="new_username" name="new_username" required>
            <label for="new_password">New Password:</label>
            <input type="password" id="new_password" name="new_password" required>
            <button type="submit" name="register">Register</button>
        </form>
    </div>
</body>
</html>
