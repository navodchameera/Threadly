var form = document.forms["formSignUp"];
var message = document.getElementById("errorUsername");

form.addEventListener("submit", async function(event) {
    event.preventDefault();

    // Validate before sending data
    if (!validate()) {
        return;
    }

    var formData = new FormData(form);

    try {
        var response = await fetch("register.php", {
            method: "POST",
            body: formData
        });

        if (!response.ok) {
            throw new Error("Server error");
        }

        var result = (await response.text()).trim();

        if (result === "username_taken") {
            message.innerText = "Username is not available!";
        }
        else if (result === "saved") {
            window.location.href = "index.html";
        }
        else {
            message.innerText = "Something went wrong!";
        }
    }
    catch (error) {
        message.innerText = "Something went wrong!";
        console.error(error);
    }
});