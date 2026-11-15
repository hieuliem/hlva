<?php

	

?>

<?php

class MyException extends Exception { }

?>

<?php

Abstract class PV{

	protected $json;

	protected $today;

	protected $nextSundayDate;

	protected $lastSundayDate;

	protected $serURL = "../taphat_media/";

	protected $mobile;
	
	protected $jsonDIR;

	protected $mediaManifest = array();
	
	public function __construct($f){ 
		require __DIR__ . '/media_config.php';
		if (!empty($THANHCA_R2_PUBLIC_URL) && ($THANHCA_PREFER_R2 || !is_dir(__DIR__ . '/../../taphat_media'))) {
			$this->serURL = rtrim($THANHCA_R2_PUBLIC_URL, '/') . '/';
		}
		$manifestFile = __DIR__ . '/json/media_manifest.json';
		if (is_file($manifestFile)) {
			$decoded = json_decode(file_get_contents($manifestFile), true);
			if (is_array($decoded)) {
				$this->mediaManifest = $decoded;
			}
		}
		
		$this->jsonDIR = substr($f, 0, strrpos($f, '/'));

		$this->json = json_decode(file_get_contents($f), true);

		$this->today = date('m/d/Y');

		$this->nextSundayDate = date('m/d/Y', strtotime('next Sunday'));

		$this->lastSundayDate = date('m/d/Y', strtotime('last Sunday'));

	}

	

	public function currYear(){		

		foreach ($this->json as $n => $d) {

			if($this->in_range($d["start"],$d["end"],$this->today)){

				return $n;

				break;

			}

		}

		

		return 'NOT SET';

	}

	

	public function getYearRange($y){

		foreach ($this->json as $n => $d) {

			if($n==$y)	return '('.substr(strrchr($d["start"], "/"), 1).'-'.substr(strrchr($d["end"], "/"), 1).')';

		}

		return 'NOT SET';

	}

	

	public function getNamPvArr(){

		return array('A','B','C');

	}

	

	public function getTcTabArr(){

		return array(			

		    "tc" => "Thánh Ca",

			"st" => "Sáng Tác Mới",
			
			"gs" => "Giáng Sinh",
			
			"tt" => "Tuần Thánh",

			"ps" => "Phục Sinh"

		);

	}
	
	public function getOrderTab($b){
	    $a = array(			

			"tc" => "Thánh Ca",

			"gs" => "Giáng Sinh",
			
			"st" => "Sáng Tác Mới",
			
			"tt" => "Tuần Thánh",

			"ps" => "Phục Sinh"

		);		
		
        $c = array();
        foreach($b as $index) {
            $c[$index] = $a[$index];
        }
	    
	    return $c;
	}
	
	public function reorder_array(&$array, $new_order) {
        $inverted = array_flip($new_order);
        uksort($array, function($a, $b) use ($inverted) {
            return $inverted[$a] > $inverted[$b];
        });
    }

	

	public function in_range($start_date, $end_date, $date_from_user){

		// Convert to timestamp

		$start_ts = strtotime($start_date);

		$end_ts = strtotime($end_date);

		$user_ts = strtotime($date_from_user);



		// Check that user date is between start & end

		return (($user_ts >= $start_ts) && ($user_ts <= $end_ts));

	}

	

	public function viewFile(){

		$jsonIterator = new RecursiveIteratorIterator(

			new RecursiveArrayIterator($this->json),

			RecursiveIteratorIterator::SELF_FIRST);

		foreach ($jsonIterator as $key => $val) {

			if(is_array($val)) {

				echo "$key:\n";

			} else {

				echo "$key => $val\n";

			}

		}

	}

	

	public function __get($property) {

		if (property_exists($this, $property)) {

			return $this->$property;

		}

    }



    public function __set($property, $value) {

        if (property_exists($this, $property)) {

            $this->$property = $value;

        }

    }

	

	public function __toString(){

        return "Phụng Vụ::FILE<br>".$this->viewFile();

    }

}

?>

<?php

class phungvu extends PV{

	public $tc;

	public $tvdc;



	public function __construct($path){

		parent::__construct($path);

    }

	

	public function thanhca($path){

		$this->tc = new TC($path);

		$this->tc->baihat = $this->tc->json;

	}

	

	public function nam($letter, $path){

		if(isset($this->json[$letter])){

			//return $this->json[$letter];

			$nam = $this->json[$letter];

			$this->tvdc = new TVDC("$path$letter.json");

			$this->tvdc->json['start'] = $nam['start'];

			$this->tvdc->json['end'] = $nam['end'];			

			$this->tvdc->baihat = $this->tvdc->json;

		}

	}

}

?>

<?php

class TC extends PV{

	public $baihat;

	public function __construct($path){

		parent::__construct($path);

    }

	

