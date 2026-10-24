<?php

session_start();

//log out if someone is actually logged in
if(isset($_SESSION["currentUserId"]))
{
    unset($_SESSION["currentUserId"]);
    unset($_SESSION["currentUsername"]);
    session_destroy();
}

header("Location: index.html");

?>