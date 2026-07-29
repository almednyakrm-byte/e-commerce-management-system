**create_إدارة-الشحن.php**

<?php
// Session validation
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Include header and navigation
require_once 'header.php';
require_once 'navigation.php';

// Form data
$data = array(
    'name' => '',
    'description' => '',
    'address' => '',
    'phone' => '',
    'email' => '',
);

// Form validation
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data['name'] = $_POST['name'];
    $data['description'] = $_POST['description'];
    $data['address'] = $_POST['address'];
    $data['phone'] = $_POST['phone'];
    $data['email'] = $_POST['email'];

    // AJAX request
    $ajax_url = '../backend/إدارة-الشحن.php';
    $ajax_data = array(
        'name' => $data['name'],
        'description' => $data['description'],
        'address' => $data['address'],
        'phone' => $data['phone'],
        'email' => $data['email'],
    );

    $ajax_response = json_decode(send_ajax_request($ajax_url, $ajax_data), true);

    if ($ajax_response['success']) {
        header('Location: list_إدارة-الشحن.php');
        exit;
    } else {
        $errors = $ajax_response['errors'];
    }
}

// Send AJAX request
function send_ajax_request($url, $data) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}

// Form HTML
?>

<div class="container mx-auto p-4 pt-6">
    <div class="bg-white rounded-lg shadow-md p-4">
        <h2 class="text-emerald-600 text-2xl font-bold mb-4">إضافة إدارة شحن جديدة</h2>
        <form id="create-shipping-admin-form" method="post">
            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">اسم الإدارة</label>
                <input type="text" id="name" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?= $data['name'] ?>">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-gray-700 text-sm font-bold mb-2">وصف الإدارة</label>
                <textarea id="description" name="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"><?= $data['description'] ?></textarea>
            </div>
            <div class="mb-4">
                <label for="address" class="block text-gray-700 text-sm font-bold mb-2">عنوان الإدارة</label>
                <input type="text" id="address" name="address" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?= $data['address'] ?>">
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">رقم الهاتف</label>
                <input type="text" id="phone" name="phone" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?= $data['phone'] ?>">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">البريد الإلكتروني</label>
                <input type="email" id="email" name="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="<?= $data['email'] ?>">
            </div>
            <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">إضافة</button>
        </form>
    </div>
</div>

<?php
// Include footer
require_once 'footer.php';
?>


**create_إدارة-الشحن.js**
javascript
// Form submission handler
document.getElementById('create-shipping-admin-form').addEventListener('submit', function(event) {
    event.preventDefault();
    var form = this;
    var formData = new FormData(form);
    var xhr = new XMLHttpRequest();
    xhr.open('POST', '../backend/إدارة-الشحن.php', true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);
            if (response.success) {
                window.location.href = 'list_إدارة-الشحن.php';
            } else {
                var errors = response.errors;
                // Display errors
                for (var key in errors) {
                    var errorElement = document.getElementById(key);
                    errorElement.innerHTML = errors[key];
                }
            }
        }
    };
    xhr.send(formData);
});


**backend/إدارة-شحن.php**

<?php
// Database connection
require_once 'db.php';

// Form data
$data = array(
    'name' => $_POST['name'],
    'description' => $_POST['description'],
    'address' => $_POST['address'],
    'phone' => $_POST['phone'],
    'email' => $_POST['email'],
);

// Validation
$errors = array();
if (empty($data['name'])) {
    $errors['name'] = 'اسم الإدارة مطلوب';
}
if (empty($data['description'])) {
    $errors['description'] = 'وصف الإدارة مطلوب';
}
if (empty($data['address'])) {
    $errors['address'] = 'عنوان الإدارة مطلوب';
}
if (empty($data['phone'])) {
    $errors['phone'] = 'رقم الهاتف مطلوب';
}
if (empty($data['email'])) {
    $errors['email'] = 'البريد الإلكتروني مطلوب';
}

// Insert data
if (empty($errors)) {
    $query = "INSERT INTO shipping_admins (name, description, address, phone, email) VALUES (:name, :description, :address, :phone, :email)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':name', $data['name']);
    $stmt->bindParam(':description', $data['description']);
    $stmt->bindParam(':address', $data['address']);
    $stmt->bindParam(':phone', $data['phone']);
    $stmt->bindParam(':email', $data['email']);
    $stmt->execute();
    $success = true;
} else {
    $success = false;
}

// Output response
$response = array('success' => $success, 'errors' => $errors);
echo json_encode($response);
?>