	public function printList($mobile){

		$mess = '';

		$slash = '/';

		$path = $this->baihat["path"].$slash;

		$subpath = $this->baihat["sub"];

		if($subpath!='')	$subpath .= $slash;

		foreach($this->baihat['list'] as $s){

			$pdf = $path.$subpath.$s['dir'].$slash.$s['pdf'].'.pdf';

			$tg = '<span class="tacgia"></span>';

			if($mobile){

				$tg = '<span class="tacgia">tg:&nbsp;&nbsp;&nbsp;&nbsp;</span>';

			}

		

			$mess .= '<div id="row">';

			$mess .= '<div class="col-md-6"><a href="'.$this->serURL.$pdf.'"><img src="images/pdf_icon.gif" width="12"/></a>';

			if($this->in_range($this->today, $s['date'], $s['date'])){

				$mess .= '&nbsp;&nbsp;&nbsp;<img src="images/new10_e0.gif" style="" width="16"/>';

			}

			if(0 === strpos($s['pdf'], 'BaiThuongKho')){

				$mess .= '&nbsp;&nbsp;&nbsp;<a href="'.$this->serURL.$pdf.'">'.$s['name'].'</a></div>';

			}else{

				$mess .= '&nbsp;&nbsp;&nbsp;<a class="tvdc" href="'.$path.$subpath.$s['dir'].$slash.'">'.$s['name'].'</a></div>';

			}

			if($s["caudau"]=="&nbsp;"){				

				$mess .= '<div class="col-md-6">'.$tg.'<span class="tgName">'.$s["tacgia"].'<span></div>';

			}else{

				$mess .= '<div class="col-md-2">'.$tg.'<span class="tgName">'.$s["tacgia"].'<span></div>';

				$mess .= '<div class="col-md-4 italic">'.$s["caudau"].'</div><div class="clearfix visible-sm-block visible-md-block visible-lg-block"></div>';				

			}

			$mess .= '</div>';

		}

		return $mess;

	}

}

?>

<?php
class ORDO extends PV{
	public $baihat;
	public $title;
	
	public function __construct($path){
		parent::__construct($path);
		$this->baihat = $this->json;
	}
	
	public function isLeExisted($le){
		return isset($this->baihat[$le]);
	}

	public function printSection($le){
		$slash = '/';
		$mess = $m1 = $m2 = $m3 = '';
		if(isset($this->baihat[$le])){
			$sections = $this->baihat[$le];
			foreach($sections as $phanle){
				$folder = $phanle['dir'].$slash;
				$pl = '<span class="pl'.strtolower($phanle['type']).'"></span>';
				if($this->mobile) 	$pl = '<span class="pl'.strtolower($phanle['type']).'">'.$phanle['type'].':&nbsp;&nbsp;</span>';
				$html = $pl.'<a class="tvdc" href="'.$folder.'"><span class="cnl_tvdc" id="">'.$phanle["name"].'</span></a>';
				if($phanle['type']=="CNL")	$m1 = $html;
				else		$m2 = $html;
			}
		}
		
		$mess .= '<div class="col-md-3">'.$m1.'</div>';			
		$mess .= '<div class="col-md-2">'.$m2.'</div>';
		$mess .= '<div class="clearfix visible-sm-block visible-md-block visible-lg-block">'.$m3.'</div>';
		return $mess;
	}
}

?>

<?php

class TVDC extends PV{

	public $baihat;

	public $cnl;
	
	public $CHL;
	
	public $ordo;

	public function __construct($path){
		parent::__construct($path);
		$jsonDIR = substr($path, 0, strrpos($path, '/'));
		$this->ordo = new ORDO($jsonDIR."/pl.json");
		//$this->ordo->viewFile();
    }	

