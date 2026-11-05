<?php

$con = mysqli_connect("localhost","root","","threadly");

if(!$con)
{
	header("Location: error.html");
    exit;
}

?>