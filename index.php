<?php
include 'php/utils.php';
include 'php/navigation.php';
include "auth/session.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['cart'])) {

}

?>
<!DOCTYPE html>
<html>
    <head>
        <title>PHP Support Ticket Form</title>
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="assets/fa/css/all.min.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
    <?php include 'php/navigation.php';?>

    <section>

    <?php
    require('php/db.php');
    // When form submitted, insert values into the database.
    if (isset($_GET['submitId'])) {

        $stmt = $mysqli->prepare("INSERT INTO tickets (user_id, order_number, category, subject) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('ssss', $_SESSION['user']['id'], $_POST['order_number'], $_POST['category'], $_POST['subject']);

        // Execute query
        if ($stmt->execute()) {
            $ticket_id = $mysqli->insert_id;
            $stmt = $mysqli->prepare("INSERT INTO ticket_messages (user_id, message, ticket_id) VALUES (?, ?, ?)");
            $stmt->bind_param('sss', $_SESSION['user']['id'], $_POST['message'], $ticket_id );
            $stmt->execute();
            header('Location: account.php');
            exit;
        } else {
            // Registration failed
            $error = 'Failed to submit ticket. Please try again later';
        }

    } else {
?>
<div class="page-container">
    <div class="form-container">
        <div class="header">
            <i class="fa-solid fa-envelope"></i>
        </div>
        <form class="auth" action="?submitId=true" method="post">
            <h1 class="login-title">Submit a support ticket</h1>
            <input type="text" class="login-input" name="order_number" placeholder="Order number" required="true" autofocus="true" >
            <div class="input-group">
                <select name="category" required="true">
                    <option value="general">General support question</option>
                    <option value="delivery">Issue with delivery</option>
                    <option value="payment">Issue with payment</option>
                    <option value="account">User account support</option>
                    <option value="other">Other issue</option>
                </select>
                <input type="subject" name="subject" placeholder="Subject" required="true" required="true">
            </div>
            <textarea id="freeform" name="message" rows="4" cols="50" placeholder="Enter text here..." required="true"></textarea>
            <input type="submit" name="submit" value="Submit Ticket" class="form-button">
            <p class="link"><a href="account.php">Back to account</a></p>
        </form>
    </div>
</div>
<?php
    }
?>

    </section>

    <script src="script.js"></script>
    </body>
</html>