<!DOCTYPE html>
<html lang="en-gb">
<HEAD>
    
    <?php $ArticleId = 4; ?>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="author" content="Jonathan Davies"/>
    <link rel="stylesheet" type="text/css" href="jgdcss2.css" media="screen"/>
   
</HEAD>
<BODY>
<h1>Creation of database table used for retrieving comments and replies</h1>
<p>
This file creates the database table used to store comments and replies, but will not delete an existing table.
</p>
<p>
<?php
	$path = isset($cmtPath)? $cmtPath : '';
    // refer to database connection variables HOST, USER, MYWORD, and NAME in a subdirectory
    require $path.'dbconnect.php';

	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PSWD, DB_NAME);
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    }
    
    // Select 1 from table_name will return false if the table does not exist.
    $val = $mysqli->query('select 1 from democomments');
    if($val !== FALSE)
    {
       echo "Table already exists.<br> Save existing data if required, then before recreating (if that is your intention) delete existing table.<br>";
    }
    else
    {
        echo "Existing table not found. <br>";
    }
    
    if (!($stmt = $mysqli->prepare('CREATE TABLE IF NOT EXISTS comments (
		            IdComment INT(11) NOT NULL AUTO_INCREMENT, 
		            IdPage INT(11) NOT NULL,
		            Name VARCHAR(30) NOT NULL, 
		            Subject VARCHAR(60) NOT NULL, 
		            Comment VARCHAR(1000) NOT NULL,
		            TimeStamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
		            IdReplyTo Int(11) DEFAULT NULL,
		            PRIMARY KEY(IdComment),
                    CONSTRAINT FK_democomments_democomments FOREIGN KEY(IdReplyTo) REFERENCES democomments(IdComment)
                    ON DELETE CASCADE ON UPDATE CASCADE)'))) {
        echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
    }
    
    
    $stmt->execute();
    
    // Select 1 from table_name will return false if the table does not exist.
    $val = $mysqli->query('select 1 from comments');
    if($val !== FALSE)
    {
        echo "Table ready for use.<br> ";
    }
    else
    {
        echo "Table not found after calling CREATE.<br>";
        echo "Table create failed: (" . $mysqli->errno . ") " . $mysqli->error . "<br>";
    }
    
?>
</p>
</BODY>
</HTML>
