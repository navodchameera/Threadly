<html>
    <head>
        <title>Home</title>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <link rel = "stylesheet" type = "text/css" href = "css/styles.css">
        <script type = "text/javascript" src="js/validatethread.js"></script>
    </head>
    <body class = "home-body" >
        <table  id = "layout" cellpadding = "0>
            <tr id = "top-nav-bar">
                <td class = "threadly-title">Threadly</td>
                <form method="GET">
                    <td>
                            <input type="search" name="search" placeholder="Search threads..." class = "search-bar">
                    </td>
                    <td>
                        <button type="submit" class = "search-button">Search</button>
                    </td>
                </form>
                
                <?php
                    try
                    {
                        session_start();
                        $currentUsername = $_SESSION["currrentUsername"];
                        $currentUserId = $_SESSION["currentUserId"];
                        echo "<td id='username'>".$currentUsername."</td>";
                    }
                    catch(Exception $ex)
                    {
                        echo "Something went wrong";
                    }
                    
                ?>
                <td id = "logout-button-background" align = "right">
                    <form method ="post" action="logout.php">
                        <input type="submit" action = "logut.php" id = "logout-button" value = "Logout"">
                    </form>
                </td> 
            </tr>
            <tr>
                <td>
                    <table id = "left-nav-bar">
                        <tr>
                            <td height = '200px'></td>
                        </tr>
                        <tr>
                            <!--currently in home so home anchor in active-->
                            <td><a href = "home.php" class = "nav-button-active">Home</a></td>
                        </tr>
                        <tr>
                            <td><a href = "home.php" class = "nav-button">My Threads</a></td>
                        </tr>
                        <tr>
                            <td><a href = "home.php" class = "nav-button">Create a Thread</a></td>
                        </tr>
                        <tr>
                            <td><a href = "home.php" class = "nav-button">Settings</a></td>
                        </tr>
                        <tr>
                            <td height = "100px"></td>
                        </tr>
                    </table>
                </td>
                <td colspan = "4">
                    <div id = "content-area">
                        <form name = "formThread" method = "post" action = "savethread.php" onsubmit="return validateThread();">
                            <table id = "input-area">
                                <tr>
                                    <td>
                                        <h2>&nbsp;&nbsp;&nbsp;Ask a question</h2>                              
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p id="errorTitle" class="error"></p>
                                        <input type="text" name="title" class="textbox" maxlength="300" placeholder="Title: what are you trying to solve?" id = "title-textbox">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p id="errorContent" class="error"></p>
                                        <textarea name="content" rows="5" maxlength="10000" placeholder="Describe your problem..." id = "content-textbox"></textarea>
                                        
                                    </td>
                                </tr>
                                <tr>
                                    <td align = "right">
                                        <input type = "submit" value = "Post" class = "post-button">
                                    </td>
                                </tr>
                            </table>
                        </form>
                        

                        <?php
                        
                        include "connection.php";
                        include "data/reactions.php";

                        try
                        {
                            $select = "SELECT thread.threadId, thread.title, thread.content, user.username
                                FROM thread INNER JOIN user 
                                ON thread.userId = user.userId
                                ORDER BY thread.threadId DESC";   //newest thread first
                            $result = $con->query($select);

                            $count = $result->num_rows;

                            while($row = $result->fetch_assoc())
                            {
                                $threadId =$row["threadId"];
                                //getting reaction counts of each thread
                                $reactionResult = getReactionsCount($con,$threadId);
                                $reationRow = $reactionResult->fetch_assoc();


                                $likeClass = "";
                                $unlikeClass = "";
                                $loveClass = "";

                                //getiing user reaction for the tread
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

                                echo "<a href = 'thread.php?threadId=".$threadId."' class = 'link'>
                                    <table class = 'thread-card'>
                                            <tr>
                                                <td class = 'user-image-thread-background'><img src = 'img/userimage.png' alt = 'user' height = '30px' width = '30px'></td>
                                                <td><h4 class = 'thread-user'>".$row["username"]."</h4></td>
                                            </tr>
                                            <tr>
                                                <td colspan = '2'><h2>".$row["title"]."</h2></td>
                                            </tr>
                                            <tr>
                                                <td colspan = '2' class = 'thread-content'><p>".$row["content"]."</p></td>
                                            </tr>
                                            <tr>
                                                <td colspan = '2' class = 'thread-reations'>
                                                    
                                                    <input type='submit' name='reaction' value='like' class='reaction-button ".$likeClass."'>&nbsp;(".$reationRow["likeCount"].")&nbsp;&nbsp;
                                                    <input type='submit' name='reaction' value='unlike' class='reaction-button ".$unlikeClass."'>&nbsp;(".$reationRow["unlikeCount"].")&nbsp;&nbsp;
                                                    <input type='submit' name='reaction' value='love' class='reaction-button ".$loveClass."'>&nbsp;(".$reationRow["loveCount"].")&nbsp;&nbsp;
                                                </td>
                                            </tr>
                                        </table>
                                    </a>";


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