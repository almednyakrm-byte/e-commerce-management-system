<?php
// Start the session to handle user authentication
session_start();

// Include the database connection file
require_once 'db.php';

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    // If the user is logged in, return a JSON response indicating success
    echo json_encode(['status' => 'success', 'message' => 'User is already logged in']);
    exit;
}

// Handle the login action
if (isset($_POST['action']) && $_POST['action'] == 'login') {
    // Check if the username and password are set
    if (!isset($_POST['username']) || !isset($_POST['password'])) {
        // If the username or password is missing, return an error response
        echo json_encode(['status' => 'error', 'message' => 'Username and password are required']);
        exit;
    }

    // Sanitize the input fields to prevent SQL injection
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

    // Prepare the SQL query to select the user
    $stmt = $db->prepare('SELECT * FROM users WHERE username = :username');
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    // Fetch the user data
    $user = $stmt->fetch();

    // Check if the user exists
    if ($user && password_verify($password, $user['password'])) {
        // If the password is correct, update the session with the user data
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        // Return a JSON response indicating success
        echo json_encode(['status' => 'success', 'message' => 'User logged in successfully']);
    } else {
        // If the password is incorrect or the user does not exist, return an error response
        echo json_encode(['status' => 'error', 'message' => 'Invalid username or password']);
    }
    exit;
}

// Handle the register action
if (isset($_POST['action']) && $_POST['action'] == 'register') {
    // Check if the username, email, and password are set
    if (!isset($_POST['username']) || !isset($_POST['email']) || !isset($_POST['password'])) {
        // If the username, email, or password is missing, return an error response
        echo json_encode(['status' => 'error', 'message' => 'Username, email, and password are required']);
        exit;
    }

    // Sanitize the input fields to prevent SQL injection
    $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

    // Check if the username and email are unique
    $stmt = $db->prepare('SELECT * FROM users WHERE username = :username OR email = :email');
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    // Fetch the user data
    $user = $stmt->fetch();

    // Check if the username or email already exists
    if ($user) {
        // If the username or email already exists, return an error response
        echo json_encode(['status' => 'error', 'message' => 'Username or email already exists']);
        exit;
    }

    // Hash the password using password_hash()
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query to insert the new user
    $stmt = $db->prepare('INSERT INTO users (username, email, password) VALUES (:username, :email, :password)');
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->execute();

    // Return a JSON response indicating success
    echo json_encode(['status' => 'success', 'message' => 'User registered successfully']);
    exit;
}

// Handle the logout action
if (isset($_POST['action']) && $_POST['action'] == 'logout') {
    // Destroy the session to log out the user
    session_destroy();
    // Return a JSON response indicating success
    echo json_encode(['status' => 'success', 'message' => 'User logged out successfully']);
    exit;
}

// Handle the get session status action
if (isset($_GET['action']) && $_GET['action'] == 'getSessionStatus') {
    // Check if the user is logged in
    if (isset($_SESSION['user_id'])) {
        // If the user is logged in, return a JSON response indicating success
        echo json_encode(['status' => 'success', 'message' => 'User is logged in']);
    } else {
        // If the user is not logged in, return a JSON response indicating failure
        echo json_encode(['status' => 'error', 'message' => 'User is not logged in']);
    }
    exit;
}