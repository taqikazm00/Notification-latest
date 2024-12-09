<?php include('./conn/conn.php'); ?>

<?php
session_start();

// Check if the user is already logged in
if (isset($_SESSION['id'])) {
    // Redirect to the home page if logged in
    header('Location: home.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reminder Task System</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap');

        * {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            background-image: url("https://images.unsplash.com/photo-1485470733090-0aae1788d5af?q=80&w=1517&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D");
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            height: 100vh;
        }

        .login-form,
        .registration-form {
            backdrop-filter: blur(100px);
            color: rgb(255, 255, 255);
            padding: 40px;
            width: 500px;
            border: 2px solid;
            border-radius: 10px;
        }

        .switch-form-link {
            text-decoration: underline;
            cursor: pointer;
            color: rgb(100, 100, 200);
        }

        @media (max-width: 600px) {

            .login-form,
            .registration-form {
                width: auto;
                margin: 20px 0px;
            }
        }

        .password-container {
            position: relative;
        }

        .password-container input {
            padding-right: 30px;
            /* Add space for the eye icon */
        }

        .password-container .eye-icon {
            position: absolute;
            top: 70%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #000;
        }
    </style>
</head>

<body>

    <div class="main">
        <!-- Display error message -->
        <?php if (isset($_GET['error']) && $_GET['error'] == 'user_exists') {
            echo '<div class="alert alert-danger" role="alert">User Already Exists</div>';
        } ?>

        <!-- Login Area -->
        <div class="login-container">
            <div class="login-form" id="loginForm">
                <h2 class="text-center">Welcome!</h2>
                <p class="text-center">Fill your login details.</p>
                <form action="./endpoint/login.php" method="POST">
                    <div class="form-group">
                        <label for="loginEmail">Email:</label>
                        <input type="email" class="form-control" id="loginEmail" name="email" required
                            pattern="[a-zA-Z0-9._%+-]+@(gmail\.com|outlook\.com|email\.com)$"
                            title="Please enter a valid email address with @gmail.com, @outlook.com, or @email.com">
                        <div class="invalid-feedback">
                            Please enter a valid email address with @gmail.com, @outlook.com, or @email.com.
                        </div>
                    </div>
                    <div class="form-group password-container">
                        <label for="loginPassword">Password:</label>
                        <input type="password" class="form-control password" id="loginPassword" name="password" required
                            placeholder="Enter Password">
                        <span class="eye-icon" onclick="togglePassword('loginPassword')">
                            <i id="eye-icon-login" class="fa fa-eye"></i>
                        </span>
                    </div>
                    <p>No Account? Register <span class="switch-form-link" onclick="showRegistrationForm()">Here.</span>
                    </p>
                    <button type="submit" class="btn btn-secondary login-btn form-control">Login</button>
                </form>
            </div>
        </div>

        <!-- Registration Area -->
        <div class="registration-form" id="registrationForm">
            <h2 class="text-center">Registration Form</h2>
            <p class="text-center">Fill in your personal details.</p>
            <form action="./endpoint/add-user.php" method="POST" id="forms">
                <div class="form-group registration row">
                    <div class="col-12">
                        <label for="firstname">Name:</label>
                        <input type="text" class="form-control" id="firstname" name="name" required
                            placeholder="Enter Name" pattern="[A-Za-z\s]+"
                            title="Please enter a valid name (letters and spaces only)">
                    </div>
                </div>
                <div class="form-group registration row">
                    <div class="col-12">
                        <label for="contactNumber">Contact Number:</label>
                        <input type="text" class="form-control" id="contactNumber" name="contact_number" required
                            maxlength="13" placeholder="+923000000000" pattern="^\+92[0-9]{10}$"
                            title="Please enter a valid number (format: +923xxxxxxxxx)">
                    </div>
                    <div class="col-12 mt-2">
                        <label for="registrationEmail">Email:</label>
                        <input type="email" class="form-control" id="registrationEmail" name="email" required
                            placeholder="Enter Email" pattern=".+@.+\.com"
                            title="Please enter a valid email address ending with .com">
                        <div class="invalid-feedback">
                            Please enter a valid email address ending with .com
                        </div>
                    </div>
                </div>
                <div class="form-group registration password-container">
                    <label for="registerPassword">Password:</label>
                    <input type="password" class="form-control password" id="registerPassword" name="password" required
                        placeholder="Enter Password">
                    <span class="eye-icon" onclick="togglePassword('registerPassword')">
                        <i id="eye-icon-register" class="fa fa-eye"></i>
                    </span>
                </div>
                <p>Already have an account? Login <span class="switch-form-link" onclick="showLoginForm()">Here.</span>
                </p>
                <button type="submit" class="btn btn-dark login-register form-control" name="register">Register</button>
            </form>
        </div>
    </div>

    <script>
        // Form validation for registration
        document.getElementById('forms').addEventListener('submit', function (event) {
            const emailField = document.getElementById('registrationEmail');
            const emailPattern = /.+@.+\.com$/;

            if (!emailPattern.test(emailField.value)) {
                event.preventDefault(); // Prevent form submission
                emailField.classList.add('is-invalid'); // Show invalid feedback
            } else {
                emailField.classList.remove('is-invalid'); // Remove invalid class if valid
            }
        });




        // Remove validation class on input change
        const emailInputs = document.querySelectorAll('input[type="email"]');
        emailInputs.forEach(input => {
            input.addEventListener('input', function () {
                this.classList.remove('is-invalid');
            });
        });

        const loginForm = document.getElementById('loginForm');
        const registrationForm = document.getElementById('registrationForm');

        registrationForm.style.display = "none";

        function showRegistrationForm() {
            registrationForm.style.display = "";
            loginForm.style.display = "none";
        }

        function showLoginForm() {
            registrationForm.style.display = "none";
            loginForm.style.display = "";
        }

        function togglePassword(passwordFieldId) {
            const passwordField = document.getElementById(passwordFieldId);
            const eyeIcon = document.getElementById(passwordFieldId === 'loginPassword' ? 'eye-icon-login' : 'eye-icon-register');

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
</body>

</html>