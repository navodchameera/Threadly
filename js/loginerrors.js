var form = document.forms["formLogin"];
var message = document.getElementById("loginError");

form.addEventListener("submit", async function(event)
{
    // Stop the normal form submission
    event.preventDefault();

    message.innerText = "";

    if(validateLogin())
    {
        var formData = new FormData(form);

        try
        {
            var response = await fetch("checkcredentials.php",
            {
                method: "POST",
                body: formData
            });

            if(response.ok)
            {
                var result = (await response.text()).trim();

                if(result == "success")
                {
                    window.location.href = "home.php";
                }
                else if(result == "invalid_credentials")
                {
                    message.innerText = "Invalid username or password.";
                }
                else if(result == "banned")
                {
                    message.innerText = "Your account is banned.";
                }
                else
                {
                    message.innerText = "Something went wrong. Please try again.";
                }
            }
            else
            {
                message.innerText = "Something went wrong. Please try again.";
            }
        }
        catch(error)
        {
            message.innerText = "Unable to connect. Please try again.";
        }
    }
});