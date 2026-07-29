**create_إدارة-المنتجات.php**

<?php
// Session validation
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
require_once 'header.php';
require_once 'navigation.php';
?>

<!-- Main content -->
<div class="container mx-auto p-4 pt-6 md:p-6 lg:px-8">
    <div class="bg-white rounded-lg shadow-md p-4 md:p-6 lg:p-8">
        <h2 class="text-lg font-bold text-emerald-600 mb-4">إضافة منتج جديد</h2>
        <form id="create-product-form">
            <div class="mb-4">
                <label class="block text-sm font-bold text-gray-700 mb-2" for="name">اسم المنتج</label>
                <input class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500" type="text" id="name" name="name" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-bold text-gray-700 mb-2" for="description">وصف المنتج</label>
                <textarea class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500" id="description" name="description" required></textarea>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-bold text-gray-700 mb-2" for="price">سعر المنتج</label>
                <input class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500" type="number" id="price" name="price" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-bold text-gray-700 mb-2" for="category">فئة المنتج</label>
                <select class="block w-full px-4 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500" id="category" name="category" required>
                    <option value="">اختر فئة</option>
                    <option value="Electronics">الالكترونيات</option>
                    <option value="Fashion">الملابس</option>
                    <option value="Home Goods">الأدوات المنزلية</option>
                </select>
            </div>
            <button class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-lg" type="submit">إضافة منتج</button>
        </form>
    </div>
</div>

<!-- Include footer -->
<?php require_once 'footer.php'; ?>

<script>
    $(document).ready(function() {
        $('#create-product-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/إدارة-المنتجات.php',
                data: $(this).serialize(),
                success: function(response) {
                    if (response === 'success') {
                        window.location.href = 'list_إدارة-المنتجات.php';
                    } else {
                        alert('Error adding product');
                    }
                }
            });
        });
    });
</script>


**backend/إدارة-المنتجات.php**

<?php
// Include database connection
require_once 'db.php';

// Check if form data is sent
if (isset($_POST['name']) && isset($_POST['description']) && isset($_POST['price']) && isset($_POST['category'])) {
    // Prepare SQL query
    $sql = "INSERT INTO products (name, description, price, category) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssds", $_POST['name'], $_POST['description'], $_POST['price'], $_POST['category']);
    // Execute query
    $stmt->execute();
    // Check if query is successful
    if ($stmt->affected_rows === 1) {
        echo 'success';
    } else {
        echo 'Error adding product';
    }
    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>