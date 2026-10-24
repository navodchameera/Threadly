<?php

include "connection.php";
include "data/reactions.php";

try
{
    //gets currently selected reation
    $reaction = $_POST["reaction"];
    $currentUserId = $_POST["currentUser"];
    $threadId = $_POST["threadId"];
    //gets db reaction
    $userReaction = $_POST["userReacion"];
    
    //if there is no user reaction for that thread...
    //insert the new reaction
    if($userReaction == "")
    {
        addThreadReaction($con, $reaction, $currentUserId, $threadId);
        //reload the thread so it refreshes
        header("Location: thread.php?threadId=".$threadId);
    }
    //if user clicks the reaction they already did....
    //remove that reaction
    else if($reaction == $userReaction)
    {
        deleteThreadReaction($con, $currentUserId, $threadId);
        //reload the thread so it refreshes
        header("Location: thread.php?threadId=".$threadId);

    }
    //if user previously reacted and chooses a new reaction...
    //update the reation
    else
    {
        updateThreadReaction($con, $reaction, $currentUserId, $threadId);
        //reload the thread so it refreshes
        header("Location: thread.php?threadId=".$threadId);
    }


}
catch(Exception $ex)
{
    header("Location: error.html");
    exit; // Stop executing the remaining PHP
}

?>