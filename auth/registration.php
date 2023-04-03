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
    include '../php/utils.php';
    include '../php/navigation.php';
    require('../php/db.php');

    if (isset($_POST['email'])){
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // Validate user input
        if (empty($first_name) || empty($last_name) ||empty($email) || empty($password) || empty($confirm_password)) {
            $error = 'All fields are required';
        } elseif ($password != $confirm_password) {
            $error = 'Passwords do not match';
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Prepare SQL statement
            $stmt = $mysqli->prepare("INSERT INTO users (first_name, last_name, email, password) VALUES (?, ?, ?, ?)");

            // Bind parameters
            $stmt->bind_param('ssss', $first_name, $last_name, $email, $hashed_password);

            // Execute query
            if ($stmt->execute()) {
                // Registration successful
                header('Location: ../account.php');
                exit;
            } else {
                // Registration failed
                $error = 'Registration failed. Please try again later.';
            }

            // Close database connection
            $mysqli->close();
        }
    } else {
?>
    <form class="auth" action="" method="post">
        <h1 class="login-title">Registration</h1>
        <input type="text" class="login-input" name="first_name" placeholder="First name" required />
        <input type="text" class="login-input" name="last_name" placeholder="Last name" required />
        <input type="text" class="login-input" name="email" placeholder="Email Adress">
        <input type="password" class="login-input" name="password" placeholder="Password">
        <input type="password" class="login-input" name="confirm_password" placeholder="Password">
        <input type="submit" name="submit" value="Register" class="login-button">
        <p class="link"><a href="login.php">Click to Login</a></p>
    </form>
<?php
    }
?>
</body>
</html>