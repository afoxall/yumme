<?php
/**
 * Created by PhpStorm.
 * User: foxal
 * Date: 2017-03-18
 * Time: 10:31 AM
 */

include_once "common/base.php";

include_once "common/header.php";
include_once "common/sidebar.php";
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Yumme!</title>
    <link rel="stylesheet" href="yummeStyle.css" type="text/css" />
</head>


<body class="index">
<!-- header starts here -->

<!--loginbox-->
</body>



<div id="main">
    <noscript>This site just doesn't work, period, without JavaScript</noscript>
    <?php
    if(isset($_SESSION['LoggedIn']) && isset($_SESSION['UID'])):


        include_once 'inc/class.recipes.inc.php';
        include_once "inc/class.users.inc.php";
        include_once 'inc/class.follows.inc.php';

        $recipes = new RecipeManager($db);

        $res = $recipes -> recipeSearch();

        ?>
        <div class="loginbox radius">
                        <h2 style="color:#141823; text-align:center;">Search for recipes!</h2>
                        <div class="loginboxinner radius">
                            <div class="loginheader">
                                <h4 class="title">Enter a recipe and let's get cooking!</h4>
                            </div>
                            <!--loginheader-->
                            <div class="loginform">
                                <form id="login" action="recipes.php" method="post" enctype="multipart/form-data">
                                    <p>
                                        <input type="text" id="name" name="name" placeholder="Recipe Name" value="" class="radius" />
                                    </p>
                                    <p>
                                        <input type="text" id="prep" name="prep" placeholder="Preparation Time" value="" class="radius mini" />
                                        <input type="text" id="cook" name="cook" placeholder="Cooking Time" value="" class="radius mini" />
                                    </p>
                                    <p>
                                        <select id="diff" name="diff">
                                            <option value="easy">Easy</option>
                                            <option value="intermediate">Intermediate</option>
                                            <option value="hard">Hard</option>
                                        </select>
                                    </p>
                                    <p>
                                        <input type="text" id="description" name="description" placeholder="Description" value="" class="radius" />
                                    </p>

                                    <p>
                                    <h4 class="title">Enter your ingredients below: </h4>
                                    </p>
                                    <div class = "ingInput">
                                        <p>
                                            <input type="text" id="ingName" name="ingredients[name][]" placeholder="Name" value="" class="radius third" />
                                            <input type="text" id="ingState" name="ingredients[state][]" placeholder="State" value="" class="radius third" />
                                            <input type="text" id="ingAmount" name="ingredients[quant][]" placeholder="Amount" value="" class="radius third" />
                                        </p>

                                    </div>
                                    <button type="button" class="radius mini" name="addIng">Add an Ingredient +</button>
                                    <p>
                                    <h4 class="title">Enter your utensils below: </h4>
                                    </p>
                                    <div class="utensilInput">
                                        <p>
                                            <input type="text" id="utensil" name="utensils[]" placeholder="Utensils" value="" class="radius" />
                                        </p>

                                    </div>
                                    <button class="radius mini" name="addUt">Add a Utensil +</button>
                                    <p>
                                    <h4 class="title">Enter the recipe steps below: </h4>
                                    </p>
                                    <div class="instInput">
                                        <p>
                                            <input type="text" id="instruction" name="instructions[]" placeholder="Enter an Instruction" class="radius" />
                                        </p>


                                        <button type="submit" name="save" id="save" class="radius title" name="signup">Submit!</button>
                                        <input type="hidden" name="action" id="action" value="addRec"/>

                                    </p>
                                </form>
                </div>
                <!--loginform-->
            </div>
            <!--loginboxinner-->
        </div>

        <div id="login_form">
            <?php echo $res; ?>
        </div>






    <?php else: header("Location: /yumme/login.php");


    endif; ?>

</div>