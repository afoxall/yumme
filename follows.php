<?php
 
session_start();
 
include_once "../inc/constants.inc.php";
include_once "../inc/class.follows.inc.php";
 
if(!empty($_POST['action'])
&& isset($_SESSION['LoggedIn'])
&& $_SESSION['LoggedIn']==1)
{
    $followObj = new FollowManager();
    switch($_POST['action'])
    {
        case 'add':
            echo $followObj->addFollow();
            break;
        case 'removeFollow':
            $followObj->removeFollow();
            break;
        case 'removeFollower':
            $followObj->removeFollow();
            break;
        case 'getFollowers':
            echo $followObj->getFollowers();
            break;
        case 'getFollowers':
            echo $followObj->getFollowers();
            break;
        default:
            header("Location: /");
            break;
    }
}
else
{
    header("Location: /");
    exit;
}
 
?>