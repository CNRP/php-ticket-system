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
    <div class="page-container">
        <div class="form-container">
            <div class="header">
            <i class="fa-solid fa-address-card"></i>
            </div>
            <form id="register" class="auth" action="" method="post">
            <h1 class="title">Register Your Account</h1>
            <div class="input-group">
                <input class="first_name_input" type="text" name="first_name" placeholder="First name" required />
                <input type="text" name="last_name" placeholder="Last name" required />
            </div>
            <input type="text" id="email" name="email" placeholder="Email Adress">
            <label id="email_alert" class="alert hidden" for="password">Email not valid format</label>
            <div class="input-group">
                <input class="password_input" type="password" name="password" id="password" placeholder="Password">
                <input type="password" name="confirm_password" id="confirm_password" placeholder="Password">
            </div>
            <label id="password_alert_1" class="alert hidden" for="password">Passwords must match</label>
            <label id="password_alert_2" class="alert hidden" for="password">Must be atleast 6 characters, 1 uppercase 1 number and a special character</label>
            <input type="submit" name="submit" value="Register" class="form-button">
            <p class="link"><a href="login.php">Have an account? Click here to Login.</a></p>
            </form>
        </div>
    </div>
    
<?php
    }
?>
<script>

document.addEventListener("DOMContentLoaded", function() {
    var form = document.getElementById('register');
    form.addEventListener("submit", function(event) {
        // Get the password and confirm password inputs
        console.log("Hello")
        var passwordInput = document.getElementsByName('password')[0].value;
        var confirmPasswordInput = document.getElementsByName('confirm_password')[0].value;


        // Check if the password and confirm password inputs are equal
        if (passwordInput !== confirmPasswordInput) {

            // If they're not equal, prevent the form from submitting
            event.preventDefault();
        alert("Password and confirm password must match");
        }

        if(!validatePassword(passwordInput)){
                alert("Password must be atleast 6 characters, 1 number 1 special character");
                event.preventDefault();
        }

    });

    var email_input = document.getElementById("email");
    var password_input = document.getElementById("password");
    var password_confirm_input = document.getElementById("confirm_password");

    // password_input.addEventListener("blur", function() {
    //   var password_alert = document.getElementById("password_alert_1");
    //   console.log("YTOOOO")
    //   // Validate the input value
    //   if (password_input.value != password_confirm_input.value) {
    //     password_alert.classList.remove('hidden');
    //   }else{
    //     password_alert.classList.add('hidden');
    //   }
    // });
    password_confirm_input.addEventListener("blur", function() {
        var password_alert = document.getElementById("password_alert_1");
        var password_alert2 = document.getElementById("password_alert_2");

        // Validate the input value
        if (password_input.value != password_confirm_input.value) {
            password_alert.classList.remove('hidden');
        }else{
            password_alert.classList.add('hidden');
        }

        console.log(validatePassword("MyPassword123!"))
        if (!validatePassword(password_input.value)) {
            password_alert2.classList.remove('hidden');
        }else{
            password_alert2.classList.add('hidden');
        }
    });
    // Add an event listener to the input's blur event
    email_input.addEventListener("blur", function() {

        var email_alert = document.getElementById("email_alert");
        // Validate the input value
        // console.log(validateEmail("connorefc@hotmail.com"))

        if (!validateEmail(email_input.value)) {
            email_alert.classList.remove('hidden');
        }else{
            email_alert.classList.add('hidden');
        }
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