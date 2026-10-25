function openReport(threadId, commentId)
{
    //setting values so they can be passed when the form is submitted.
    document.getElementById("reportThreadId").value = threadId;
    document.getElementById("reportCommentId").value = commentId;
    document.getElementById("reportReason").value = "";

    //show popup
    document.getElementById("reportPopup").showModal();
}

function closeReport()
{
    document.getElementById("reportPopup").close();
}