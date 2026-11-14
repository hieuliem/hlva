<?php
$from = (isset($_GET['fr']))?$_GET['fr']:"NOT PASSED!";
$to = (isset($_GET['t']))?$_GET['t']:"NOT PASSED!";
$filemode = 0755;

if(isset($_GET['filecopy'])){// copy file to all
	$file = (isset($_GET['f']))?$_GET['f']:"NOT PASSED!";
		
	echo 'loading...<br>';
	if(is_dir($from)&&is_dir($to)&&file_exists("$from/$file")){
		$directories = glob($to.'/*', GLOB_ONLYDIR);
		//print_r($directories);
		foreach($directories as $dir){
			if(file_exists("$dir/$file"))		copy("$from/$file","$dir/$file");
		}
		echo 'DONE<br>';
	}
}

if(isset($_GET['dircopy'])){// copy files within dir
	if(!is_dir($to))		mkdir($to);
	chmod($to,$filemode);
	foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($from)) as $file){
		$fname = $file->getFilename();
		if(!is_dir($file)){
			copy($file,"$to/$fname");
			echo "$to/$fname coppied<br>";
		}
	}
}	
?>