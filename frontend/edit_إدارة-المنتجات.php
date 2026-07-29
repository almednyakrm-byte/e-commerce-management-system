**edit_إدارة-المنتجات.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get the product ID from URL
$id = $_GET['id'];

// Fetch product details via AJAX
$js = '
<script>
    $(document).ready(function() {
        $.ajax({
            type: "GET",
            url: "../backend/إدارة-المنتجات.php?id=' . $id . '",
            dataType: "json",
            success: function(data) {
                $("#name").val(data.name);
                $("#description").val(data.description);
                $("#price").val(data.price);
            }
        });
    });
</script>
';

// Include JavaScript code
echo $js;

?>

<!-- Edit Product Form -->
<div class="max-w-md mx-auto p-8 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-emerald-600 mb-4">تعديل المنتج</h2>
    <form id="edit-product-form" method="post">
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">اسم المنتج:</label>
            <input type="text" id="name" name="name" class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-md focus:ring-emerald-600 focus:border-emerald-600">
        </div>
        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700">وصف المنتج:</label>
            <textarea id="description" name="description" class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-md focus:ring-emerald-600 focus:border-emerald-600"></textarea>
        </div>
        <div class="mb-4">
            <label for="price" class="block text-sm font-medium text-gray-700">سعر المنتج:</label>
            <input type="number" id="price" name="price" class="block w-full px-4 py-2 text-gray-700 border border-gray-300 rounded-md focus:ring-emerald-600 focus:border-emerald-600">
        </div>
        <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">تعديل المنتج</button>
    </form>
</div>

<script>
    $(document).ready(function() {
        $('#edit-product-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "PUT",
                url: "../backend/إدارة-المنتجات.php",
                data: $(this).serialize(),
                dataType: "json",
                success: function(data) {
                    if (data.status == "success") {
                        window.location.href = "list_إدارة-المنتجات.php";
                    } else {
                        alert(data.message);
                    }
                }
            });
        });
    });
</script>


**backend/إدارة-المنتجات.php**

<?php
// Get product ID from URL
$id = $_GET['id'];

// Fetch product details from database
$product = get_product($id);

// Return product details as JSON
echo json_encode($product);

function get_product($id) {
    // Connect to database
    $conn = mysqli_connect("localhost", "username", "password", "database");

    // Query product details
    $query = "SELECT * FROM products WHERE id = '$id'";
    $result = mysqli_query($conn, $query);

    // Fetch product details
    $product = mysqli_fetch_assoc($result);

    // Close database connection
    mysqli_close($conn);

    return $product;
}


**backend/update_product.php**

<?php
// Get product ID and updated data from PUT request
$id = $_GET['id'];
$data = json_decode(file_get_contents('php://input'), true);

// Update product details in database
update_product($id, $data);

function update_product($id, $data) {
    // Connect to database
    $conn = mysqli_connect("localhost", "username", "password", "database");

    // Query product update
    $query = "UPDATE products SET name = '$data[name]', description = '$data[description]', price = '$data[price]' WHERE id = '$id'";
    mysqli_query($conn, $query);

    // Close database connection
    mysqli_close($conn);

    // Return success message as JSON
    echo json_encode(array('status' => 'success', 'message' => 'Product updated successfully'));
}