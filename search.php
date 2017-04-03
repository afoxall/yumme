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
                    <h4 class="title" style="color:#141823">Enter a recipe and let's get cooking!</h4>
                </div>
                <!--loginheader-->
                <div class="loginform">
                    <form id="login" action="recipes.php" method="post" enctype="multipart/form-data">
                        <p>
                            <input type="text" id="name" name="name" placeholder="Recipe Name" value="" class="radius" />
                        </p>

                        <p>
                            <h4 class="title" style="color:#141823">Select Difficulty:</h4>
                            <select id="diff" name="diff">
                                <option value="easy">Easy</option>
                                <option value="intermediate">Intermediate</option>
                                <option value="hard">Hard</option>
                            </select>
                        </p>

                        <h4 class="title" style="color:#141823">Enter the ingredients below: </h4>


                        <div class = "ingInput">
                            <p>
                                <input type="text" id="ingName" name="ingredients[name][]" placeholder="Name" value="" class="radius third" />
                            </p>

                        </div>
                        <button type="button" class="radius mini" name="addIng">Add an Ingredient +</button>

                        <p>
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

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
        <script>

            $(document).ready(function() {
                var max_fields      = 20;
                var ingWrapper        = $(".ingInput");
                var add_ing         = document.getElementsByName("addIng")[0];

                var x = 1;
                $(add_ing).click(function(e){
                    e.preventDefault();
                    if(x < max_fields){
                        x++;
                        $(ingWrapper).append('<p><input type="text" id="ingName" name="ingredients[name][]" placeholder="Name" value="" class="radius third" /></p>'); //add input box
                    }
                    else
                    {
                        alert('You Reached the limits')
                    }
                });

                //TODO this deletes all of the things, fix that
                $(ingWrapper).on("click",".delete", function(e){
                    e.preventDefault(); $(this).parent('div').remove(); x--;
                })
            });
        </script>




    <?php else: header("Location: /yumme/login.php");


    endif; ?>

</div>