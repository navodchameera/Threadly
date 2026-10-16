<?php

include "connection.php";

$username = $_POST["username"];
$password = password_hash($_POST["password"],PASSWORD_BCRYPT);

try
{
    $select =  $con->prepare("SELECT * FROM user WHERE username = ? AND password = ?");
    $select->bind_param("ss",$username,$password);
    $select->execute();

    $result = $select->get_result();

    if($result->num_rows == 1)
    {
        echo "hoi";
    }
    else
    {
        
    }

    mysqli_close($con);
}
catch(Exception $ex)
{
    die("Something went wrong");
}

?>