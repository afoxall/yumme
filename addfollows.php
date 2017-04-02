<?php
/**
 * Created by PhpStorm.
 * User: foxal
 * Date: 2017-04-02
 * Time: 3:20 PM
 */

include_once "common/base.php";

include_once "inc/constants.inc.php";
include_once "inc/class.recipes.inc.php";


if(!empty($_POST['action'])
    && isset($_SESSION['LoggedIn'])
    && $_SESSION['LoggedIn']==1)
{


    if($_GET['r'] == $_SESSION['UID'] ){
        return;
    }



    $sql = "INSERT INTO follow (follower, followee) VALUES (:f1, :f2)";


    if($stmt = $this->_db->prepare($sql)){
        $stmt->bindParam(':f2', $_GET['r'], PDO::PARAM_INT);
        $stmt->bindParam(':f1', $_SESSION['UID'], PDO::PARAM_INT);

        $stmt->execute();
    }
    else
    {
        return "tttt<li> Something went wrong.563 ". $this->_db->errorInfo(). "</li>n";
    }

}
else{

    header("Location: /yumme/index.php");
    exit;
}

?>

?>