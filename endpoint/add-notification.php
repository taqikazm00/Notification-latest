<?php
include('../conn/conn.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $type = $_POST['type'];

    $stmt = $conn->prepare("INSERT INTO `tbl_notifiction` (`user_id`, `title`, `description`, `date`, `type`) VALUES (:user_id, :title, :description, :date, :type)");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':type', $type);

    if ($stmt->execute()) {
        echo "
        <script>
            alert('Notification Added Successfully!');
            window.location.href = '../home.php';
        </script>
        ";
    } else {
        echo "
        <script>
            alert('Failed to Add Notification!');
            window.location.href = '../home.php';
        </script>
        ";
    }
}
?>