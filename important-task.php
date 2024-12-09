<?php include('./conn/conn.php');
session_start();
if (!isset($_SESSION['id'])) {
    // Redirect to the index page
    header('Location: index.php');
    exit(); // Ensure no further code is executed after the redirect
}


if (isset($_SESSION['id']) && isset($_POST['color'])) {
    $userId = $_SESSION['id'];
    $color = $_POST['color']; // The new color from the user input

    $stmt = $conn->prepare("UPDATE tbl_user SET color = :color WHERE id = :userId");
    $stmt->bindParam(':color', $color);
    $stmt->bindParam(':userId', $userId);

    if ($stmt->execute()) {

    } else {
        echo "Failed to update background color.";
    }
}


if (isset($_SESSION['id'])) {
    $userId = $_SESSION['id'];
    $stmt2 = $conn->prepare("SELECT color, name FROM tbl_user WHERE id = :userId");
    $stmt2->bindParam(':userId', $userId);
    $stmt2->execute();
    $result = $stmt2->fetch(PDO::FETCH_ASSOC);
    $backgroundColor = $result['color'];
    $user_name = $result['name'];

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

        /* IE can just show/hide with no transition */
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
            <?php if ($backgroundColor): ?>
                background-color:
                    <?php echo htmlspecialchars($backgroundColor); ?>
                ;
                background-image: none !important;
            <?php else: ?>
                background-image: url("https://images.unsplash.com/photo-1485470733090-0aae1788d5af?q=80&w=1517&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D");
            <?php endif; ?>
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


    <!-- Add Modal -->
    <div class="modal fade mt-5" id="addUserModal" tabindex="-1" aria-labelledby="addUser" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUser">Add Task</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="./endpoint/add-notification.php" method="POST">
                        <input type="hidden" value="<?php echo $_SESSION['id']; ?>" name="user_id" />
                        <div class="form-group">
                            <label for="addTitle">Title:</label>
                            <input type="text" class="form-control" id="addTitle" name="title" required />
                        </div>
                        <div class="form-group">
                            <label for="addDescription">Description:</label>
                            <textarea class="form-control" id="addDescription" name="description" required></textarea>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="addDate">Date:</label>
                                <input type="datetime-local" class="form-control" id="addDate" name="date" required />
                            </div>
                            <div class="form-group col-md-6">
                                <label for="addDate">Task Type:</label>
                                <select name="type" class="form-control">
                                    <option value="today">Today Tasks</option>
                                    <option value="planned">Planned Tasks</option>
                                    <option value="important">Important Tasks</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-dark login-register form-control">Add</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Modal -->
    <div class="modal fade mt-5" id="updateUserModal" tabindex="-1" aria-labelledby="updateUser" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateUserModal">Update Task</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="./endpoint/update-notification.php" method="POST">
                        <div class="form-group">
                            <input type="hidden" name="id" id="updateNotificationID" />
                            <label for="updateTitle">Title:</label>
                            <input type="text" class="form-control" id="updateTitle" name="title" required />
                        </div>
                        <div class="form-group">
                            <label for="updateDescription">Description:</label>
                            <textarea class="form-control" id="updateDescription" name="description"
                                required></textarea>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="updateDate">Date:</label>
                                <input type="datetime-local" class="form-control" id="updateDate" name="date"
                                    required />
                            </div>
                            <div class="form-group col-md-6">
                                <label for="addDate">Task Type:</label>
                                <select name="type" class="form-control">
                                    <option value="today">Today Tasks</option>
                                    <option value="planned">Planned Tasks</option>
                                    <option value="important">Important Tasks</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-dark login-register form-control">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="container mt-5">
        <div class=" d-flex justify-content-between mb-5">
            <!-- Add Button -->
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addUserModal">Add
                Task</button>
            <form class="d-flex align-items-center" method="post" action="">
                <label for="color">Change Color:</label>
                <input type="color" id="color" name="color" class="ms-1 me-1">
                <input type="submit" value="Set">
            </form>

        </div>
        <div class="row ">
            <div class="sidebar col-lg-2 col-12">
                <h4>Tasks</h4>
                <?php
                $current_page = basename($_SERVER['PHP_SELF']);
                $active_class = ($current_page == 'home.php') ? 'active' : '';

                echo '<a href="home.php"><button style="font-size:13px;width:100%;" type="button" class="btn btn-primary mt-4 ' . $active_class . '">Today Tasks</button></a>';
                ?>

                <?php
                $current_page = basename($_SERVER['PHP_SELF']);
                $active_class = ($current_page == 'planned.php') ? 'active' : '';

                echo '<a href="planned.php"><button style="font-size:13px;width:100%;" type="button" class="btn btn-primary mt-4 ' . $active_class . '">Planned Tasks</button></a>';
                ?>

                <?php
                $current_page = basename($_SERVER['PHP_SELF']);
                $active_class = ($current_page == 'important-task.php') ? 'active' : '';

                echo '<a href="important-task.php"><button style="font-size:13px;width:100%;" type="button" class="btn btn-primary mt-4 ' . $active_class . '">Important Tasks</button></a>';
                ?>
            </div>

            <div class="content col-lg-10 col-12">
                <h4>List of Tasks</h4>
                <hr />
                <div class="table-responsive">
                    <table class="table table-hover table-collapse">
                        <thead>
                            <tr>
                                <th scope="col">Task ID</th>
                                <th scope="col">Title</th>
                                <th scope="col">Description</th>
                                <th scope="col">Date</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $user_id = $_SESSION['id'];
                            $stmt = $conn->prepare("SELECT * FROM `tbl_notifiction` WHERE user_id = :user_id AND `type` = 'important'");
                            $stmt->bindParam(':user_id', $user_id);
                            $stmt->execute();
                            $result = $stmt->fetchAll();
                            if (isset($result)) {
                                foreach ($result as $key => $row) {
                                    $notificationID = $row['id'];
                                    $title = $row['title'];
                                    $description = $row['description'];
                                    $date = $row['date'];
                                    $status = $row['status'];
                                    $message = 'Delivered';
                                    $badge = 'success';
                                    if ($status == 0) {
                                        $message =
                                            'Pending';
                                        $badge = 'warning';
                                    } ?>
                                    <tr>
                                        <td id="notificationID-<?= $notificationID ?>"><?php echo $notificationID ?></td>
                                        <td id="title-<?= $notificationID ?>"><?php echo $title ?></td>
                                        <td style="position:relative;">
                                            <textarea readonly style="display: block;width:100%;" name="description-content"
                                                id="description-<?= $notificationID ?>"><?php echo $description ?></textarea>

                                            <div>
                                                <div class="wrapper">
                                                    !
                                                    <div class="tooltip"><button style="display:block;margin: auto; id="
                                                            id="solvebtn-<?= $notificationID ?>" class="solvebtn"
                                                            title="store">Solve it</button></div>
                                                </div>
                                            </div>

                                        </td>
                                        <td id="date-<?= $notificationID ?>"><?php echo $date ?></td>
                                        <td>
                                            <span class="badge badge-<?php echo $badge ?>"
                                                style="padding: 4px;"><?php echo $message ?></span>
                                        </td>
                                        <td>
                                            <button id="editBtn" onclick="update_notification(<?php echo $notificationID ?>)"
                                                title="Edit">&#9998;</button>
                                            <button id="deleteBtn"
                                                onclick="delete_notification(<?php echo $notificationID ?>)">&#128465;</button>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll(".solvebtn").forEach((button) => {
            button.addEventListener("click", function () {
                // Get the ID from the button's ID attribute (after 'solvebtn-')
                const id = this.id.split("-")[1];

                // Find the textarea by ID
                const descriptionTextArea = document.getElementById("description-" + id);

                if (descriptionTextArea) {
                    const descriptionValue = descriptionTextArea.value;

                    // Store the value in localStorage
                    localStorage.setItem("storedDescription", descriptionValue);

                    // Redirect to Page 2
                    window.location.href = "../task-helper";
                } else {
                    console.error("Textarea element not found!");
                }
            });
        });
    </script>

    <script>
        // Update notification
        function update_notification(id) {
            $("#updateUserModal").modal("show");

            let updateNotificationID = $("#notificationID-" + id).text();
            let title = $("#title-" + id).text();
            let updateDescription = $("#description-" + id).text();
            let updateDate = $("#date-" + id).text();

            $("#updateNotificationID").val(updateNotificationID);
            $("#updateTitle").val(title);
            $("#updateDescription").val(updateDescription);
            $("#updateDate").val(updateDate);
        }

        // Delete notification
        function delete_notification(id) {
            if (confirm("Do you want to delete this notification?")) {
                window.location = "./endpoint/delete-notification.php?notification=" + id;
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const now = new Date();
            const formattedDate = now.toISOString().slice(0, 16);

            // Get both input elements
            const addDateInput = document.getElementById('addDate');
            const updateDateInput = document.getElementById('updateDate');

            // Set the min attribute
            addDateInput.setAttribute('min', formattedDate);
            updateDateInput.setAttribute('min', formattedDate);
        });
    </script>

    <!-- Bootstrap Js -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
</body>

</html>