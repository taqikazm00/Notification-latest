<?php
include('../conn/conn.php');
session_start(); // Start the session at the beginning of the script

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Determine the current URL for redirection
    $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;
    $protocol = $isHttps ? 'https://' : 'http://';
    $hostname = $_SERVER['HTTP_HOST'];
    $currentUrl = $protocol . $hostname;

    // Sanitize input data
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    // Prepare and execute query to fetch stored password
    $stmt = $conn->prepare("SELECT `id`, `password` FROM `tbl_user` WHERE `email` = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $userId = $row['id'];
        $storedPassword = $row['password'];

        // Verify the password
        if (password_verify($password, $storedPassword)) {
            // Set session variables
            $_SESSION['id'] = $userId;
            $_SESSION['email'] = $email;

            echo "
            <script>
                // alert('Login Successfully!');
                window.location.href = '../home.php';
            </script>
            ";
        } else {
            echo "
            <script>
                alert('Login Failed, Incorrect Password!');
                window.location.href = '../index.php';
            </script>
            ";
        }
    } else {
        echo "
            <script>
                alert('Login Failed, User Not Found!');
                window.location.href = '../index.php';
            </script>
        ";
    }
}
?>