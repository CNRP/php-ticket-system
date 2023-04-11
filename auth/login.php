<?php
include '../php/utils.php';
include_once '../php/navigation.php';
require '../auth/db.php';
session_start();

$page_title = "Login / Register";
include '../php/header.php'; ?>

<body>
<?php
    // When form submitted, check and create user session.
    if (isset($_POST['email'])) {

        // Prepare SQL statement
        $stmt = $mysqli->prepare("SELECT * FROM users WHERE email = ?");
        // Bind parameters
        $stmt->bind_param('s', $_POST['email']);
        // Execute query
        $stmt->execute();
        // Get query result
        $result = $stmt->get_result();

        // Check if user exists
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $hashed_password = $row['password'];
            // Verify password
            if (password_verify($_POST['password'], $hashed_password)) {
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
    <div class="page-container">
        <div class="form-container">
            <div class="form-toggles">
                <div>
                    <a class="toggle-forms-button disabled" id="login-toggle" href="#">Login</a>
                    <a class="toggle-forms-button" id="register-toggle" href="#">Register</a>
                </div>
                <div id="line"></div>
            </div>
            <form class="auth" id="login" method="post" name="login">
                <input type="text" class="login-input" name="email" placeholder="Email" autofocus="true" required="true"/>
                <input type="password" class="login-input" name="password" placeholder="Password" required="true"/>
                <input type="submit" value="Login" name="submit" class="form-button" required="true"/>
                <p class="link"><a href="registration.php">New Registration</a></p>
            </form>
            <form class="auth hidden visually-hidden" id="register" action="" method="post">
                <div class="input-group">
                    <input class="first_name_input" type="text" name="first_name" placeholder="First name" required />
                    <input type="text" name="last_name" placeholder="Last name" required />
                </div>
                <input type="text" id="email" name="email" placeholder="Email Adress">
                <label id="email_alert" class="alert hidden" for="password">Email not valid format</label>
                <div class="input-group">
                    <input class="password_input" type="password" name="password" id="password" required placeholder="Password">
                    <input type="password" name="confirm_password" id="confirm_password" required placeholder="Password">
                </div>
                <label id="password_alert_1" class="alert hidden" for="password">Passwords must match</label>
                <label id="password_alert_2" class="alert hidden" for="password">Must be atleast 6 characters, 1 uppercase 1 number and a special character</label>
                <input id="register_submit" type="submit" name="submit" value="Register" class="form-button">
                <p class="link"><a href="login.php">Have an account? Click here to Login.</a></p>
            </form>
        </div>
    </div>
<?php
    }
?>

<!-- script controls the toggling of login/register forms  -->
<script src="../js/auth/script.js"></script>
</body>
</html>