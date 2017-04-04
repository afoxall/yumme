<?php
include_once 'inc/class.recipes.inc.php';
include_once "inc/class.users.inc.php";
include_once 'inc/class.follows.inc.php';
include_once 'common/base.php';
/**
 * Created by PhpStorm.
 * User: foxal
 * Date: 2017-04-02
 * Time: 10:29 PM
 */
    if(isset($_SESSION['LoggedIn']) && isset($_SESSION['UID']) &&$_SESSION['ISADMIN']==1){
        if(isset($_GET['du'])){
            $user = new UserManager();
            echo $user->deleteUser();
            header("Location: /index.php");
        }
        if(isset($_GET['dr'])){
            $rec = new RecipeManager();
            echo $rec->deleteRecipe();
            header("Location: /index.php");
        }
        if(isset($_GET['drev'])){
            $rec = new RecipeManager();
            echo $rec->deleteReview();
            header("Location: /index.php");
        }
        if(isset($_GET['adda'])){
            $user = new UserManager();
            echo $user->addAdmin();
            header("Location: /index.php");
        }
    }
?>