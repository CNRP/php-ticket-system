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
    <div class="page-container">
        <div class="form-container">
            <!-- <div class="header">
                <i class="fa-solid fa-right-to-bracket"></i>
            </div> -->
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
<script>

document.addEventListener('DOMContentLoaded', e => {
// const toggleFormsButton1 = document.getElementById('toggle-forms-button');

document.querySelectorAll(".toggle-forms-button").forEach(e => {

    const loginForm = document.getElementById('login');
    const registerForm = document.getElementById('register');
    const loginToggle = document.getElementById('login-toggle');
    const registerToggle = document.getElementById('register-toggle');
    const line = document.getElementById('line');

    e.addEventListener('click', () => {

    if (loginForm.classList.contains('hidden')) {
        // Show the login form and hide the register form
        loginForm.classList.remove('hidden');
        registerForm.classList.add('hidden');
        setTimeout(() => {
        loginForm.classList.remove('visually-hidden');
        }, 10);
        registerForm.classList.add('visually-hidden');

        registerToggle.classList.toggle('disabled');
        loginToggle.classList.toggle('disabled');
        line.classList.toggle('left');

    } else {
        // Show the register form and hide the login form
        registerForm.classList.remove('hidden');
        loginForm.classList.add('hidden');
        setTimeout(() => {
        registerForm.classList.remove('visually-hidden');
        }, 10);
        loginForm.classList.add('visually-hidden');
        registerToggle.classList.toggle('disabled');
        loginToggle.classList.toggle('disabled');
        line.classList.toggle('left');
    }
    });
  });

    var email_input = document.getElementById("email");
    var password_input = document.getElementById("password");
    var password_confirm_input = document.getElementById("confirm_password");
    var submit_registration = document.getElementById("register_submit");
    var password_alert = document.getElementById("password_alert_1");
    var password_alert2 = document.getElementById("password_alert_2");
    var email_alert = document.getElementById("email_alert");


    var form_enabled = false;
    var password_valid = false;
    var email_valid = false;
    // Add an event listener to the input's blur event (when it is unfocused)
    email_input.addEventListener("blur", function() {
        var email_alert = document.getElementById("email_alert");
        console.log(email_valid + " "+ password_valid);
        // Validate the input value
        if(validateEmail(email_input.value)){
            email_valid = true;
            email_alert.classList.add('hidden');
        }else{
            email_alert.classList.remove('hidden');
        }
        (password_valid && email_valid) ? submit_registration.classList.remove('disabled') : submit_registration.classList.add('disabled');
    });

    password_confirm_input.addEventListener("blur", function() {
        console.log(email_valid + " "+ password_valid);
        // Validate the input value
        if(validatePassword(password_input.value) && (password_input.value === password_confirm_input.value)){
            password_valid = true;
        }else{
            (password_input.value != password_confirm_input.value) ? password_alert.classList.remove('hidden') : password_alert.classList.add('hidden');
            (!validatePassword(password_input.value)) ? password_alert2.classList.remove('hidden') : password_alert2.classList.add('hidden');
        }
        (password_valid && email_valid) ? submit_registration.classList.remove('disabled') : submit_registration.classList.add('disabled');
    });
});

function validatePassword(password) {
  // Define the regular expression to test the password against
  var passwordRegex = /^(?=.*\d)(?=.*[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?])(?=.*[a-zA-Z]).{6,}$/;

  // Test the password against the regular expression
  return passwordRegex.test(password);
}

function validateEmail(email) {
  // Regular expression for email validation
  var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  // Test the input value against the regular expression
  return emailRegex.test(email);
}

</script>
</body>
</html>