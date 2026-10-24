<?php

include "connection.php";

$title = htmlspecialchars($_POST["title"]);
$content = htmlspecialchars($_POST["content"]);

try
{
    session_start();
    $currentUserId = $_SESSION["CurrentUserId"];

    $insert = $con->prepare("INSERT INTO thread (title, content, userId) VALUES (?,?,?)");
    $insert->bind_param("sss",$title,$content,$currentUserId);
    $insert->execute();
    

    mysqli_close($con);
    //reopens the home to refresh it
    header("Location: home.php");
    
}
catch(Exception $ex)
{
    die("Something went wrong");
}

?>