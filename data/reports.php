<?php


function getPendingReports($con, $sort, $type)
{
    $sql = "
        SELECT report.reportId, report.reason,
               report.threadId, report.commentId,
               reporter.username AS reporterName,
               reportedUser.userId AS reportedUserId,
               reportedUser.username AS reportedUsername,
               reportedUser.status AS reportedUserStatus,
               reportedUser.role AS reportedUserRole,
               comment.content AS commentContent,
               parentThread.threadId AS parentThreadId,
               parentThread.title AS threadTitle,
               parentThread.content AS threadContent,
               (
                   SELECT COUNT(*)
                   FROM report AS otherReport
                   WHERE otherReport.status = 'pending'
                   AND (
                       (report.commentId IS NOT NULL
                        AND otherReport.commentId = report.commentId)
                       OR
                       (report.commentId IS NULL
                        AND otherReport.commentId IS NULL
                        AND otherReport.threadId = report.threadId)
                   )
               ) AS reportCount
        FROM report
        INNER JOIN user AS reporter ON report.userId = reporter.userId
        LEFT JOIN comment ON report.commentId = comment.commentId
        INNER JOIN thread AS parentThread
            ON parentThread.threadId = COALESCE(comment.treadID, report.threadId)
        INNER JOIN user AS reportedUser
            ON reportedUser.userId = COALESCE(comment.userID, parentThread.userId)
        WHERE report.status = 'pending'
    ";

    if($type == "threads")
    {
        $sql .= " AND report.commentId IS NULL";
    }
    else if($type == "comments")
    {
        $sql .= " AND report.commentId IS NOT NULL";
    }

    if($sort == "most")
    {
        $sql .= " ORDER BY reportCount DESC, report.reportId DESC";
    }
    else
    {
        $sql .= " ORDER BY report.reportId DESC";
    }

    // Only fixed SQL strings above are added to the query
    $select = $con->prepare($sql);
    $select->execute();

    return $select->get_result();
}

function getReportDetails($con, $reportId)
{
    $select = $con->prepare("
        SELECT report.commentId, report.threadId,
               COALESCE(comment.userID, thread.userId) AS reportedUserId
        FROM report
        LEFT JOIN comment ON report.commentId = comment.commentId
        LEFT JOIN thread ON report.threadId = thread.threadId
        WHERE report.reportId = ?
    ");

    $select->bind_param("i", $reportId);
    $select->execute();

    return $select->get_result();
}


function updateReportStatus($con, $reportId, $status)
{
    $update = $con->prepare("UPDATE report SET status = ? WHERE reportId = ?");
    $update->bind_param("si", $status, $reportId);
    $update->execute();
}

function banReportedUser($con, $userId)
{
    $update = $con->prepare("UPDATE user SET status = 'banned' WHERE userId = ? AND role <> 'admin'");
    $update->bind_param("i", $userId);
    $update->execute();
}

function deleteReportedThread($con, $threadId)
{
    $delete = $con->prepare("DELETE FROM thread WHERE threadId = ?");
    $delete->bind_param("i", $threadId);
    $delete->execute();
}

function deleteReportedComment($con, $commentId)
{
    $delete = $con->prepare("DELETE FROM comment WHERE commentId = ?");
    $delete->bind_param("i", $commentId);
    $delete->execute();
}

?>