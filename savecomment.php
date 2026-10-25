<?php

include "connection.php";

try
{
    $content = htmlspecialchars($_POST["comment"]);
    $threadId = $_POST["threadId"];
    $userId = $_POST["currentUserId"];

    $insert = $con->prepare("INSERT INTO comment (content, treadID, userID) VALUES (?, ?, ?)");
    $insert->bind_param("sii", $content, $threadId, $userId);
    $insert->execute();

    //reopen the read to refresh it
    header("Location: thread.php?threadId=".$threadId);

}
catch(Exception $ex)
{
    header("Location: error.html");
    exit; // Stop executing the remaining PHP
}

?>