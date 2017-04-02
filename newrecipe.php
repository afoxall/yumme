<?php
session_start();
include_once "common/header.php";?>
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
            <form id="login" action="" method="post">
                <p>
                    <input type="text" id="rName" name="rName" placeholder="Recipe Name" value="" class="radius" />
                </p>
                <p>
                    <input type="text" id="prepTime" name="prepTime" placeholder="Preparation Time" value="" class="radius mini" />
                    <input type="text" id="cookTime" name="cookTime" placeholder="Cooking Time" value="" class="radius mini" />
                </p>
                <p>
                    <select>
                        <option value="easy">Easy</option>
                        <option value="intermediate">Intermediate</option>
                        <option value="hard">Hard</option>
                    </select>
                </p>
                <p>
                    <h4 class="title">Enter your ingredients below: </h4>
                    </p>
                    <div class = "ingInput">
                        <p>
                            <input type="text" id="ingName" name="ingName" placeholder="Name" value="" class="radius third" />
                            <input type="text" id="ingState" name="ingState" placeholder="State" value="" class="radius third" />
                            <input type="text" id="ingAmount" name="ingAmount" placeholder="Amount" value="" class="radius third" />
                        </p>

                    </div>
                    <button type="button" class="radius mini" name="addIng">Add an Ingredient +</button>
                <p>
                <h4 class="title">Enter your utensils below: </h4>
                </p>
                <div class="utensilInput">
                <p>
                    <input type="text" id="utensil" name="utensil" placeholder="Utensils (separated by commas)" value="" class="radius" />
                </p>
                    <button class="radius mini" name="addUt">Add a Utensil +</button>
                </div>
                <p>
                <h4 class="title">Enter the recipe steps below: </h4>
                </p>
                <div class="instInput">
                <p>
                    <input type="text" id="instruction" name="instruction" placeholder="Enter an Instruction" class="radius" />
                </p>
                    <button class="radius mini" name="addInst">Add a step +</button>
                </div>
                <p>
                    <button class="radius title" name="signup">Submit!</button>
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

<h2>New Recipe</h2>
<form method="post" action="recipes.php" id="recform">
    <div class="recipeInput">
        <label for="name">Recipe Name:</label>
        <input type="text" name="name" id="name" /><br />
        <label for="prep">Prep time:</label>
        <input type="text" name="prep" id="prep" /><br />
        <label for="cook">Cook Time:</label>
        <input type="text" name="cook" id="cook" /><br />
        <label for="diff">Difficulty:</label>

        <select name="diff">
            <option value="Easy">Easy</option>
            <option value="Intermediate">Medium</option>
            <option value="Hard">Hard</option>
        </select>

        <div class = "ingInput">
            <label>Ingredients</label><br/>
            <label for="ingredients[name][]">Name </label>
            <input type="text" name="ingredients[name][]">
            <label for="ingredients[state][]">State</label>
            <input type = "text" name="ingredients[state][]">
            <label for="ingredients[quant][]">Quantity</label>
            <input type = "text" name="ingredients[quant][]"><a href="#" class="delete">Delete</a><br/>
        </div>


        <button type="button" class="add_ingredient">Add Ingredient &nbsp; <span style="font-size:16px; font-weight:bold;">+ </span></button>
        <div class = "utensilInput">
            <label>Utensils</label>
            <div><input type="text" name="utensils[]"><a href="#" class="delete">Delete</a></div>

        </div>
        <button type="button" class="add_utensil">Add Utensil &nbsp; <span style="font-size:16px; font-weight:bold;">+ </span></button>

        <div class = "instInput">
            <label for="instructions[]">Instructions</label>
            <div><input type="text" name="instructions[]"><a href="#" class="delete">Delete</a></div>

        </div>
        <button type="button" class="add_step">Add Step &nbsp; <span style="font-size:16px; font-weight:bold;">+ </span></button>


        <input type="hidden" name="action" id="action" value="addRec"/>
        <input type="submit" name="save" id="save" value="save" />
    </div>

</form>


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
                $(ingWrapper).append('<p><input type="text" id="ingName" name="ingName" placeholder="Name" value="" class="radius third" /><input type="text" id="ingState" name="ingState" placeholder="State" value="" class="radius third" /><input type="text" id="ingAmount" name="ingAmount" placeholder="Amount" value="" class="radius third" /></p>'); //add input box
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
                $(uteWrapper).append(' <div> <p><input type="text" id="utensil" name="utensil" placeholder="Utensils (separated by commas)" value="" class="radius" /> </p> <button class="radius mini" name="addUt">Add a Utensil +</button> </div>'); //add input box
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
                $(instWrapper).append('<p><input type="text" id="instruction" name="instruction" placeholder="Enter an Instruction" class="radius" /> </p>'); //add input box
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