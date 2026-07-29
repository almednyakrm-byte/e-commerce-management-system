<?php
// Session validation
session_start();
if (!isset($_SESSION['authenticated'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة المنتجات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .emerald-600 {
            color: #34C759;
        }
        .teal-500 {
            color: #1ABC9C;
        }
    </style>
</head>
<body>
    <header class="bg-emerald-600 text-white p-4">
        <nav class="flex justify-between">
            <a href="index.php" class="text-lg font-bold">الرئيسية</a>
            <div class="flex items-center">
                <span class="mr-2"><?= $_SESSION['username'] ?></span>
                <a href="logout.php" class="text-lg font-bold">تسجيل الخروج</a>
            </div>
        </nav>
    </header>
    <main class="p-4">
        <h1 class="text-3xl font-bold mb-4">إدارة المنتجات</h1>
        <div class="flex justify-between mb-4">
            <button id="add-new-item" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">
                <a href="create_إدارة-المنتجات.php">إضافة منتج جديد</a>
            </button>
            <input id="search" type="text" class="py-2 pl-10 text-sm text-gray-700" placeholder="بحث...">
        </div>
        <table id="products-table" class="w-full text-right">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2">الاسم</th>
                    <th class="px-4 py-2">الوصف</th>
                    <th class="px-4 py-2">العمليات</th>
                </tr>
            </thead>
            <tbody id="table-body">
                <!-- Table data will be populated here -->
            </tbody>
        </table>
    </main>

    <script>
        // Fetch products data from backend
        fetch('../backend/إدارة-المنتجات.php')
            .then(response => response.json())
            .then(data => {
                const tableBody = document.getElementById('table-body');
                data.forEach(product => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-4 py-2">${product.name}</td>
                        <td class="px-4 py-2">${product.description}</td>
                        <td class="px-4 py-2">
                            <a href="edit_إدارة-المنتجات.php?id=${product.id}" class="text-emerald-600 hover:text-emerald-900">تعديل</a>
                            <button class="text-red-600 hover:text-red-900 ml-2" onclick="deleteProduct(${product.id})">حذف</button>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });
            });

        // Delete product
        function deleteProduct(id) {
            fetch('../backend/إدارة-المنتجات.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove the deleted product from the table
                    const tableBody = document.getElementById('table-body');
                    const rows = tableBody.children;
                    for (let i = 0; i < rows.length; i++) {
                        const row = rows[i];
                        const deleteButton = row.querySelector('button');
                        if (deleteButton && deleteButton.onclick.toString().includes(`deleteProduct(${id})`)) {
                            tableBody.removeChild(row);
                            break;
                        }
                    }
                }
            });
        }

        // Search functionality
        const searchInput = document.getElementById('search');
        searchInput.addEventListener('input', () => {
            const searchValue = searchInput.value.toLowerCase();
            const tableBody = document.getElementById('table-body');
            const rows = tableBody.children;
            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const nameCell = row.children[0];
                const descriptionCell = row.children[1];
                if (nameCell.textContent.toLowerCase().includes(searchValue) || descriptionCell.textContent.toLowerCase().includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    </script>
</body>
</html>