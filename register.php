<?php
include_once "common/base.php";
$pageTitle = "Verify Your Account";


//echo "<div style=\"color:black; text-align:center;\">". $_POST['v']."</div>";

if(isset($_GET['v']) && isset($_GET['e']))
{
    include_once "inc/class.users.inc.php";
    $users = new UserManager($db);
    $ret = $users->verifyAccount();
    //echo "<div style=\"color:black; text-align:center;\">". $ret[0]."</div>";
}
elseif(isset($_POST['v']))
{
    include_once "inc/class.users.inc.php";
    echo "<div style=\"color:black; text-align:center;\">HELLO</div>";
    $users = new UserManager($db);
    $ret = $users->updateInfo();

}
else
{

    header("Location: /home.php");
    exit;
}

if(isset($ret[0])):

//echo isset($ret[1]) ? $ret[1]  : NULL;

if($ret[0]<3):
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Yumme!</title>
    <link rel="stylesheet" href="yummeStyle.css" type="text/css" />
</head>
<body class="login">
<!-- header starts here -->
<div id="facebook-Bar">
    <div id="facebook-Frame">
        <div id="logo"> <img src="logo.png" style="margin:auto; width:140px; height:70px; display:block" /> </div>
        <div id="header-main-right">
            </div>
        </div>
    </div>
</div>
<!-- header ends here -->
<div class="loginbox radius">
    <h2 style="color:#141823; text-align:center;">Welcome to Yumme!</h2>
    <div class="loginboxinner radius">
        <div class="loginheader">
            <h4 style="color:#141823;" class="title">Connect with the community to find fresh, free recipes!</h4>
        </div>
        <!--loginheader-->
        <div class="loginform">
            <form id="login" action="register.php" method="post">
                <p>
                    <input type="text" id="fn" name="fn" placeholder="First Name" value="" class="radius mini" />
                    <input type="text" id="ln" name="ln" placeholder="Last Name" value="" class="radius mini" />
                </p>
                <p>
                    <input type="password" id="p" name="p" placeholder="New Password" class="radius" />
                </p>
                <p>
                    <input type="password" id="r" name="r" placeholder="Re-enter Password" class="radius" />
                </p>
                <p><input name="v" type="hidden" id="v" value="<?php echo $_GET['v'] ?>" /></p>
                <p>
                    <button type="submit" class="radius title" name="signup">Sign Up!</button>
                </p>
            </form>
        </div>
        <!--loginform-->
    </div>
    <!--loginboxinner-->
</div>
<!--loginbox-->
</body>
</html>

    <?php
endif;
else:

    header("Location: /index.php");

    //echo '<meta http-equiv="refresh" content="0;/">';
endif;

include_once 'common/close.php';
?>