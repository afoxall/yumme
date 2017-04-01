<?php
    include_once "common/base.php";
    $pageTitle = "New Recipe";
    include_once "common/header.php";

    if($_SESSION['LoggedIn'] == 0):
        header("Location: /yumme/login.php");
    else:
	//need to figure out how to structure the form (or maybe use something other than a form?) and generate the xml the recipe class expects
?>
 
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
                var max_fields      = 10;
                var ingWrapper        = $(".ingInput");
                var uteWrapper        = $(".utensilInput");
                var instWrapper        = $(".instInput");
                var add_ing         = $(".add_ingredient");
                var add_ute         = $(".add_utensil");
                var add_inst        =$(".add_step");

                var x = 1;
                $(add_ing).click(function(e){
                    e.preventDefault();
                    if(x < max_fields){
                        x++;
                        $(ingWrapper).append('<label>Name </label><input type="text" name="ingredients[name][]"><label>State</label> <input type = "text" name="ingredients[state][]"> <label>Quantity</label><input type = "text" name="ingredients[quant][]"><a href="#" class="delete">Delete</a></div><br\>'); //add input box
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
                        $(uteWrapper).append('<div><input type="text" name="utensils[]"/><a href="#" class="delete">Delete</a></div>'); //add input box
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
                        $(instWrapper).append('<div><input type="text" name="instructions[]"/><a href="#" class="delete">Delete</a></div>'); //add input box
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

