<?php

/*
	RecipeManager:
		create recipe
		add a review
		search for recipes
		view a recipe
		load a bunch of recipes from one or many users fir a timeline or profile
*/
class RecipeManager{
	
	private $_db;

	public function __construct($db = null){
		if(is_object($db)){
			$this->_db = $db;
		}
		else{
			$dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME;
			$this->_db = new PDO($dsn, DB_USER, DB_PASS);
		}
	}
	/*
		Returns XML containing the entire recipe and its reviews (maybe limit reviews # to n most recent)?
	*/
	public function getFullRecipe(){

	    if(!isset($_POST['rid'])){
	        header("Location: /yumme/home.php");
        }
		//first get the recipe (we will get the author reviews, ingredients and utensils later)
		$sql = "SELECT recipe.description, recipe.imagename, recipe.prepTime, recipe.cookTime, recipe.difficulty, recipe.date, recipe.title, user.uname
              FROM recipe join user on recipe.authorID = user.UID WHERE recipe.rid=:rid";

		if($stmt = $this->_db->prepare($sql)){
			$stmt->bindParam(':rid', $_POST['rid']);
			$stmt->execute();
			
			if($stmt->rowCount()==1){
				$recipe = $stmt->fetch();
				//echo $recipe['title'];
				$res = "
                    <p>
                        <h2 style=\"color:#141823; text-align:center;\">". $recipe['title']."</h2>
                    </p>
                    <p>
                        <h4 style=\"color:#141823; text-align:center;\"> Created by ". $recipe['uname']." on " . $recipe['date']."</h4>
                    </p>
                    <p>
                        <h4 class=\"title\">".$recipe['description']."</h4>
                    </p><br>
                    <p>
                        <h4 style=\"color:#141823; text-align:center;\">Prep Time: ". $recipe['prepTime']."    
                        Cook Time: ". $recipe['cookTime']."    Difficulty: " . $recipe['difficulty']."</h4>
                    </p><br>";

			}
			else{
				return "<li> Something went wrong.126 RID:". $_POST['rid'] .$this->_db->errorInfo()[0] . $this->_db->errorInfo()[1] .$this->_db->errorInfo()[2] . "</li>";
			}
		}
		else{
            return "tttt<li> Something went wrong. 47". $this->_db->errorInfo()[0]. "</li>n";
        }

        $res .= "<p><h3 style=\"color:#141823; text-align:center;\">Ingredients:</h3></p>";
		//now get ingredients
		$sql = "SELECT * FROM ingredient WHERE rid=:rid";	
		
		if($stmt = $this->_db->prepare($sql)) {
            $stmt->bindParam(':rid', $_POST['rid']);
            $stmt->execute();
            $res .= "<p><table  style=\"color:#141823; text-align:center;\" align=\"center\"> <tr><th>Name</th><th>State</th><th>Quantity</th></tr>";

            while ($row = $stmt->fetch()) {
                $res .= "<tr>
							<td>" . $row['name'] . "</td>
							<td>" . $row['state'] . "</td>
							<td>" . $row['quantity'] . "</td>
						</tr>";
            }
            $res .= "</table></p> ";
        }

		else
        {
            return "tttt<li> Something went wrong 8. ". $this->_db->errorInfo()[0]. "</li>n";
        }
        $res .= "<p><h3 style=\"color:#141823; text-align:center;\">Utensils:</h3></p><p  style=\"color:#141823; text-align:center;\">";
        //now get utensils
        $sql = "SELECT * FROM utensil WHERE rid=:rid";

        if($stmt = $this->_db->prepare($sql)){
            $stmt->bindParam(':rid', $_POST['rid']);
            $stmt->execute();

            while($row = $stmt->fetch()){
                $res .= $row['name'].", ";

            }
            $res = substr($res, 0, -2);
        }
        else
        {
            return "tttt<li> Something went wrong 12. ". $this->_db->errorInfo(). "</li>n";
        }

        $res .= "</p><p> <h3 style=\"color:#141823; text-align:center;\">Steps:</h3> </p><p>";
		//now get instructions
		$sql = "SELECT * FROM instruction WHERE rid=:rid";	
		
		if($stmt = $this->_db->prepare($sql)){
			$stmt->bindParam(':rid', $_POST['rid']);
			$stmt->execute();
			
			while($row = $stmt->fetch()){
				$res .= "<div>
							<h5  style=\"color:#141823; text-align:center;\">".$row['StepNum'].": ".$row['Text']."</h5>					
						</div>";
			}
		}
		else
        {
            return "tttt<li> Something went wrong 10. ". $this->_db->errorInfo(). "</li>n";
        }
        $res .= "</p><p> <h3 style=\"color:#141823; text-align:center;\">Reviews:</h3> </p><p>";



		//now get reviews
		$sql = "SELECT * FROM review WHERE recId=:rid ORDER BY date LIMIT 10"; //10 is arbitrary for now
		
		if($stmt = $this->_db->prepare($sql)){
			$stmt->bindParam(':rid', $_POST['rid']);
			$stmt->execute();

			while($row = $stmt->fetch()){
				$res .= "<div>
							<h4  style=\"color:#141823; text-align:center;\"> Rating:".$row['rating']."
							Date: ".$row['Date']."</text>
							</h4><p style=\"color:#141823; text-align:center;\">".$row['text']."</p>
						</div>";
			}

		}
		else
        {
            return "tttt<li> Something went wrong 6. ". $this->_db->errorInfo(). "</li>n";
        }
        $rid=$_POST['rid'];
        $res .= "
                <table align='center'>
                <tr>
                <td><label>Rating (1-5):</label> </td>
                   <td> <select id=\"rating\" name=\"rating\">
                        <option value=\"1\">1</option>
                        <option value=\"2\">2</option>
                        <option value=\"3\">3</option>
                        <option value=\"4\">4</option>
                        <option value=\"5\">5</option>
                    </select>
                    </td>
                    <td><a href=\"/yumme/reblog.php?r=$rid\"> Reblog</a></a></td>
                </tr>
                </table>
            ";

        $res .= "<p><input type=\"text\" id=\"review\" name=\"review\" placeholder=\"Review this Recipe!\" class=\"radius\" />
             <button type=\"submit\" class=\"radius title\" name=\"signup\">Submit</button></p>";
		return $res . "</div>";
	}
	
