<?php



try
{
    session_start();
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
        $role = $row["role"];
        $status = $row["status"];

        if(password_verify($password, $dbPassword))
        {
            if($status == "active")
            {
                session_regenerate_id(true);

                $_SESSION["CurrentUserId"] = $userId;
                $_SESSION["CurrrentUsername"] = $row["username"];

                mysqli_close($con);

                header("Location: home.php");
                exit;
            }
            else
            {
                echo "Your account is banned. Please contact an administrator.";
            }
        }
        else
        {
            echo "Invalid username or password.";
        }
    }
    else
    {
        echo "Invalid username or password.";
    }

    mysqli_close($con);
}
catch(Exception $ex)
{
    header("Location: error.html");
    exit; // Stop executing the remaining PHP
}

?>