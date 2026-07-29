<?php
require_once 'db.php';

// Get user role and ID from session
$userRole = $_SESSION['userRole'];
$userID = $_SESSION['userID'];

// Check if user is logged in
if (!$userID) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get input data
$inputData = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Get all sales data
    $stmt = $pdo->prepare('SELECT * FROM المبيعات');
    $stmt->execute();
    $salesData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return sales data
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($salesData);
    exit;
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input data
    if (!isset($inputData['product_name']) || !isset($inputData['quantity']) || !isset($inputData['price'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }

    // Sanitize input data
    $productName = filter_var($inputData['product_name'], FILTER_SANITIZE_STRING);
    $quantity = filter_var($inputData['quantity'], FILTER_SANITIZE_NUMBER_INT);
    $price = filter_var($inputData['price'], FILTER_SANITIZE_NUMBER_FLOAT);

    // Insert new sale data
    $stmt = $pdo->prepare('INSERT INTO المبيعات (product_name, quantity, price) VALUES (:product_name, :quantity, :price)');
    $stmt->bindParam(':product_name', $productName);
    $stmt->bindParam(':quantity', $quantity);
    $stmt->bindParam(':price', $price);
    $stmt->execute();

    // Return success message
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Sale created successfully']);
    exit;
}

// Handle PUT request
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Validate input data
    if (!isset($inputData['id']) || !isset($inputData['product_name']) || !isset($inputData['quantity']) || !isset($inputData['price'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }

    // Sanitize input data
    $id = filter_var($inputData['id'], FILTER_SANITIZE_NUMBER_INT);
    $productName = filter_var($inputData['product_name'], FILTER_SANITIZE_STRING);
    $quantity = filter_var($inputData['quantity'], FILTER_SANITIZE_NUMBER_INT);
    $price = filter_var($inputData['price'], FILTER_SANITIZE_NUMBER_FLOAT);

    // Update sale data
    $stmt = $pdo->prepare('UPDATE المبيعات SET product_name = :product_name, quantity = :quantity, price = :price WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':product_name', $productName);
    $stmt->bindParam(':quantity', $quantity);
    $stmt->bindParam(':price', $price);
    $stmt->execute();

    // Return success message
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Sale updated successfully']);
    exit;
}

// Handle DELETE request
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Validate input data
    if (!isset($inputData['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }

    // Sanitize input data
    $id = filter_var($inputData['id'], FILTER_SANITIZE_NUMBER_INT);

    // Delete sale data
    $stmt = $pdo->prepare('DELETE FROM المبيعات WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Return success message
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Sale deleted successfully']);
    exit;
}