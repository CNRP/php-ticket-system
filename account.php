<?php
//include auth_session.php file on all user panel pages
include "auth/session.php";
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Dashboard - Client area</title>
    <link rel="stylesheet" href="style.css" />
</head>
<body>
    <?php include 'php/navigation.php'; ?>
    <div class="form">
        <p>Hey, <?php echo $_SESSION['user']['first_name']." ".$_SESSION['user']['last_name']; ?>!</p>
        <p>Email: <?php echo $_SESSION['user']['email'] ?></p>
        <p>Account Created: <?php echo $_SESSION['user']['create_datetime']?></p>
        <p>You are now user dashboard page.</p>
        <p><a href="/auth/logout.php">Logout</a></p>
    </div>
</body>
</html>