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
	* Adds a new follow to the current user
	* POST contains the uid desired followee 
	*/
	public function addFollow(){ 
		
		if(isset($_POST['uid'])){
			$uid = $_POST['uid'];	
		}
		/*
		else if(isset($_POST['uname'])){
			$sql = "select uid FROM user WHERE uname = :uname";
		
			if($stmt = $this->_db->prepare($sql)){
				$stmt->bindParam(':uname', $_POST['uname']);
				$stmt->execute();
				
				$uid = $stmt->fetch()['uid'];
			}
			else
			{
				return "tttt<li> Something went wrong getting the uid. ", $db->errorInfo, "</li>n";
			}
		}*/
		else{
		    $err = $this->_db->errorInfo;
		    return "tttt<li> I need the uid. " . $err . "</li>n";
			
		}
		
		$sql = "INSERT IGNORE INTO follows SET follower = :follower, followee = :followee";
		
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
		/*else if(isset($_POST['uname'])){
			$sql = "select uid FROM user WHERE uname = :uname";
		
			if($stmt = $this->_db->prepare($sql)){
				$stmt->bindParam(':uname', $_SESSION['uname']);
				$stmt->execute();
			}
			else
			{
				return "tttt<li> Something went wrong getting the uid. ", $db->errorInfo, "</li>n";
			}
		}*/
		else{
			return "tttt<li> I need either the uid or uname. " . $this->_db->errorInfo . "</li>n";
			return;
		}
		
		$sql = "DELETE FROM follows WHERE follower = :follower, followee = :followee";
		
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
		/*else if(isset($_POST['uname'])){
			$sql = "select uid FROM user WHERE uname = :uname";
		
			if($stmt = $this->_db->prepare($sql)){
				$stmt->bindParam(':uname', $_SESSION['uname']);
				$stmt->execute();
			}
			else
			{
				return "tttt<li> Something went wrong getting the uid. ", $db->errorInfo, "</li>n";
			}
		}*/
		else{
			return "tttt<li> I need the uid. " . $this->_db->errorInfo . "</li>n";
			return;
		}
		
		$sql = "DELETE FROM follows WHERE follower = :follower, followee = :followee";
		
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
		$sql = "SELECT uid FROM follows WHERE followee = :uid";
		
		$res = array();
		if($stmt = $this->_db->prepare($sql)){
			$stmt->bindParam(':uid', $_SESSION['UID']);
			$stmt->execute();
			
			$count = 0;
			while($row = $stmt->fetch()){
				$res[$count] = $row['uid'];
				$count++;
			}
			$stmt->closeCursor();
		}
		else
        {
            return "tttt<li> Something went wrong. " . $this->_db->errorInfo. "</li>n";
        }
		return $res;
	}
	/*
	* return back xml containing all of the current user's follows (uname, uid for now)
	*/
	public function getFollows(){
		//get current user's uid
		//$sql = "SELECT uid, uname FROM  user, follows
		//where follows.follower = :uid and follows.followee = user.uid"; // TODO sketchy, defs test

        $sql = "SELECT followee from follow where follower=:uid";
		$res = array();
		if($stmt = $this->_db->prepare($sql)){
			$stmt->bindParam(':uid', $_SESSION['UID']);
			$stmt->execute();
			
			$count = 0;
			while($row = $stmt->fetch()){
				/*$res.= "<user>
							<uname>".$row['uname']."</uname>
							<uid>".$row['uid']."</uid>
						</user>";*/
                array_push($res, $row['followee']);

			}
			$stmt->closeCursor();
		}
		else
        {
            return "tttt<li> Something went wrong. ". $this->_db->errorInfo. "</li>n";
        }
		return $res;
	}
	}
	?>