<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login/Register</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <form id="login-form" class="form">
                <h2>Login to your Account</h2>
                <input type="text" id="login-email" placeholder="Email" required>
                <input type="password" id="login-password" placeholder="Password" required>
                <button type="button" id="login-button">Login</button>
                <p id="show-register">————— New User? Register Here —————</p>
            </form>

            <form id="register-form" class="form hidden" enctype="multipart/form-data">
                <h2>Create your Account</h2>
                <input type="text" id="register-name" name="name" placeholder="Name" required>
                <input type="number" id="register-contact" name="contact" placeholder="Contact Number" required>
                <input type="text" id="register-address" name="address" placeholder="Address/City" required>
                <input type="text" id="register-email" name="email" placeholder="Email" required>
                <input type="password" id="register-password" name="password" placeholder="Password" required>
                <input type="password" id="register-confirm-password" name="confirmPassword" placeholder="Confirm Password" required>
                <input type="file" id="register-image" name="images" accept="image/*" required>
                <button type="button" class="register-button" id="register-button">Register</button>
                <p id="registration-message" style="color: red;"></p>
                <button type="button" id="back-to-login-button">Back to Login</button> 
            </form>
        </div>
    </div>

    <script>
        
        document.getElementById('show-register').onclick = function() {
            document.getElementById('login-form').classList.toggle('hidden');
            document.getElementById('register-form').classList.toggle('hidden');
        };

        document.getElementById('back-to-login-button').onclick = function() {
            document.getElementById('register-form').classList.add('hidden');
            document.getElementById('login-form').classList.remove('hidden');
        };

        // Registration form submission with validation
        document.getElementById('register-button').onclick = function() {
            const name = document.getElementById('register-name').value;
            const contact = document.getElementById('register-contact').value;
            const address = document.getElementById('register-address').value;
            const email = document.getElementById('register-email').value;
            const password = document.getElementById('register-password').value;
            const confirmPassword = document.getElementById('register-confirm-password').value;
            const images = document.getElementById('register-image').files[0];

           
            if (password !== confirmPassword) {
                document.getElementById('registration-message').innerText = "Passwords do not match!";
                return;
            }

            const formData = new FormData();
            formData.append('name', name);
            formData.append('contact', contact);
            formData.append('address', address);
            formData.append('email', email);
            formData.append('password', password);
            formData.append('images', images);

            
            fetch('CusReg.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message); 
                    window.location.href = 'CustomerMain.php'; 
                } else {
                    document.getElementById('registration-message').innerText = data.message; 
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('registration-message').innerText = 'An error occurred during registration.';
            });
        };

        
        document.getElementById('login-button').onclick = function() {
            const email = document.getElementById('login-email').value;
            const password = document.getElementById('login-password').value;

            const formData = new URLSearchParams();
            formData.append('email', email);
            formData.append('password', password);

            // Submit login form using fetch API
            fetch('CusLogin.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message); 
                    window.location.href = 'CustomerDashboard.php'; 
                } else {
                    alert(data.message); 
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred during login.');
            });
        };
    </script>
</body>
</html>
