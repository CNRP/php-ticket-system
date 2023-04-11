<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Login</title>
    <link rel="stylesheet" href="../assets/fa/css/all.min.css">
    <link rel="stylesheet" href="../style.css"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

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

document.addEventListener('DOMContentLoaded', e =>{
    document.querySelectorAll(".toggle-forms-button").forEach(e =>{
        e.addEventListener('click', () =>{
            toggleForms();
        });
    });

    var password_valid = false;
    var email_valid = false;

    function toggleSubmitButton() {
        const isDisabled = !password_valid || !email_valid;
        document.getElementById("register_submit").classList.toggle('disabled', isDisabled);
    }

    // Add an event listener to the input's blur events (when it is unfocused / clicked off)
    document.getElementById("email").addEventListener("blur", function() {
        email_valid = validateEmail(document.getElementById("email").value);
        document.getElementById("email_alert").classList.toggle('hidden', email_valid);
        toggleSubmitButton();
    });

    document.getElementById("confirm_password").addEventListener("blur", function() {
        password_valid = false;
        //check if password inputs are the same
        var isSame = document.getElementById("password").value === document.getElementById("confirm_password").value;
        //check if password meets validation requirements
        var isValid = validatePassword(document.getElementById("password").value);
        //tell script password is valid (both inputs the same and meet format requirements)
        password_valid = isSame && isValid;

        //check if password inputs are the same, if so hide the alert error message
        document.getElementById("password_alert_1").classList.toggle('hidden', isSame);
        //check if the password is formatted correctly, if so ihide the alert error message
        document.getElementById("password_alert_2").classList.toggle('hidden', isValid);
        toggleSubmitButton();
    });
});


//code for toggling which form is in view on the login/register page
function toggleForms() {
    const form1 = "login";
    const form2 = "register";
    const line = document.getElementById('line');

    console.log("hello");
    [document.getElementById(form1), document.getElementById(form2)].forEach(form => {
        if(form.classList.contains('hidden')){
            //remove hidden class (display: none -> display: flex)
            form.classList.remove('hidden');
            setTimeout(() => {
                // 10 after, remove visually-hidden class which makes opacity 1 and allows the form to fade into view.
                form.classList.remove('visually-hidden');
            }, 10);
            //toggle 'disabled' on the button for this form as it is selected already
            document.getElementById(form1+'-toggle').classList.toggle('disabled');
        }else{
            //This does the oposite of the last code block to the other form, as one form will always be hidden while the other is visible
            //since I dont need the form to fade away there is no need to add a delay
            form.classList.add('hidden');
            form.classList.add('visually-hidden');
            document.getElementById(form2+'-toggle').classList.toggle('disabled');
        }
    });
    line.classList.toggle('left');
}

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