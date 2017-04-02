<?php
/**
 * Created by PhpStorm.
 * User: foxal
 * Date: 2017-03-18
 * Time: 10:31 AM
 */

include_once "common/base.php";
include_once "inc/class.recipes.inc.php";

$pageTitle = "Welcome to Yumme";
include_once "common/header.php";
$recObj = new RecipeManager();
echo $recObj->getFullRecipe();
?>