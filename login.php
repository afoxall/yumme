<?php
    include_once "common/base.php";
    $pageTitle = "Home";
    include_once "common/header.php";
    include_once "inc/constants.inc.php";

    if(!empty($_SESSION['LoggedIn']) && !empty($_SESSION['Email'])):
?>
 
        <p>You are currently <strong>logged in.</strong></p>
<?php
    $url = BASE_URL . "/logout.php";
    echo "<p><a href='$url'>Log out</a></p>";

    elseif(!empty($_POST['email']) && !empty($_POST['password'])):
        include_once 'inc/class.users.inc.php';
        $users = new UserManager($db);
        if($users->accountLogin()==TRUE):
            header("Location: /".BASE_URL . "/index.php");
            exit;
        else:
?>
 
        <h2>Login Failed&mdash;Try Again?</h2>
        <form method="post" action="login.php" name="loginform" id="loginform">
            <div>
                <input type="text" name="email" id="email" />
                <label for="email">Email</label>
                <br /><br />
                <input type="password" name="password" id="password" />
                <label for="password">Password</label>
                <br /><br />
                <input type="submit" name="login" id="login" value="Login" class="button" />
            </div>
        </form>
<?php
        $url = BASE_URL . "/password.php";
        echo "<p><a href= '$url'>Did you forget your password?</a></p>";

        endif;
    else:
?>
 
        <h2>Let's get cooking!</h2>
        <form method="post" action="login.php" name="loginform" id="loginform">
            <div>
                <input type="text" name="email" id="email" />
                <label for="email">Email</label>
                <br /><br />
                <input type="password" name="password" id="password" />
                <label for="password">Password</label>
                <br /><br />
                <input type="submit" name="login" id="login" value="Login" class="button" />
            </div>
        </form><br /><br />
<?php
        $url = BASE_URL . "/password.php";
        echo "<p><a href='$url'>Did you forget your password?</a></p>";

    endif;
?>
 
        <div style="clear: both;"></div>
<?php

?>