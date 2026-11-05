<?php

try
{
    session_start();

    include "connection.php";
    include "data/reports.php";

    // Check if the user is logged in
    if(!isset($_SESSION["currentUserId"]))
    {
        header("Location: index.html");
        exit;
    }

    // Check if the logged-in user is an admin
    if(!isset($_SESSION["currentRole"]) || $_SESSION["currentRole"] != "admin")
    {
        header("Location: home.php");
        exit;
    }

    // Get values passed by post
    $reportId = $_POST["reportId"];
    $action = $_POST["action"];

    // Get the report details from the database
    $result = getReportDetails($con, $reportId);

    // If the report does not exist, load the error page
    if($result->num_rows == 0)
    {
        header("Location: error.html");
        exit;
    }
    else
    {
        // Get the reported content and user IDs
        $row = $result->fetch_assoc();

        $commentId = $row["commentId"];
        $threadId = $row["threadId"];
        $reportedUserId = $row["reportedUserId"];

        if($action == "dismiss")
        {
            // Update the report status to dismissed
            updateReportStatus($con, $reportId, "dismissed");
        }
        else if($action == "delete")
        {
            // Check if it is a comment report
            if($commentId != NULL)
            {
                // Delete the comment
                deleteReportedComment($con, $commentId);
            }
            else
            {
                // Delete the thread
                deleteReportedThread($con, $threadId);
            }
            
            // Update the report status to resolved
            updateReportStatus($con, $reportId, "resolved");

            
        }
        else if($action == "ban")
        {
            // Ban the reported user
            banReportedUser($con, $reportedUserId);

            // Update the report status to resolved
            updateReportStatus($con, $reportId, "resolved");
        }
        else if($action == "ban_delete")
        {
            // Ban the reported user
            banReportedUser($con, $reportedUserId);

            // Check if it is a comment report
            if($commentId != NULL)
            {
                // Delete the comment
                deleteReportedComment($con, $commentId);
            }
            else
            {
                // Delete the thread
                deleteReportedThread($con, $threadId);
            }

            // Update the report status to resolved
            updateReportStatus($con, $reportId, "resolved");
        }
        else
        {
            header("Location: error.html");
            exit;
        }
    }

    // Close the database connection
    mysqli_close($con);

    // Reload the reports page to show the changes
    header("Location: report.php");
    exit;
}
catch(Throwable $ex)
{
    header("Location: error.html");
    exit;
}

?>