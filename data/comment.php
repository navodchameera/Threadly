<?php

function addComment($con, $content, $threadId, $userId)
{
    $insert = $con->prepare("INSERT INTO comment (content, treadID, userID) VALUES (?, ?, ?)");
    $insert->bind_param("sii", $content, $threadId, $userId);
    $insert->execute();
}


function getThreadComments($con, $threadId)
{
    $select = $con->prepare("
        SELECT comment.commentId, comment.content, user.username
        FROM comment
        INNER JOIN user ON comment.userID = user.userId
        WHERE comment.treadID = ?
        ORDER BY comment.commentId ASC
    ");

    $select->bind_param("i", $threadId);
    $select->execute();

    return $select->get_result();
}

function getCommentReactionsCount($con, $commentId)
{
    $select = $con->prepare("
        SELECT
        COUNT(CASE WHEN reaction = 'like' THEN 1 END) AS likeCount,
        COUNT(CASE WHEN reaction = 'unlike' THEN 1 END) AS unlikeCount,
        COUNT(CASE WHEN reaction = 'love' THEN 1 END) AS loveCount
        FROM reaction
        WHERE commentId = ?
    ");

    $select->bind_param("i", $commentId);
    $select->execute();

    return $select->get_result();
}

function getUserCommentReaction($con, $userId, $commentId)
{
    $select = $con->prepare("SELECT reaction FROM reaction WHERE userId = ? AND commentId = ?");
    $select->bind_param("ii", $userId, $commentId);
    $select->execute();

    $result = $select->get_result();

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

function addCommentReaction($con, $reaction, $userId, $commentId)
{
    $insert = $con->prepare("INSERT INTO reaction (reaction, userId, threadId, commentId) VALUES (?, ?, NULL, ?)");
    $insert->bind_param("sii", $reaction, $userId, $commentId);
    $insert->execute();
}

function updateCommentReaction($con, $reaction, $userId, $commentId)
{
    $update = $con->prepare("UPDATE reaction SET reaction = ? WHERE userId = ? AND commentId = ?");
    $update->bind_param("sii", $reaction, $userId, $commentId);
    $update->execute();
}

function deleteCommentReaction($con, $userId, $commentId)
{
    $delete = $con->prepare("DELETE FROM reaction WHERE userId = ? AND commentId = ?");
    $delete->bind_param("ii", $userId, $commentId);
    $delete->execute();
}



?>