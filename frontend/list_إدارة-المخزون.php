**list_إدارة-المخزون.php**

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
    <title>إدارة المخزون</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
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
            width: 80%;
            margin: 2rem auto;
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
            margin: 2rem auto;
            padding: 1rem;
            border: 1px solid #ddd;
            border-radius: 0.5rem;
        }
        .search-bar input {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 0.5rem;
        }
        .search-bar input:focus {
            outline: none;
            box-shadow: 0 0 0 0.2rem #1a1d23;
        }
        .actions {
            text-align: center;
        }
        .actions button {
            margin: 1rem;
            padding: 1rem 2rem;
            border: none;
            border-radius: 0.5rem;
            background-color: #1a1d23;
            color: #fff;
            cursor: pointer;
        }
        .actions button:hover {
            background-color: #2c3e50;
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="index.php">الصفحة الرئيسية</a>
        <span class="text-lg font-bold">مرحباً <?php echo $_SESSION['username']; ?></span>
        <a href="logout.php">تسجيل الخروج</a>
    </div>
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-4">إدارة المخزون</h1>
        <div class="search-bar">
            <input type="search" id="search-input" placeholder="بحث...">
            <button id="search-button">بحث</button>
        </div>
        <button class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_إدارة-المخزون.php'">إضافة عنصر جديد</button>
        <table class="table">
            <thead>
                <tr>
                    <th>اسم العنصر</th>
                    <th>الكمية</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody id="records-table">
                <!-- Records will be loaded here -->
            </tbody>
        </table>
        <div class="actions">
            <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded" id="delete-button">حذف</button>
        </div>
    </div>

    <script>
        // Fetch records from backend
        fetch('../backend/إدارة-المخزون.php', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            const recordsTable = document.getElementById('records-table');
            data.forEach(record => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${record.name}</td>
                    <td>${record.quantity}</td>
                    <td>
                        <a href="edit_إدارة-المخزون.php?id=${record.id}" class="text-blue-600 hover:text-blue-800">تعديل</a>
                        <button class="text-red-600 hover:text-red-800" onclick="deleteRecord(${record.id})">حذف</button>
                    </td>
                `;
                recordsTable.appendChild(row);
            });
        })
        .catch(error => console.error(error));

        // Search functionality
        const searchInput = document.getElementById('search-input');
        const searchButton = document.getElementById('search-button');
        searchButton.addEventListener('click', () => {
            const searchQuery = searchInput.value.trim();
            if (searchQuery) {
                fetch('../backend/إدارة-المخزون.php', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    params: {
                        search: searchQuery
                    }
                })
                .then(response => response.json())
                .then(data => {
                    const recordsTable = document.getElementById('records-table');
                    recordsTable.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${record.name}</td>
                            <td>${record.quantity}</td>
                            <td>
                                <a href="edit_إدارة-المخزون.php?id=${record.id}" class="text-blue-600 hover:text-blue-800">تعديل</a>
                                <button class="text-red-600 hover:text-red-800" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        `;
                        recordsTable.appendChild(row);
                    });
                })
                .catch(error => console.error(error));
            } else {
                fetch('../backend/إدارة-المخزون.php', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    const recordsTable = document.getElementById('records-table');
                    recordsTable.innerHTML = '';
                    data.forEach(record => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${record.name}</td>
                            <td>${record.quantity}</td>
                            <td>
                                <a href="edit_إدارة-المخزون.php?id=${record.id}" class="text-blue-600 hover:text-blue-800">تعديل</a>
                                <button class="text-red-600 hover:text-red-800" onclick="deleteRecord(${record.id})">حذف</button>
                            </td>
                        `;
                        recordsTable.appendChild(row);
                    });
                })
                .catch(error => console.error(error));
            }
        });

        // Delete record functionality
        function deleteRecord(id) {
            if (confirm('هل تريد حذف العنصر؟')) {
                fetch('../backend/إدارة-المخزون.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف العنصر بنجاح!');
                        window.location.reload();
                    } else {
                        alert('حدث خطأ أثناء حذف العنصر!');
                    }
                })
                .catch(error => console.error(error));
            }
        }
    </script>
</body>
</html>

Note: This code assumes that you have a backend script (`إدارة-المخزون.php`) that handles the GET and DELETE requests. You will need to create this script to handle the data fetching and deletion logic.