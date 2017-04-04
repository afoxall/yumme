<?php
include_once "common/base.php";
$pageTitle = "Home";
include_once "inc/constants.inc.php";

if(!empty($_SESSION['LoggedIn']) && !empty($_SESSION['UID'])):
    header("Location: /index.php");


elseif(!empty($_POST['email']) && !empty($_POST['password'])):
    include_once 'inc/class.users.inc.php';
    $users = new UserManager($db);
    if($users->accountLogin()==TRUE):

        header("Location: /index.php");
        exit;

    else:
        echo("Login Failed");
    endif;

endif;


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
            <div id="header-main-right-nav">
                <form method="post" action="" id="login_form" name="login_form">
                    <table border="0" style="border:none">
                        <tr>
                            <td ><input type="text" tabindex="1"  id="email" placeholder="Email" name="email" class="inputtext radius1" value=""></td>
                            <td ><input type="password" tabindex="2" id="password" placeholder="Password" name="password" class="inputtext radius1" ></td>
                            <td ><input type="submit" class="fbbutton" name="login" value="Login" /></td>
                        </tr>


                        <tr>

                            <?php
                            $url = "/password.php";
                            echo "<td><label><a href='$url' style=\"color:#ccc; text-decoration:none\">Forgot your password?</a></label></td>";?>
                            <!--<td><label><a href=$url style="color:#ccc; text-decoration:none">forgot your password?</a></label></td>-->

                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- header ends here -->
<div class="loginbox radius">

    <?php
        if(!empty($_POST['signupemail'])):

            include_once "inc/class.users.inc.php";
            $users = new UserManager($db);
            echo "<div style=\"color:black; text-align:center;\">". $users->createAccount()."</div>";
        else:
    ?>

    <h5 style="color:#141823; font-size:130%; text-align:center;">Welcome to Yumme!</h5>
    <div class="loginboxinner radius">
        <div class="loginheader">
            <h4 style="color:#141823;" class="title">Connect with the community to find fresh, free recipes!</h4>
        </div>
        <!--loginheader-->
        <div class="loginform">
            <form id="login" action="" method="post">
                <p>
                    <input type="text" id="signupemail" name="signupemail" placeholder="Your Email" value="" class="radius" />
                </p>

                <p>
                    <input type="text" id="uname" name="uname" placeholder="Username" class="radius" />
                </p>
                <p>
                    <button type="submit" class="radius title" name="signup">Sign Up!</button>
                </p>
            </form>
        </div>
        <!--loginform-->

    </div>
        <?php endif;?>
    <!--loginboxinner-->
</div>
<!--loginbox-->
</body>
</html>
