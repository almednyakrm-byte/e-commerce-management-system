**edit_المنتجات.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get product ID from URL
$id = $_GET['id'];

// Fetch product details via AJAX
$js = "
    $(document).ready(function() {
        $.ajax({
            type: 'GET',
            url: '../backend/المنتجات.php?id=" . $id . "',
            dataType: 'json',
            success: function(data) {
                $('#name').val(data.name);
                $('#description').val(data.description);
                $('#price').val(data.price);
            }
        });
    });
";

// Include JavaScript code
echo '<script>' . $js . '</script>';

// Create form
?>

<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-emerald-600 mb-4">تعديل المنتج</h2>
    <form id="edit-product-form" class="space-y-4">
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">اسم المنتج</label>
            <input type="text" id="name" name="name" class="block w-full px-4 py-2 text-sm text-gray-700 border-gray-300 rounded-lg focus:border-teal-500 focus:ring-teal-500">
        </div>
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700">وصف المنتج</label>
            <textarea id="description" name="description" class="block w-full px-4 py-2 text-sm text-gray-700 border-gray-300 rounded-lg focus:border-teal-500 focus:ring-teal-500"></textarea>
        </div>
        <div>
            <label for="price" class="block text-sm font-medium text-gray-700">سعر المنتج</label>
            <input type="number" id="price" name="price" class="block w-full px-4 py-2 text-sm text-gray-700 border-gray-300 rounded-lg focus:border-teal-500 focus:ring-teal-500">
        </div>
        <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-600">حفظ التعديلات</button>
    </form>
</div>

<script>
    // Submit form via AJAX
    $('#edit-product-form').submit(function(e) {
        e.preventDefault();
        $.ajax({
            type: 'PUT',
            url: '../backend/المنتجات.php',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(data) {
                if (data.success) {
                    window.location.href = 'list_المنتجات.php';
                } else {
                    alert(data.message);
                }
            }
        });
    });
</script>


**backend/المنتجات.php**

<?php
// Check if product ID is set
if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get product details
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $_GET['id']);
$stmt->execute();
$result = $stmt->get_result();

// Fetch product details
$product = $result->fetch_assoc();

// Close connection
$conn->close();

// Output product details as JSON
echo json_encode($product);


**backend/edit_product.php**

<?php
// Check if product ID is set
if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get product details
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $_GET['id']);
$stmt->execute();
$result = $stmt->get_result();

// Fetch product details
$product = $result->fetch_assoc();

// Update product details
if (isset($_POST['name']) && isset($_POST['description']) && isset($_POST['price'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    $stmt = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ? WHERE id = ?");
    $stmt->bind_param("ssdi", $name, $description, $price, $_GET['id']);
    $stmt->execute();

    // Close connection
    $conn->close();

    // Output success message
    echo json_encode(array('success' => true, 'message' => 'Product updated successfully'));
} else {
    // Close connection
    $conn->close();

    // Output error message
    echo json_encode(array('success' => false, 'message' => 'Invalid request'));
}