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
