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
        // removes backslashes
        $order_no    = stripslashes($_REQUEST['order_number']);
        $order_no    = mysqli_real_escape_string($con, $order_no);

        $message    = stripslashes($_REQUEST['message']);
        $message    = mysqli_real_escape_string($con, $message);

        $create_datetime = date("Y-m-d H:i:s");
        $result = dbQuery("INSERT into `tickets` (user_id, order_number, status, create_datetime)
                     VALUES ('".$_SESSION['user']['id']."', '$order_no', 'pending', '$create_datetime')");

        if ($result) {
          echo "<div class='form'>
                <h3>Succesfully added ticket</h3><br/>
                </div>";
        } else {
            echo "<div class='form'>
                  <h3>couldnt add ticket</h3><br/>
                  </div>";
        }

        $result = dbQueryAssoc("SELECT `id` FROM `tickets` WHERE create_datetime='$create_datetime' ");

        if ($result) {
          echo "<div class='form'>
                <h3>Succesfully found ticket id ".$result['id']."</h3><br/>
                </div>";
        } else {
            echo "<div class='form'>
                  <h3>couldnt find ticket</h3><br/>
                  </div>";
        }

        $id = $_SESSION['user']['id'];
        $result = dbQuery("INSERT INTO `ticket_messages`( `user_id`, `message`, `create_datetime`, `ticket_id`)
                  VALUES ('$id', '$message', '$create_datetime', '".$result['id']. "')");

        if ($result) {
          echo "<div class='form'>
                <h3>Succesfully added ticket message to ticket</h3><br/>
                </div>";
        } else {
            echo "<div class='form'>
                  <h3>couldnt add ticket message ticket</h3><br/>
                  </div>";
        }

    } else {
?>
    <form class="auth" action="?submitId=true" method="post">
        <h1 class="login-title">Submit a support ticket</h1>
        <input type="text" class="login-input" name="order_number" placeholder="Order number" />
        <input type="text" class="login-input" name="email" placeholder="Email Adress">
        <textarea id="freeform" name="message" rows="4" cols="50">Enter text here...</textarea>
        <input type="submit" name="submit" value="Register" class="login-button">
        <p class="link"><a href="account.php">Submit Ticket</a></p>
    </form>
<?php
    }
?>

    </section>

    <script src="script.js"></script>
  </body>
</html>