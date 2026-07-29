<?php
require_once 'db.php';

// Get user role and authentication token from session
$userRole = $_SESSION['userRole'];
$authToken = $_SESSION['authToken'];

// Check if user is logged in
if (!$authToken) {
    http_response_code(401);
    echo json_encode(array('error' => 'Unauthorized'));
    exit;
}

// Handle GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Validate and sanitize input parameters
    $params = array();
    if (isset($_GET['id'])) {
        $params['id'] = intval($_GET['id']);
    }

    // Prepare SQL query
    $sql = 'SELECT * FROM إدارة_الشحن';
    if (!empty($params)) {
        $sql .= ' WHERE id = :id';
    }

    // Execute query using PDO Prepared Statements
    try {
        $stmt = $pdo->prepare($sql);
        if (!empty($params)) {
            $stmt->bindParam(':id', $params['id']);
        }
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($data);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Read JSON input
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input data
    if (!isset($input['name']) || !isset($input['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request data'));
        exit;
    }

    // Prepare SQL query
    $sql = 'INSERT INTO إدارة_الشحن (name, description) VALUES (:name, :description)';

    // Execute query using PDO Prepared Statements
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $input['name']);
        $stmt->bindParam(':description', $input['description']);
        $stmt->execute();
        $id = $pdo->lastInsertId();
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(array('id' => $id));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Validate and sanitize input parameters
    $params = array();
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }
    $params['id'] = intval($_GET['id']);

    // Read JSON input
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input data
    if (!isset($input['name']) || !isset($input['description'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request data'));
        exit;
    }

    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Prepare SQL query
    $sql = 'UPDATE إدارة_الشحن SET name = :name, description = :description WHERE id = :id';

    // Execute query using PDO Prepared Statements
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $input['name']);
        $stmt->bindParam(':description', $input['description']);
        $stmt->bindParam(':id', $params['id']);
        $stmt->execute();
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Updated successfully'));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Validate and sanitize input parameters
    $params = array();
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode(array('error' => 'Invalid request'));
        exit;
    }
    $params['id'] = intval($_GET['id']);

    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        echo json_encode(array('error' => 'Forbidden'));
        exit;
    }

    // Prepare SQL query
    $sql = 'DELETE FROM إدارة_الشحن WHERE id = :id';

    // Execute query using PDO Prepared Statements
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $params['id']);
        $stmt->execute();
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(array('message' => 'Deleted successfully'));
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(array('error' => 'Internal Server Error'));
    }
}