	/*
		takes an array of uids and returns xml with basic info about the n (or less) most recent recipes by all these users.
		Can be used to populate a newsfeed(pass in all follows) or a usr page(pass in only that user's uid. 
	*/
	public function getUsersRecipes($ar, $n){

		$ids = join("','",$ar); //this is at risk of injection but ids are never seen or enterd by users so fine
		//$sql = "SELECT title, prepTime + cookTime as time, difficulty FROM recipe WHERE authorID IN ('$ids') ORDER BY date LIMIT :n";
		$sql = "SELECT recipe.rid, recipe.authorID, recipe.title, recipe.prepTime + recipe.cookTime as time, recipe.description, user.uname, 
      recipe.difficulty from recipe join user on recipe.authorID=user.uid where recipe.authorID in ('$ids') ORDER BY date LIMIT :n ";
		$res = "";


		if($stmt = $this->_db->prepare($sql)) {
            $stmt->bindParam(':n', $n, PDO::PARAM_INT);
            $stmt->execute();

            $count = $stmt->rowCount();

            while ($count > 0) {
                $count -= 1;
                $row = $stmt->fetch();

                $n = $row['title'];
                $t = $row['time'];
                $d = $row['difficulty'];
                $desc = $row['description'];
                $a = $row['uname'];
                $u = $row['authorID'];
                $rid = $row['rid'];

                $res .= "<div class=\"login_form\">
                            <div class=\"loginbox radius\">
                            <div class=\"loginboxinner radius\">
                            <!--loginheader-->
                            <div class=\"loginform\">
                               
            
                                <div class=\"mini_recipe\">
                                            <form id=\"recipe\" action=\"viewrecipe.php\" method=\"post\">
                                                    <p>
                                                        <h4 class=\"title\" style=\"font-size:150%\">$n</h4>
                                                    </p>
                                                    <p>
                                                        <h4 class=\"title\">$desc</h4>
                                                    </p>
                                                    <p>
                                                        <h4 class=\"title\">Total Time: $t    Difficulty: $d</h4>
                                                        <h4 class=\"title\">Author: <a href='/yumme/userprofile.php?u=$u&uname=$a'>$a</a></h4>
                                                    </p>
                                                    
                                                    <input name=\"rid\" type=\"hidden\" id=\"rid\" value=\"$rid\"  />
                                                    
                                                    <button type=\"submit\" class=\"radius mini\">View</button>
                                            </form>
                    
                                            </div>
      
    </div>
    <!--loginform-->

</div>
</div>
<?php endif;?>
<!--loginboxinner-->

                        
";
            }

		}
		else
        {
            return "<li> Something went wrong 623. ". $this->_db->errorInfo(). "</li>n";
        }

		return $res;
	}
	
	/*
	* Adds a new recipe 
	*/

