**list_المنتجات.php**

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
    <title>المنتجات</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            direction: rtl;
        }
        .emerald-600 {
            color: #008000;
        }
        .teal-500 {
            color: #0097a7;
        }
    </style>
</head>
<body>
    <header class="bg-gray-100 py-4">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <a href="index.php" class="text-lg font-bold">الصفحة الرئيسية</a>
            <div class="flex items-center">
                <span class="text-lg font-bold"><?= $_SESSION['username'] ?></span>
                <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ml-4" onclick="location.href='logout.php'">تسجيل الخروج</button>
            </div>
        </div>
    </header>
    <main class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-4">المنتجات</h1>
        <button class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded mb-4" onclick="location.href='create_المنتجات.php'">إضافة منتج جديد</button>
        <div class="flex justify-between items-center mb-4">
            <input type="search" class="w-full py-2 pl-10 text-lg font-bold border border-gray-400 rounded-lg focus:outline-none focus:border-emerald-600" placeholder="بحث" id="search" onkeyup="filterData()">
            <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded" onclick="location.href='create_المنتجات.php'">إضافة منتج جديد</button>
        </div>
        <table class="w-full border-collapse border border-gray-400">
            <thead>
                <tr>
                    <th class="border border-gray-400 px-4 py-2">الاسم</th>
                    <th class="border border-gray-400 px-4 py-2">التفاصيل</th>
                    <th class="border border-gray-400 px-4 py-2">الإجراءات</th>
                </tr>
            </thead>
            <tbody id="data">
                <?php
                // Fetch data from backend
                $response = file_get_contents('../backend/المنتجات.php');
                $data = json_decode($response, true);
                foreach ($data as $item) {
                    ?>
                    <tr>
                        <td class="border border-gray-400 px-4 py-2"><?= $item['name'] ?></td>
                        <td class="border border-gray-400 px-4 py-2"><?= $item['details'] ?></td>
                        <td class="border border-gray-400 px-4 py-2">
                            <a href="edit_المنتجات.php?id=<?= $item['id'] ?>" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded mr-2">تعديل</a>
                            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteItem(<?= $item['id'] ?>)">حذف</button>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </main>
    <script>
        function filterData() {
            const search = document.getElementById('search').value.toLowerCase();
            const data = document.getElementById('data');
            const rows = data.getElementsByTagName('tr');
            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const cells = row.getElementsByTagName('td');
                let match = false;
                for (let j = 0; j < cells.length; j++) {
                    const cell = cells[j];
                    if (cell.textContent.toLowerCase().includes(search)) {
                        match = true;
                        break;
                    }
                }
                if (match) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        }

        function deleteItem(id) {
            if (confirm('هل أنت متأكد من حذف هذا المنتج؟')) {
                fetch('../backend/المنتجات.php', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id: id })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('تم حذف المنتج بنجاح');
                        location.reload();
                    } else {
                        alert('حدث خطأ أثناء حذف المنتج');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }
    </script>
</body>
</html>

This code creates a premium Tailwind UI layout with a header navigation, a table showing the list of records, and a search bar filtering elements in real-time. The `deleteItem` function uses an AJAX DELETE request to delete a record from the backend.