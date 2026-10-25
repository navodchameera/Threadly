<?php

try
{
    
    include "connection.php";
    include "data/comment.php";

    // Get the selected reaction and comment
    $commentId = $_POST["commentId"]; 
    $currentUserId = $_POST["currentUserId"];
    $reaction = $_POST["reaction"];
    $userCommentReaction = $_POST["userCommentReaction"];
    $threadId = $_POST["threadId"];


    // If there is no reaction, insert the selected reaction
    if($userCommentReaction == "")
    {
        addCommentReaction($con, $reaction, $currentUserId, $commentId);
    }
    // If the same reaction is clicked, remove it
    else if($reaction == $userCommentReaction)
    {
        deleteCommentReaction($con, $currentUserId, $commentId);
    }
    // If a different reaction is clicked, update it
    else
    {
        updateCommentReaction($con, $reaction, $currentUserId, $commentId);
    }

    mysqli_close($con);

    // Reload the thread and return to this comment
    header("Location: thread.php?threadId=".$threadId."#comment-".$commentId);
    exit;
        
    
}
catch(Exception $ex)
{
    

    header("Location: error.html");
    exit;
}

?>