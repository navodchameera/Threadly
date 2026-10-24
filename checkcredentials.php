<?php

session_start();

try
{
    include "connection.php";

    $username = $_POST["username"];
    $password = $_POST["password"];

    $select = $con->prepare("SELECT * FROM user WHERE username = ?");
    $select->bind_param("s", $username);
    $select->execute();

    $result = $select->get_result();

    if($result->num_rows == 1)
    {
        $row = $result->fetch_assoc();

        $userId = $row["userId"];
        $dbPassword = $row["password"];
        $status = $row["status"];

        if(password_verify($password, $dbPassword))
        {
            if($status == "active")
            {

                $_SESSION["currentUserId"] = $userId;
                $_SESSION["currrentUsername"] = $row["username"];

                echo "success";
            }
            else
            {
                echo "banned";
            }
        }
        else
        {
            echo "invalid_credentials";
        }
    }
    else
    {
        echo "invalid_credentials";
    }

    
    mysqli_close($con);
}
catch(Exception $ex)
{
    echo "error";
}

?>