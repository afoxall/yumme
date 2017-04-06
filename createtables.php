<?php

include_once "inc/constants.inc.php";
// Create connection
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT);;
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

// sql to create table
$sql = "CREATE TABLE Administrator(
	AID int NOT NULL AUTO_INCREMENT,
	UID int NOT NULL,
	PRIMARY KEY (AID),
	FOREIGN KEY (UID) REFERENCES User(UID)
	)";

if ($conn->query($sql) === TRUE) {
    echo "Table Administrator created successfully";
} else {
    echo "Error creating table Administrator: " . $conn->error;
}

// sql to create table
//unique is new, and verification stuff

$sql = "
CREATE TABLE User(
	UID int NOT NULL AUTO_INCREMENT,
	email varchar(50) UNIQUE, 
	FName varchar(50),
	LName varchar(50),
	UName varchar(50),
	password varchar(32),
	ver_code varchar(150),
	verified TINYINT DEFAULT 0,
	PRIMARY KEY (UID)
	)";

if ($conn->query($sql) === TRUE) {
    echo "Table User created successfully";
} else {
    echo "Error creating table User: " . $conn->error;
}

// sql to create table
$sql = "
CREATE TABLE Recipe(
	RID int NOT NULL AUTO_INCREMENT,
	authorID int,
	description varchar(300),
	imagename varchar(100),
	Date DATETIME,
	title varchar(100),
	prepTime int,
	cookTime int,
	difficulty varchar(15),
	PRIMARY KEY (RID),
	FOREIGN KEY (authorID) REFERENCES User(UID)
	ON DELETE CASCADE
	)";

if ($conn->query($sql) === TRUE) {
    echo "Table Recipe created successfully";
} else {
    echo "Error creating table Recipe: " . $conn->error;
}

$sql = 	"
	CREATE TRIGGER recipe_before_insert 
	BEFORE INSERT ON recipe
	FOR EACH ROW
	BEGIN
    IF NOT NEW.difficulty IN ('Easy', 'Intermediate', 'Hard') THEN
        SIGNAL SQLSTATE '12345'
            SET MESSAGE_TEXT = 'check constraint on recipe.difficulty failed';
    END IF;
	END
	";

	if ($conn->query($sql) === TRUE) {
    echo "Recipe trigger created successfully";
} else {
    echo "Error creating recipe trigger: " . $conn->error;
}

//TODO: Instructions and Utensils are both weak entities ehre, change in ER diagram

// sql to create table
$sql = "
CREATE TABLE Instruction(
	RID int,
	StepNum int,
	Text varchar(200),
	FOREIGN KEY (RID) REFERENCES Recipe(RID)
	ON DELETE CASCADE
	)";

if ($conn->query($sql) === TRUE) {
    echo "Table Instruction created successfully";
} else {
    echo "Error creating table Instruction: " . $conn->error;
}

// sql to create table
$sql = "CREATE TABLE Utensil(
	RID int,
	name varchar(20),
	FOREIGN KEY (RID) REFERENCES Recipe(RID)
	ON DELETE CASCADE
	)";

if ($conn->query($sql) === TRUE) {
    echo "Table Utensil created successfully";
} else {
    echo "Error creating table Utensil: " . $conn->error;
}

// sql to create table
$sql = "CREATE TABLE Review(
	RecID int,
	RevID int NOT NULL AUTO_INCREMENT, 
	AuthorID int,
	Date DATETIME,
	text varchar(5000),
	rating int,
	PRIMARY KEY (RevID),
	FOREIGN KEY (RecID) REFERENCES Recipe(RID)
	ON DELETE CASCADE,
	FOREIGN KEY (AuthorID) REFERENCES User(UID)
	ON DELETE CASCADE
	)";
	

if ($conn->query($sql) === TRUE) {
    echo "Table Review created successfully\n";
} else {
    echo "\nError creating table Review: " . $conn->error;
}


$sql = 	"
	CREATE TRIGGER review_before_insert 
	BEFORE INSERT ON review
	FOR EACH ROW
	BEGIN
    IF NEW.rating < 0 OR NEW.rating > 5 THEN
        SIGNAL SQLSTATE '12345'
            SET MESSAGE_TEXT = 'check constraint on review.rating failed';
    END IF;
	END
	";

	if ($conn->query($sql) === TRUE) {
    echo "Review trigger created successfully";
} else {
    echo "Error creating review trigger: " . $conn->error;
}
// sql to create table
$sql = "
CREATE TABLE Ingredient(
	RID int,
	name varchar(50),
	state varchar(30),
	quantity varchar(30),
	FOREIGN KEY (RID) REFERENCES Recipe(RID) ON DELETE CASCADE
	)";

if ($conn->query($sql) === TRUE) {
    echo "\nTable Ingredient created successfully\n";
} else {
    echo "\nError creating table Ingredient: " . $conn->error;
}

// sql to create table
$sql = "
CREATE TABLE Reblog(
	RID int,
	UID int,
	Date DATETIME,
	FOREIGN KEY (RID) REFERENCES Recipe(RID) ON DELETE CASCADE,
	FOREIGN KEY (UID) REFERENCES User(UID) ON DELETE CASCADE
	)";

if ($conn->query($sql) === TRUE) {
    echo "Table Reblog created successfully\n";
} else {
    echo "Error creating table Reblog: " . $conn->error;
}
$sql = "
CREATE TABLE Follow(
	follower int,
	FOREIGN KEY (follower) REFERENCES User(UID) ON DELETE CASCADE,
	followee int,
	FOREIGN KEY (followee) REFERENCES User(UID) ON DELETE CASCADE,
	
	CONSTRAINT UC_Follow UNIQUE (follower,followee)
	)";

if ($conn->query($sql) === TRUE) {
    echo "Table Follow created successfully\n";
} else {
    echo "Error creating table Follow: " . $conn->error;
}

$conn->close();


?>