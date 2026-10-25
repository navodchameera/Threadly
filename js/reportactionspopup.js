function openActionDialog(reportId, action)
{
    document.getElementById("actionReportId").value = reportId;
    document.getElementById("selectedAction").value = action;

    var title = document.getElementById("actionTitle");
    var message = document.getElementById("actionMessage");
    var button = document.getElementById("actionConfirmButton");

    if(action == "dismiss")
    {
        title.innerText = "Dismiss report?";
        message.innerText = "This report will be marked as dismissed. No changes will be made to the user or content.";
        button.value = "Dismiss Report";
    }
    else if(action == "delete")
    {
        title.innerText = "Delete content?";
        message.innerText = "This cannot be undone. Deleting a thread also deletes its comments.";
        button.value = "Delete Content";
    }
    else if(action == "ban")
    {
        title.innerText = "Ban user?";
        message.innerText = "This user will be marked as banned and will not be able to log in.";
        button.value = "Ban User";
    }
    else if(action == "ban_delete")
    {
        title.innerText = "Ban user and delete content?";
        message.innerText = "The user will be banned and the reported content deleted. Deleting a thread also deletes its comments.";
        button.value = "Ban + Delete";
    }

    document.getElementById("actionDialog").showModal();
}

function closeActionDialog()
{
    document.getElementById("actionDialog").close();
}