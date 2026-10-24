function openReport(threadId, commentId)
{
    document.getElementById("reportThreadId").value = threadId;
    document.getElementById("reportCommentId").value = commentId;
    document.getElementById("reportReason").value = "";

    document.getElementById("reportPopup").showModal();
}

function closeReport()
{
    document.getElementById("reportPopup").close();
}