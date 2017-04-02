<?php
include_once "common/base.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Yumme!</title>
    <link rel="stylesheet" href="yummeStyle.css" type="text/css" />
</head>

<body>

<div id="facebook-Bar">
    <div id="facebook-Frame">
        <div id="logo"> <img src="logo.png" style="margin:auto; width:140px; height:70px; display:block" /> </div>
        <div id="header-main-right">
            <div id="header-main-right-nav">

                <table border="0" style="border:none">
                    <tr>
                        <td >
                            <?php
                                echo "<label><a href=\"/yumme/logout.php\" class=\"button\">Log out</a></label>";
                            ?>
                        </td>
                        <td ><label><?php echo $_SESSION['UNAME']?><label></td>
                        <td ></td>
                    </tr>

                </table>
            </div>
        </div>
        <td ><input type="submit" class="fbbutton" name="login" value="Login" /></td>
        <div><label><a href ="/yumme/logout.php" >Logout</a></label></div>
        <div><label><a href ="/yumme/newrecipe.php" >New Recipe</a></label></div>
        <div><label><a href ="/yumme/index.php" > <?php echo $_SESSION['UNAME'] ?></a></label></div>
    </div>
</div>
</body>

