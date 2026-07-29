**create_إدارة-المخزون.php**

<?php
// Session validation
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

// Include header
require_once 'header.php';

// Include Tailwind CSS
?>
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

<?php
// Include navigation
require_once 'navigation.php';
?>

<div class="container mx-auto p-4 mt-6">
    <h1 class="text-3xl font-bold text-emerald-600">إضافة إدارة المخزون</h1>
    <form id="create-form" class="bg-white p-4 rounded shadow-md">
        <div class="grid grid-cols-1 gap-4">
            <div class="col-span-2">
                <label for="name" class="block text-sm font-medium text-gray-700">اسم الإدارة</label>
                <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-sm text-gray-700 border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500">
            </div>
            <div class="col-span-2">
                <label for="description" class="block text-sm font-medium text-gray-700">وصف الإدارة</label>
                <textarea id="description" name="description" class="block w-full p-2 mt-1 text-sm text-gray-700 border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500"></textarea>
            </div>
            <div class="col-span-2">
                <label for="status" class="block text-sm font-medium text-gray-700">حالة الإدارة</label>
                <select id="status" name="status" class="block w-full p-2 mt-1 text-sm text-gray-700 border-gray-300 rounded-md focus:ring-teal-500 focus:border-teal-500">
                    <option value="active">فعال</option>
                    <option value="inactive">غير فعال</option>
                </select>
            </div>
        </div>
        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded">حفظ</button>
    </form>
</div>

<?php
// Include footer
require_once 'footer.php';
?>

<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/إدارة-المخزون.php',
                data: $(this).serialize(),
                success: function(response) {
                    if (response == 'success') {
                        window.location.href = 'list_إدارة-المخزون.php';
                    } else {
                        alert('Error: ' + response);
                    }
                }
            });
        });
    });
</script>


**backend/إدارة-المخزون.php**

<?php
// Include database connection
require_once 'db.php';

// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize input data
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
    $status = filter_var($_POST['status'], FILTER_SANITIZE_STRING);

    // Insert data into database
    $query = "INSERT INTO إدارة_المخزون (name, description, status) VALUES ('$name', '$description', '$status')";
    $result = mysqli_query($conn, $query);

    // Check if data is inserted successfully
    if ($result) {
        echo 'success';
    } else {
        echo 'Error: ' . mysqli_error($conn);
    }
}
?>