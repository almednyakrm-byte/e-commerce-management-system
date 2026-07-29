<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get user role
$user_role = $_SESSION['user_role'];

// Check if user is admin
$is_admin = ($user_role == 'admin');

// Get input data
$input_data = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Validate and sanitize input
    if (!isset($input_data['id'])) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('SELECT * FROM المشتريات WHERE id = :id');
    $stmt->bindParam(':id', $input_data['id']);
    $stmt->execute();

    // Fetch data
    $data = $stmt->fetch();

    // Check if data exists
    if (!$data) {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Not found'));
        exit;
    }

    // Return data
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate and sanitize input
    if (!isset($input_data['name']) || !isset($input_data['description']) || !isset($input_data['price'])) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('INSERT INTO المشتريات (name, description, price) VALUES (:name, :description, :price)');
    $stmt->bindParam(':name', $input_data['name']);
    $stmt->bindParam(':description', $input_data['description']);
    $stmt->bindParam(':price', $input_data['price']);
    $stmt->execute();

    // Return data
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Created successfully'));
    exit;
}

// Handle PUT request
if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    // Validate and sanitize input
    if (!isset($input_data['id']) || !isset($input_data['name']) || !isset($input_data['description']) || !isset($input_data['price'])) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Check if user is admin
    if (!$is_admin) {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('UPDATE المشتريات SET name = :name, description = :description, price = :price WHERE id = :id');
    $stmt->bindParam(':id', $input_data['id']);
    $stmt->bindParam(':name', $input_data['name']);
    $stmt->bindParam(':description', $input_data['description']);
    $stmt->bindParam(':price', $input_data['price']);
    $stmt->execute();

    // Return data
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Updated successfully'));
    exit;
}

// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    // Validate and sanitize input
    if (!isset($input_data['id'])) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }

    // Check if user is admin
    if (!$is_admin) {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Prepare SQL query
    $stmt = $pdo->prepare('DELETE FROM المشتريات WHERE id = :id');
    $stmt->bindParam(':id', $input_data['id']);
    $stmt->execute();

    // Return data
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(array('message' => 'Deleted successfully'));
    exit;
}