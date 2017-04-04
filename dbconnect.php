<?php
/**
 * Created by PhpStorm.
 * User: foxal
 * Date: 2017-04-04
 * Time: 10:47 AM
 */
include_once "inc/constants.inc.php";

if(isset($_POST['query'])){
    $dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME;
    $db = new PDO($dsn, $_POST['username'], $_POST['password']);
    echo "<div>Results: </div>";
    if($stmt = $db->prepare($_POST['query'])){
        $stmt->execute();
        echo $stmt->rowCount();
        while($row = $stmt->fetch()){
            foreach ($row as $k => $v){
                echo "<div>$k :  $v</div>";
            }
        }
    }
    else{ echo "<li> Something went wrong 623. ". $db->errorInfo(). "</li>n";}
}
else {
    ?>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Add a recipe!</title>
        <link rel="stylesheet" href="yummeStyle.css" type="text/css" />
    </head>
    <form method="post" action="dbconnect.php" id="db_form" name="db_form">
        <table border="0" style="border:none">
            <tr>
                <td><input type="text" tabindex="1" id="username" placeholder="username" name="username"
                           class="inputtext radius1" value=""></td>
                <td><input type="password" tabindex="2" id="password" placeholder="Password" name="password"
                           class="inputtext radius1"></td>
                <td width="70%"><input width="500px" type="text" tabindex="3" id="query" placeholder="query" name="query"
                           class="radius"></td>
                <td><input type="submit" class="fbbutton" name="login" value="Submit"/></td>
            </tr>


        </table>
    </form>
    <?php
}
?>



