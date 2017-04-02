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
			$this->_db = new PDO($dsn, DB_USER, DB_PASS);
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
		$sql = "SELECT COUNT(uname) AS theCount FROM user where uname=:uname";
		
		if($stmt = $this->_db->prepare($sql)){
			$stmt->bindParam(":uname", $uname, PDO::PARAM_STR);
			$stmt->execute();
			$row = $stmt->fetch();
			if($row['theCount']!=0){
				return "Sorry, that username is already on the system.";
			}
		}
		
		$sql = "SELECT COUNT(email) AS theCount FROM user where email=:email";

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
		
		$sql = "INSERT INTO user(email, uname, ver_code) VALUES(:email, :uname, :ver)";
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
		$msg = <<<EMAIL
You have created an account at Yumlee. Please click the activation ink below to finalize your account.

http://localhost:8080/yumme/register.php?v=$ver&e=$e
	
Thanks!
EMAIL;
        $res = mail($to, $subject, $msg, $headers);
        echo "Email Sent";
		return $res;
	}
	
	/*
	* takes an email and verification code from the verification email and verifies the account
	*/
	public function verifyAccount(){
		$sql = "SELECT * from user WHERE ver_code = :ver AND SHA1(email) = :email AND verified = 0";


		if($stmt = $this->_db->prepare($sql)){
			$stmt->bindParam(':ver', $_GET['v'], PDO::PARAM_STR);
			$stmt ->bindParam(':email', $_GET['e'], PDO::PARAM_STR);
			$stmt->execute();


			$row = $stmt->fetch();

			if(isset($row['email'])){
				$_SESSION['UID'] = $row['UID'];
				$_SESSION['LoggedIn'] = 1;
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



			$sql = "UPDATE user SET `password` = MD5(:pass), verified = 1, fname = :fname, lname = :lname WHERE ver_code = :ver LIMIT 1";
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
		
		$sql = "SELECT email, uname, uid from user where email=:email AND `password` = MD5(:pass) LIMIT 1";
		
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
					return TRUE;
			}
			else{
				return FALSE;
			}
		}
		catch(PDOException $e){
			return FALSE;
		}
	}
	
	//skipping modifying account information, not critical
	
	public function resetPassword(){
		$sql = "UPDATE user SET verified=0 WHERE email = :email LIMIT 1";
		
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

http://localhost:8080/yumme/resetpassword.php?v=$ver&e=$e
	
Thanks!
EMAIL;


	
	return mail($to, $subject, $msg, $headers);
	}
	
		
	/*
	* Admin functoin to remove bad users. expects uid in post
	*/
	public function deleteUser(){
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
		
		//TODO: send the deleted user an email telling them they have been deleted
		
		$sql = "DELETE FROM user WHERE uid=:uid";
		if($stmt = $this->_db->prepare($sql)){
			$stmt->bindParam(":uid", $_POST['uid'], PDO::PARAM_INT);
			$stmt->execute();
		}
		else{
			return "Something went wrong deleting the user.";
		}
	}
	
}
	?>
	