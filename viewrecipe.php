<?php
include_once "common/header.php";
include_once "common/base.php";
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Yumme!</title>
    <link rel="stylesheet" href="yummeStyle.css" type="text/css" />
</head>


<body class="viewRecipe">
<!-- header starts here -->
<div class="loginbox radius">
<div class="loginboxinner radius">
    <!--loginheader-->
    <div class="loginform">
        <form id="login" action="recipes.php" method="post">
            <input type="hidden" name="action" id="action" value="addReview"/>
            <input type="hidden" name="rid" id="rid" value="<?php echo $_POST['rid']?>"/>

            <?php
            include_once "common/base.php";
            include_once "inc/class.recipes.inc.php";
            $pageTitle = "Welcome to Yumme";

            $recObj = new RecipeManager();

            echo $recObj->getFullRecipe();?>

        </form>
    </div>
    <!--loginform-->

</div>

<!--loginboxinner-->
</div>
<!--loginbox-->
</body>