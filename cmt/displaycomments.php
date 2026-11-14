<?php
    // Reads comments from a database and displays those comments in a list with any replies in a separate ist below the comment being replied to.
    // Intended to be called from webpages that want to: display comments and allow replys; and to add formating - or directly with unformatted output.
    // Comments and Replies are identified by different classes for use by CSS or script. There are two levels: Comments and replies. Comments are 
    // given an ID (the primary key from the db) so a Reply can use it to show which comment the reply is for. 
    // Author Jonathan Davies, JGD PRojects Ltd 2013
    
    // defined database connection strings: DB_HOST, DB_USER, DB_PSWD, DB_NAME 
	$path = isset($cmtPath)? $cmtPath : '';
    require $path.'dbconnect.php';

    
    function displaycomments($ArticleId){
        $connect = mysqli_connect(DB_HOST, DB_USER, DB_PSWD, DB_NAME);
        
        // Check connection
        if (mysqli_connect_errno())
        {
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }

        if(!$connect)
	      {
		      die("Failed to connect to database: " . mysqli_error());
	      } 
                
        $query = 'SELECT IdComment, IdReplyTo, Name, Subject, DATE_FORMAT(TimeStamp,"%m/%d/%Y %k:%i"), Comment FROM comments WHERE IdPage = ' . $ArticleId . ' ORDER BY COALESCE ( IdReplyTo, IdComment) DESC';
        
        
        $result = mysqli_query($connect, $query);
        
        //mysqli_store_result ($result);
        
        /* Wether there are 0 comments (rows) for this page or >0 still need to put the 'new comment' link and comment container on the page.
           The 'new comment' link for the user, and the comment-container so javascript knows where to put any comment when its made */
        
        /* Need to know if there are any comments yet... */
        $rowcount = mysqli_num_rows($result);
        
        // New comment link
        echo '<div id="newcomment_container">' . "\n";
        if(!$rowcount){
			   printf('<div class="newcomment"><div class="new-com-bt"><span>Write a comment ...</span></div></div>');
        }
		
        if($rowcount){
          printf('<div class="newcomment"><div class="new-com-bt"><span>Write a comment ...</span></div></div>');
        }    
        echo '</div>' . "\n<br>\n";
        
        /* Start the enclosure of 0 or more comments in one DIV */
        echo '<br><div id="cmt-container">' . "\n";
        
        
        /* Don't think the 'prevlev' and 'thislevel' thing is needed any more since I dumped the use of lists and sub lists.
           (In first version) Need(ed) to detect changes in comment level to start or end lists and for this require the current level and the 
           level of the previous level so these variables are used for that */
        $prevlevel = 0;
        $thislevel = 0;
        $indent    = "";
        
        $default = "mm";
        $size = 35;
		    $email = "me@example.com";
        $grav_url = "http://www.gravatar.com/avatar/".md5(strtolower(trim($email)))."?d=".$default."&s=".$size;
  
        if($rowcount)
        {  
          /* Start the loop getting one comment row each loop ... */
          while ($row = mysqli_fetch_row($result)){
              if(empty($row[1])){  // If ParentId is NULL it's a top level (level 0) comment 
                $thislevel = 0;
              }else{               // Otherwise it'slevel 1 comment. Only two levels use in this implementation.
                $thislevel = 1;
              }
              
                
              if($thislevel == 0){
                // This is a top level comment so store its CommentId in a DIV around it so it can be identified in any reply
                // using a class to allow script identification of all commentId's in order to add links or buttons or whatever that
                // the user can hit to intiate a reply form. Some mechanism can put the id in a hidden field within the form it ready 
                // to send back with the reply.
                // Was already inserting data-id when realised needed id to allow selection by id for jquery append, maybe data-id is now supurfluous?
                // so id added anyway setting to comments unique identifier.
                printf('%s<div class="cmt-cnt level0" id="%s"  data-id="%s">%n',"\t", $row[0], $row[0], "\n");
                $indent = "";
              }else{ // its level 1
                // Could use CSS to style replies differently if desired
                echo "\t\t\t\t" . '<div class="cmt-cnt level1">';
                $indent = "\t\t\t\t";
              }
              printf('<img src="'.$grav_url.'" /><div class="thecom">');
              // The actual subject, poster, date, comment etc
              printf ("%s<span class='poster'>%s: </span><span class='subject'>%s</span><span class='com-dt'>%s</span><br><p>%s</p></div>\n", $indent, $row[2], $row[3],  $row[4], $row[5]);
			  //printf ("%s<span class='poster'>%s: </span><h5>%s</h5><span class='com-dt'>%s</span><br><p>%s</p></div>\n", $indent, $row[2], $row[3],  $row[4], $row[5]);
              
              if($thislevel == 0){
                // Want to give the client-end something to work with if offering the reader a reply option though could add this by script I suppose
                // data-id passed again whilst sorting out which to use and how to aquire it in script.
                printf('%s<a href="#" class="replylink" id="%s" data-id="%s" >Reply</a>', $indent, $row[0], $row[0]);
              }

              
              if($thislevel == 0){
                echo "\t" . '</div>' . "\n";
              }else{
                echo "\t\t\t\t" . '</div>' . "\n";
              }
              
              $prevlevel = $thislevel;
          }
        }
  
        // Close off the comment container
        echo '</div>' . "\n";
  

        /* free result set */
        if(!is_null($result)){
			mysqli_free_result($result);     
        }
        mysqli_close($connect);
    }
?>