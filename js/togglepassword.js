function togglePassword()
{
    var password = document.getElementById("password");
    var button = document.getElementById("passwordToggleButton");

    if(password.type == "password")
    {
        password.type = "text";
        button.value = "Hide";
    }
    else
    {
        password.type = "password";
        button.value = "Show";
    }
}