<?php
include_once "common/base.php";
$pageTitle = "Home";
include_once "common/header.php";
include_once "inc/constants.inc.php";

if(!empty($_SESSION['LoggedIn']) && !empty($_SESSION['Email'])):
    header("Location: /".BASE_URL . "/index.php");


elseif(!empty($_POST['email']) && !empty($_POST['password'])):
    include_once 'inc/class.users.inc.php';
    $users = new UserManager($db);
    if($users->accountLogin()==TRUE):
        header("Location: /".BASE_URL . "/index.php");
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
                            <td ><input type="text" tabindex="1"  id="email" placeholder="Email or Phone" name="email" class="inputtext radius1" value=""></td>
                            <td ><input type="password" tabindex="2" id="pass" placeholder="Password" name="pass" class="inputtext radius1" ></td>
                            <td ><input type="submit" class="fbbutton" name="login" value="Login" /></td>
                        </tr>


                        <tr>

                            <?php
                            $url = "/yumme/password.php";
                            echo "<td><label><a href='$url' style=\"color:#ccc; text-decoration:none\">forgot your password?</a></label></td>";?>
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
    <h2 style="color:#141823; text-align:center;">Welcome to Yumme!</h2>
    <div class="loginboxinner radius">
        <div class="loginheader">
            <h4 class="title">Connect with the community to find fresh, free recipes!</h4>
        </div>
        <!--loginheader-->
        <div class="loginform">
            <form id="login" action="" method="post">
                <p>
                    <input type="text" id="firstname" name="firstname" placeholder="First Name" value="" class="radius mini" />
                    <input type="text" id="lastname" name="lastname" placeholder="Last Name" value="" class="radius mini" />
                </p>
                <p>
                    <input type="text" id="userEmail" name="email" placeholder="Your Email" value="" class="radius" />
                </p>
                <p>
                    <input type="text" id="remail" name="remail" placeholder="Re-enter Email" class="radius" />
                </p>
                <p>
                    <input type="password" id="password" name="password" placeholder="New Password" class="radius" />
                </p>
                <p>
                    <button class="radius title" name="signup">Sign Up!</button>
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