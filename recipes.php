<?php
 
include_once "common/base.php";
 
include_once "inc/constants.inc.php";
include_once "inc/class.recipes.inc.php";


if(!empty($_POST['action'])
&& isset($_SESSION['LoggedIn'])
&& $_SESSION['LoggedIn']==1)
{
    $recObj = new RecipeManager();
    switch($_POST['action'])
    {
        case 'addRec':
            echo $recObj->addRecipe();
            header("Location: /index.php"); //change this to take you to the apge for that recipe if successful
            break;
        case 'getFull':
            header("Location: /viewrecipe.php");
            break;
        case 'getUsers':
            $recObj->getUsersRecipes();
            break;
        case 'addReview':
            echo $recObj->addReview();
            header("Location: /viewrecipe.php?rid=".$_POST['rid']);
            break;
        case 'searchRecipes':
            echo $recObj->recipeSearch();
            break;
		case 'deleteRecipe':
			echo $recObj->deleteRecipe();
			break;
		case 'deleteReview':
			echo $recObj->deleteReview();
			break;
        default:
            header("Location: /index.php");
            break;
    }
}
else{

    header("Location: /index.php");
    exit;
}
 
?>