<?php
include('../conn/conn.php');

session_start();

if (!isset($_SESSION['id'])) {
    // Redirect to the index page
    header('Location: /index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id'];
    $name = $_POST['name'];
    $contactNumber = $_POST['contact_number'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        // Prepare the base update query
        $query = "UPDATE tbl_user SET name = :name, contact_number = :contact_number, email = :email";

        // Only include password in the update if it's provided
        if (!empty($password)) {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $query .= ", password = :password";
        }

        $query .= " WHERE id = :userId";

        $stmt = $conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':contact_number', $contactNumber);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':userId', $userId);

        // Bind the hashed password if it exists
        if (!empty($password)) {
            $stmt->bindParam(':password', $hashedPassword);
        }

        $stmt->execute();

        // Redirect back to the profile page with a success message
        echo "
        <script>
            alert('User Update Successfully.');
            window.location.href = '../profile.php?success=1';
        </script>
        ";

        exit();
    } catch (PDOException $e) {
        // Log error and redirect with error message
        error_log("Database error: " . $e->getMessage());
        header('Location: ../profile.php?error=update_failed');
        exit();
    }
}


?>