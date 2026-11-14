<?php

    $rootPath = $_SERVER['DOCUMENT_ROOT'];
    $uploadDirectory = "/taphat/thanhvinh/cn/";
    $uploadDIR = $rootPath . $uploadDirectory;
    if(isset($_FILES['tvdc']))
    {
    	for($count = 0; $count < count($_FILES['tvdc']['name']); $count++)
    	{
    		$extension = pathinfo($_FILES['tvdc']['name'][$count], PATHINFO_EXTENSION);
    
    		$new_name = $_FILES['tvdc']['name'][$count];//uniqid() . '.' . $extension;
    
    		move_uploaded_file($_FILES['tvdc']['tmp_name'][$count], $uploadDIR . $new_name);
    	}
    
    	echo 'success';
    }

?>
