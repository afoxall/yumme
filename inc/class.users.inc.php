<?php
/**Classes:

	UserManager: 
		key:
		create user
		verify user
		check if admin
		populate timeline with recipes from follows
		
		secondary:
		update account info
		delete account
		update password
		reset forgotten password	

	*/
class UserManager{
	private $_db;

	public function __construct($db=null){
		if(is_object($db)){
			$this->_db = $db;
		}
		else{
			$dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME;
			$this->_db = new PDO($dsn, DB_USER, DB_PASSWORD);
		}
	}
		
	/*
	*Registers a new user. Should throw an error if the email is already in use (check this)
	*/
	public function createAccount(){
		
		$email = trim($_POST['signupemail']);
		$uname = trim($_POST['uname']);
		
		$v = sha1(time());
		//check for previous uses of that username
		$sql = "SELECT COUNT(UName) AS theCount FROM User where UName=:uname";
		
		if($stmt = $this->_db->prepare($sql)){
			$stmt->bindParam(":uname", $uname, PDO::PARAM_STR);
			$stmt->execute();
			$row = $stmt->fetch();
			if($row['theCount']!=0){
				return "Sorry, that username is already on the system.";
			}
		}
		
		$sql = "SELECT COUNT(email) AS theCount FROM User where email=:email";

		if($stmt = $this->_db->prepare($sql)){
			$stmt->bindParam(":email", $email, PDO::PARAM_STR);
			$stmt->execute();
			$row = $stmt->fetch();
			if($row['theCount']!=0){
				return "Sorry, that email is already on the system. ";
			}
			if(!$this->sendVerificationEmail($email, $v)){
				return "There was an error sending the verification email.";
			}
			$stmt ->closeCursor();
		}
		
		$sql = "INSERT INTO User(email, UName, ver_code) VALUES(:email, :uname, :ver)";
		if($stmt = $this->_db->prepare($sql)) {
			$stmt->bindParam(":email", $email, PDO::PARAM_STR);
			$stmt->bindParam(":ver", $v, PDO::PARAM_STR);
			$stmt->bindParam(":uname", $uname, PDO::PARAM_STR);
			$stmt->execute();
			$stmt->closeCursor();
		}
		else{
			return "Could not create the user";
		}
	}

	private function sendVerificationEmail($email, $ver){
		$e = sha1($email);
		$to = trim($email);
		
		$subject = "[Yumlee] Please verify your account";
		
		$headers = <<<MESSAGE
From: Yumlee <foxallster@gmail.com>
Content-Type:text/plain;
MESSAGE;
		$root = $_SERVER['SERVER_NAME'];
		$msg = <<<EMAIL
You have created an account at Yumlee. Please click the activation ink below to finalize your account.

http://$root/register.php?v=$ver&e=$e
	
Thanks!
EMAIL;
        $res = mail($to, $subject, $msg, $headers);
        echo "<h3 style=\"color:#141823;\">Email Sent</h3>";
        echo "<h3 style=\"color:#141823;\">$msg</h3>";
		return $res;
	}
	
	/*
	* takes an email and verification code from the verification email and verifies the account
	*/
	public function verifyAccount(){
		$sql = "SELECT UID, UName, email from User WHERE ver_code = :ver AND SHA1(email) = :email AND verified = 0";


		if($stmt = $this->_db->prepare($sql)){
			$stmt->bindParam(':ver', $_GET['v'], PDO::PARAM_STR);
			$stmt ->bindParam(':email', $_GET['e'], PDO::PARAM_STR);
			$stmt->execute();


			$row = $stmt->fetch();

			if(isset($row['email'])){
				$_SESSION['UID'] = $row['UID'];
                $_SESSION['UNAME'] = $row['UName'];
				$_SESSION['LoggedIn'] = 1;
				$_SESSION['ISADMIN'] = 0;
			}
			else{
				return array(4, "<h2>Verification error. This account has already been verified.</h2>");
			}
			$stmt->closeCursor();

			return array(0, null);
		}
		else{
			return array(2, "Database error");
		}
	}

	public function updateInfo(){
		if(isset($_POST['p']) && isset($_POST['r'])
			&& isset($_POST['fn'])&& isset($_POST['fn'])
			&& $_POST['p'] == $_POST['r']){



			$sql = "UPDATE User SET `password` = MD5(:pass), verified = 1, FName = :fname, LName = :lname WHERE ver_code = :ver LIMIT 1";
			try{
				$stmt = $this->_db->prepare($sql);
				$stmt->bindParam(":pass", $_POST['p'], PDO::PARAM_STR);
				$stmt->bindParam(":lname", $_POST['ln'], PDO::PARAM_STR);
				$stmt->bindParam(":fname", $_POST['fn'], PDO::PARAM_STR);
				$stmt->bindParam(":ver", $_POST['v'], PDO::PARAM_STR);
				$stmt->execute();
				$stmt->closeCursor();

				return True;
			}
			catch(PDOException $e){

				return FALSE;
			}
		}
		else{
			echo "Something sent wrong";
			sleep(5);
			return FALSE;
		}
	}
	
