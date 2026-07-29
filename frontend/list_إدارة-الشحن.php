**list_إدارة-الشحن.php**

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
    <title>إدارة الشحن</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
        }
        .header {
            background-color: #2d6fde;
            color: #fff;
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
            background-color: #2d6fde;
            color: #fff;
        }
        .search-bar {
            width: 50%;
            padding: 1rem;
            border: 1px solid #ccc;
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
        <span class="text-lg font-bold">مركز إدارة الشحن</span>
        <a href="profile.php">حسناً <?= $_SESSION['username'] ?></a>
        <a href="logout.php">تسجيل خروج</a>
    </div>
    <div class="container mx-auto p-4">
        <div class="flex justify-between mb-4">
            <h1 class="text-2xl font-bold">إدارة الشحن</h1>
            <a href="create_إدارة-الشحن.php" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded">إضافة جديد</a>
        </div>
        <div class="flex justify-between mb-4">
            <input type="search" id="search" class="search-bar" placeholder="بحث...">
            <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded" onclick="searchRecords()">بحث</button>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>رقم الشحنة</th>
                    <th>اسم المرسل</th>
                    <th>اسم المرسل إليه</th>
                    <th>تاريخ الشحنة</th>
                    <th>حالة الشحنة</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="records">
                <!-- Records will be displayed here -->
            </tbody>
        </table>
    </div>

    <script>
        // Fetch API to get records
        async function getRecords() {
            try {
                const response = await fetch('../backend/إدارة-الشحن.php', { method: 'GET' });
                const data = await response.json();
                displayRecords(data);
            } catch (error) {
                console.error(error);
            }
        }

        // Display records in the table
        function displayRecords(data) {
            const records = document.getElementById('records');
            records.innerHTML = '';
            data.forEach((record) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${record.id}</td>
                    <td>${record.sender_name}</td>
                    <td>${record.recipient_name}</td>
                    <td>${record.shipment_date}</td>
                    <td>${record.shipment_status}</td>
                    <td>
                        <a href="edit_إدارة-الشحن.php?id=${record.id}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded">تعديل</a>
                        <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                    </td>
                `;
                records.appendChild(row);
            });
        }

        // Search records
        function searchRecords() {
            const searchInput = document.getElementById('search');
            const searchQuery = searchInput.value.trim();
            if (searchQuery) {
                fetch('../backend/إدارة-شحن.php', { method: 'GET', params: { search: searchQuery } })
                    .then((response) => response.json())
                    .then((data) => displayRecords(data))
                    .catch((error) => console.error(error));
            } else {
                getRecords();
            }
        }

        // Delete record
        async function deleteRecord(id) {
            if (confirm('هل تريد حذف هذا السجل؟')) {
                try {
                    const response = await fetch('../backend/إدارة-الشحن.php', { method: 'DELETE', params: { id } });
                    if (response.ok) {
                        getRecords();
                    } else {
                        alert('حذف السجل غير موفق');
                    }
                } catch (error) {
                    console.error(error);
                }
            }
        }

        // Initialize records
        getRecords();
    </script>
</body>
</html>

Note: This code assumes that you have a backend PHP script (`إدارة-الشحن.php`) that handles GET and DELETE requests to retrieve and delete records, respectively. You will need to implement this script separately.