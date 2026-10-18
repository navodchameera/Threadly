<?php

session_start();

//log out if someone is actually logged in
if(isset($_SESSION["CurrentUserId"]))
{
    unset($_SESSION["CurrentUserId"]);
    unset($_SESSION["CurrentUsername"]);
    session_destroy();
}

header("Location: index.html");

?>