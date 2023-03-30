<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Registration</title>
    <link rel="stylesheet" href="../assets/fa/css/all.min.css">
    <link rel="stylesheet" href="../style.css"/>
</head>
<body>
<?php
    include '../php/navigation.php';
    require('../php/db.php');
    // When form submitted, insert values into the database.
    if (isset($_REQUEST['email'])) {
        // removes backslashes
        $first_name = stripslashes($_REQUEST['first_name']);
        //escapes special characters in a string
        $first_name = mysqli_real_escape_string($con, $first_name);

        $last_name = stripslashes($_REQUEST['last_name']);
        $last_name = mysqli_real_escape_string($con, $last_name);

        $email    = stripslashes($_REQUEST['email']);
        $email    = mysqli_real_escape_string($con, $email);

        $password = stripslashes($_REQUEST['password']);
        $password = mysqli_real_escape_string($con, $password);

        $create_datetime = date("Y-m-d H:i:s");

        $result = dbQuery("INSERT into `users` (first_name, last_name, password, email, create_datetime)
                     VALUES ('$first_name', '$last_name', '" . md5($password) . "', '$email', '$create_datetime')");
        if ($result) {
            echo "<div class='form'>
                  <h3>You are registered successfully.</h3><br/>
                  <p class='link'>Click here to <a href='login.php'>Login</a></p>
                  </div>";
        } else {
            echo "<div class='form'>
                  <h3>Required fields are missing.</h3><br/>
                  <p class='link'>Click here to <a href='registration.php'>registration</a> again.</p>
                  </div>";
        }
    } else {
?>
    <form class="auth" action="" method="post">
        <h1 class="login-title">Registration</h1>
        <input type="text" class="login-input" name="first_name" placeholder="First name" required />
        <input type="text" class="login-input" name="last_name" placeholder="Last name" required />
        <input type="text" class="login-input" name="email" placeholder="Email Adress">
        <input type="password" class="login-input" name="password" placeholder="Password">
        <input type="submit" name="submit" value="Register" class="login-button">
        <p class="link"><a href="login.php">Click to Login</a></p>
    </form>
<?php
    }
?>
</body>
</html>