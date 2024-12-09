<?php
include('../conn/conn.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

$mail = new PHPMailer(true);

if (isset($_POST['register'])) {
    try {
        // Determine the current URL for redirection
        $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;
        $protocol = $isHttps ? 'https://' : 'http://';
        $hostname = $_SERVER['HTTP_HOST'];
        $currentUrl = $protocol . $hostname;

        // Sanitize and hash the input data
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
        $contactNumber = filter_input(INPUT_POST, 'contact_number', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Begin transaction
        $conn->beginTransaction();

        // Check if the email already exists
        $stmt = $conn->prepare("SELECT `name` FROM `tbl_user` WHERE `email` = :email");
        $stmt->execute([
            'email' => $email
        ]);

        $nameExist = $stmt->fetch(PDO::FETCH_ASSOC);

        if (empty($nameExist)) {
            // Insert new user if email does not exist
            $insertStmt = $conn->prepare("INSERT INTO `tbl_user` (`name`, `contact_number`, `email`, `password`) VALUES (:name, :contact_number, :email, :password)");
            $insertStmt->bindParam(':name', $name, PDO::PARAM_STR);
            $insertStmt->bindParam(':contact_number', $contactNumber, PDO::PARAM_STR);
            $insertStmt->bindParam(':email', $email, PDO::PARAM_STR);
            $insertStmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
            $insertStmt->execute();

            // Commit transaction
            $conn->commit();
            // Redirect to login page
            header('Location: ../home.php');
            exit();
        } else {
            // Redirect to registration form with error if email exists
            header('Location: ../index.php?error=user_exists');
            exit();
        }
    } catch (PDOException $e) {
        // Rollback transaction on error
        $conn->rollBack();
        echo "Error: " . $e->getMessage();
    }
}


?>