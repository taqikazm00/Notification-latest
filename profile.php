<?php include('./conn/conn.php'); ?>

<?php
session_start();
if (!isset($_SESSION['id'])) {
    // Redirect to the index page
    header('Location: /index.php');
    exit();
}

if (isset($_SESSION['id'])) {
    $userId = $_SESSION['id'];
    $stmt2 = $conn->prepare("SELECT * FROM tbl_user WHERE id = :userId");
    $stmt2->bindParam(':userId', $userId);
    $stmt2->execute();
    $result = $stmt2->fetch(PDO::FETCH_ASSOC);


}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Reminder Task</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" />

    <style>
        .btn-primary:not(:disabled):not(.disabled).active,
        .btn-primary:not(:disabled):not(.disabled):active,
        .show>.btn-primary.dropdown-toggle {
            color: #fff;
            background-color: #0062cc;
            border-color: #ffffff;
            border-width: 2px;
            font-weight: 600;
        }


        .wrapper {
            text-transform: uppercase;
            background: #007bff;
            color: #fff;
            cursor: help;
            font-family: "Gill Sans", Impact, sans-serif;
            font-size: 12px;
            position: relative;
            text-align: center;
            width: 20px;
            border-radius: 10px;
            -webkit-transform: translateZ(0);
            -webkit-font-smoothing: antialiased;
            position: absolute;
            top: 6px;
            right: 8px;
        }

        .wrapper .tooltip {
            background: #007bff;
            bottom: 100%;
            color: #fff;
            padding: 5px !important;
            display: block;
            left: -40px;
            margin-bottom: 15px;
            opacity: 0;
            padding: 20px;
            pointer-events: none;
            position: absolute;
            width: 106px;
            -webkit-transform: translateY(10px);
            -moz-transform: translateY(10px);
            -ms-transform: translateY(10px);
            -o-transform: translateY(10px);
            transform: translateY(10px);
            -webkit-transition: all .25s ease-out;
            -moz-transition: all .25s ease-out;
            -ms-transition: all .25s ease-out;
            -o-transition: all .25s ease-out;
            transition: all .25s ease-out;
            -webkit-box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.28);
            -moz-box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.28);
            -ms-box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.28);
            -o-box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.28);
            box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.28);
        }

        /* This bridges the gap so you can mouse into the tooltip without it disappearing */
        .wrapper .tooltip:before {
            bottom: -20px;
            content: " ";
            display: block;
            height: 20px;
            left: 0;
            position: absolute;
            width: 100%;
        }

        /* CSS Triangles - see Trevor's post */
        .wrapper .tooltip:after {
            border-left: solid transparent 10px;
            border-right: solid transparent 10px;
            border-top: solid #007bff 10px;
            bottom: -10px;
            content: " ";
            height: 0;
            left: 50%;
            margin-left: -13px;
            position: absolute;
            width: 0;
        }

        .wrapper:hover .tooltip {
            opacity: 1;
            pointer-events: auto;
            -webkit-transform: translateY(0px);
            -moz-transform: translateY(0px);
            -ms-transform: translateY(0px);
            -o-transform: translateY(0px);
            transform: translateY(0px);
        }

        .lte8 .wrapper .tooltip {
            display: none;
        }

        .lte8 .wrapper:hover .tooltip {
            display: block;
        }

        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap");

        * {
            margin: 0;
            padding: 0;
            font-family: "Poppins", sans-serif;
        }

        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            /* background-image: url("https://images.unsplash.com/photo-1485470733090-0aae1788d5af?q=80&w=1517&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"); */
            background-color: #979292;
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            height: 100vh;
        }

        .content {
            backdrop-filter: blur(100px);
            color: rgb(255, 255, 255);
            padding: 25px;
            border: 1px solid;
            border-radius: 10px;
        }

        .sidebar {
            color: rgb(255, 255, 255);
            padding: 25px;
            border: 1px solid;
            border-radius: 10px;
        }

        .table {
            color: rgb(255, 255, 255) !important;
        }

        td button {
            font-size: 10px;
            padding: 4px 15px;
        }

        .table-hover tbody tr:hover {
            color: #ffffff !important;
        }

        .update_form {
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

            .update_form {
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

        @media (max-width: 600px) {

            tr {
                font-size: 12px;
            }

            a.navbar-brand.ml-5 {
                margin-left: 0px !important;
            }

            div#navbarNav {
                display: contents;
            }
        }

        li.nav-item {
            margin: 10px;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-secondary" style="width: 100%;">

        <a class="navbar-brand ml-5" href="home.php">Task System</a>
        <div class="navbar-collapse" id="navbarNav">

            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link"><span>Welcome: </span><?php echo $result['name']; ?></a>
                </li>
                <li class="nav-item active" style="background: #43798f;">
                    <a class="nav-link" href="home.php">Add Task</a>
                </li>
                <li class="nav-item active" style="background: #43798f;">
                    <a class="nav-link" href="profile.php">Profile</a>
                </li>
                <li class="nav-item active" style="background: #43798f;">
                    <a class="nav-link" href="./endpoint/logout.php"> Log Out</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="main">
        <!-- Display error message -->
        <?php if (isset($_GET['error']) && $_GET['error'] == 'user_exists') {
            echo '<div class="alert alert-danger" role="alert">User Already Exists</div>';
        } ?>

        <div class="update_form">
            <h2 class="text-center">User Profile</h2>
            <p class="text-center">Update your personal details.</p>
            <form action="./endpoint/update-user.php" method="POST" id="forms">
                <input type="hidden" value="<?php echo $result['id']; ?>" name="user_id" id="user_id" />
                <div class="form-group registration row">
                    <div class="col-12">
                        <label for="name">Name:</label>
                        <input type="text" class="form-control" value="<?php echo $result['name']; ?>" id="name"
                            name="name" placeholder="Enter Name" pattern="[A-Za-z\s]+"
                            title="Please enter a valid name (letters and spaces only)">
                    </div>
                </div>
                <div class="form-group registration row">
                    <div class="col-12">
                        <label for="contactNumber">Contact Number:</label>
                        <input type="text" class="form-control" id="contact_number"
                            value="<?php echo $result['contact_number']; ?>" name="contact_number" maxlength="13"
                            placeholder="+923000000000" pattern="^\+92[0-9]{10}$"
                            title="Please enter a valid number (format: +923xxxxxxxxx)">
                    </div>
                    <div class="col-12 mt-2">
                        <label for="updateEmail">Email:</label>
                        <input type="email" class="form-control" value="<?php echo $result['email']; ?>"
                            id="updateEmail" name="email" placeholder="Enter Email" pattern=".+@.+\.com"
                            title="Please enter a valid email address ending with .com">
                        <div class="invalid-feedback">
                            Please enter a valid email address ending with .com
                        </div>
                    </div>
                </div>
                <div class="form-group registration password-container">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control password" id="password" name="password"
                        placeholder="Enter Password">
                    <span class="eye-icon" onclick="togglePassword('password')">
                        <i id="eye-icon-update" class="fa fa-eye"></i>
                    </span>
                </div>

                <button type="submit" class="btn btn-dark form-control" name="update_info">Update</button>
            </form>
        </div>
    </div>
    <script>
        // Form validation for update
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


        function togglePassword(passwordFieldId) {
            const passwordField = document.getElementById(passwordFieldId);
            const eyeIcon = document.getElementById(passwordFieldId === 'password' ? 'eye-icon-login' : 'eye-icon-update');

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
    <!-- Bootstrap Js -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
</body>

</html>