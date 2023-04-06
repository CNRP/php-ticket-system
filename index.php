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
    </head>
    <body>
    <?php include 'php/navigation.php';?>

    <section>

    <?php
    require('php/db.php');
    // When form submitted, insert values into the database.
    if (isset($_GET['submitId'])) {

        $stmt = $mysqli->prepare("INSERT INTO tickets (user_id, order_number) VALUES (?, ?)");
        $stmt->bind_param('ss', $_SESSION['user']['id'], $_POST['order_number']);

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
            <input type="text" class="login-input" name="order_number" placeholder="Order number" />
            <input type="text" id="email" class="login-input" value="<?php echo $_SESSION['user']['email'] ?>" name="email" placeholder="Email Adress">
            <textarea id="freeform" name="message" rows="4" cols="50" placeholder="Enter text here..."></textarea>
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