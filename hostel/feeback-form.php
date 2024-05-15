<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();

// Initialize the feedback_submitted variable
$feedback_submitted = isset($_SESSION['feedback_submitted']) && $_SESSION['feedback_submitted'];

if ($feedback_submitted) {
    // Unset the session variable to reset the form for the next time the page is loaded
    unset($_SESSION['feedback_submitted']);
}
?>

<!doctype html>
<html lang="en" class="no-js">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="theme-color" content="#3e454c">
    <title>Give Feedbacks</title>
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .feedback-card {
            margin-bottom: 20px;
        }
        .feedback-card .card-body {
            padding: 20px;
        }
        .submit-btn {
            margin-top: 20px;
            background-color: #3e454c;
            color: #fff;
            border: none;
        }
        .submit-btn:hover {
            background-color: #2c2f33;
        }
        .hidden {
            display: none;
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
                        <h2 class="page-title" style="margin-top: 4%">Give Feedbacks</h2>
                        <div class="panel panel-default">
                            <div class="panel-heading">All Feedback Details</div>
                            <div class="panel-body">
                                <?php if ($feedback_submitted): ?>
                                    <div class="alert alert-success">Feedback submitted successfully!</div>
                                <?php endif; ?>
                                <form action="submit_feedback.php" method="post" id="feedbackForm" <?php echo $feedback_submitted ? 'class="hidden"' : ''; ?>>
                                    <div class="feedback-container">
                                        <?php
                                        $ret = "SELECT * FROM feedback";
                                        $stmt = $mysqli->prepare($ret);
                                        if ($stmt) {
                                            $stmt->execute();
                                            $res = $stmt->get_result();
                                            $cnt = 1;
                                            while ($row = $res->fetch_object()) {
                                        ?>
                                                <div class="card feedback-card">
                                                    <div class="card-body">
                                                        <h5 class="card-title">Feedback #<?php echo $cnt; ?></h5>
                                                        <p class="card-text"><strong>Feedback Question:</strong> <?php echo htmlspecialchars($row->feedback); ?></p>
                                                        <div class="form-group">
                                                            <label for="answer_<?php echo $row->feedback_id; ?>">Answer:</label>
                                                            <textarea class="form-control" id="answer_<?php echo $row->feedback_id; ?>" name="answer_<?php echo $row->feedback_id; ?>" rows="2"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                        <?php
                                                $cnt++;
                                            }
                                            $stmt->close();
                                        } else {
                                            echo "<div class='alert alert-danger'>Failed to prepare statement</div>";
                                        }
                                        ?>
                                    </div>
                                    <button type="submit" class="btn submit-btn">Submit All Answers</button>
                                </form>
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
    <script src="js/main.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if ($feedback_submitted): ?>
                document.getElementById('feedbackForm').classList.add('hidden');
            <?php endif; ?>
        });
    </script>
</body>
</html>
