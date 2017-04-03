<?php
include_once "common/base.php";
$pageTitle = "New Recipe";
include_once "common/header.php";

if($_SESSION['LoggedIn'] == 0):
    header("Location: /yumme/login.php");
else:
//need to figure out how to structure the form (or maybe use something other than a form?) and generate the xml the recipe class expects
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Add a recipe!</title>
    <link rel="stylesheet" href="yummeStyle.css" type="text/css" />
</head>
<body class="recipe">

<div class="loginbox radius">
    <h2 style="color:#141823; text-align:center;">Enter a recipe!</h2>
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
                    <input type="text" id="utensil" name="utensils[]" placeholder="Utensils (separated by commas)" value="" class="radius" />
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

                </div>
                <button class="radius mini" name="addInst">Add a step +</button>
                <p>
                    <input type="file" name="fileToUpload" id="fileToUpload"><br>

                    <button type="submit" name="save" id="save" class="radius title" name="signup">Submit!</button>
                    <input type="hidden" name="action" id="action" value="addRec"/>

                </p>
            </form>
        </div>
        <!--loginform-->
    </div>
    <!--loginboxinner-->
</div>
<!--loginbox-->
</body>
</html>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script>

    $(document).ready(function() {
        var max_fields      = 20;
        var ingWrapper        = $(".ingInput");
        var uteWrapper        = $(".utensilInput");
        var instWrapper        = $(".instInput");
        var add_ing         = document.getElementsByName("addIng")[0];
        var add_ute         = document.getElementsByName("addUt")[0];
        var add_inst        =document.getElementsByName("addInst")[0];

        var x = 1;
        $(add_ing).click(function(e){
            e.preventDefault();
            if(x < max_fields){
                x++;
                $(ingWrapper).append('<p><input type="text" id="ingName" name="ingredients[name][]" placeholder="Name" value="" class="radius third" /><input type="text" id="ingState" name="ingredients[state][]" placeholder="State" value="" class="radius third" /><input type="text" id="ingAmount" name="ingredients[quant][]" placeholder="Amount" value="" class="radius third" /></p>'); //add input box
            }
            else
            {
                alert('You Reached the limits')
            }
        });

        x = 1;
        $(add_ute).click(function(e){
            e.preventDefault();
            if(x < max_fields){
                x++;
                $(uteWrapper).append('<p><input type="text" id="utensil" name="utensils[]" placeholder="Utensils (separated by commas)" value="" class="radius" /> </p> '); //add input box
            }
            else
            {
                alert('You Reached the limits')
            }
        });

        x = 1;
        $(add_inst).click(function(e){
            e.preventDefault();
            if(x < max_fields){
                x++;
                $(instWrapper).append('<p><input type="text" id="instruction" name="instructions[]" placeholder="Enter an Instruction" class="radius" /> </p>'); //add input box
            }
            else
            {
                alert('You Reached the limits')
            }
        });

        //TODO this deletes all of the things, fix that
        $(instWrapper).on("click",".delete", function(e){
            e.preventDefault(); $(this).parent('div').remove(); x--;
        })
        $(ingWrapper).on("click",".delete", function(e){
            e.preventDefault(); $(this).parent('div').remove(); x--;
        })
        $(uteWrapper).on("click",".delete", function(e){
            e.preventDefault(); $(this).parent('div').remove(); x--;
        })
    });
</script>

    <?php
endif;
include_once 'common/close.php';
?>