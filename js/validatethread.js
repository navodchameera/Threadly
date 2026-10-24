function validateThread()
{
    var title = document.forms["formThread"]["title"].value;
    var content = document.forms["formThread"]["content"].value;

    var titleError = document.getElementById("errorTitle");
    var contentError = document.getElementById("errorContent");

    // Clear previous errors
    titleError.innerText = "";
    contentError.innerText = "";

    if(title.trim() == "")
    {
        titleError.innerText = "Please enter a title.";
        return false;
    }

    else if(content.trim() == "")
    {
        contentError.innerText = "Please enter the thread content.";
        return false
    }
    else
    {
        return true;
    }
    
}