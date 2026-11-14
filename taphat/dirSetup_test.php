<?php
	$path = (isset($_GET['d']))?$_GET['d']:"NOT PASSED!";
	if(isset($_GET['copy'])){// copy directory
		$from = (isset($_GET['f']))?$_GET['f']:"";
		$to = (isset($_GET['t']))?$_GET['t']:"";
		//$from = realpath($fr);
		$filemode = 0755;
		//chmod($to,$filemode);
		if(is_dir($from)&&is_dir($to)){
			//listDir("$from/cn30A");
			
			$extCoppied = array('pdf', 'mp3', 'jpg');
			$destDir = '';
			foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($from)) as $file){
				if(is_dir($file)){//create dir in $to
					$fpath = $file->getPath();
					$folder = str_replace($from,"",$fpath);//ltrim($fpath, $from);
					$dest = $to.$folder;
					
					$depth = explode("/", $folder);
					echo $file.' || '.$fpath.' || '.$folder.' || '.$dest.' || '.sizeof($depth).'<br>';
					if(sizeof($depth)==2){
						$destDir = $dest;
						if(!is_dir($dest)){
							//mkdir($dest);							
							echo " ** $folder created in $dest<br>";
						}else{
							//chmod($dest,$filemode);
							echo " o0o $folder existed<br>";
						}
						
						// copy index file
						if(is_dir($dest)&&!file_exists("$dest/index.php")){
							//copy("_src/index.php","$dest/index.php");
							//copy("_src/media.php","$dest/media.php");
						}
					}					
				}else{// then copy
					if(is_dir($destDir)){
						$pathinfo = pathinfo($file);
						if(!in_array($pathinfo['extension'], $extCoppied)) { continue; }
						
						$fname = $file->getFilename();
						$dest = $destDir.'/'.$fname;
						//if(!file_exists($dest))	copy($file, $dest);
						echo "$dest coppied</br>";
					}
				}
			}
		}
	}else{
		if($path!="NOT PASSED!"){// for specific path provided.		
			$folders = glob($path.'*' , GLOB_ONLYDIR);
			foreach($folders as $dir){
				echo "$dir</br>";
				if(!file_exists($dir."/index.php")) 			listDir($dir);
			}
		}else{
			echo $path.'<br>';
			$folders = glob('thanhvinh/*' , GLOB_ONLYDIR);
			foreach($folders as $dir){
				echo "$dir</br>";
				if(!file_exists($dir."/index.php")) 	listDir($dir);
			}
		}
	}
	
	function listDir($d){
		$rs = getDirectoryList($d);
		foreach($rs as $v){
			echo $v.'<br>';
		}
	}
	
	function buildIndex($d){
		//open file and get data
		$data = file_get_contents("path/to/sourcefile.php");

		// do tag replacements or whatever you want
		$data = str_replace("<tag1>", "<tag2>", $data);

		//save it back:
		file_put_contents("path/to/destinationfile.php", $data);
	}
		
	
?>
<?php
function getDirectoryList($directory){
	$results = array();
	$handler = opendir($directory);
	while ($file = readdir($handler)) {
		if ($file != "." && $file != "..") {
			$results[] = $file;
		}
	}
	closedir($handler);

	// done!
	return $results;
}

// Does not support flag GLOB_BRACE
function rglob($pattern, $flags = 0) {
    $files = glob($pattern, $flags); 
    foreach (glob(dirname($pattern).'/*', GLOB_ONLYDIR|GLOB_NOSORT) as $dir) {
        $files = array_merge($files, rglob($dir.'/'.basename($pattern), $flags));
    }
    return $files;
}
	
function rsearch($folder, $pattern) {
    $dir = new RecursiveDirectoryIterator($folder);
    $ite = new RecursiveIteratorIterator($dir);
    $files = new RegexIterator($ite, $pattern, RegexIterator::GET_MATCH);
    $fileList = array();
    foreach($files as $file) {
        $fileList = array_merge($fileList, $file);
    }
    return $fileList;
}

/**
 * Copy a file, or recursively copy a folder and its contents
 * @param       string   $source    Source path
 * @param       string   $dest      Destination path
 * @param       string   $permissions New folder creation permissions
 * @return      bool     Returns true on success, false on failure
 */
function xcopy($source, $dest, $permissions = 0755)
{
    // Check for symlinks
    if (is_link($source)) {
        return symlink(readlink($source), $dest);
    }

    // Simple copy for a file
    if (is_file($source)) {
        return copy($source, $dest);
    }

    // Make destination directory
    if (!is_dir($dest)) {
        mkdir($dest, $permissions);
    }

    // Loop through the folder
    $dir = dir($source);
    while (false !== $entry = $dir->read()) {
        // Skip pointers
        if ($entry == '.' || $entry == '..') {
            continue;
        }

        // Deep copy directories
        xcopy("$source/$entry", "$dest/$entry");
    }
	
	  // Clean up
    $dir->close();
    return true;
}
?>