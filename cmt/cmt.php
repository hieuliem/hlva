 <?php
 	$UniquePageId = 0; 
	$cmtPath = 'cmt/';

	include $cmtPath.'displaycomments.php'; displaycomments($UniquePageId);
    include $cmtPath.'commentsform.htm';

	echo '<link rel="stylesheet" type="text/css" href="'.$cmtPath.'example.css" media="screen"/>';
	echo '<link rel="stylesheet" type="text/css" href="'.$cmtPath.'commentstyle.css" media="screen"/>';	
	echo '<script src="cmt/jgdhandlers.js"></script>';
?>
    
