<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

$hostname = "localhost";
$username = "root";
$password = "";
$database = "hostel";

$mysqli = new mysqli($hostname, $username, $password, $database);
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$aid = $_SESSION['id'];

// Query to fetch user details
$ret_user = "SELECT firstName, lastName FROM userregistration WHERE id=?";
$stmt_user = $mysqli->prepare($ret_user);
if ($stmt_user) {
    $stmt_user->bind_param('i', $aid);
    $stmt_user->execute();
    $res_user = $stmt_user->get_result();
    $user = $res_user->fetch_object();
    $firstName = $user->firstName;
    $lastName = $user->lastName;
    $userName = $firstName . ' ' . $lastName;
    $stmt_user->close();
} else {
    die("Error preparing statement for user details: " . $mysqli->error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $eventName = $_POST['eventName'];
    $action = $_POST['action'];

    if ($action == 'enroll') {
        $query = "INSERT INTO enrollments (username, events) VALUES (?, ?)";
    } else if ($action == 'unenroll') {
        $query = "DELETE FROM enrollments WHERE username = ? AND events = ?";
    }

    $stmt = $mysqli->prepare($query);
    if ($stmt) {
        $stmt->bind_param('ss', $userName, $eventName);
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => ucfirst($action) . ' successful']);
        } else {
            echo json_encode(['status' => 'error', 'message' => ucfirst($action) . ' failed: ' . $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error preparing statement: ' . $mysqli->error]);
    }
    exit;
}
?>
<!doctype html>
<html lang="en" class="no-js">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="theme-color" content="#3e454c">
    <title>Event Details</title>
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .enroll-btn.enrolled {
            background-color: red;
            color: white;
        }
    </style>
</head>
<body>
    <?php include('includes/header.php'); ?>
    <div class="ts-main-content">
        <?php include('includes/sidebar.php'); ?>
        <div class="content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="page-title" style="margin-top:4%">Event Details</h2>
                        <div class="panel panel-default">
                            <div class="panel-heading">All Events</div>
                            <div class="panel-body">
                                <table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Sno.</th>
                                            <th>Event Code</th>
                                            <th>Event Name (Short)</th>
                                            <th>Event Name (Full)</th>
                                            <th>Posting Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php  
                                        $ret = "SELECT * FROM courses";
                                        $stmt = $mysqli->prepare($ret);
                                        $stmt->execute();
                                        $res = $stmt->get_result();
                                        $cnt = 1;
                                        while ($row = $res->fetch_object()) {
                                            ?>
                                            <tr>
                                                <td><?php echo $cnt; ?></td>
                                                <td><?php echo $row->course_code; ?></td>
                                                <td><?php echo $row->course_sn; ?></td>
                                                <td><?php echo $row->course_fn; ?></td>
                                                <td><?php echo $row->posting_date; ?></td>
                                                <td>
                                                    <a class="btn btn-primary enroll-btn" data-course-id="<?php echo $row->course_id; ?>" data-event-name="<?php echo $row->course_sn; ?>" onclick="toggleEnrollment(this)">Enroll</a>
                                                </td>
                                            </tr>
                                            <?php
                                            $cnt = $cnt + 1;
                                        } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Scripts -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/dataTables.bootstrap.min.js"></script>
    <script src="js/main.js"></script>
    <script>
    function toggleEnrollment(button) {
        var action = button.classList.contains('enrolled') ? 'unenroll' : 'enroll';
        var eventName = button.getAttribute('data-event-name');

        if (confirm('Are you sure you want to ' + action + ' in this event?')) {
            $.ajax({
                url: 'http://localhost/test/hostel/event-details.php', 
                type: 'POST',
                data: {
                    eventName: eventName,
                    action: action
                },
                success: function(response) {
                    var result = JSON.parse(response);
                    if (result.status === 'success') {
                        if (action === 'enroll') {
                            button.classList.remove('btn-primary');
                            button.classList.add('enrolled');
                            button.innerText = 'Unenroll';
                        } else {
                            button.classList.remove('enrolled');
                            button.classList.add('btn-primary');
                            button.innerText = 'Enroll';
                        }
                        alert(result.message);
                    } else {
                        alert(result.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                }
            });
        }
    }
    </script>
</body>
</html>
