<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Login</title>
    <link rel="stylesheet" href="../assets/fa/css/all.min.css">
    <link rel="stylesheet" href="../style.css"/>
</head>
<body>
<?php
    include '../php/utils.php';
    include_once '../php/navigation.php';
    require '../php/db.php';
    session_start();
    // When form submitted, check and create user session.
    if (isset($_POST['email'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Prepare SQL statement
        $stmt = $mysqli->prepare("SELECT * FROM users WHERE email = ?");

        // Bind parameters
        $stmt->bind_param('s', $email);

        // Execute query
        $stmt->execute();

        // Get query result
        $result = $stmt->get_result();

        // Check if user exists
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $hashed_password = $row['password'];
            // Verify password
            if (password_verify($password, $hashed_password)) {
                // Login successful
                $_SESSION['user'] = [
                    'id' => $row['id'],
                    'first_name' => $row['first_name'],
                    'last_name' => $row['last_name'],
                    'email' => $row['email'],
                    'created_at' => $row['created_at'],
                    'user_type' => $row['user_type'],
                ];
                header("Location: ../account.php");
                exit;
            } else {
                // Invalid password
                $error = 'Invalid password';
            }
        } else {
            // User not found
            $error = 'Invalid username';
        }

        // Close database connection
        $mysqli->close();

    } else {
?>
    <form class="auth" method="post" name="login">
        <h1 class="login-title">Login</h1>
        <input type="text" class="login-input" name="email" placeholder="Email" autofocus="true"/>
        <input type="password" class="login-input" name="password" placeholder="Password"/>
        <input type="submit" value="Login" name="submit" class="login-button"/>
        <p class="link"><a href="registration.php">New Registration</a></p>
    </form>
<?php
    }
?>
</body>
</html>