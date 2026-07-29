<?php
session_start();

// Check if user is authenticated
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام إدارة المتجر الإلكتروني</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .glassmorphism {
            background-color: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 10px;
            padding: 20px;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4 pt-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-3xl font-bold text-emerald-600">نظام إدارة المتجر الإلكتروني</h1>
            <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='logout.php'">تسجيل خروج</button>
        </div>
        <div class="glassmorphism bg-white p-4 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold text-emerald-600">مرحباً <?= $_SESSION['username'] ?></h2>
            <p class="text-gray-600">إدارة المتجر الإلكتروني</p>
        </div>
        <div class="glassmorphism bg-white p-4 rounded-lg shadow-md mt-4">
            <h2 class="text-2xl font-bold text-emerald-600">إحصائيات المتجر</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h3 class="text-lg font-bold text-emerald-600">عدد المنتجات</h3>
                    <p id="product-count" class="text-gray-600"></p>
                </div>
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h3 class="text-lg font-bold text-emerald-600">المخزون الحالي</h3>
                    <p id="inventory-count" class="text-gray-600"></p>
                </div>
                <div class="bg-white rounded-lg shadow-md p-4">
                    <h3 class="text-lg font-bold text-emerald-600">عدد الشحنات</h3>
                    <p id="shipment-count" class="text-gray-600"></p>
                </div>
            </div>
        </div>
        <div class="glassmorphism bg-white p-4 rounded-lg shadow-md mt-4">
            <h2 class="text-2xl font-bold text-emerald-600">إدارة المتجر</h2>
            <div class="flex justify-between items-center mt-4">
                <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='products.php'">إدارة المنتجات</button>
                <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='inventory.php'">إدارة المخزون</button>
                <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='shipments.php'">إدارة الشحن</button>
            </div>
        </div>
    </div>

    <script>
        fetch('api/stats.php')
            .then(response => response.json())
            .then(data => {
                document.getElementById('product-count').innerHTML = data.product_count;
                document.getElementById('inventory-count').innerHTML = data.inventory_count;
                document.getElementById('shipment-count').innerHTML = data.shipment_count;
            })
            .catch(error => console.error(error));
    </script>
</body>
</html>


This code assumes you have a PHP file named `api/stats.php` that returns a JSON object with the following structure:

{
    "product_count": 100,
    "inventory_count": 500,
    "shipment_count": 20
}

You will need to create this file and modify it to fetch the data from your database.

Also, make sure to replace the `logout.php` file with your actual logout script.

This code uses the `fetch` API to make a GET request to the `api/stats.php` file and retrieve the stats data. It then updates the HTML elements with the received data.

Note that this code assumes you have the `api/stats.php` file in the same directory as the `index.php` file. If it's in a different directory, you'll need to modify the `fetch` URL accordingly.