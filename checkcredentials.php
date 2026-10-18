<?php

include "connection.php";

$username = $_POST["username"];

$password = $_POST["password"];

try
{
    $select =  $con->prepare("SELECT * FROM user WHERE username = ?");
    $select->bind_param("s",$username);
    $select->execute();

    $result = $select->get_result();

    if($result->num_rows == 1)
    {
        //getting db password
        $row = $result->fetch_assoc();
        //getting user details
        $userId = $row["userId"];
        //storing db password
        $dbPassword = $row["password"];
        $role = $row["role"];
        //if user enterd password and dbpassword equals
        if(password_verify($password, $dbPassword))
        {
            echo "loged in Successfully";
            session_start();
            $_SESSION["CurrentUserId"] = $userId;
            $_SESSION["CurrrentUsername"] = $username;
            if($role == "admin")
            {
                header("Location: home.php");
            }
            else
            {
                header("Location: home.php");
            }

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
    die($ex->getMessage());
}

?>