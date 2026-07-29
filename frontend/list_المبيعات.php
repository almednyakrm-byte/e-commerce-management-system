**list_المبيعات.php**

<?php
// Session validation
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
    <title>المبيعات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #1a1d23;
            padding: 1rem;
            text-align: center;
        }
        .header a {
            color: #fff;
            text-decoration: none;
        }
        .header a:hover {
            color: #ccc;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 1rem;
            text-align: center;
        }
        .table th {
            background-color: #1a1d23;
            color: #fff;
        }
        .search-bar {
            width: 50%;
            padding: 1rem;
            border: 1px solid #ddd;
            border-radius: 0.5rem;
        }
        .search-bar input[type="search"] {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.5rem;
        }
        .search-bar input[type="search"]:focus {
            outline: none;
            box-shadow: 0 0 0 0.25rem rgba(0, 0, 0, 0.25);
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الرئيسية</a>
        <span class="text-teal-500 font-bold">مرحباً, <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php">تسجيل الخروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl text-emerald-600 font-bold mb-4">المبيعات</h1>
        <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded mb-4" onclick="location.href='create_المبيعات.php'">إضافة جديد</button>
        <div class="search-bar">
            <input type="search" id="search-input" placeholder="بحث...">
            <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>رقم المبيعات</th>
                    <th>تاريخ المبيعات</th>
                    <th>المبلغ</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody id="records-table">
                <!-- Records will be fetched from backend using AJAX -->
            </tbody>
        </table>
    </div>

    <script>
        // Fetch records from backend using AJAX
        async function fetchRecords() {
            const response = await fetch('../backend/المبيعات.php');
            const data = await response.json();
            const recordsTable = document.getElementById('records-table');
            recordsTable.innerHTML = '';
            data.forEach(record => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${record.id}</td>
                    <td>${record.date}</td>
                    <td>${record.amount}</td>
                    <td>
                        <a href="edit_المبيعات.php?id=${record.id}" class="text-emerald-600 hover:text-emerald-900">تعديل</a>
                        <button class="text-teal-500 hover:text-teal-900" onclick="deleteRecord(${record.id})">حذف</button>
                    </td>
                `;
                recordsTable.appendChild(row);
            });
        }

        // Search records using AJAX
        async function searchRecords() {
            const searchInput = document.getElementById('search-input');
            const searchQuery = searchInput.value.trim();
            if (searchQuery === '') {
                fetchRecords();
                return;
            }
            const response = await fetch('../backend/المبيعات.php?search=' + searchQuery);
            const data = await response.json();
            const recordsTable = document.getElementById('records-table');
            recordsTable.innerHTML = '';
            data.forEach(record => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${record.id}</td>
                    <td>${record.date}</td>
                    <td>${record.amount}</td>
                    <td>
                        <a href="edit_المبيعات.php?id=${record.id}" class="text-emerald-600 hover:text-emerald-900">تعديل</a>
                        <button class="text-teal-500 hover:text-teal-900" onclick="deleteRecord(${record.id})">حذف</button>
                    </td>
                `;
                recordsTable.appendChild(row);
            });
        }

        // Delete record using AJAX
        async function deleteRecord(id) {
            if (confirm('هل أنت متأكد من حذف هذا السجل؟')) {
                const response = await fetch('../backend/المبيعات.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                });
                if (response.ok) {
                    fetchRecords();
                } else {
                    alert('حدث خطأ أثناء حذف السجل');
                }
            }
        }

        // Fetch records on page load
        fetchRecords();
    </script>
</body>
</html>

This code uses the Fetch API to fetch records from the backend and display them in a table. It also includes a search bar that filters the records in real-time. The delete button uses an AJAX request to delete the record from the backend. The code is written in PHP and HTML, with some JavaScript used for the AJAX requests and event handling.