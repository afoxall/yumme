<?php
/**
 * Created by PhpStorm.
 * User: foxal
 * Date: 2017-03-18
 * Time: 10:31 AM
 */

include_once "common/base.php";
$pageTitle = "Welcome to Yumme";
include_once "common/header.php";
include_once "common/sidebar.php";
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Yumme!</title>
    <link rel="stylesheet" href="yummeStyle.css" type="text/css" />
</head>


<body class="index">
<!-- header starts here -->

<!--loginbox-->
</body>

<div id="main">
    <noscript>This site just doesn't work, period, without JavaScript</noscript>
    <?php
    if(isset($_SESSION['LoggedIn']) && isset($_SESSION['UID']) &&isset($_GET['u'])):


        include_once 'inc/class.recipes.inc.php';
        include_once "inc/class.users.inc.php";
        include_once 'inc/class.follows.inc.php';

        $recipes = new RecipeManager($db);
        $u = $_GET['u'];
        $name=$_GET['uname'];
        $res = "<br/><br/><p align=\"center\" ><label style=\"font-size:500%\"align=\"center\" class=\"title\">$name</label></p>";
        $res .= "<br><div align='center'><a href=\"/yumme/addfollows.php?u=$u\">Follow</a></div>";
        if($_SESSION['ISADMIN'] == 1){
            $res .= "<div align='center'><a href=\"/yumme/delete.php?u=$u\">Delete this user</a></div>";
        }

        $res .= $recipes->getUsersRecipes(array($_GET['u']), 20);
        echo $res;

        ?>






    <?php else: header("Location: /yumme/login.php");


    endif; ?>

</div>