**edit_المبيعات.php**

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get record ID from URL
$id = $_GET['id'];

// Fetch existing record details via AJAX
$js = "
<script>
    $(document).ready(function() {
        $.ajax({
            type: 'GET',
            url: '../backend/المبيعات.php?id=" . $id . "',
            dataType: 'json',
            success: function(data) {
                $('#name').val(data.name);
                $('#price').val(data.price);
                $('#quantity').val(data.quantity);
            }
        });
    });
</script>
";

// Include header and JS
include 'header.php';
echo $js;

// Form to update existing record
?>

<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-emerald-600 mb-4">تعديل المبيعات</h2>
    <form id="update-form" class="space-y-4">
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">اسم المنتج</label>
            <input type="text" id="name" name="name" class="block w-full px-4 py-2 text-gray-700 placeholder-gray-300 border border-gray-300 rounded-md focus:ring-emerald-600 focus:border-emerald-600" required>
        </div>
        <div>
            <label for="price" class="block text-sm font-medium text-gray-700">السعر</label>
            <input type="number" id="price" name="price" class="block w-full px-4 py-2 text-gray-700 placeholder-gray-300 border border-gray-300 rounded-md focus:ring-emerald-600 focus:border-emerald-600" required>
        </div>
        <div>
            <label for="quantity" class="block text-sm font-medium text-gray-700">الكمية</label>
            <input type="number" id="quantity" name="quantity" class="block w-full px-4 py-2 text-gray-700 placeholder-gray-300 border border-gray-300 rounded-md focus:ring-emerald-600 focus:border-emerald-600" required>
        </div>
        <button type="submit" class="w-full px-4 py-2 text-white bg-emerald-600 rounded-md hover:bg-emerald-700 focus:ring-emerald-600 focus:border-emerald-600">تعديل</button>
    </form>
</div>

<script>
    $(document).ready(function() {
        $('#update-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'PUT',
                url: '../backend/المبيعات.php',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        window.location.href = 'list_المبيعات.php';
                    } else {
                        alert('Error updating record');
                    }
                }
            });
        });
    });
</script>

<?php
include 'footer.php';
?>


**header.php**

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل المبيعات</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <?php echo $js; ?>


**footer.php**

</body>
</html>


**backend/المبيعات.php**

<?php
// Check if record ID is set
if (!isset($_GET['id'])) {
    exit;
}

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get record ID
$id = $_GET['id'];

// Fetch existing record details
$sql = "SELECT * FROM المبيعات WHERE id = '$id'";
$result = $conn->query($sql);

// Check if record exists
if ($result->num_rows > 0) {
    // Get record details
    $row = $result->fetch_assoc();
    echo json_encode($row);
} else {
    echo json_encode(array('error' => 'Record not found'));
}

// Close database connection
$conn->close();
?>


**backend/المبيعات.php (PUT request handler)**

<?php
// Check if record ID is set
if (!isset($_GET['id'])) {
    exit;
}

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get record ID
$id = $_GET['id'];

// Get updated record details
$name = $_POST['name'];
$price = $_POST['price'];
$quantity = $_POST['quantity'];

// Update record
$sql = "UPDATE المبيعات SET name = '$name', price = '$price', quantity = '$quantity' WHERE id = '$id'";
if ($conn->query($sql) === TRUE) {
    echo json_encode(array('success' => true));
} else {
    echo json_encode(array('error' => 'Error updating record'));
}

// Close database connection
$conn->close();
?>