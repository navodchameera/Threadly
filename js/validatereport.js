function validateReport()
{
    var reason = document.forms["formReport"]["reason"].value;
    var reasonError = document.getElementById("errorReason");
    var reportButton = document.getElementById("reportButton");

    reasonError.innerText = "";
    reportButton.disabled = false;

    if(reason.trim() == "")
    {
        reasonError.innerText = "Please enter a reason.";
        return false;
    }
    //if validation is ok....
    else
    {
        //disable the submit button
        reportButton.disabled = true;
        //change error text to show the feedback to the user
        reasonError.innerText = "Report submitted successfully.";
        //change the <p> colour to green
        reasonError.style.color = "green";
        reasonError.style.fontSize = "14px";
        ree
        
    }
}