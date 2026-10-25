<?php
try
{
    session_start();

    if(!isset($_SESSION["currentUserId"], $_SESSION["currrentUsername"],$_SESSION["currentRole"]))
    {
        header("Location: index.html");
        exit;
    }
    else
    {
        $currentUserId = $_SESSION["currentUserId"];
        $currentUsername = $_SESSION["currrentUsername"];
        $currentRole = $_SESSION["currentRole"];
        //if current user is not a admin they can not access this page so send them to home
        if($currentRole != "admin")
        {
            header("Location: home.php");
            exit;
        }
    }

    include "connection.php";
    include "data/reports.php";

    //defults 
    $sort = "new";
    $type = "all";

    //if get method gets a new value and its "most"
    if(isset($_GET["sort"]) && $_GET["sort"] === "most")
    {
        $sort = "most";
    }

    if(isset($_GET["type"]))
    {
        $type = $_GET["type"];
    }

    $newClass = "";
    $mostClass = "";
    $allClass = "";
    $threadsClass = "";
    $commentsClass = "";

    if($sort == "new")
    {
        $newClass = "report-filter-active";
    }
    else
    {
        $mostClass = "report-filter-active";
    }

    if($type == "all")
    {
        $allClass = "report-filter-active";
    }
    else if($type == "threads")
    {
        $threadsClass = "report-filter-active";
    }
    else
    {
        $commentsClass = "report-filter-active";
    }

?>


<html>
    <head>
        <title>Reports - Threadly</title>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="css/styles.css">
        <script type = "text/javascript" src = "js/reportactionspopup.js"></script>
    </head>

    <body class="home-body">

        <dialog id="actionDialog" class="report-popup">
            <form method="post" action="reportaction.php">
                <h3 id="actionTitle">Confirm action</h3>
                <p id="actionMessage"></p>

                <input type="hidden" name="reportId" id="actionReportId">
                <input type="hidden" name="action" id="selectedAction">
                
                
                <div class="report-popup-buttons">
                    <input type="button" value="Cancel" class="report-cancel-button" onclick="closeActionDialog()">
                    <input type="submit" value="Confirm" id="actionConfirmButton" class="report-submit-button">
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
                            <td><a href="home.php" class="nav-button">Home</a></td>
                        </tr>
                        <tr>
                            <td><a href="home.php" class="nav-button">My Threads</a></td>
                        </tr>
                        <tr>
                            <td><a href="home.php#input-area" class="nav-button">Create a Thread</a></td>
                        </tr>
                        <tr>
                            <td><a href="reports.php" class="nav-button-active">Reports</a></td>
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
                        <table class="reports-heading">
                            <tr>
                                <td>
                                    <h2>Pending Reports</h2>
                                    <p>Review reported content and choose an action.</p>
                                </td>
                            </tr>

                            <tr>
                                <td class="report-filters">
                                    <?php

                                    echo "<a href='reports.php?sort=new&type=".$type."' class='report-filter ".$newClass."'>New</a>
                                          <a href='reports.php?sort=most&type=".$type."' class='report-filter ".$mostClass."'>Most</a>

                                          <a href='reports.php?sort=".$sort."&type=all' class='report-filter ".$allClass."'>All</a>
                                          <a href='reports.php?sort=".$sort."&type=threads' class='report-filter ".$threadsClass."'>Threads</a>
                                          <a href='reports.php?sort=".$sort."&type=comments' class='report-filter ".$commentsClass."'>Comments</a>";

                                    ?>
                                </td>
                            </tr>
                        </table>

                        <?php

                        $result = getPendingReports($con, $sort, $type);

                        if($result->num_rows > 0)
                        {
                            while($row = $result->fetch_assoc())
                            {
                                $reportId = $row["reportId"];
                                $parentThreadId = $row["parentThreadId"];
                                $threadId = $row["threadId"];
                                $commentId = $row["commentId"];
                                $reportedUserId = $row["reportedUserId"];
                                

                                //at the start report type is thread
                                $reportType = "Thread";
                                $contentLabel = "Reported thread";

                                //if comment id is not null that means its a comment report..
                                //so change the report type to comment
                                if($commentId != NULL)
                                {
                                    $reportType = "Comment";
                                    $contentLabel = "Thread containing this comment";
                                }

                                echo "<table class='thread-card report-card'>
                                    <tr>
                                        <td>
                                            <span class='report-type'>".$reportType." report</span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class='report-data'>
                                            <p>Reported by: <strong>".htmlspecialchars($row["reporterName"])."</strong></p>
                                            <p>Reported user: <strong>".htmlspecialchars($row["reportedUsername"])."</strong></p>
                                            <p>Pending reports on this content: <strong>".$row["reportCount"]."</strong></p>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <div class='report-reason'>
                                                <h4>Reason</h4>
                                                <p>".nl2br(htmlspecialchars($row["reason"]))."</p>
                                            </div>
                                        </td>
                                    </tr>";

                                // For comment reports display the comment first
                                //If comment id is not null that means its a comment report
                                if($row["commentId"] != NULL)
                                {
                                    echo "<tr>
                                        <td>
                                            <div class='reported-comment'>
                                                <h4>Reported comment</h4>
                                                <p>".nl2br(htmlspecialchars($row["commentContent"]))."</p>
                                            </div>
                                        </td>
                                    </tr>";
                                }

                                echo "<tr>
                                    <td>
                                        <div class='reported-thread'>
                                            <h4>".$contentLabel."</h4>
                                            <h3>".htmlspecialchars($row["threadTitle"])."</h3>
                                            <p>".nl2br(htmlspecialchars($row["threadContent"]))."</p>
                                            <a href='thread.php?threadId=".$parentThreadId."' class='thread-back-link'>View Thread</a>
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td class='report-actions'>
                                        <form method='post' action='reportaction.php'>
                                            
                                            <input type='hidden' name='reportId' value='".$reportId."'>
                                            <input type='hidden' name='reportedUserId' value='".$reportedUserId."'>
                                            <input type='submit' value='Dismiss Report' class='report-action report-dismiss' onclick='openActionDialog(".$reportId.", \"dismiss\"); return false;'>
                                                <input type='submit' value='Delete Content' class='report-action report-delete' onclick='openActionDialog(".$reportId.", \"delete\"); return false;'>";
                                             //stops banning admins
                                            if($row["reportedUserRole"] != "admin")
                                            {
                                                echo "<input type='submit' value='Ban User' class='report-action report-ban' onclick='openActionDialog(".$reportId.", \"ban\"); return false;'>
                                                    <input type='submit' value='Ban + Delete Content' class='report-action report-ban-delete' onclick='openActionDialog(".$reportId.", \"ban_delete\"); return false;'>";
                                            }

                                echo "</form>
                                    </td>
                                </tr>
                                </table>";
                            }
                        }
                        else
                        {
                            echo "<table class='thread-card'>
                                <tr>
                                    <td class='report-empty'>No pending reports found.</td>
                                </tr>
                            </table>";
                        }

                        mysqli_close($con);

                        ?>
                    </div>
                </td>
            </tr>
        </table>
    </body>
</html>

<?php

}
catch(Throwable $ex)
{

    die(htmlspecialchars($ex->getMessage()));

    header("Location: error.html");
    exit;
}

?>