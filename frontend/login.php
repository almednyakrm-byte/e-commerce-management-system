<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-image: linear-gradient(to bottom, #0f0f0f, #2c2c2c);
            background-size: 100% 300px;
            background-position: 0% 100%;
            transition: background-position 1s linear;
        }
        .glassmorphic {
            background: linear-gradient(90deg, #ffffff44, #ffffff44);
            backdrop-filter: blur(10px);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .gradient {
            background: linear-gradient(to bottom, #0f0f0f, #2c2c2c);
            background-size: 100% 300px;
            background-position: 0% 100%;
            transition: background-position 1s linear;
        }
    </style>
</head>
<body class="h-screen flex justify-center items-center bg-gray-200">
    <div class="glassmorphic w-96 p-10 rounded-lg shadow-md">
        <h1 class="text-3xl font-bold text-emerald-600 mb-4">Login</h1>
        <form id="login-form">
            <div class="mb-4">
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <input type="text" id="username" name="username" class="block w-full px-4 py-2 mt-2 text-gray-700 placeholder-gray-300 border border-gray-300 rounded-md focus:outline-none focus:ring focus:border-blue-600" placeholder="Username" pattern="[A-Za-z\u0600-\u06FF0-9\s]+">
                <div id="username-error" class="text-red-500 hidden"></div>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" id="password" name="password" class="block w-full px-4 py-2 mt-2 text-gray-700 placeholder-gray-300 border border-gray-300 rounded-md focus:outline-none focus:ring focus:border-blue-600" placeholder="Password">
                <div id="password-error" class="text-red-500 hidden"></div>
            </div>
            <button type="submit" class="w-full px-4 py-2 mt-2 text-white bg-teal-500 hover:bg-teal-700 rounded-md focus:outline-none focus:ring focus:border-blue-600">Login</button>
            <p class="text-sm text-gray-500 mt-2">Don't have an account? <a href="register.php" class="text-emerald-600 hover:text-teal-500">Register</a></p>
        </form>
    </div>

    <script>
        const form = document.getElementById('login-form');
        const usernameInput = document.getElementById('username');
        const passwordInput = document.getElementById('password');
        const usernameError = document.getElementById('username-error');
        const passwordError = document.getElementById('password-error');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            usernameError.classList.remove('text-red-500');
            passwordError.classList.remove('text-red-500');
            usernameError.textContent = '';
            passwordError.textContent = '';

            const username = usernameInput.value.trim();
            const password = passwordInput.value.trim();

            if (!username || !password) {
                if (!username) {
                    usernameError.textContent = 'Username is required';
                    usernameError.classList.add('text-red-500');
                }
                if (!password) {
                    passwordError.textContent = 'Password is required';
                    passwordError.classList.add('text-red-500');
                }
                return;
            }

            try {
                const response = await fetch('../backend/auth.php?action=login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ username, password })
                });

                const data = await response.json();

                if (data.success) {
                    window.location.href = 'dashboard.php';
                } else {
                    if (data.error.username) {
                        usernameError.textContent = data.error.username;
                        usernameError.classList.add('text-red-500');
                    }
                    if (data.error.password) {
                        passwordError.textContent = data.error.password;
                        passwordError.classList.add('text-red-500');
                    }
                }
            } catch (error) {
                console.error(error);
                alert('Error logging in. Please try again later.');
            }
        });
    </script>
</body>
</html>


This code creates a premium-looking login page with a glassmorphic layout, gradients, and a form for username and password input. It uses the Tailwind CSS CDN for styling and includes a beautiful glassmorphic layout with gradients. The form includes standard HTML input pattern validators to support Arabic and Latin characters. The code also includes AJAX JavaScript using the Fetch API to submit credentials to the `../backend/auth.php?action=login` endpoint and handle response or error alerts dynamically. Finally, it includes a direct link to the `register.php` page.