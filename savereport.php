<?php

try
{
    //happens after 5secons so user sees the feedback
    sleep(2);
    include "connection.php";

    $threadId = htmlspecialchars($_POST["threadId"]);
    $commentId = htmlspecialchars($_POST["commentId"]);
    $userId = $_POST["userId"];
    $reason = htmlspecialchars($_POST["reason"]);
    $returnTo = $_POST["returnTo"];
    $status = "pending";
    //if post sends thread id as 0 that is a comment report
    if($threadId == 0)
    {
        $insert = $con->prepare("INSERT INTO report (reason, status, commentId, userId) VALUES (?, ?, ?, ?)");
        $insert->bind_param("ssii", $reason, $status, $commentId, $userId);
        $insert->execute();
        //return to the thread
        header("Location: thread.php?threadId=".$returnTo);
    }
    //if post send comment id as 0 that is a thread report
    else if($commentId == 0)
    {
        $insert = $con->prepare("INSERT INTO report (reason, status, threadId, userId) VALUES (?, ?, ?, ?)");
        $insert->bind_param("ssii", $reason, $status, $threadId, $userId);
        $insert->execute();
        //return to the thread
        header("Location: thread.php?threadId=".$returnTo);
    }
    else
    {
        header("Location: error.html");
        exit; // Stop executing the remaining PHP
    }

}
catch(Exception $ex)
{
    
    header("Location: error.html");
    exit; // Stop executing the remaining PHP
}




?>