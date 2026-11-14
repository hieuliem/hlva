<?php
    // Accepts comments and replies from the client and puts them in the database
    $path = isset($path)? $path : '';
    // defined database connection strings: DB_HOST, DB_USER, DB_PSWD, DB_NAME 
    require $path.'dbconnect.php';
    
    // Who will the mail with a copy of the comment be sent to 
    define("EMAILADDRESS", "ellbee2010@gmail.com, hoang-tiep@hotmail.com");
            
    define('ECHOTEXT', false);
    define('MINPAGEID', 0);                 // Unique Identifier of page lower limit must be 1 so 0 cn indicate 'no reply'
    define('MAXPAGEID', 10000);             // Unique Identifier of page upper limit to stop silly numbers
    define('MINIDREPLYTO', 0);              // 0 indicates 'no reply'
    define('MAXIDREPLYTO', 10000);          // Stop silly numbers
    
    $response = array();
    
    
    class Comment
    {
        private $mysqli = NULL;                                        // MySQLi connection object
        
        public function __construct ()
        {
            // Already defined database connection strings: DB_HOST, DB_USER, DB_PSWD, DB_NAME 
            
            $this->mysqli = new mysqli(DB_HOST, DB_USER, DB_PSWD, DB_NAME);
            if ($this->mysqli->connect_errno) {
                $response = "Failed to connect to MySQL: (" . $this->mysqli->connect_errno . ") " . $this->mysqli->connect_error;
                trigger_error($response, E_USER_ERROR);
            }
        }
        
        function __destruct() 
        {
            $this->mysqli->close();
        }
   
        /** Get a comment using its idcomment field. 
            The comment is returned inside the same html as is used to dislay a reply, though the two are not synchronized as yet
            and are maintained separately.
         */
        public function get($_idcomment)
        {
            if(is_numeric($_idcomment)===FALSE){
                trigger_error('idcomment ' . $_idcomment . ' is not numeric', E_USER_ERROR);
                return false;
            }
            
            if(is_null($this->mysqli)){
                trigger_error("Database connection not established", E_USER_ERROR);
                return false;
            }
            
            if (!($stmt = $this->mysqli->prepare('SELECT IdComment, IdReplyTo, Subject, Name, DATE_FORMAT(TimeStamp,"%m/%d/%Y %k:%i"), Comment FROM comments WHERE IdComment = ?'))) {
                trigger_error("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error, E_USER_ERROR);
                return false;
            }

            settype ( $pageid , "integer");

            /* bind parameters for markers */
            $stmt->bind_param("i", $_idcomment);


            if (!$stmt->execute()) {
                trigger_error("Execute failed: (" . $mysqli->errno . ") " . $mysqli->error, E_USER_ERROR);
                return false;
            }

            $IdComment  = NULL;
            $IdReplyTo  = NULL;
            $Subject    = NULL;
            $Name       = NULL;
            $TimeStamp  = NULL; 
            $Comment    = NULL;

            if (!$stmt->bind_result($IdComment, $IdReplyTo, $Subject, $Name, $TimeStamp, $Comment)) {
                trigger_error("Binding output failed: (" . $mysqli->errno . ") " . $mysqli->error, E_USER_ERROR);
                return false;
            }

            while ($stmt->fetch()){
				$html = "";
				if(empty($IdReplyTo)){
					$html .= '<div class="cmt-cnt level0" id="'.$IdComment.'" data-id="'.$IdComment.'">';
				}else{					
					$html .= '<div class="cmt-cnt level1" id="'.$IdComment.'" data-id="'.$IdReplyTo.'">';
				}
				$html .= '<img src="http://www.gravatar.com/avatar/d41d8cd98f00b204e9800998ecf8427e?d=mm&s=35" /><div class="thecom">';
				$html .= '<span class="poster">'.$Name.': </span><span class="subject">'.$Subject.'</span><span class="com-dt">'.$TimeStamp.'</span><br><p>'.$Comment.'</p></div>';
				if(empty($IdReplyTo)){
					$html .= '<a href="#" class="replylink" id="'.$IdComment.'" data-id="'.$IdComment.'" >Reply</a>';
				}else{					
					$html .= '';
				}
				$html .= "</div>";
            }
            return $html;
        }
        
        public function add($_name, $_email, $_subject, $_comment, $_idreplyto, $_pageid)
        {
            
            if(!is_string($_name)){
                trigger_error("Name is not a string", E_USER_ERROR);
                return false;            
            }
            
            if(!is_string($_email)){
                trigger_error("Email is not a string", E_USER_ERROR);
                return false;            
            }
            
            if(!is_string($_subject)){
                trigger_error("Subject is not a string", E_USER_ERROR);
                return false;            
            }
            
            if(!is_string($_comment)){
                trigger_error("Comment is not a string", E_USER_ERROR);
                return false;            
            }
            
            if(is_numeric($_idreplyto)===FALSE){
                trigger_error("Idreplyto is not numeric", E_USER_ERROR);
                return false;            
            }
            
            if(is_numeric($_pageid)===FALSE){
                trigger_error("pageid is not numeric", E_USER_ERROR);
                return false;
            }
            
            $name       = null;
            $email      = null;  
            $subject    = null;
            $comment    = null;
            $idreplyto  = -1;
            $pageid     = -1;
   
     
            $name = filter_var($_name, FILTER_SANITIZE_STRING);
            if(FALSE == $name){
                trigger_error("Name did not pass the input filter", E_USER_ERROR);
                return false;            
            }
            
            $subject = filter_var($_subject, FILTER_SANITIZE_STRING);
            if(FALSE == $subject){
                trigger_error("Subject did not pass the input filter", E_USER_ERROR);
                return false;            
            }
            
            $email = filter_var($_email,   FILTER_VALIDATE_EMAIL);
            if(FALSE == $subject){
                trigger_error("Email did not pass the input filter", E_USER_ERROR);
                return false;            
            }
            
            $comment  = filter_var($_comment, FILTER_SANITIZE_STRING);
            if(FALSE == $comment){
                trigger_error("Comment did not pass the input filter", E_USER_ERROR);
                return false;            
            }
            
            $pageid = filter_var($_pageid,    FILTER_VALIDATE_INT,    array("options" => array("min_range" => MINPAGEID, "max_range"=> MAXPAGEID)));
            if(FALSE === $pageid){
                trigger_error("pageid did not pass the input filter", E_USER_ERROR);
                return false;            
            }
            
            $idreplyto = filter_var($_idreplyto, FILTER_VALIDATE_INT,    array("options" => array("min_range" => MINIDREPLYTO, "max_range"=>MAXIDREPLYTO)));
            if(FALSE === $idreplyto){
                trigger_error("idreplyto did not pass the input filter", E_USER_ERROR);
                return false;            
            }
     
            // Send an email
            $to = EMAILADDRESS;
            $headers = "From: $email";
            $body = $comment . "\nfrom " . $name ."\n\nThis email was generated once a new comment made from http://thanhca.chungnhan.org \n\nCĐ. Seraphim";
            mail($to, $subject, $body, $headers);
			//mail_utf8($to, $subject, $body, $headers);
  
            // Check database connection is already in place          
            if(is_null($this->mysqli)){
                trigger_error("Database connection not established", E_USER_ERROR);
                return false;
            }
              
            settype ( $pageid , "integer");
                
            if($idreplyto == "0")
            {
                settype ( $idreply , "integer");
                
                $stmt = $this->mysqli->prepare("INSERT INTO comments(IdPage, Name, Subject, Comment) VALUES (?,?,?,?)");
                if (!($stmt)) 
                {
                    $response = "Prepare failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;
                    trigger_error($response, E_USER_ERROR);
                    $this->mysqli->close();
                    return false;            
                }        
                /* bind parameters for markers */
                $stmt->bind_param("isss", $pageid, $name, $subject, $comment);
                
            }
            else
            {
                $stmt = $this->mysqli->prepare("INSERT INTO comments(IdPage, Name, Subject, Comment, IdReplyTo) VALUES (?,?,?,?,?)");
                if (!($stmt)) 
                {
                    $response = "Prepare failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;
                    $this->mysqli->close();
                    trigger_error($response, E_USER_ERROR);
                    return false;            
                }        
                
                /* bind parameters for markers */
                $stmt->bind_param("isssi", $pageid, $name, $subject, $comment, $idreplyto);
            }
            
            

            /* execute prepared statement */
            if(!$stmt->execute())
            {
                $response = "Execute failed: (" . $this->mysqli->errno . ") " . $this->mysqli->error;
                $this->mysqli->close();
                trigger_error($response, E_USER_ERROR);
                return false;            
            }
            /* Return the auto incremented id so this record can be retirieved if needed */
            $recordid = $this->mysqli->insert_id;
                        
            return $recordid;
        }
		
		function mail_utf8($to, $subject = '(No subject)', $message = '', $header = '') {
			$header_ = 'MIME-Version: 1.0' . "\r\n" . 'Content-type: text/plain; charset=UTF-8' . "\r\n";
			mail($to, '=?UTF-8?B?'.base64_encode($subject).'?=', $message, $header_ . $header);
		}
    }   
?>