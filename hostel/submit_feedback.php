<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Loop through all posted answers
    foreach ($_POST as $key => $answer) {
        if (strpos($key, 'answer_') === 0 && !empty($answer)) {
            $feedback_id = str_replace('answer_', '', $key);
            $username = $_SESSION['login']; // Assuming the username is stored in session after login

            // Prepare and execute the SQL statement to insert the answer
            $stmt = $mysqli->prepare("INSERT INTO useranswer (answer, username) VALUES (?, ?)");
            if ($stmt) {
                $stmt->bind_param('ss', $answer, $username);
                $stmt->execute();
                $stmt->close();
            } else {
                echo "<div class='alert alert-danger'>Failed to prepare statement</div>";
            }
        }
    }

    // Set session variable to indicate feedback has been submitted
    $_SESSION['feedback_submitted'] = true;

    // Redirect back to the previous page
    header("Location: {$_SERVER['HTTP_REFERER']}");
    exit;
}

echo "<h1>Form submitted successfully. Redirecting...</h1>";
?>