	public function printList($mobile){
		$this->ordo->mobile = $mobile;
		$mess = '';
		$slash = '/';
		$path = $this->baihat["path"].$slash;

		$done = false; $miss = false;		

		foreach($this->baihat['list'] as $s){			
			$tv = $s['tv'];
			if(is_numeric($tv))	$tv = $this->baihat["type"].' '.$s['tv'];

			$folder = $path.$s['dir'].$slash;
			$folderKey = rtrim($folder, '/');
			$miss = !(is_dir(__DIR__ . "/../../taphat_media/$folder") || is_dir(__DIR__ . "/../taphat/$folder") || isset($this->mediaManifest[$folderKey]));
			$dateClss = "d";
			$flashingID_1 = $flashingID_2 = "";

			if(!$done && $this->in_range($this->lastSundayDate, $this->nextSundayDate, $s['date'])){
				$dateClss = "r";
				$flashingID_1 = 'id="flashingtext1"';

				if(!empty($cnl)){
					$emptyID = 'id=""';
					$flashingID_2 = 'id="flashingtext2"';
					$cnl = str_replace($emptyID,$flashingID_2,$cnl);
				}
				$done = true;
			}			

			$pldc = '<span class="pldc"></span>';
			$cls = '';

			if($miss)	$cls = 'style="color: silver;"';
			if($mobile){
				$pldc = '<span class="pldc">ĐC:&nbsp;&nbsp;&nbsp;&nbsp;</span>';
				$cls = 'style="margin-bottom: 15px;"';
			}

			$mess .= '<div id="row" '.$cls.'>';
			$mess .= '<div class="col-md-1"><span class="'.$dateClss.'">'.$s['date'].'</span></div>';
			$mess .= '<div class="col-md-4"><span class="le_tvdc">'.$s['le'].'</span></div>';
			$mess .= '<div class="col-md-2">'.$pldc;

			if($miss){
				$mess .= '<span class="tv_tvdc">'.$tv.'</span>';
			}else{
				$mess .= '<a class="tvdc" href="'.$folder.'"><span class="tv_tvdc" '.$flashingID_1.'>'.$tv.'</span></a>';
			}
			
			if(isset($s["baikhac"])){
				foreach($s["baikhac"] as $bk){
					$mess .= '<br><a class="tvdc" href="'.$path.$bk['dir'].$slash.'"><span class="tv_tvdc" >'.$bk["name"].'</span></a>';
				}
			}

			$mess .= '</div>';
			
			// Should print 3 columns from other section CNL-DL-CHL-KL ...
			$mess .= $this->ordo->printSection($s['le']);

			$mess .= '</div>';

		}

	

		return $mess;

	}
	
	public function printList_($mobile){
		if($mobile){
			$this->cnl->mobile = $mobile;
			$this->CHL->mobile = $mobile;
		}

		$mess = '';

		$slash = '/';

		$path = $this->baihat["path"].$slash;

		$done = false; $miss = false;

		

		foreach($this->baihat['list'] as $s){

			$cnl = ''; $chl = '';

			if($this->cnl->isLeExisted($s['le'])){
				//$cnl = $this->cnl->printCNL($s['le']);
				$cnl = $this->cnl->printSection($s['le'], "plcnl");
			}

			/*if($this->CHL->isLeExisted($s['le'])){
				$chl = $this->CHL->printSection($s['le'], "plchl");
			}*/

			$tv = $s['tv'];

			if (is_numeric($tv)){

				$tv = $this->baihat["type"].' '.$s['tv'];

			}

			$folder = $path.$s['dir'].$slash;

			if(!is_dir("../taphat/$folder")){

				$miss = true;

			}else{

				$miss = false;

			}

			

			$dateClss = "d";

			$flashingID_1 = $flashingID_2 = "";

			if(!$done && $this->in_range($this->lastSundayDate, $this->nextSundayDate, $s['date'])){

				$dateClss = "r";

				$flashingID_1 = 'id="flashingtext1"';

				if(!empty($cnl)){

					$emptyID = 'id=""';

					$flashingID_2 = 'id="flashingtext2"';					

					$cnl = str_replace($emptyID,$flashingID_2,$cnl);

				}

				$done = true;

			}

			

			$pldc = '<span class="pldc"></span>';

			$cls = '';

			if($miss)	$cls = 'style="color: silver;"';

			if($mobile){

				$pldc = '<span class="pldc">ĐC:&nbsp;&nbsp;&nbsp;&nbsp;</span>';

				$cls = 'style="margin-bottom: 15px;"';

			}

			$mess .= '<div id="row" '.$cls.'>';

			$mess .= '<div class="col-md-1"><span class="'.$dateClss.'">'.$s['date'].'</span></div>';

			$mess .= '<div class="col-md-3"><span class="le_tvdc">'.$s['le'].'</span></div>';

			$mess .= '<div class="col-md-2">'.$pldc;

			if($miss){

				$mess .= '<span class="tv_tvdc">'.$tv.'</span>';

			}else{

				$mess .= '<a class="tvdc" href="'.$folder.'"><span class="tv_tvdc" '.$flashingID_1.'>'.$tv.'</span></a>';

			}

			

			if(isset($s["baikhac"])){

				foreach($s["baikhac"] as $bk){

					$mess .= '<br><a class="tvdc" href="'.$path.$bk['dir'].$slash.'"><span class="tv_tvdc" >'.$bk["name"].'</span></a>';

				}

			}

			$mess .= '</div>';

			
			// Should print 3 columns from other section CNL-DL-CHL-KL ...
			$mess .= '<div class="col-md-2">'.$cnl.'</div>';
			
			$mess .= '<div class="col-md-2">'.$chl.'</div>';

			$mess .= '<div class="clearfix visible-sm-block visible-md-block visible-lg-block"></div></div>';

		}

	

		return $mess;

	}

}

?>