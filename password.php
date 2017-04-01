<?php
    include_once "common/base.php";
    $pageTitle = "Reset Your Password";
    include_once "common/header.php";
?>
    <link rel="stylesheet" href="yummeStyle.css" type="text/css" />
        <h2>Reset Your Password</h2>
        <p>Enter the email address you signed up with and we'll send
        you a link to reset your password.</p>
        <form action="db-interaction/users.php" method="post">
            <div>
                <input type="hidden" name="action"
                    value="resetpassword" />
                <input type="text" name="email" id="email" />
                <label for="email">Email</label><br /><br />
                <input type="submit" name="reset" id="reset"
                    value="Reset Password" class="button" />
            </div>
        </form>
<?php
    
    include_once "common/close.php";
?>