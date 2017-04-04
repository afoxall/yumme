<?php

/*
		add follow
		remove follow
		remove follower
		view all follows
		view all followers
*/
class FollowManager{
	
	private $_db;

	public function __construct($db=null)
    {
        if (is_object($db)) {
            $this->_db = $db;
        } else {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
            $this->_db = new PDO($dsn, DB_USER, DB_PASSWORD);
        }
    }
	
	/*
	* Adds a new Follow to the current user
	* POST contains the uid desired followee 
	*/
	public function addFollow(){ 
		
		if(isset($_GET['u'])){

			$uid = $_GET['u'];
		}
		else{
		    header("Location: /index.php");
        }

        if($uid == $_SESSION['UID']){

            header("Location: /index.php");

            return;
        }
		
		$sql = "INSERT IGNORE INTO Follow SET follower = :follower, followee = :followee";

		if($stmt = $this->_db->prepare($sql)){

			$stmt->bindParam(':follower', $_SESSION['UID']);
			$stmt->bindParam(':followee', $uid);
			$stmt->execute();
		}
		else
		{
			return "tttt<li> Something went wrong. " . $this->_db->errorInfo+"</li>n";
		}	
	
	}
	
	public function removeFollow(){ 
		if(isset($_POST['uid'])){
			$uid = $_POST['uid'];	
		}

		else{
			return "tttt<li> I need either the uid or uname. " . $this->_db->errorInfo . "</li>n";
			return;
		}
		
		$sql = "DELETE FROM Follow WHERE follower = :follower, followee = :followee";
		
		if($stmt = $this->_db->prepare($sql)){
			$stmt->bindParam(':follower', $_SESSION['UID']);
			$stmt->bindParam(':followee', $uid);
			$stmt->execute();
		}
		else
		{
			return "tttt<li> Something went wrong. " . $this->_db->errorInfo . "</li>n";
		}	
	}
	//allows someone to block one of their followers (not permanent, that would need a new table)
	public function removeFollower(){ 
		if(isset($_POST['uid'])){
			$uid = $_POST['uid'];	
		}

		else{
			return "tttt<li> I need the uid. " . $this->_db->errorInfo . "</li>n";
			return;
		}
		
		$sql = "DELETE FROM Follow WHERE follower = :follower, followee = :followee";
		
		if($stmt = $this->_db->prepare($sql)){
			$stmt->bindParam(':followee', $_SESSION['UID']);
			$stmt->bindParam(':follower', $uid);
			$stmt->execute();
		}
		else
		{
			return "tttt<li> Something went wrong. ". $this->_db->errorInfo. "</li>n";
		}	
		
	}
	
	/*
		return an array of uids all users following the current user
	*/
	public function getFollowers(){
		
		//get current user's uid
		$sql = "SELECT UName, UID FROM Follow join User on follow.follower = user.UID  WHERE follow.followee = :uid";
		
		$usernames = array();
		$uids = array();

		if($stmt = $this->_db->prepare($sql)){
			$stmt->bindParam(':uid', $_SESSION['UID']);
			$stmt->execute();
			
			$count = 0;
			while($row = $stmt->fetch()){
				$usernames[$count] = $row['uname'];
				$uids[$count] = $row['uid'];
				$count++;
			}
			$stmt->closeCursor();
		}
		else
        {
            return "tttt<li> Something went wrong. " . $this->_db->errorInfo. "</li>n";
        }
		return [$usernames, $uids];
	}
	/*
	* return back xml containing all of the current user's follows (uname, uid for now)
	*/
	public function getFollows(){
		//get current user's uid
		//$sql = "SELECT uid, uname FROM  user, follows
		//where follows.follower = :uid and follows.followee = user.uid"; // TODO sketchy, defs test

        $sql = "SELECT UName, UID FROM Follow join User on follow.followee = user.UID  WHERE follow.follower = :uid";
		$usernames = array();
		$uids = array();

		if($stmt = $this->_db->prepare($sql)){
			$stmt->bindParam(':uid', $_SESSION['UID']);
			$stmt->execute();
			
			$count = 0;
			while($row = $stmt->fetch()){
				/*$res.= "<user>
							<uname>".$row['uname']."</uname>
							<uid>".$row['uid']."</uid>
						</user>";*/
                array_push($usernames, $row['uname']);
                array_push($uids, $row['uid']);
			}
			$stmt->closeCursor();
		}
		else
        {
            return "tttt<li> Something went wrong. ". $this->_db->errorInfo. "</li>n";
        }
		return [$usernames, $uids];
	}
	}
	?>