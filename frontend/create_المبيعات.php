**create_المبيعات.php**

<?php
// Session validation
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit;
}

// Include header
include 'header.php';

// Include Tailwind CSS
?>

<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

<div class="container mx-auto p-4">
    <h1 class="text-3xl font-bold mb-4 text-emerald-600">إضافة مبيعات جديدة</h1>
    <form id="create-form" class="bg-white p-4 rounded shadow-md">
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">اسم المبيعات</label>
            <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-sm text-gray-700 border-gray-300 rounded-md focus:ring-emerald-600 focus:border-emerald-600">
        </div>
        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700">وصف المبيعات</label>
            <textarea id="description" name="description" class="block w-full p-2 mt-1 text-sm text-gray-700 border-gray-300 rounded-md focus:ring-emerald-600 focus:border-emerald-600"></textarea>
        </div>
        <div class="mb-4">
            <label for="price" class="block text-sm font-medium text-gray-700">سعر المبيعات</label>
            <input type="number" id="price" name="price" class="block w-full p-2 mt-1 text-sm text-gray-700 border-gray-300 rounded-md focus:ring-emerald-600 focus:border-emerald-600">
        </div>
        <div class="mb-4">
            <label for="quantity" class="block text-sm font-medium text-gray-700">كمية المبيعات</label>
            <input type="number" id="quantity" name="quantity" class="block w-full p-2 mt-1 text-sm text-gray-700 border-gray-300 rounded-md focus:ring-emerald-600 focus:border-emerald-600">
        </div>
        <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">إضافة</button>
    </form>
</div>

<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/المبيعات.php',
                data: formData,
                success: function(response) {
                    if (response == 'success') {
                        window.location.href = 'list_المبيعات.php';
                    } else {
                        alert('Error: ' + response);
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


**Note:** This code assumes that you have jQuery and Tailwind CSS installed. Also, you need to replace `../backend/المبيعات.php` with the actual URL of your backend script that handles the form submission.