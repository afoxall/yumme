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

?>

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

    $followArray = $follows->getFollows();
    array_push($followArray, $_SESSION["UID"]);
    $res = $recipes->getUsersRecipes($followArray, 20);
    echo $res;

?>
 
    <p><a href="/yumme/addrecipe.php" class="button">New Recipe</a>
 


 
<?php else: header("Location: /yumme/login.php");


endif; ?>
 
        </div>