	function accountLogin(){
		
		$sql = "SELECT email, UName, UID from User where email=:email AND `password` = MD5(:pass) LIMIT 1";
		
		try{
			$stmt = $this->_db->prepare($sql);
			$stmt->bindParam(":email", $_POST['email'], PDO::PARAM_STR);
			$stmt->bindParam(":pass", $_POST['password'], PDO::PARAM_STR);

			$stmt->execute();
			
			if($stmt->rowCount()==1){
					$row = $stmt->fetch();
					$_SESSION['UID'] = $row['uid'];
					$_SESSION['LoggedIn'] = 1;
					$_SESSION['UNAME'] = $row['uname'];
			}
			else{
				return FALSE;
			}
		}
		catch(PDOException $e){
			return FALSE;
		}
		$sql = "SELECT * from Administrator where UID=:uid";
        try{
            $stmt = $this->_db->prepare($sql);
            $stmt->bindParam(":uid", $_SESSION['UID'], PDO::PARAM_STR);
            $stmt->execute();

            if($stmt->rowCount()==1){
				$_SESSION['ISADMIN'] = 1;

            }
            else{
                $_SESSION['ISADMIN'] = 0;
            }
        }
        catch(PDOException $e){
            return FALSE;
        }
        return TRUE;
	}
	
	//skipping modifying account information, not critical
	
	public function resetPassword(){
		$sql = "UPDATE User SET verified=0 WHERE email = :email LIMIT 1";
		
		try{
			$stmt = $this->$this->_db->prepare($sql);
			$stmt->bindParam(":email", $_POST['email'], PDO::PARAM_STR);
			$stmt->execute();
			$stmt->closeCursor();
		}catch(PDOException $e){
			return $e->getMessage();
		}
		
		if(!$this->sendResetEmail($_POST['email'], $_POST['v'])){
			return "Sending the email failed";
		}
		return TRUE;
	}
	private function sendResetEmail($email, $ver){
		$e = sha1($email);
		$to = trim($email);
		
		$subject = "[Yumlee] Request to reset your account";
		
		$headers = <<<MESSAGE
From: Yumlee <foxallster@gmail.com>
Content-Type:text/plain;
MESSAGE;
		$msg = <<<EMAIL
You have have requested to rest your account password. Click the link below to do so.

http://localhost:8080/resetpassword.php?v=$ver&e=$e
	
Thanks!
EMAIL;



	return mail($to, $subject, $msg, $headers);
	}
	
		
	/*
	* Admin functoin to remove bad users. expects uid in post
	*/
	public function deleteUser(){
		$sql = "SELECT COUNT(AID) AS theCount FROM Administrator where UID=:uid";

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
		
		//TODO: send the deleted user an email telling them they have been deleted
		
		$sql = "DELETE FROM User WHERE UID=:uid";
		if($stmt = $this->_db->prepare($sql)){
			$stmt->bindParam(":uid", $_GET['u'], PDO::PARAM_INT);
			$stmt->execute();
		}
		else{
			return "Something went wrong deleting the user.";
		}

		return "done";
	}

	public function addAdmin(){
        $sql = "SELECT COUNT(AID) AS theCount FROM Administrator where UID=:uid";

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

        //TODO: send the deleted user an email telling them they have been deleted

        $sql = "INSERT INTO Administrator SET  UID=:uid";
        if($stmt = $this->_db->prepare($sql)){
            $stmt->bindParam(":uid", $_GET['adda'], PDO::PARAM_INT);
            $stmt->execute();
        }
        else{
            return "Something went wrong deleting the user.";
        }

        return "done";
	}

	/*
	 * This function is used to find users by "firstname lastname" or username
	 *
	 */
	public function findUser(){
		$val = $_POST['user'];

        $pieces = explode(" ", $val);
		if(count($pieces) > 1){

			$sql = "SELECT * FROM User WHERE FName=:fn AND LName=:ln";
            if($stmt = $this->_db->prepare($sql)){
                $stmt->bindParam(":fn", $pieces[0], PDO::PARAM_STR);
                $stmt->bindParam(":ln", $pieces[1], PDO::PARAM_STR);
                $stmt->execute();

            }
            else{
                return "Something went wrong deleting the user.";
            }

		}
		else{
			$sql = "SELECT * FROM User WHERE UName=:u";

            if($stmt = $this->_db->prepare($sql)){
                $stmt->bindParam(":u", $pieces[0], PDO::PARAM_STR);

                $stmt->execute();
            }
            else{
                return "Something went wrong deleting the user.";
            }
		}
		if($stmt->rowCount()>0){
        	$row = $stmt->fetch();

        	header("Location: /userprofile.php?u=".$row['UID']."&uname=".$row['UName']);

		}
		else{
			header("Location: /index.php");
		}

	}
	
}
	?>
	