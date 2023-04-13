<?php
include '../php/utils.php';
include '../php/navigation.php';
include "../auth/session.php";
require '../auth/db.php';


$page_title = "Create a new ticket";
include '../php/header.php';
include '../php/navigation.php';

    // When form submitted, insert values into the database.
    if (isset($_GET['submitId'])) {
        $stmt = $mysqli->prepare("INSERT INTO tickets (display_id, user_id, order_number, category, subject) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('sssss', rand(10000,99999), $_SESSION['user']['id'], $_POST['order_number'], $_POST['category'], $_POST['subject']);

        // Execute query
        if ($stmt->execute()) {
            $ticket_id = $mysqli->insert_id;
            $stmt = $mysqli->prepare("INSERT INTO ticket_messages (user_id, message, ticket_id) VALUES (?, ?, ?)");
            $stmt->bind_param('sss', $_SESSION['user']['id'], $_POST['message'], $ticket_id );
            $stmt->execute();
            header('Location: ../account.php');
            exit;
        } else {
            // Registration failed
            $error = 'Failed to submit ticket. Please try again later';
        }

    } else {
?>
<div class="page-container">
    <div class="form-container">
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
include 'php/footer.php';
?>
