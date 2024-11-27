<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Login/Register</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <!-- Login Form -->
            <form id="login-form" class="form">
                <h2>Customer LogIn</h2>
                <input type="text" id="login-email" placeholder="Email" required>
                <input type="password" id="login-password" placeholder="Password" required>
                <button type="button" id="login-button">Login</button>
                <p id="show-register">————— New User? Register Here —————</p>
                <a href="VeryMain.php" class="btn">Back</a>
            </form>

            <!-- Registration Form -->
            <form id="register-form" class="form hidden" enctype="multipart/form-data">
                <h2>Customer New Account</h2>
                <input type="text" id="register-name" name="name" placeholder="Name" required>
                <input type="number" id="register-contact" name="contact" placeholder="Contact Number" required>
                
                
                <select id="register-address" name="address" required>
                    <option value="" disabled selected>Select Barangay</option>
                    <option value="Abangay">Abangay</option>
                    <option value="Amamaros">Amamaros</option>
                    <option value="Bagacay">Bagacay</option>
                    <option value="Barasan">Barasan</option>
                    <option value="Batuan">Batuan</option>
                    <option value="Bongco">Bongco</option>
                    <option value="Cahaguichican">Cahaguichican</option>
                    <option value="Callan">Callan</option>
                    <option value="Cansilayan">Cansilayan</option>
                    <option value="Casalsagan">Casalsagan</option>
                    <option value="Cato-ogan">Cato-ogan</option>
                    <option value="Cau-ayan">Cau-ayan</option>
                    <option value="Culob">Culob</option>
                    <option value="Danao">Danao</option>
                    <option value="Dapitan">Dapitan</option>
                    <option value="Dawis">Dawis</option>
                    <option value="Dongsol">Dongsol</option>
                    <option value="Fernando Parcon Ward">Fernando Parcon Ward</option>
                    <option value="Guibuangan">Guibuangan</option>
                    <option value="Guinacas">Guinacas</option>
                    <option value="Igang">Igang</option>
                    <option value="Intaluan">Intaluan</option>
                    <option value="Iwa Ilaud">Iwa Ilaud</option>
                    <option value="Iwa Ilaya">Iwa Ilaya</option>
                    <option value="Jamabalud">Jamabalud</option>
                    <option value="Jebioc">Jebioc</option>
                    <option value="Lay-ahan">Lay-ahan</option>
                    <option value="Lopez Jaena Ward">Lopez Jaena Ward</option>
                    <option value="Lumbo">Lumbo</option>
                    <option value="Macatol">Macatol</option>
                    <option value="Malusgod">Malusgod</option>
                    <option value="Nabitasan">Nabitasan</option>
                    <option value="Naga">Naga</option>
                    <option value="Nanga">Nanga</option>
                    <option value="Naslo">Naslo</option>
                    <option value="Pajo">Pajo</option>
                    <option value="Palanguia">Palanguia</option>
                    <option value="Pitogo">Pitogo</option>
                    <option value="Primitivo Ledesma Ward">Primitivo Ledesma Ward</option>
                    <option value="Purog">Purog</option>
                    <option value="Rumbang">Rumbang</option>
                    <option value="San Jose Ward">San Jose Ward</option>
                    <option value="Sinuagan">Sinuagan</option>
                    <option value="Tuburan">Tuburan</option>
                    <option value="Tumcon Ilaud">Tumcon Ilaud</option>
                    <option value="Tumcon Ilaya">Tumcon Ilaya</option>
                    <option value="Ubang">Ubang</option>
                    <option value="Zarrague">Zarrague</option>
                </select>

                
                <input type="text" id="register-email" name="email" placeholder="Email" required>
                <input type="password" id="register-password" name="password" placeholder="Password" required>
                <input type="password" id="register-confirm-password" name="confirmPassword" placeholder="Confirm Password" required>
                <input type="file" id="register-image" name="images" accept="image/*" required>
                
                <button type="button" class="register-button" id="register-button">Register</button>
                <p id="registration-message" style="color: red;"></p>
                
                <button type="button" id="back-to-login-button">Back to Login</button>
            </form>


            <!-- Verification Code Form -->
            <form id="verification-form" class="form hidden">
                <h2>Enter Verification Code</h2>
                <input type="text" id="verification-code" placeholder="Enter the code sent to your email" required>
                <button type="button" id="verify-button">Verify Code</button>
                <p id="verification-message" style="color: red;"></p>
                <p id="back-register">——— Back to Register ———</p>
            </form>
        </div>
    </div>

    <script>
        let verificationCode = ''; 

        document.getElementById('show-register').onclick = function() {
            document.getElementById('login-form').classList.toggle('hidden');
            document.getElementById('register-form').classList.toggle('hidden');
        };

        document.getElementById('back-to-login-button').onclick = function() {
            document.getElementById('register-form').classList.add('hidden');
            document.getElementById('login-form').classList.remove('hidden');
        };

        document.getElementById('back-register').onclick = function() {
            document.getElementById('verification-form').classList.add('hidden');
            document.getElementById('register-form').classList.remove('hidden');
        };
        
        // Generate a random verification code (6 digits)
        function generateVerificationCode() {
            return Math.floor(100000 + Math.random() * 900000).toString(); 
        }

        // Registration form submission with validation
        document.getElementById('register-button').onclick = async function() {
            const name = document.getElementById('register-name').value;
            const contact = document.getElementById('register-contact').value;
            const address = document.getElementById('register-address').value;
            const email = document.getElementById('register-email').value;
            const password = document.getElementById('register-password').value;
            const confirmPassword = document.getElementById('register-confirm-password').value;
            const images = document.getElementById('register-image').files[0];
            
            // Validate if all fields are filled
            if (!name || !contact || !address || !email || !password || !confirmPassword || !images) {
                document.getElementById('registration-message').innerText = "All fields are required!";
                return;
            }

            // Check if passwords match
            if (password !== confirmPassword) {
                document.getElementById('registration-message').innerText = "Passwords do not match!";
                return;
            }

            // Check for valid email format (optional)
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                document.getElementById('registration-message').innerText = "Please enter a valid email address!";
                return;
            }

            // Check for valid contact number
            if (!/^\d{11}$/.test(contact)) {
                document.getElementById('registration-message').innerText = "Please enter a valid 11-digit contact number!";
                return;
            }

            // Generate verification code
            verificationCode = generateVerificationCode();

            // Send verification code to the user's email
            await sendVerificationEmail(email, verificationCode);

            // Show the verification form
            document.getElementById('register-form').classList.add('hidden');
            document.getElementById('verification-form').classList.remove('hidden');
        };

        // Send verification code to the email (via PHPMailer)
        async function sendVerificationEmail(email, code) {
            const response = await fetch('send_verification_email.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ email: email, code: code })
            });

            const data = await response.json();
            if (data.success) {
                console.log("Verification email sent!");
            } else {
                console.error("Failed to send verification email.");
                alert("Failed to send verification code.");
            }
        }

        // Verify the code entered by the user
        document.getElementById('verify-button').onclick = function() {
            const enteredCode = document.getElementById('verification-code').value;

            if (enteredCode === verificationCode) {
                
                completeRegistration();
            } else {
                document.getElementById('verification-message').innerText = "Invalid verification code!";
            }
        };

        // Complete the registration process
        async function completeRegistration() {
            const name = document.getElementById('register-name').value;
            const contact = document.getElementById('register-contact').value;
            const address = document.getElementById('register-address').value;
            const email = document.getElementById('register-email').value;
            const password = document.getElementById('register-password').value;
            const images = document.getElementById('register-image').files[0];

            // Prepare form data to send to the server
            const formData = new FormData();
            formData.append('name', name);
            formData.append('contact', contact);
            formData.append('address', address);
            formData.append('email', email);
            formData.append('password', password);
            formData.append('images', images);

            // Send registration data to the server
            const response = await fetch('CusReg.php', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();
            if (data.success) {
                alert("Registration successful!");
                window.location.href = 'CustomerMain.php'; 
            } else {
                alert("Registration failed: " + data.message);
            }
        }

        // Login form submission
        document.getElementById('login-button').onclick = function() {
            const email = document.getElementById('login-email').value;
            const password = document.getElementById('login-password').value;

            const formData = new URLSearchParams();
            formData.append('email', email);
            formData.append('password', password);

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
