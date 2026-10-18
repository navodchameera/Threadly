
function validateLogin()
{
    var username = document.forms["formLogin"]["username"].value;
    var password = document.forms["formLogin"]["password"].value;

    var usernameError = document.getElementById("errorUsername");
    var passwordError = document.getElementById("errorPassword");


    // Clear previous errors
    usernameError.innerText = "";
    passwordError.innerText = "";

    // Username validation
    if (username.trim() === "")
    {
        usernameError.innerText = "Username is required!";
        return false;
    }
    // Password validation
    else if (password === "")
    {
        passwordError.innerText = "Password is required!";
        return false;
    }
    else
    {
        return true;
    }

}
