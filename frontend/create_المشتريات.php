**create_المشتريات.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../config/database.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $quantity = trim($_POST['quantity']);
    $price = trim($_POST['price']);

    // Check if fields are empty
    if (empty($name) || empty($description) || empty($quantity) || empty($price)) {
        $error = 'Please fill in all fields';
    } else {
        // Insert data into database
        $sql = "INSERT INTO المشتريات (name, description, quantity, price) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssds", $name, $description, $quantity, $price);
        $stmt->execute();

        // Redirect to list page
        header('Location: list_المشتريات.php');
        exit;
    }
}

// Include header
require_once '../includes/header.php';
?>

<!-- Create purchase form -->
<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-emerald-600 mb-4">Create Purchase</h2>
    <form id="create-purchase-form" method="post">
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Name:</label>
            <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-sm text-gray-700 border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500" required>
        </div>
        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700">Description:</label>
            <textarea id="description" name="description" class="block w-full p-2 mt-1 text-sm text-gray-700 border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500" required></textarea>
        </div>
        <div class="mb-4">
            <label for="quantity" class="block text-sm font-medium text-gray-700">Quantity:</label>
            <input type="number" id="quantity" name="quantity" class="block w-full p-2 mt-1 text-sm text-gray-700 border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500" required>
        </div>
        <div class="mb-4">
            <label for="price" class="block text-sm font-medium text-gray-700">Price:</label>
            <input type="number" id="price" name="price" class="block w-full p-2 mt-1 text-sm text-gray-700 border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500" required>
        </div>
        <button type="submit" name="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded">Create Purchase</button>
    </form>
    <?php if (isset($error)) : ?>
        <p class="text-red-500 mt-2"><?= $error ?></p>
    <?php endif; ?>
</div>

<!-- Include footer -->
<?php require_once '../includes/footer.php'; ?>


**create_المشتريات.js**
javascript
// Get form element
const form = document.getElementById('create-purchase-form');

// Add event listener to form submission
form.addEventListener('submit', (e) => {
    // Prevent default form submission
    e.preventDefault();

    // Get form data
    const formData = new FormData(form);

    // Send AJAX request to backend
    fetch('../backend/المشتريات.php', {
        method: 'POST',
        body: formData,
    })
    .then((response) => response.json())
    .then((data) => {
        if (data.success) {
            // Redirect to list page
            window.location.href = 'list_المشتريات.php';
        } else {
            // Display error message
            const errorElement = document.querySelector('.text-red-500');
            errorElement.textContent = data.error;
        }
    })
    .catch((error) => console.error(error));
});


**../backend/المشتريات.php**

<?php
// Include database connection
require_once '../config/database.php';

// Check if form data has been sent
if (isset($_POST['name']) && isset($_POST['description']) && isset($_POST['quantity']) && isset($_POST['price'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $quantity = trim($_POST['quantity']);
    $price = trim($_POST['price']);

    // Insert data into database
    $sql = "INSERT INTO المشتريات (name, description, quantity, price) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssds", $name, $description, $quantity, $price);
    $stmt->execute();

    // Return success response
    echo json_encode(['success' => true]);
} else {
    // Return error response
    echo json_encode(['error' => 'Invalid form data']);
}