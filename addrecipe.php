<?php
    include_once "common/base.php";
    $pageTitle = "New Recipe";
    include_once "common/header.php";
 
    if(!empty($_POST['email'])):
        include_once "inc/class.recipes.inc.php";
        $rec = new RecipeManager($db);
        echo $users->createAccount();
    else:
	//need to figure out how to structure the form (or maybe use something other than a form?) and generate the xml the recipe class expects
?>
 
        <h2>New Recipe</h2>
        <form method="post" action="addrecipe.php" id="recform">
            <div>
                <label for="name">Recipe Name:</label>
                <input type="text" name="name" id="name" /><br />
                <input type="submit" name="save" id="save" value="save" />
            </div>
        </form>
 
<?php
    endif;
    include_once 'common/close.php';
?>