function validatecomments()
{
    var content = document.forms["formComment"]["comment"].value;
    var contentError = document.getElementById("errorComment");

    contentError.innerText = "";

    if(content.trim() == "")
    {
        contentError.innerText = "Please enter a comment.";
        return false;
    }
    else
    {
        return true;
    }
}