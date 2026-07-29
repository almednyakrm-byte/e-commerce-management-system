**create_المنتجات.php**

<?php
// Session validation
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
include 'header.php';
include 'navigation.php';
?>

<!-- Page content -->
<div class="container mx-auto p-4 pt-6 md:p-6 lg:px-12 xl:px-24">
    <h1 class="text-3xl font-bold mb-4 text-emerald-600">إضافة منتج جديد</h1>

    <!-- Form -->
    <form id="create-product-form" class="bg-white rounded shadow-md p-6">
        <div class="mb-4">
            <label for="name" class="block text-sm font-bold text-gray-700">اسم المنتج</label>
            <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-sm text-gray-700 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500">
        </div>

        <div class="mb-4">
            <label for="description" class="block text-sm font-bold text-gray-700">وصف المنتج</label>
            <textarea id="description" name="description" class="block w-full p-2 mt-1 text-sm text-gray-700 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500"></textarea>
        </div>

        <div class="mb-4">
            <label for="price" class="block text-sm font-bold text-gray-700">سعر المنتج</label>
            <input type="number" id="price" name="price" class="block w-full p-2 mt-1 text-sm text-gray-700 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500">
        </div>

        <div class="mb-4">
            <label for="stock" class="block text-sm font-bold text-gray-700">مخزون المنتج</label>
            <input type="number" id="stock" name="stock" class="block w-full p-2 mt-1 text-sm text-gray-700 border border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500">
        </div>

        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded">إضافة المنتج</button>
    </form>
</div>

<!-- JavaScript -->
<script>
    $(document).ready(function() {
        $('#create-product-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/المنتجات.php',
                data: formData,
                success: function(response) {
                    if (response == 'success') {
                        window.location.href = 'list_المنتجات.php';
                    } else {
                        alert('Error adding product');
                    }
                }
            });
        });
    });
</script>

<?php
// Include footer
include 'footer.php';
?>


**المنتجات.php (backend)**

<?php
// Include database connection
include 'database.php';

// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize input data
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
    $price = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
    $stock = filter_var($_POST['stock'], FILTER_SANITIZE_NUMBER_INT);

    // Insert data into database
    $query = "INSERT INTO products (name, description, price, stock) VALUES ('$name', '$description', '$price', '$stock')";
    $result = mysqli_query($conn, $query);

    // Check if data is inserted successfully
    if ($result) {
        echo 'success';
    } else {
        echo 'Error adding product';
    }
}
?>