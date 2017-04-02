<?php

include_once "common/base.php";

include_once "inc/constants.inc.php";
include_once "inc/class.recipes.inc.php";


$recObj = new RecipeManager();
$recObj->reblog();
header("Location: /yumme/index.php");

?>