
function validateLogin()
{
    var username = document.forms["formLogin"]["username"].value;
    var password = document.forms["formLogin"]["password"].value;

    var usernameError = document.getElementById("errorUsername");
    var passwordError = document.getElementById("errorPassword");

    var isValid = true;

    // Clear previous errors
    usernameError.innerText = "";
    passwordError.innerText = "";

    // Username validation
    if (username.trim() === "")
    {
        usernameError.innerText = "Username is required!";
        isValid = false;
    }

    // Password validation
    if (password === "")
    {
        passwordError.innerText = "Password is required!";
        isValid = false;
    }

    return isValid;
}
