function validate()
{
    var username = document.forms["formSignUp"]["username"].value;
    var email = document.forms["formSignUp"]["email"].value;
    var password = document.forms["formSignUp"]["password"].value;
    var confirmPassword = document.forms["formSignUp"]["confirmPassword"].value;

    var usernameError = document.getElementById("errorUsername");
    var emailError = document.getElementById("errorEmail");
    var passwordError = document.getElementById("errorPassword");
    var confirmPasswordError = document.getElementById("errorConfirmPassword");

    const regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/; //regular expression
    

    // Clear previous errors
    usernameError.innerText = "";
    emailError.innerText = "";
    passwordError.innerText = "";
    confirmPasswordError.innerText = "";

    // Username validation
    if (username.trim() == "")
    {
        usernameError.innerText = "Username is required!";
        return false;
    }
    else if (username.trim().length < 3)
    {
        usernameError.innerText = "Username should have at least 3 characters!";
        return false;
    }
    else if (email.trim() == "")    // email validation
    {
        emailError.innerText = "Email is required!";
        return false;
    }
    else if (!regex.test(email.trim()))
    {
        emailError.innerText = "Invalid email address!";
        return false;
    }

    if (password == "") // password validation
    {
        passwordError.innerText = "Password is required!";
        return false;
    }
    else if (password.length < 8)
    {
        passwordError.innerText = "Password must contain at least 8 characters!";
        return false;
    }
    if (confirmPassword == "") // confirm password validation
    {
        confirmPasswordError.innerText = "Please confirm your password!";
        return false;
    }
    else if (password !== confirmPassword)
    {
        confirmPasswordError.innerText = "Passwords do not match!";
        return false;
    }
    else // if everything is ok
    {
        return true;
    }

}