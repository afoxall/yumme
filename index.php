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
if(isset($_SESSION['LoggedIn']) && isset($_SESSION['UID'])):

 
    include_once 'inc/class.recipes.inc.php';
    include_once "inc/class.users.inc.php";
    include_once 'inc/class.follows.inc.php';

    $recipes = new RecipeManager($db);
    $user = new UserManager($db);
    $follows = new FollowManager($db);

    $followArray = $follows->getFollows()[1];
    array_push($followArray, $_SESSION["UID"]);

    $res = $recipes->getUsersRecipes($followArray, 20);
    ?>
    <div id="login_form">
        <?php echo $res; ?>
    </div>

 


 
<?php else: header("Location: /yumme/login.php");


endif; ?>
 
        </div>