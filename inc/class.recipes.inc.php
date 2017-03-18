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
		//first get the recipe (we will get the author reviews, ingredients and utensils later)
		$sql = "SELECT * FROM recipe WHERE rid=:rid";	
		
		if($stmt = $this->_db->prepare($sql)){
			$stmt->bindParam(':rid', $_POST['RID']);
			$stmt->execute();
			
			if($stmt->rowCount()==1){
				$recipe = $stmt->fetch();
				$res = "<recipe>
						<author>".$recipe['']."<\author>
						<prep>".$recipe['prepTime']."</prep>
						<cook>".$recipe['cookTime']."</cook>
						<diff>".$recipe['difficulty']."</diff>
						<date>".$recipe['date']."</date>
						<title>".$recipe['title']."</title>";						
			}
			else{
				return "tttt<li> Something went wrong.126 ". $this->_db->errorInfo. "</li>n";
			}
		}
		else{
            return "tttt<li> Something went wrong. 47". $this->_db->errorInfo. "</li>n";
        }
		//time to get author username
		$sql = "SELECT uname FROM user WHERE uid=:uid";	
		
		if($stmt = $this->_db->prepare($sql)){
			$stmt->bindParam(':uid', $recipe['uid']);
			$stmt->execute();
			
			if($stmt->rowCount()==1){
				$row = $stmt->fetch();
				$res .= "<author>".$row['uname']."<\author>";						
			}
			else{
				return "tttt<li> Something went wrong 5. ". $this->_db->errorInfo. "</li>n";
			}
		}
		else
        {
            return "tttt<li> Something went wrong 6. ". $this->_db->errorInfo. "</li>n";
        }
		
		//now get ingredients
		$sql = "SELECT * FROM ingredient WHERE rid=:rid";	
		
		if($stmt = $this->_db->prepare($sql)) {
            $stmt->bindParam(':rid', $_POST['RID']);
            $stmt->execute();

            while ($row = $stmt->fetch()) {
                $res .= "<ingredient>
							<quantity>" . $row['quantity'] . "<\quantity>
							<state>" . $row['state'] . "</state>
							<name>" . $row['name'] . "</name>
						<\ingredient>";
            }
        }

		else
        {
            return "tttt<li> Something went wrong 8. ". $this->_db->errorInfo. "</li>n";
        }
		
		//now get instructions
		$sql = "SELECT * FROM instruction WHERE rid=:rid";	
		
		if($stmt = $this->_db->prepare($sql)){
			$stmt->bindParam(':rid', $_POST['RID']);
			$stmt->execute();
			
			while($row = $stmt->fetch()){
				$res .= "<instruction>
							<num>".$row['stepNum']."<\num>
							<text>".$row['instructionText']."</text>					
						<\instruction>";						
			}
		}
		else
        {
            return "tttt<li> Something went wrong 10. ". $this->_db->errorInfo. "</li>n";
        }
		
		//now get utensils
		$sql = "SELECT * FROM utensil WHERE rid=:rid";	
		
		if($stmt = $this->_db->prepare($sql)){
			$stmt->bindParam(':rid', $_POST['RID']);
			$stmt->execute();
			
			while($row = $stmt->fetch()){
				$res .= "<utensil>
							<name>".$row['name']."<\name>
						<\utensil>";						
			}

		}
		else
        {
            return "tttt<li> Something went wrong 12. ". $this->_db->errorInfo. "</li>n";
        }
		
		//now get reviews
		$sql = "SELECT * FROM review WHERE rid=:rid ORDER BY date LIMIT 10"; //10 is arbitrary for now	
		
		if($stmt = $this->_db->prepare($sql)){
			$stmt->bindParam(':rid', $_POST['RID']);
			$stmt->execute();
			
			while($row = $stmt->fetch()){
				$res .= "<review>
							<rating>".$row['rating']."<\rating>
							<text>".$row['text']."</text>
							<date>".$row['date']."</date>
						<\ingredient>";						
			}

		}
		else
        {
            return "tttt<li> Something went wrong 6. ". $this->_db->errorInfo. "</li>n";
        }
		return $res . "</recipe>";
	}
	
	/*
		takes an array of uids and returns xml with basic info about the n (or less) most recent recipes by all these users.
		Can be used to populate a newsfeed(pass in all follows) or a usr page(pass in only that user's uid. 
	*/
	public function getUsersRecipes($ar, $n){
		
		$ids = join("','",$ar); //this is at risk of injection but ids are never seen or enterd by users so fine
		$sql = "SELECT name, prepTime, cookTime as time, difficulty FROM recipe WHERE uid IN ('%ids') ORDER BY date LIMIT :n"; 
		$res = "";
		
		if($stmt = $this->_db->prepare($sql)) {
            $stmt->bindParam(':n', $n);
            $stmt->execute();

            while ($row = $stmt->fetch()) {
                //try a nested statement
                $ratingsql = "SELECT avg(rating) as a_rating FROM review where rid = :rid";
                if ($stmt = $this->_db->prepare($sql)) {
                    $stmt->bindParam(':rid', $row['rid']);
                    $stmt->execute();
                    $rating = $stmt->fetch()['a_rating'];
                } else {
                    return "tttt<li> Something went wrong 523. " . $this->_db->errorInfo . "</li>n";
                }

                $res .= "<recipe>
							<name>" . $row['name'] . "<\name>
							<ptime>" . $row['prepTime'] . "</ptime>
							<ctime>" . $row['cookTime'] . "</ctime>
							<diff>" . $row['difficulty'] . "</diff>
							<rating>" . $rating . "</rating>
						<\recipe>";
            }

		}
		else
        {
            return "tttt<li> Something went wrong 623. ". $this->_db->errorInfo. "</li>n";
        }
		return $res;
	}
	
	/*
	* Adds a new recipe 
	*/
	public function addRecipe(){ 
		
		$xml=simplexml_load_string($_POST['rec']) or die("Error: Cannot create object");
		
		$sql = "INSERT INTO recipe (title, prepTime, cookTime, Difficulty, date) VALUES
				(:name, :prep, :cook, :diff, now())";
				
		if($stmt = $this->_db->prepare($sql)){
		    $name = $xml->name;
		    $prep = $xml->prep;
		    $cook = $xml->cook;
		    $difficulty = $xml->difficulty;

			$stmt->bindParam(':name', $name, PDO::PARAM_STR);
			$stmt->bindParam(':prep', $prep, PDO::PARAM_INT);
			$stmt->bindParam(':cook', $cook, PDO::PARAM_INT);
			$stmt->bindParam(':diff', $difficulty, PDO::PARAM_STR);
			$stmt->execute();
		}
		else
		{
			return "tttt<li> Something went wrong.368 ". $this->_db->errorInfo. "</li>n";
		}	
		$rid = $this->_db->lastInsertIDd();
		
		//now add instructoins
		$sql = "INSERT INTO instructions (rid, stepNum, instructionText) VALUES";
		
		foreach($xml->instructions as $in){
			$sql .= "($rid, :num, :text)";
		}
		
		if($stmt = $this->_db->prepare($sql)){
		    $num = $in->num;
		    $text = $in->text;
			$stmt->bindParam(':num', $in, PDO::PARAM_INT);
			$stmt->bindParam(':text', $in, PDO::PARAM_INT);
			$stmt->execute();
		}
		else
		{
			return "tttt<li> Something went wrong.364 " . $this->_db->errorInfo . "</li>n";
		}
		
		//and ingredients 
		$sql = "INSERT INTO ingredient (rid, quantity, state, name) VALUES";
		
		foreach($xml->ingredients as $ing){
			$sql .= "($rid, :quant, :state, :name)";
		}
		
		if($stmt = $this->_db->prepare($sql)){
		    $num = $ing->name;
		    $state = $ing->state;
		    $name = $ing->name;
			$stmt->bindParam(':quant', $num, PDO::PARAM_STR);
			$stmt->bindParam(':state', $state, PDO::PARAM_STR);
			$stmt->bindParam(':name', $name, PDO::PARAM_STR);
			$stmt->execute();
		}
		else
		{
			return "tttt<li> Something went wrong.369 ". $this->_db->errorInfo. "</li>n";
		}
		
		//and utensils 
		$sql = "INSERT INTO utensil (rid, name) VALUES";
		
		foreach($xml->ingredients as $ute){
			$sql .= "($rid, :name)";
		}
		
		if($stmt = $this->_db->prepare($sql)){
		    $name = $ute->name;
			$stmt->bindParam(':name', $name, PDO::PARAM_STR);
			$stmt->execute();
		}
		else
		{
			return "tttt<li> Something went wrong.363 ". $this->_db->errorInfo. "</li>n";
		}
	}
	/*
	* Adds a new recipe 
	*/
	public function addReview(){ 
		$sql = "INSERT INTO review (rid, rating, text, date) VALUES (:rid, :rating, :text, now())";
		
		if($stmt = $this->_db->prepare($sql)){
		    $stmt->bindParam(':rid', $_POST['rid'], PDO::PARAM_INT);
			$stmt->bindParam(':rating', $_POST['r'], PDO::PARAM_INT);
			$stmt->bindParam(':text', $_POST['t'], PDO::PARAM_STR);
			$stmt->execute();
		}
		else
		{
			return "tttt<li> Something went wrong.563 ". $this->_db->errorInfo. "</li>n";
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