	public function addRecipe(){
        $sql = "INSERT INTO recipe (authorID, title, prepTime, cookTime, Difficulty, description, date) VALUES
				(:uid, :n, :prep, :cook, :diff, :description, now())";

        if($stmt = $this->_db->prepare($sql)){
            $name = $_POST['name'];
            $prep = $_POST['prep'];
            $cook = $_POST['cook'];
            $difficulty = $_POST['diff'];
            $description = $_POST['description'];

            $stmt->bindParam(':uid', $_SESSION['UID'], PDO::PARAM_INT);
            $stmt->bindParam(':n', $name, PDO::PARAM_STR);
            $stmt->bindParam(':prep', $prep, PDO::PARAM_INT);
            $stmt->bindParam(':cook', $cook, PDO::PARAM_INT);
            $stmt->bindParam(':diff', $difficulty, PDO::PARAM_STR);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            $stmt->execute();
        }
        else
        {
            return "tttt<li> Something went wrong.368 ". $this->_db->errorInfo(). "</li>n";
        }
        $rid = $this->_db->lastInsertID();

        //now add instructoins

        //wonder if this can be done in one statement to go faster, but not sure how to bind a variable number of things
        $i = 1;
        foreach($_POST['instructions'] as $key => $n) {
            $sql = "INSERT INTO instruction (rid, stepNum, Text) VALUES ($rid, $i, :text) ";
            $i += 1;
            if ($stmt = $this->_db->prepare($sql)) {
                $stmt->bindParam(':text', $n, PDO::PARAM_STR);
                $stmt->execute();
            } else {
                return "tttt<li> Something went wrong.364 " . $this->_db->errorInfo() . "</li>n";
            }
        }

        foreach($_POST['utensils'] as $key => $n) {
            $sql = "INSERT INTO utensil (rid,  name) VALUES ($rid, :name) ";

            if ($stmt = $this->_db->prepare($sql)) {
                $stmt->bindParam(':name', $n, PDO::PARAM_STR);
                $stmt->execute();
            } else {
                return "tttt<li> Something went wrong.364 " . $this->_db->errorInfo() . "</li>n";
            }
        }

        $tmp = "";

        for($j = 0; $j < sizeof($_POST['ingredients']['state']); $j++){
            $sql = "INSERT INTO ingredient (rid, name, state, quantity) VALUES ($rid, :n, :state, :quantity) ";

            //$tmp .= $_POST['ingredients']['name'][$j] . "  ";

            if ($stmt = $this->_db->prepare($sql)) {
                $stmt->bindParam(':n', $_POST['ingredients']['name'][$j], PDO::PARAM_STR);
                $stmt->bindParam(':state', $_POST['ingredients']['state'][$j], PDO::PARAM_STR);
                $stmt->bindParam(':quantity', $_POST['ingredients']['quant'][$j], PDO::PARAM_STR);

                $stmt->execute();
            } else {
                return "tttt<li> Something went wrong.364 " . $this->_db->errorInfo() . "</li>n";
            }
        }

        return $tmp;

	}
	/*
	* Adds a new recipe 
	*/
	public function addReview(){ 
		$sql = "INSERT INTO review (recId, authorID, rating, text, date) VALUES (:rid, :author, :rating, :text, now())";


		if($stmt = $this->_db->prepare($sql)){
		    $stmt->bindParam(':rid', $_POST['rid'], PDO::PARAM_INT);
			$stmt->bindParam(':rating', $_POST['rating'], PDO::PARAM_INT);
			$stmt->bindParam(':text', $_POST['review'], PDO::PARAM_STR);
            $stmt->bindParam(':author', $_SESSION['UID'], PDO::PARAM_INT);
			$stmt->execute();
		}
		else
		{
			return "tttt<li> Something went wrong.563 ". $this->_db->errorInfo(). "</li>n";
		}

	}
	
	/*
	* Adds a new recipe 
	*/
	public function recipeSearch(){ 
		//this will be hard
	
	}
	
	/*
	* These are the admin functions, they need to check that the current user is ad administrator before doing its thing 
	*
	*
	*/
	
	//expects a field 'rid' in the POST
	public function deleteRecipe(){
		
		$sql = "SELECT COUNT(aid) AS theCount FROM adminstrator where uid=:uid";

		if($stmt = $this->_db->prepare($sql)){
			$stmt->bindParam(":uid", $_SESSION['UID'], PDO::PARAM_INT);
			$stmt->execute();
			$row = $stmt->fetch();
			if($row['theCount']==0){
				return "<h2> Error </h2>" . 
					"<p> Only administrators can do this. </p>";
			}
			$stmt ->closeCursor();
		}
		else{
			return "Something went wrong checking the admin table.";
		}
		
		$sql = "DELETE FROM recipe WHERE rid=:rid";
		if($stmt = $this->_db->prepare($sql)){
			$stmt->bindParam(":rid", $_POST['rid'], PDO::PARAM_INT);
			$stmt->execute();
		}
		else{
			return "Something went wrong deleting a recipe.";
		}
	}
	//expects post field revid
	public function deleteReview(){
		$sql = "SELECT COUNT(aid) AS theCount FROM adminstrator where uid=:uid";

		if($stmt = $this->_db->prepare($sql)){
			$stmt->bindParam(":uid", $_SESSION['UID'], PDO::PARAM_INT);
			$stmt->execute();
			$row = $stmt->fetch();
			if($row['theCount']==0){
				return "<h2> Error </h2>" . 
					"<p> Only administrators can do this. </p>";
			}
			$stmt ->closeCursor();
		}
		else{
			return "Something went wrong checking the admin table.";
		}
		
		$sql = "DELETE FROM review WHERE revid=:revid";
		if($stmt = $this->_db->prepare($sql)){
			$stmt->bindParam(":revid", $_POST['revid'], PDO::PARAM_INT);
			$stmt->execute();
		}
		else{
			return "Something went wrong deleting the review.";
		}
		
	}
}
