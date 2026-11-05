
<?php

session_start();

if(!isset($_SESSION["currentUserId"], $_SESSION["currrentUsername"], $_SESSION["currentRole"]))
{
    //if there is no user in the current session tell user to log in
    header("Location: index.html");
    exit;
}
else
{
    //if there is a user in the session save it in variables for later use
    $currentUserId = $_SESSION["currentUserId"];
    $currentUsername = $_SESSION["currrentUsername"];
    $currentRole = $_SESSION["currentRole"];
}

//display messages
$message = "";
$messageClass = "";

if(isset($_GET["message"]))
{
    if($_GET["message"] == "username_updated")
    {
        $message = "Username updated successfully!";
        $messageClass = "settings-success";
    }
    else if($_GET["message"] == "password_updated")
    {
        $message = "Password updated successfully!";
        $messageClass = "settings-success";
    }
    else if($_GET["message"] == "username_taken")
    {
        $message = "That username is already taken.";
        $messageClass = "settings-error";
    }
    else if($_GET["message"] == "wrong_password")
    {
        $message = "Your current password is incorrect.";
        $messageClass = "settings-error";
    }
    else if($_GET["message"] == "password_mismatch")
    {
        $message = "The new passwords do not match.";
        $messageClass = "settings-error";
    }
    else if($_GET["message"] == "missing_fields")
    {
        $message = "Please fill in all required fields.";
        $messageClass = "settings-error";
    }
    else if($_GET["message"] == "invalid_action")
    {
        $message = "Invalid settings request.";
        $messageClass = "settings-error";
    }
}

?>

<html>

<head>

    <title>Settings - Threadly</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="css/styles.css">

</head>

<body class="home-body">

    <table id="layout" cellpadding="0">

        <tr id="top-nav-bar">

            <td class="threadly-title">Threadly</td>

            <td></td>

            <td id="username">
                <?php echo htmlspecialchars($currentUsername); ?>
            </td>

            <td id="logout-button-background" align="right">

                <form method="post" action="logout.php">

                    <input type="submit" id="logout-button" value="Logout">

                </form>

            </td>

        </tr>

        <tr>

            <td>

                <table id="left-nav-bar">
                    <tr>

                        <td>

                            <a href="home.php" class="nav-button">Home</a>

                            <a href="mythread.php" class="nav-button">My threads</a>

                            

                            <?php

                            //only admin can see this
                            if($currentRole == "admin")
                            {
                                echo "<a href='report.php' class='nav-button'>Dashboard</a>";
                            }

                            ?>

                            
                        </td>

                    </tr>

                    

                </table>

            </td>

            <td colspan="3">

                <div id="content-area">

                    <div class="settings-container">

                        <h2>Account Settings</h2>

                        <p class="settings-description">
                            Manage your Threadly account details and password.
                        </p>

                        <?php

                        if($message != "")
                        {
                            echo "<p class='settings-message ".$messageClass."'>".htmlspecialchars($message)."</p>";
                        }

                        ?>

                        <!-- Change Username -->

                        <div class="settings-card">

                            <h3>Change Username</h3>

                            <p class="settings-description">
                                Update the username displayed on your Threadly account.
                            </p>

                            <form method="post" action="savesettings.php">

                                <input type="hidden" name="action" value="changeUsername">

                                <label class="settings-label">New Username</label>

                                <input type="text" name="newUsername" class="settings-input" maxlength="50" placeholder="Enter new username" required>

                                <label class="settings-label">Current Password</label>

                                <input type="password" name="currentPassword" class="settings-input" placeholder="Enter current password" required>

                                <input type="submit" value="Update Username" class="settings-button">

                            </form>

                        </div>

                        <!-- Change Password -->

                        <div class="settings-card">

                            <h3>Change Password</h3>

                            <p class="settings-description">
                                Choose a new password to keep your account secure.
                            </p>

                            <form method="post" action="savesettings.php">

                                <input type="hidden" name="action" value="changePassword">

                                <label class="settings-label">Current Password</label>

                                <input type="password" name="currentPassword" class="settings-input" placeholder="Enter current password" required>

                                <label class="settings-label">New Password</label>

                                <input type="password" name="newPassword" class="settings-input" placeholder="Enter new password" required>

                                <label class="settings-label">Confirm New Password</label>

                                <input type="password" name="confirmPassword" class="settings-input" placeholder="Confirm new password" required>

                                <input type="submit" value="Update Password" class="settings-button">

                            </form>

                        </div>

                    </div>

                </div>

            </td>

        </tr>

    </table>

</body>

</html>
