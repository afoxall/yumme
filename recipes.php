<?php
 
session_start();
 
include_once "../inc/constants.inc.php";
include_once "../inc/class.recipes.inc.php";
 
if(!empty($_POST['action'])
&& isset($_SESSION['LoggedIn'])
&& $_SESSION['LoggedIn']==1)
{
    $recObj = new RecipeManager();
    switch($_POST['action'])
    {
        case 'addRec':
            echo $recObj->addRecipe();
            break;
        case 'getFull':
            $recObj->getFullRecipe();
            break;
        case 'getUsers':
            $recObj->getUsersRecipes();
            break;
        case 'addRev':
            echo $recObj->addReview();
            break;
        case 'search':
            echo $recObj->recipeSearch();
            break;
		case 'deleteRecipe':
			echo $recObj->deleteRecipe();
			break;
		case 'deleteReview':
			echo $recObj->deleteReview();
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