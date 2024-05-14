<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();

if(isset($_GET['del'])) {
    $feedback_id = intval($_GET['del']); // Convert to integer
    if ($feedback_id) {
        // Delete the feedback entry based on feedback_id
        $delete_query = "DELETE FROM feedback WHERE feedback_id=?";
        $stmt = $mysqli->prepare($delete_query);
        $stmt->bind_param('i', $feedback_id);
        if ($stmt->execute()) {
            echo "<script>alert('Data Deleted');</script>";
            echo "<script>window.location.href='manage-feedback.php';</script>";
            exit();
        } else {
            echo "<script>alert('Failed to delete data: ".$stmt->error."');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Invalid Feedback ID');</script>";
    }
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
    <title>Manage Feedbacks</title>
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
                        <h2 class="page-title" style="margin-top: 4%">Manage Feedbacks</h2>
                        <div class="panel panel-default">
                            <div class="panel-heading">All Feedback Details</div>
                            <div class="panel-body">
                                <table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Sno.</th>
                                            <th>Username</th>
                                            <th>Feedback</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                    </tfoot>
                                    <tbody>
                                        <?php
                                        $ret = "SELECT * FROM feedback";
                                        $stmt = $mysqli->prepare($ret);
                                        if ($stmt) {
                                            $stmt->execute();
                                            $res = $stmt->get_result();
                                            $cnt = 1;
                                            while ($row = $res->fetch_object()) {
                                        ?>
                                                <tr>
                                                    <td><?php echo $cnt; ?></td>
                                                    <td><?php echo $row->username; ?></td>
                                                    <td><?php echo $row->feedback; ?></td>
                                                    <td>
                                                        <a href="manage-feedback.php?del=<?php echo $row->feedback_id; ?>" onclick="return confirm('Do you want to delete this feedback?');"><i class="fa fa-close"></i></a>
                                                    </td>
                                                </tr>
                                        <?php
                                                $cnt++;
                                            }
                                            $stmt->close();
                                        } else {
                                            echo "<tr><td colspan='4'>Failed to prepare statement</td></tr>";
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
