<?php

include "connection.php";

$username = $_POST["username"];
$password = $_POST["password"];

try
{
    $select =  $con->prepare("SELECT password FROM user WHERE username = ?");
    $select->bind_param("s",$username);
    $select->execute();

    $result = $select->get_result();

    if($result->num_rows == 1)
    {
        //getting db password
        $row = $result->fetch_assoc();
        //storing db password
        $dbPassword = $row["password"];
        //if user enterd password and dbpassword equals
        if(password_verify($password, $dbPassword))
        {
            echo "loged in Successfully";
        }
        else
        {
            echo "invild username or password";
        }
    }
    else
    {
        echo "invild username or password";
    }

    mysqli_close($con);
}

catch(Exception $ex)
{
    die("Something went wrong");
}

?>