<?php
    session_start();
    include('includes/config.php');
    include('includes/checklogin.php');
    check_login();
    //TODO : event name and the user name for database
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
            color:white;
        }
    </style>
</head>

<body>
    <?php include('includes/header.php');?>

    <div class="ts-main-content">
        <?php include('includes/sidebar.php');?>
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
                                            <th>Action</th> <!-- Add a new column for action -->
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
                                                <td><?php echo $cnt;?></td>
                                                <td><?php echo $row->course_code;?></td>
                                                <td><?php echo $row->course_sn;?></td>
                                                <td><?php echo $row->course_fn;?></td>
                                                <td><?php echo $row->posting_date;?></td>
                                                <td>
                                                   
                                                    <a class="btn btn-primary enroll-btn" data-course-id="<?php echo $row->course_id; ?>" onclick="enrollCourse(this) ">Enroll</a>
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
       function enrollCourse(button) {
    if (button.classList.contains('enrolled')) {
        unenrollCourse(button);
    } else {
        if (confirm('Are you sure you want to enroll in this event?')) {
            // Change button color and text
            button.classList.remove('btn-primary');
            button.classList.add('enrolled');
            button.innerText = 'Unenroll';
        }
    }
}

function unenrollCourse(button) {
    if (confirm('Are you sure you want to unenroll from this course?')) {
        // Change button color and text
        button.classList.remove('enrolled');
        button.classList.add('btn-primary');
        button.innerText = 'Enroll';
    }
}
    </script>
</body>

</html>
