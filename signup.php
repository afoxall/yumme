<?php
    include_once "common/base.php";
    $pageTitle = "Register";
    include_once "common/header.php";
    include_once "inc/constants.inc.php";

    if(!empty($_POST['email'])):
        include_once "inc/class.users.inc.php";
        $users = new UserManager($db);
        echo $users->createAccount();
    else:
?>
 
        <h2>Sign up</h2>
        <form method="post" action="signup.php" id="registerform">
            <div>
                <label for="email">Email:</label>
                <input type="text" name="email" id="email" /><br />
                <label for="username">Username:</label>
                <input type="text" name="uname" id="uname" /><br />
                <input type="submit" name="register" id="register" value="Sign up" />
            </div>
        </form>
 
<?php
    endif;
    include_once 'common/close.php';
?>