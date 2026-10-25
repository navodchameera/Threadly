<?php

session_start();

if(!isset($_SESSION["currentUserId"], $_SESSION["currrentUsername"],$_SESSION["currentRole"]))
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

//search is empty at the begining
$search = "";

if(isset($_GET["search"]) && is_string($_GET["search"]))
{
    $search = trim($_GET["search"]);
}


?>

<html>
    <head>
        <title>Home - Threadly</title>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="css/styles.css?v=7">
        <script type="text/javascript" src="js/validatethread.js"></script>
    </head>

    <body class="home-body">

        <dialog id="reportPopup" class="report-popup">
            <form method="post" action="savereport.php">
                <h3>Report</h3>

                <input type="hidden" name="threadId" id="reportThreadId">
                <input type="hidden" name="commentId" id="reportCommentId">

                <label for="reportReason">Why are you reporting this?</label>
                <textarea name="reason" id="reportReason" maxlength="100" placeholder="Enter your reason..." required></textarea>

                <div class="report-popup-buttons">
                    <button type="button" class="reaction-button" onclick="closeReport()">Cancel</button>
                    <button type="submit" class="reaction-button reaction-love-selected">Submit Report</button>
                </div>
            </form>
        </dialog>

        <table id="layout" cellpadding="0">
            <tr id="top-nav-bar">
                <td class="threadly-title">Threadly</td>

                <td>
                    <form id="searchForm" method="get" action="home.php">
                        <input type="search" name="search" placeholder="Search threads..." class="search-bar" value="<?php echo htmlspecialchars($search); ?>">
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
                        <?php
                            //only admin can see this
                            if($currentRole == "admin")
                            {
                                echo "<tr>
                                        <td><a href='report.php' class='nav-button'>Reports</a></td>
                                      </tr>";
                            }
                        ?>
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
                        <form name="formThread" method="post" action="savethread.php" onsubmit="return validateThread();">
                            <table id="input-area">
                                <tr>
                                    <td>
                                        <h2>&nbsp;&nbsp;&nbsp;Ask a question</h2>
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <p id="errorTitle" class="error"></p>
                                        <input type="text" name="title" class="textbox" maxlength="300" placeholder="Title: what are you trying to solve?" id="title-textbox">
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <p id="errorContent" class="error"></p>
                                        <textarea name="content" rows="5" maxlength="10000" placeholder="Describe your problem..." id="content-textbox"></textarea>
                                    </td>
                                </tr>

                                <tr>
                                    <td align="right">
                                        <input type="submit" value="Post" class="post-button">
                                    </td>
                                </tr>
                            </table>
                        </form>

                        <?php

                        try
                        {
                            include "connection.php";
                            include "data/threads.php";
                            include "data/reactions.php";

                            $result = getAllThreads($con, $search);

                            if($result->num_rows > 0)
                            {
                                while($row = $result->fetch_assoc())
                                {
                                    $threadId = $row["threadId"];

                                    // Get reaction counts for this thread
                                    $reactionResult = getReactionsCount($con, $threadId);
                                    $reationRow = $reactionResult->fetch_assoc();

                                    $likeClass = "";
                                    $unlikeClass = "";
                                    $loveClass = "";

                                    // Get the current user's selected reaction
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

                                    echo "<a href='thread.php?threadId=".$threadId."' class = 'link'>
                                    <table class='thread-card'>
                                        <tr>
                                            <td class='user-image-thread-background'>
                                                <img src='img/userimage.png' alt='User' height='30' width='30'>
                                            </td>
                                            <td>
                                                <h4 class='thread-user'>".htmlspecialchars($row["username"])."</h4>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan='2'>
                                                <h2>".htmlspecialchars($row["title"])."</h2>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan='2' class='thread-content'>
                                                
                                                <p>"
                                                //nl2br() adds html <br> tags at newline so text keeps its line breaks when displayed.
                                                .nl2br(htmlspecialchars($row["content"])).
                                                "</p>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan='2' class='thread-reations'>
                                                <button type='submit' name='reaction' value='like' class='reaction-button ".$likeClass."'>Like (".$reationRow["likeCount"].")</button>
                                                <button type='submit' name='reaction' value='unlike' class='reaction-button ".$unlikeClass."'>Unlike (".$reationRow["unlikeCount"].")</button>
                                                <button type='submit' name='reaction' value='love' class='reaction-button ".$loveClass."'>Love (".$reationRow["loveCount"].")</button>
                                            </td>
                                        </tr>
                                    </table>
                                    </a>";
                                }
                            }
                            else
                            {
                                echo "<table class='thread-card'>
                                    <tr>
                                        <td class='thread-empty-comments'>No threads found.</td>
                                    </tr>
                                </table>";
                            }

                            mysqli_close($con);
                        }
                        catch(Exception $ex)
                        {

                            error_log($ex->getMessage());
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