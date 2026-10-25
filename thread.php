<?php

session_start();

if(!isset($_SESSION["currentUserId"], $_SESSION["currrentUsername"]))
{
    //if thres no user in the current session tell user to log in
    header("Location: index.html");
    exit;
}
else
{
    //if thers is a user in the session save it in varibales for later use
    $currentUserId = $_SESSION["currentUserId"];
    $currentUsername = $_SESSION["currrentUsername"];
    $currentRole = $_SESSION["currentRole"];

}

if(isset($_GET["threadId"]))
{
    $threadId = $_GET["threadId"];
}

?>

<html>
    <head>
        <title>Thread - Threadly</title>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="css/styles.css">
        <script type="text/javascript" src="js/validatecomment.js"></script>
        <script type = "text/javascript" src = "js/reportpopup.js"></script>
        <script type = "text/javascript" src = "js/validatereport.js"></script>
    </head>

    <body class="home-body">

        <dialog id="reportPopup" class="report-popup">
            <form method="post" name = "formReport" action="savereport.php" onsubmit="return validateReport()">
                <h3>Report</h3>

                <input type="hidden" name="threadId" id="reportThreadId">
                <input type="hidden" name="commentId" id="reportCommentId">
                <?php
                    //setting currentuserr id to send
                    echo "<input type='hidden' name='userId' value='".$currentUserId."'>
                        <input type='hidden' name='returnTo' value='".$threadId."'>";
                ?>
                <h5>Why are you reporting this?</h5>
                <textarea name="reason" id="reportReason" maxlength="500" placeholder="Enter your reason..."></textarea>
                <p id="errorReason" class="error"></p>
                <div class="report-popup-buttons">
                    <input type="button" class="report-cancel-button" onclick="closeReport()" value = "Cancel">
                    <input type="submit" class="report-submit-button" id = "reportButton" value = "Submit Report">
                </div>
            </form>
        </dialog>

        <table id="layout" cellpadding="0">
            <tr id="top-nav-bar">
                <td class="threadly-title">Threadly</td>

                <td>
                    <form id="searchForm" method="get" action="home.php">
                        <input type="search" name="search" placeholder="Search threads..." class="search-bar">
                    </form>
                </td>

                <td>
                    <button type="submit" form="searchForm" class="search-button">Search</button>
                </td>

                <?php
                    echo "<td id='username'>".htmlspecialchars($currentUsername)."</td>";
                ?>

                <td id="logout-button-background" align="right">
                    <form method="post" action="logout.php">
                        <input type="submit" id="logout-button" value="Logout">
                    </form>
                </td>
            </tr>

            <tr>
                <td>
                    <table id="left-nav-bar">
                        <tr>
                            <td height="200px"></td>
                        </tr>
                        <tr>
                            <td><a href="home.php" class="nav-button-active">Home</a></td>
                        </tr>
                        <tr>
                            <td><a href="home.php" class="nav-button">My Threads</a></td>
                        </tr>
                        <tr>
                            <td><a href="home.php#input-area" class="nav-button">Create a Thread</a></td>
                        </tr>
                        <tr>
                            <?php
                                //only admin can see this
                                if($currentRole == "admin")
                                {
                                    echo "<tr>
                                            <td><a href='report.php' class='nav-button'>Reports</a></td>
                                        </tr>";
                                }
                            ?>
                        </tr>
                        <tr>
                            <td><a href="home.php" class="nav-button">Settings</a></td>
                        </tr>
                        <tr>
                            <td height="100px"></td>
                        </tr>
                    </table>
                </td>

                <td colspan="4">
                    <div id="content-area">
                        <table class="thread-page-heading">
                            <tr>
                                <td>
                                    <a href="home.php" class="thread-back-link">Back to Home</a>
                                </td>
                            </tr>
                        </table>

                        <?php

                        try
                        {
                            include "connection.php";
                            include "data/threads.php";
                            include "data/reactions.php";
                            include "data/comment.php";

                            $result = getThread($con, $threadId);

                            if($result->num_rows == 1)
                            {
                                $row = $result->fetch_assoc();

                                $reactionResult = getReactionsCount($con, $threadId);
                                $reationRow = $reactionResult->fetch_assoc();

                                $likeClass = "";
                                $unlikeClass = "";
                                $loveClass = "";

                                $userReaction = getUserReaction($con, $currentUserId, $threadId);

                                if($userReaction == "like")
                                {
                                    $likeClass = "reaction-like-selected";
                                }
                                else if($userReaction == "unlike")
                                {
                                    $unlikeClass = "reaction-unlike-selected";
                                }
                                else if($userReaction == "love")
                                {
                                    $loveClass = "reaction-love-selected";
                                }
                                

                                echo "<table class='thread-card thread-detail-card'>
                                    <tr>
                                        <td class='user-image-thread-background'>
                                            <img src='img/userimage.png' alt='User' height='30' width='30'>
                                        </td>
                                        <td>
                                            <details class='more-menu'>
                                                <summary class='more-button'>⋮</summary>
                                                <div class='more-menu-items'>
                                                    <input type='button' class='report-menu-button' onclick='openReport(".$threadId.", 0)' value='Report Thread'>
                                                </div>
                                            </details>
                                            <h4 class='thread-user'>".htmlspecialchars($row["username"])."</h4>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan='2'>
                                            <h2 class='thread-detail-title'>".htmlspecialchars($row["title"])."</h2>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan='2' class='thread-content'>
                                            <p>".nl2br(htmlspecialchars($row["content"]))."</p>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan='2' class='thread-reations'>
                                            <form method='post' action = 'reactaction.php'>
                                                <input type = 'hidden' name ='currentUser' value='".$currentUserId."'>
                                                <input type = 'hidden' name = 'threadId' value ='".$threadId."'>
                                                <input type = 'hidden' name = 'userReacion' value = '".$userReaction."'>
                                                <button type='submit' name='reaction' value='like' class='reaction-button ".$likeClass."'>Like (".$reationRow["likeCount"].")</button>
                                                <button type='submit' name='reaction' value='unlike' class='reaction-button ".$unlikeClass."'>Unlike (".$reationRow["unlikeCount"].")</button>
                                                <button type='submit' name='reaction' value='love' class='reaction-button ".$loveClass."'>Love (".$reationRow["loveCount"].")</button>
                                                
                                            </form>
                                        </td>
                                    </tr>
                                </table>";

                                echo "<form name='formComment' method='post' action='savecomment.php' onsubmit = 'return validatecomments()'>
                                    <input type='hidden' name='threadId' value='".$threadId."'>
                                    <input type= 'hidden' name = 'currentUserId' value='".$currentUserId."'>
                                    <table id='input-area'>
                                        <tr>
                                            <td>
                                                <h3>Add a comment</h3>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <textarea name='comment' id='content-textbox' rows='4' maxlength='10000' placeholder='Write your comment...'></textarea>
                                                <p id = 'errorComment' class = 'error'></p>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td align='right'>
                                                <input type='submit' value='Post' class='post-button'>
                                            </td>
                                        </tr>
                                    </table>
                                </form>";

                                // Get only comments belonging to this thread
                                $commentResult = getThreadComments($con, $threadId);
                                $commentCount = $commentResult->num_rows;

                                echo "<table class='thread-comments-heading'>
                                    <tr>
                                        <td><h3>Comments (".$commentCount.")</h3></td>
                                    </tr>
                                </table>";

                                if($commentCount > 0)
                                {
                                    while($commentRow = $commentResult->fetch_assoc())
                                    {
                                        $commentId = $commentRow["commentId"];

                                        // Get reaction counts for this comment
                                        $commentReactionResult = getCommentReactionsCount($con, $commentId);
                                        $commentReactionRow = $commentReactionResult->fetch_assoc();

                                        $commentLikeClass = "";
                                        $commentUnlikeClass = "";
                                        $commentLoveClass = "";

                                        // Get the current user's reaction
                                        $userCommentReaction = getUserCommentReaction($con, $currentUserId, $commentId);

                                        if($userCommentReaction == "like")
                                        {
                                            $commentLikeClass = "reaction-like-selected";
                                        }
                                        else if($userCommentReaction == "unlike")
                                        {
                                            $commentUnlikeClass = "reaction-unlike-selected";
                                        }
                                        else if($userCommentReaction == "love")
                                        {
                                            $commentLoveClass = "reaction-love-selected";
                                        }

                                        echo "<table class='thread-card comment-card' id='comment-".$commentId."'>
                                            <tr>
                                                <td class='user-image-thread-background'>
                                                    <img src='img/userimage.png' alt='User' height='30' width='30'>
                                                </td>
                                                <td>
                                                    <details class='more-menu'>
                                                        <summary class='more-button' aria-label='Thread options'>&#8942;</summary>
                                                        <div class='more-menu-items'>
                                                            <input type='button' class='report-menu-button' onclick='openReport(0,".$commentId.")' value='Report Comment'>
                                                        </div>
                                                    </details>
                                                    <h4 class='thread-user'>".htmlspecialchars($commentRow["username"])."</h4>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td colspan='2' class='thread-content'>
                                                    <p>".nl2br(htmlspecialchars($commentRow["content"]))."</p>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td colspan='2' class='thread-reations'>
                                                    <form method='post' action='commentreaction.php'>
                                                        <input type='hidden' name='commentId' value='".$commentId."'>
                                                        <input type='hidden' name='currentUserId' value='".$currentUserId."'>
                                                        <input type= 'hidden' name = 'userCommentReaction' value='".$userCommentReaction."'>
                                                        <input type='hidden' name='threadId' value='".$threadId."'>

                                                        <button type='submit' name='reaction' value='like' class='reaction-button ".$commentLikeClass."'>Like (".$commentReactionRow["likeCount"].")</button>
                                                        <button type='submit' name='reaction' value='unlike' class='reaction-button ".$commentUnlikeClass."'>Unlike (".$commentReactionRow["unlikeCount"].")</button>
                                                        <button type='submit' name='reaction' value='love' class='reaction-button ".$commentLoveClass."'>Love (".$commentReactionRow["loveCount"].")</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        </table>";
                                    }
                                }
                                else
                                {
                                    echo "<table class='thread-card comment-card'>
                                        <tr>
                                            <td class='thread-empty-comments'>No comments yet.</td>
                                        </tr>
                                    </table>";
                                }
                            }
                            else
                            {
                                header("Location: error.html");
                                exit; // Stop executing the remaining PHP
                            }
                            

                            mysqli_close($con);
                        }
                        catch(Exception $ex)
                        {
                            header("Location: error.html");
                            exit; // Stop executing the remaining PHP
                        }

                        ?>
                    </div>
                </td>
            </tr>
        </table>
    </body>
</html>