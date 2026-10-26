<?php

function getReactionsCount($con, $threadId)
{
    $select = $con->prepare("
        SELECT
        COUNT(CASE WHEN reaction = 'like' THEN 1 END) AS likeCount,
        COUNT(CASE WHEN reaction = 'unlike' THEN 1 END) AS unlikeCount,
        COUNT(CASE WHEN reaction = 'love' THEN 1 END) AS loveCount
        FROM reaction
        WHERE threadId = ?
    ");

    $select->bind_param("i", $threadId);
    $select->execute();

    return $select->get_result();
    mysqli_close($con);
}

function getUserReaction($con, $userId, $threadId)
{
    $select = $con->prepare("SELECT reaction FROM reaction WHERE userId = ? AND threadId = ?");
    $select->bind_param("ii", $userId, $threadId);
    $select->execute();

    $result = $select->get_result();

    //only taking results if there are availble results
    if($result->num_rows > 0)
    {
        $row = $result->fetch_assoc();
        return $row["reaction"];
    }
    else
    {
        return "";
    }

}


function getThreadReaction($con, $userId, $threadId)
{
    $select = $con->prepare("SELECT * FROM reaction WHERE userId = ? AND threadId = ?");
    $select->bind_param("ii", $userId, $threadId);
    $select->execute();

    return $select->get_result();
}

function addThreadReaction($con, $reaction, $userId, $threadId)
{
    $insert = $con->prepare("INSERT INTO reaction (reaction, userId, threadId, commentId) VALUES (?, ?, ?, NULL)");
    $insert->bind_param("sii", $reaction, $userId, $threadId);
    $insert->execute();
}

function updateThreadReaction($con, $reaction, $userId, $threadId)
{
    $update = $con->prepare("UPDATE reaction SET reaction = ? WHERE userId = ? AND threadId = ?");
    $update->bind_param("sii", $reaction, $userId, $threadId);
    $update->execute();
}

function deleteThreadReaction($con, $userId, $threadId)
{
    $delete = $con->prepare("DELETE FROM reaction WHERE userId = ? AND threadId = ?");
    $delete->bind_param("ii", $userId, $threadId);
    $delete->execute();
}

function getMyThreads($con, $userId)
{
    $select = $con->prepare("
        SELECT thread.threadId, thread.title, thread.content, user.username
        FROM thread
        INNER JOIN user ON thread.userId = user.userId
        WHERE thread.userId = ?
        ORDER BY thread.threadId DESC
    ");

    $select->bind_param("i", $userId);
    $select->execute();

    return $select->get_result();
}

?>