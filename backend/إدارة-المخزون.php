<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Define routes
$routes = array(
    'GET' => array(
        '/stock-management' => 'getStockManagement',
        '/stock-management/:id' => 'getStockManagementById'
    ),
    'POST' => array(
        '/stock-management' => 'createStockManagement'
    ),
    'PUT' => array(
        '/stock-management/:id' => 'updateStockManagement'
    ),
    'DELETE' => array(
        '/stock-management/:id' => 'deleteStockManagement'
    )
);

// Define route handlers
function getStockManagement() {
    global $db;
    $stmt = $db->prepare('SELECT * FROM stock_management');
    $stmt->execute();
    $stockManagement = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($stockManagement);
}

function getStockManagementById($id) {
    global $db;
    $stmt = $db->prepare('SELECT * FROM stock_management WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $stockManagement = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($stockManagement) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($stockManagement);
    } else {
        http_response_code(404);
        echo json_encode(array('error' => 'Not found'));
    }
}

function createStockManagement() {
    global $db;
    // Validate input data
    if (!isset($input['name']) || !isset($input['quantity'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input'));
        exit;
    }
    
    // Sanitize input data
    $name = htmlspecialchars($input['name']);
    $quantity = htmlspecialchars($input['quantity']);
    
    // Prepare SQL query
    $stmt = $db->prepare('INSERT INTO stock_management (name, quantity) VALUES (:name, :quantity)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':quantity', $quantity);
    
    // Execute query
    if ($stmt->execute()) {
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Stock management created successfully'));
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal server error'));
    }
}

function updateStockManagement($id) {
    global $db;
    // Check if user is admin
    if ($_SESSION['role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    
    // Validate input data
    if (!isset($input['name']) || !isset($input['quantity'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid input'));
        exit;
    }
    
    // Sanitize input data
    $name = htmlspecialchars($input['name']);
    $quantity = htmlspecialchars($input['quantity']);
    
    // Prepare SQL query
    $stmt = $db->prepare('UPDATE stock_management SET name = :name, quantity = :quantity WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':quantity', $quantity);
    
    // Execute query
    if ($stmt->execute()) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Stock management updated successfully'));
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal server error'));
    }
}

function deleteStockManagement($id) {
    global $db;
    // Check if user is admin
    if ($_SESSION['role'] != 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
    
    // Prepare SQL query
    $stmt = $db->prepare('DELETE FROM stock_management WHERE id = :id');
    $stmt->bindParam(':id', $id);
    
    // Execute query
    if ($stmt->execute()) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Stock management deleted successfully'));
    } else {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal server error'));
    }
}

// Get route
$route = $_SERVER['REQUEST_URI'];
$routeParts = explode('/', $route);
$route = '/' . $routeParts[count($routeParts) - 1];

// Get HTTP method
$method = $_SERVER['REQUEST_METHOD'];

// Get route handler
if (isset($routes[$method][$route])) {
    $handler = $routes[$method][$route];
    if (count($routeParts) > 1) {
        $id = $routeParts[count($routeParts) - 2];
        $handler($id);
    } else {
        $handler();
    }
} else {
    http_response_code(404);
    echo json_encode(array('error' => 'Not found'));
}