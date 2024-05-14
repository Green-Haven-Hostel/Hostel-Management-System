<!DOCTYPE html>
<html lang="en" class="no-js">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="theme-color" content="#3e454c">
    <title>Enrollments</title>
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-social.css">
    <link rel="stylesheet" href="css/bootstrap-select.css">
    <link rel="stylesheet" href="css/fileinput.min.css">
    <link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php include('includes/header.php'); ?>

    <div class="ts-main-content">
        <?php include('includes/sidebar.php'); ?>
        <div class="content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="page-title" style="margin-top:4%">Enrollments</h2>
                        <div class="panel panel-default">
                            <div class="panel-heading">All Events Details</div>
                            <div class="panel-body">
                                <table id="zctb" class="display table table-striped table-bordered table-hover"
                                    cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Sno.</th>
                                            <th>UserName</th>
                                            <th>Event Name</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                            <?php
// Start session
session_start();

// Include necessary files
include('includes/config.php');
include('includes/checklogin.php');

// Check login status
check_login();

// Query to fetch all data from enrollments table
$ret = "SELECT id,username, events FROM enrollments";
$stmt = $mysqli->prepare($ret);
$stmt->execute();
$cnt=1;
$res = $stmt->get_result();

// Display all rows
while ($row = $res->fetch_object()) {
    $username = $row->username;
    $event = $row->events;

    // Display each row
    echo "<tr>";
    echo "<td>$cnt</td>"; 
    echo "<td>$username</td>"; 
    echo "<td>$event</td>"; 
    echo "</tr>";
    $cnt +=1;
}
?>
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
    <script src="js/bootstrap-select.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/dataTables.bootstrap.min.js"></script>
    <script src="js/Chart.min.js"></script>
    <script src="js/fileinput.js"></script>
    <script src="js/chartData.js"></script>
    <script src="js/main.js"></script>

</body>

</html>