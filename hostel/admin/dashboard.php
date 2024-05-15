<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();
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
    <title>DashBoard</title>
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-social.css">
    <link rel="stylesheet" href="css/bootstrap-select.css">
    <link rel="stylesheet" href="css/fileinput.min.css">
    <link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
</head>

<body>
<script type="text/javascript" src="https://canvasjs.com/assets/script/jquery-1.11.1.min.js"></script>
    <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
    <?php include("includes/header.php"); ?>
    <div class="ts-main-content">
        <?php include("includes/sidebar.php"); ?>
        <div class="content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
						<br>
                        <h2 class="page-title" style="margin-top:4%">Dashboard</h2>
                        <div class="row">
                            <div class="col-md-6">
                                <div id="pieChartContainer" style="height: 370px; width: 100%;"></div>
                            </div>
                            <div class="col-md-6">
                                <div id="barChartContainer" style="height: 370px; width: 100%;"></div>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 20px;">
                            <div class="col-md-4">
                                <div class="panel panel-default">
                                    <div class="panel-body bk-primary text-light">
                                        <div class="stat-panel text-center">
                                            <?php
                                            $result = "SELECT count(*) FROM registration ";
                                            $stmt = $mysqli->prepare($result);
                                            $stmt->execute();
                                            $stmt->bind_result($count);
                                            $stmt->fetch();
                                            $stmt->close();
                                            ?>
                                            <div class="stat-panel-number h1 "><?php echo $count; ?></div>
                                            <div class="stat-panel-title text-uppercase"> Students</div>
                                        </div>
                                    </div>
                                    <a href="manage-students.php" class="block-anchor panel-footer">Full Detail <i class="fa fa-arrow-right"></i></a>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="panel panel-default">
                                    <div class="panel-body bk-success text-light">
                                        <div class="stat-panel text-center">
                                            <?php
                                            $result1 = "SELECT count(*) FROM rooms ";
                                            $stmt1 = $mysqli->prepare($result1);
                                            $stmt1->execute();
                                            $stmt1->bind_result($count1);
                                            $stmt1->fetch();
                                            $stmt1->close();
                                            ?>
                                            <div class="stat-panel-number h1 "><?php echo $count1; ?></div>
                                            <div class="stat-panel-title text-uppercase">Total Rooms </div>
                                        </div>
                                    </div>
                                    <a href="manage-rooms.php" class="block-anchor panel-footer text-center">See All &nbsp; <i class="fa fa-arrow-right"></i></a>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="panel panel-default">
                                    <div class="panel-body bk-info text-light">
                                        <div class="stat-panel text-center">
                                            <?php
                                            $result2 = "SELECT count(*) FROM courses ";
                                            $stmt2 = $mysqli->prepare($result2);
                                            $stmt2->execute();
                                            $stmt2->bind_result($count2);
                                            $stmt2->fetch();
                                            $stmt2->close();
                                            ?>
                                            <div class="stat-panel-number h1 "><?php echo $count2; ?></div>
                                            <div class="stat-panel-title text-uppercase">Total Events</div>
                                        </div>
                                    </div>
                                    <a href="manage-courses.php" class="block-anchor panel-footer text-center">See All &nbsp; <i class="fa fa-arrow-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<br>
<br>
<br>
<br>
    <?php
    $dataPoints = array(
        array("label" => "Enrollments", "y" => 590),
        array("label" => "Room bookings", "y" => 158),
        array("label" => "Feedback", "y" => 72),
        array("label" => "Student", "y" => 191),
        array("label" => "Events", "y" => 126)
    );
    ?>

    <script>
        window.onload = function() {
            var pieChart = new CanvasJS.Chart("pieChartContainer", {
                animationEnabled: true,
                exportEnabled: true,
                title: {
                    text: "Pie chart"
                },
                data: [{
                    type: "pie",
                    showInLegend: "true",
                    legendText: "{label}",
                    indexLabelFontSize: 16,
                    indexLabel: "{label} - #percent%",
                    yValueFormatString: "฿#,##0",
                    dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
                }]
            });
            pieChart.render();

            var barChart = new CanvasJS.Chart("barChartContainer", {
                animationEnabled: true,
                exportEnabled: true,
                title: {
                    text: "Bar Diagarm"
                },
                axisY: {
                    title: "In metric tons"
                },
                data: [{
                    type: "column",
                    toolTipContent: "{y} metric tons",
                    dataPoints: []
                }]
            });

            $.get("https://canvasjs.com/data/gallery/php/tuna-production.csv", function(data) {
                var dataPoints = [];
                var csvLines = data.split(/[\r?\n|\r|\n]+/);
                for (var i = 0; i < csvLines.length; i++) {
                    if (csvLines[i].length > 0) {
                        var points = csvLines[i].split(",");
                        dataPoints.push({
                            label: points[0],
                            y: parseFloat(points[1])
                        });
                    }
                }
                barChart.options.data[0].dataPoints = dataPoints;
                barChart.render();
            });
        }
    </script>
	<br>
	<br>
	<br>
    <script type="text/javascript" src="https://canvasjs.com/assets/script/jquery-1.11.1.min.js"></script>
    <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
</body>

</html>
