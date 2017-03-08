<?php
    if(isset($_SESSION['LoggedIn']) && isset($_SESSION['Username'])
        && $_SESSION['LoggedIn']==1):
?>
                <p><a href="/logout.php" class="button">Log out</a> <a href="/account.php" class="button">Your Account</a></p>
<?php else: ?>
                <p><a class="button" href="/signup.php">Sign up</a> &nbsp; <a class="button" href="/login.php">Log in</a></p>
<?php endif; ?>