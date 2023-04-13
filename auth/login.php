<?php
include '../php/utils.php';
require '../auth/db.php';
session_start();

$page_title = "Login / Register";
include '../php/header.php';
include_once '../php/navigation.php';
    // When form submitted, check and create user session.
    if (isset($_POST['login_submit'])) {

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
            $error = 'Invalid email';
        }
        echo $error;
        // Close database connection
        $mysqli->close();

    }elseif (isset($_POST['register_submit'])){
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
                <input type="submit" value="Login" name="login_submit" class="form-button" required="true"/>
                <p class="link"><a href="registration.php">New Registration</a></p>
            </form>

            <form class="auth hidden visually-hidden" id="register" method="post" name="register">
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
                <input id="register_submit" type="submit" name="register_submit" value="Register" class="form-button">
                <p class="link"><a href="login.php">Have an account? Click here to Login.</a></p>
            </form>
        </div>
    </div>
<?php
    }
include '../php/footer.php';
?>
