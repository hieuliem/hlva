<?php
    $path = isset($path)? $path : '';
    require $path.'comments_class_comment.php';
    
    // pf here means pre-filter   
    $pfname      =$_REQUEST["name"];
    $pfemail     =$_REQUEST["email"];
    $pfsubject   =$_REQUEST["subject"];
    $pfcomment   =$_REQUEST["comment"];
    $pfidreplyto = intval($_REQUEST["idreplyto"]);
    $pfpageid    = intval($_REQUEST["pageid"]);
    
    $name       = null;
    $email      = null;  
    $subject    = null;
    $comment    = null;
    $idreplyto  = -1;
    $pageid     = -1;
    
    // Apply same filters as used when validating input form                                
    $name = filter_var($pfname, FILTER_SANITIZE_STRING);
    if(FALSE == $name){
            exit("comments_add.php Name: ".$pfname." did not pass the input filter");
    }
    
    $subject = filter_var($pfsubject, FILTER_SANITIZE_STRING);
    if(FALSE == $subject){
            exit("comments_add.php Subject: ".$pfsubject." did not pass the input filter");
    }
    
    $email = filter_var($pfemail,   FILTER_VALIDATE_EMAIL);
    if(FALSE == $subject){
            exit("comments_add.php Email: ".$pfemail." did not pass the input filter");
    }
    
    $comment  = filter_var($pfcomment, FILTER_SANITIZE_STRING);
    if(FALSE == $comment){
            exit("comments_add.php Comment: ".$pfcomment." did not pass the input filter");
    }
    
    
    $pageid = filter_var($pfpageid,    FILTER_VALIDATE_INT,    array("options" => array("min_range" => MINPAGEID, "max_range"=> MAXPAGEID)));
    if(FALSE === $pageid){
            exit("comments_add.php pageid = ".$pfpageid." with MIN = ".MINPAGEID." and MAX = ".MAXPAGEID." did not pass the input filter");
    }
    
    $idreplyto = filter_var($pfidreplyto, FILTER_VALIDATE_INT,    array("options" => array("min_range" => MINIDREPLYTO, "max_range"=>MAXIDREPLYTO)));
    if(FALSE === $idreplyto){
            exit("comments_add.php IdReplyTo = ".$pfidreplyto." with MIN = ".MINIDREPLYTO." and MAX = ".MAXIDREPLYTO." did not pass the input filter");
    }
    
    $com = new Comment();
    $idComment = $com->add($name, $email, $subject, $comment, $idreplyto, $pageid);
    
    echo $com->get($idComment);
    
?>