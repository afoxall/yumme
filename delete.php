<?php
include_once 'inc/class.recipes.inc.php';
include_once "inc/class.users.inc.php";
include_once 'inc/class.follows.inc.php';
/**
 * Created by PhpStorm.
 * User: foxal
 * Date: 2017-04-02
 * Time: 10:29 PM
 */
    if(isset($_SESSION['LoggedIn']) && isset($_SESSION['UID']) &&isset($_SESSION['ISADMIN'])){
        if(isset($_GET['u'])){
            $user = new UserManager();
            echo $user->deleteUser();
        }

    }
?>