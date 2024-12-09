<?php
include('../conn/conn.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $type = $_POST['type'];

    $stmt = $conn->prepare("UPDATE `tbl_notifiction` SET `title` = :title, `description` = :description, `date` = :date, `type` = :type WHERE `id` = :id");
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':type', $type);

    if ($stmt->execute()) {
        echo "
        <script>
            alert('Notification Updated Successfully!');
            window.location.href = '../home.php';
        </script>
        ";
    } else {
        echo "
        <script>
            alert('Failed to Update Notification!');
            window.location.href = '../home.php';
        </script>
        ";
    }
}
?>