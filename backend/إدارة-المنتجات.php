<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Get user role
$user_role = $_SESSION['user_role'];

// Get input data
$input_data = json_decode(file_get_contents('php://input'), true);

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Check if user is admin
    if ($user_role == 'admin') {
        // Get all products
        $stmt = $pdo->prepare('SELECT * FROM products');
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($products);
    } else {
        // Get user's products
        $stmt = $pdo->prepare('SELECT * FROM products WHERE user_id = :user_id');
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($products);
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if user is admin or owner
    if ($user_role == 'admin' || $_SESSION['user_id'] == $input_data['user_id']) {
        // Validate input data
        if (!isset($input_data['name']) || !isset($input_data['description']) || !isset($input_data['price'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid input data'));
            exit;
        }
        
        // Sanitize input data
        $name = htmlspecialchars($input_data['name']);
        $description = htmlspecialchars($input_data['description']);
        $price = htmlspecialchars($input_data['price']);
        
        // Insert product
        $stmt = $pdo->prepare('INSERT INTO products (name, description, price, user_id) VALUES (:name, :description, :price, :user_id)');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        $stmt->execute();
        
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Product created successfully'));
    } else {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    // Check if user is admin or owner
    if ($user_role == 'admin' || $_SESSION['user_id'] == $input_data['user_id']) {
        // Validate input data
        if (!isset($input_data['id']) || !isset($input_data['name']) || !isset($input_data['description']) || !isset($input_data['price'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid input data'));
            exit;
        }
        
        // Sanitize input data
        $id = htmlspecialchars($input_data['id']);
        $name = htmlspecialchars($input_data['name']);
        $description = htmlspecialchars($input_data['description']);
        $price = htmlspecialchars($input_data['price']);
        
        // Update product
        $stmt = $pdo->prepare('UPDATE products SET name = :name, description = :description, price = :price WHERE id = :id AND user_id = :user_id');
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        $stmt->execute();
        
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Product updated successfully'));
    } else {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    // Check if user is admin
    if ($user_role == 'admin') {
        // Validate input data
        if (!isset($input_data['id'])) {
            http_response_code(400);
            echo json_encode(array('error' => 'Invalid input data'));
            exit;
        }
        
        // Sanitize input data
        $id = htmlspecialchars($input_data['id']);
        
        // Delete product
        $stmt = $pdo->prepare('DELETE FROM products WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Product deleted successfully'));
    } else {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }
}