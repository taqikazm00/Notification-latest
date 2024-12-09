<?php
include('../conn/conn.php');

if (isset($_GET['notification'])) {
    $id = $_GET['notification'];

    $stmt = $conn->prepare("DELETE FROM `tbl_notifiction` WHERE `id` = :id");
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        echo "
        <script>
            alert('Notification Deleted Successfully!');
            window.location.href = '../home.php';
        </script>
        ";
    } else {
        echo "
        <script>
            alert('Failed to Delete Notification!');
            window.location.href = '../home.php';
        </script>
        ";
    }
}
?>
