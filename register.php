<?php

include "connection.php";

$username = htmlspecialchars($_POST["username"]);
$email = htmlspecialchars($_POST["email"]);
$password = password_hash($_POST["password"], PASSWORD_BCRYPT);
$role = "user";
$status = "active";

try
{
    //checks if user enterd username is already taken
    $select = $con->prepare("SELECT * FROM user WHERE username = ?");
    $select->bind_param("s",$username);
    $select->execute();

    $result = $select->get_result();
    //if a record is avaliable in that username
    if($result->num_rows == 0)
    {
        //if there is no user name for that we can register that username
        $insert = $con->prepare("INSERT INTO user(email,username,password,role,status) VALUES (?,?,?,?,?)");
        $insert->bind_param("sssss",$email,$username,$password,$role,$status);
        $insert->execute();
        echo "saved";
        //load the home page
    }
    else
    {
        echo "username_taken";
    }
    
    mysqli_close($con);
    
}
catch(Exception $ex)
{
    header("Location: error.html");
    exit; // Stop executing the remaining PHP
}

?>