<html>
    <head>
        <title>Home</title>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <link rel = "stylesheet" type = "text/css" href = "css/styles.css">
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
                        $currentUsername = $_SESSION["CurrrentUsername"];
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
                        <form method = "post" action = "savethread.php">
                            <table id = "input-area">
                                <tr>
                                    <td>
                                        <h2>&nbsp;&nbsp;&nbsp;Ask a question</h2>                              
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="text" name="title" class="textbox" maxlength="300" placeholder="Title: what are you trying to solve?" id = "title-textbox">
                                    </td>
                                </tr>
                                <tr>
                                    <td>
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
                                echo "<table class = 'thread-card'>
                                        <tr>
                                            <td class = 'user-image-thread-background'><img src = 'img/userimage.png' alt = 'user' height = '30px' width = '30px'></td>
                                            <td><h4 class = 'thread-user'>".$row["username"]."</h4></td>
                                        </tr>
                                        <tr>
                                            <td colspan = '2'><h2>".$row["title"]."</h2></td>
                                        </tr>
                                        <tr>
                                            <td colspan = '2' class = 'thread-content'>".$row["content"]."</td>
                                        </tr>
                                    </table>";
                            }
                            mysqli_close($con);
                        }  
                        catch(Exception $ex)
                        {
                            die("Something went wrong");
                        }                      
                                                
                        ?>
                    </div>
                </td>
            </tr>
        </table>
    </body>


</html>