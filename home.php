<html>
    <head>
        <title>Home</title>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <link rel = "stylesheet" type = "text/css" href = "css/styles.css">
    </head>
    <body class = "home-body" >
        <table  id = "layout" cellpadding = "0">
            <tr id = "top-nav-bar">
                <td class = "threadly-title">Threadly</td>
                
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
                            <td>Home</td>
                        </tr>
                    </table>
                </td>
                <td colspan = "2">
                    <div id = "content-area">
                        <table id = "input-area">
                            <tr>
                                <td>
                                    <h3>Ask a question</h3>                              
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

                        <?php
                        
                        include "connection.php";

                        

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
                <td class = 'thread-user'><h4>".$row["username"]."</h4></td>
            </tr>
            <tr>
                <td><h2>".$row["title"]."</h2></td>
            </tr>
            <tr>
                <td class = 'thread-content'>".$row["content"]."</td>
            </tr>
          </table>";
}
                        
                        ?>
                    </div>
                </td>
            </tr>
        </table>
    </body>


</html>