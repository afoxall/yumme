<?php

include_once "common/base.php";

include_once "inc/constants.inc.php";
include_once "inc/class.recipes.inc.php";


if(!empty($_POST['action'])
    && isset($_SESSION['LoggedIn'])
    && $_SESSION['LoggedIn']==1)
{

    $sql = "SELECT * from recipe where RID=:rid and authorID = :uid";


    if($stmt = $this->_db->prepare($sql)){
        $stmt->bindParam(':rid', $_GET['r'], PDO::PARAM_INT);
        $stmt->bindParam(':uid', $_SESSION['UID'], PDO::PARAM_INT);

        $stmt->execute();

        if($stmt->rowCount > 0){
            return;
        }
    }
    else
    {
        return "tttt<li> Something went wrong.563 ". $this->_db->errorInfo(). "</li>n";
    }



    $sql = "INSERT INTO reblog (rid, uid, date) VALUES (:rid, :uid, now())";


    if($stmt = $this->_db->prepare($sql)){
        $stmt->bindParam(':rid', $_GET['r'], PDO::PARAM_INT);
        $stmt->bindParam(':uid', $_SESSION['UID'], PDO::PARAM_INT);

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