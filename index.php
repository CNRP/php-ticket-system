<?php

include 'php/utils.php';
include 'php/navigation.php';
include 'php/db.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['cart'])) {

}

?>
<!DOCTYPE html>
<html>
  <head>
    <title>PHP Login Form</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="assets/fa/css/all.min.css">
    <script src="https://js.stripe.com/v3/"></script>
  </head>
  <body>
    <?php include 'php/navigation.php';?>


    <script src="script.js"></script>
  </body>
</html>