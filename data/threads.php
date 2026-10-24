<?php

function getAllThreads($con, $search)
{
    $searchText = "%".$search."%";

    $select = $con->prepare("
        SELECT thread.threadId, thread.title, thread.content, user.username
        FROM thread
        INNER JOIN user ON thread.userId = user.userId
        WHERE thread.title LIKE ? OR thread.content LIKE ?
        ORDER BY thread.threadId DESC
    ");

    $select->bind_param("ss", $searchText, $searchText);
    $select->execute();

    return $select->get_result();
}

function getThread($con, $threadId)
{
    $select = $con->prepare("
        SELECT thread.threadId, thread.title, thread.content, user.username
        FROM thread
        INNER JOIN user ON thread.userId = user.userId
        WHERE thread.threadId = ?
    ");

    $select->bind_param("i", $threadId);
    $select->execute();

    return $select->get_result();
}



?>