<?php
trait litcalAPI{
	private $url = "https://litcal.johnromanodorazio.com/api/v3/LitCalEngine.php";
	private $opt = '{"Year":2023,"Epiphany":"SUNDAY_JAN2_JAN8","Ascension":"SUNDAY","CorpusChristi":"SUNDAY","Locale":"EN","ReturnType":"JSON"}';
	
	public $setting = [
		'Year'      	=> 2023,
		'Epiphany' 		=> "SUNDAY_JAN2_JAN8", 	/*JAN6*/
		'Ascension'		=> "SUNDAY",			/*THURSDAY*/
		'CorpusChristi'	=> "SUNDAY",			/*THURSDAY*/
		'Locale'		=> 	"EN",
		'ReturnType'	=>	"JSON"				/*XML, ICS*/
	];
	
	function __construct($setting){
		$this -> $setting = $setting;
	}
	
	public function do_curl($fields = null){
		//if(isset($fields))	foreach($fields as $k => $v)	$this->setting[$k] = $v;
		
		//url-ify the data for the POST
		$fields_string = http_build_query($this->setting);

		//open connection
		$ch = curl_init();

		//set the url, number of POST vars, POST data
		curl_setopt($ch,CURLOPT_URL, $this->url);
		curl_setopt($ch,CURLOPT_POST, true);
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

		//So that curl_exec returns the contents of the cURL; rather than echoing it
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 

		//execute post get String
		$result = curl_exec($ch);
		
		//execute post get JSON
		$resultJSON = json_decode($result, true);
		return $resultJSON;
	}
	
	public function do_post($params) {
		$options = array(
			'http' => array(
				'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
				'method'  => 'POST',
				'content' => $params
			)
		);
		$result = file_get_contents($this->url, false, stream_context_create($options));
		
		return $result;
	}
	
	public function __get($property) {
		if(property_exists($this, $property)) {
			return $this->$property;
		}
    }

    public function __set($property, $value) {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
    }
}

trait pvu{
	public $namPV = ['Năm C','Năm A','Năm B'];
	public $chuky = ['chu kỳ I','chu kỳ II'];
	
	private $vFile = 'data/le.json';
	private $comma = ",";
	private $space = "XX";
	private $underscore = "_";
	
	public $leJSON = null;
	
	public function getNamPV($dStr=null){		
		$year = is_null($dStr) ? date("Y") : date("Y", strtotime($dStr));
		$month = is_null($dStr) ? date("m") : date('m', strtotime($dStr));
		if($month==12)	$year = $year + 1;
		
		return $this->namPV[$year%3];
	}
	
	public function getLeViet($k){
		if($this->leJSON==null){	
			$this->leJSON = myUtils::readJsonFile($this->vFile);
		}
				
		$leArr = array();
		if(str_contains($k, "Christmas_vigil"))		$leArr["Christmas_vigil"] = $this->leJSON["Christmas_vigil"];
		else{
			if(str_contains($k, $this->comma)){
				$str_arr = explode($this->comma, $k);
				//$str_arr = preg_split ("/\,/", $k); 
				foreach($str_arr as $str){
					if(isset($this->leJSON[$str])){
						$leArr[$str] = $this->leJSON[$str];
					}
				}
			}else if(isset($this->leJSON[$k]))	$leArr[$k] = $this->leJSON[$k]; 
		}
		
		return $leArr;
	}
}

trait lich{
	
}

class lichPV{
	use litcalAPI;
	use pvu;
	
	const VIET = 'vi';
	const DATA_PATH = "data/";
	const NAM_NEEDLE = "năm --";
	const CN = 'Chúa Nhật';
	const ADVENT = 'Advent1';
	const MAIN_CAL = "LitCal";
	const JSON_EXT = '.json';
	const SETTINGS = "Settings";
	const METADATA = "Metadata";
	const SOLEMNITIES = "Solemnities";
	const MEMORIALS = "FeastsMemorials";
	
	protected $myUtils;
	public function __construct() {
        $myUtils = new myUtils;
    }
	
	public function getLichPV($year){
		$filename = self::DATA_PATH.$year.self::JSON_EXT;
		
		// Retrieve file
		if(!is_readable($filename)){			
			$this->setting['Year'] = $year;
			$json_data = $this->do_curl();
			// testing
			//myUtils::pretty($json_data["Settings"]);
			if (file_put_contents($filename, json_encode($json_data)))
				echo "JSON file created successfully...";
			else 
				echo "Oops! Error creating json file...";
		}
		
		return myUtils::readJsonFile($filename);
	}
	
	function printCalendar($calendar, $language=self::VIET){
		$m = 1;
		$set = true;
		$flashing_set = false;
		$cal = myUtils::sortMyDateArr($calendar[self::MAIN_CAL]);
		$today = new DateTime("today"); // This object represents current date/time with time set to midnight

		echo '<div class="two alt-two"><h1>Lịch Thánh Ca '.$this->setting['Year'].'<span>Phụng Vụ Chúa Nhật '.self::getNamPV().'</span></h1></div>';
		echo '<table cellpadding="5" cellspacing="1" width="95%" border="2" class="t">';
		echo '<tr class="tblHeader" align="center"><td width="4%" class="t">Ngày / Tháng</td><td width="21%" align="center">Thứ</td><td width="1%"></td><td width="50%" align="center">Lễ</td></tr>';
		foreach($cal as $k => $v){
			$leObj = $this->getLeViet($v);
			if(empty($leObj))	continue;
			
			$leName = reset($leObj);
			if(str_contains(reset($leObj), self::NAM_NEEDLE)) $leName = str_replace(self::NAM_NEEDLE, self::getNamPV(), reset($leObj));
			$le = $calendar[self::MAIN_CAL][key($leObj)];
						
			$mon = date('m', strtotime($k));
			$wd = myUtils::getWeekday($k, $language);
			
			$clsCN = ($wd==self::CN) ? "chuanhat" : "ngaythuong";
			
			$next_date_in_cal = $on_date_in_cal = '';
			$match_date = new DateTime($k);
			$match_date->setTime( 0, 0, 0 ); // set time part to midnight, in order to prevent partial comparison
			if($match_date==$today){
				$on_date_in_cal = ' bgblack';				
				//$flashing_set = true;
			}else if(!$flashing_set && $match_date>$today){
				$next_date_in_cal = ' selected';
				$flashing_set = true;
			}
			
			if($m != $mon){
				$thang = 'Tháng '.$mon;
				$thangDescription = $this->getLeViet($thang);
				$nam = $this->setting['Year'];
				if($set && $mon>10){
					$nam = $nam-1;
					$thangDescription[$thang] = "";
				}else $set = false;
						
				echo '<tr class="monHeader"><td colspan="2" align="center">'.$thang.', '.$nam.'</td><td></td><td align="center">'.reset($thangDescription).'</td></tr> ';
				$m = $mon;
			}
			echo '<tr class="'.$next_date_in_cal.'"><td width="10%" align="center" class="daymon'.$on_date_in_cal.'">'.date('d/m', strtotime($k)).'</td><td width="15%" class="'.$clsCN.'">'.$wd.'</td><td style="background-color: '.$le['color'][0].'"></td><td width="50%" class="'.$le['color'][0].'">'.$leName.'</td></tr>';
			//echo '<tr><td width="10%" align="center" class="daymon">'.($match_date==$today).'</td><td width="15%" class="'.$clsCN.'">'.$wd.'</td><td style="background-color: '.$le['color'][0].'"></td><td width="50%" class="'.$le['color'][0].'">'.$leName.'</td></tr>';
		}
		echo '</table>';
	}
}

class thanhca extends lichPV{
	public $lich = array();
	public function setLichPV($year){
		$lastMV = lichPV::getLichPV($year-1);
		$lastMV[self::MAIN_CAL] = myUtils::extractMV($lastMV);
		//myUtils::pretty(myUtils::sortMyDateArr($lastMV[self::MAIN_CAL]));
		
		$thisMV = lichPV::getLichPV($year);
		$thisMV[self::MAIN_CAL] = myUtils::extractMV($thisMV, true);
		//myUtils::pretty(myUtils::sortMyDateArr($thisMV[self::MAIN_CAL]));
		
		$this->lich[$year][self::MAIN_CAL] = array_merge($thisMV[self::MAIN_CAL],$lastMV[self::MAIN_CAL]);
		//myUtils::pretty(myUtils::sortMyDateArr($this->lich[$year][self::MAIN_CAL]));
	}
	
	public function printLichPV($year){		
		lichPV::printCalendar($this->lich[$year]);
	}
	
	public function getWDay($dStr){
		echo myUtils::getWeekday($dStr, self::VIET);
	}
}

/*====================================================================================================================*/
// Main Function for Lich Phụng Vụ
//printCalendar($json_data, $language);
//echo $tcpv->do_post("year=2023&corpuschristi=SUNDAY&epiphany=SUNDAY_JAN2_JAN8&ascension=SUNDAY&locale=EN&returntype=JSON");
/*====================================================================================================================*/
class myUtils{
	const WDays = array("en" => array('Sunday', 'Monday', 'Tuesday', 'Wednesday','Thursday','Friday', 'Saturday'),
						"vi" => array('Chúa Nhật', 'Thứ Hai', 'Thứ Ba', 'Thứ Tư','Thứ Năm','Thứ Sáu', 'Thứ Bảy'));
	const COMMA = ",";
	
	static function extractMV($arr, $main=false){
		$found = false;
		$cal = $arr[lichPV::MAIN_CAL];
		foreach($cal as $k => $v){
			if($k == lichPV::ADVENT)	$found = true;

			if(!$main&&!$found || $main&&$found) unset($cal[$k]);
		}
		return $cal;
	}
	
	static function printKey($arr, $needle){
		foreach($arr as $k => $v){
			$display = "";
			if(array_key_exists($needle, $v))	$display = $v[$needle];
			echo $k.": ".$display."<br>";
		}
	}
	
	static function readJsonFile($filename){
		// Read the JSON file 
		$json = file_get_contents($filename);
		// Decode the JSON file
		return json_decode($json, true); 
	}
	
	static function pretty($json_data){
		$json_pretty = json_encode($json_data, JSON_PRETTY_PRINT);
		echo "<pre>"; 
		echo $json_pretty; 
		echo "<pre>";
	}

	static function getWeekday($date, $lang) {
		$day = date('w', strtotime($date));
		return self::WDays[$lang][$day];
	}

	static function cmp($a, $b) {
	  return $a['date'] > $b['date'];
	}

	static function sortMyDateArr($arr){
		$sortedArr = array();
		foreach($arr as $k => $v){
			$dStr = (strlen($v['date']) === 10 && ctype_digit($v['date'])) ? date('Y-m-d', $v['date']) : date('Y-m-d', strtotime($v['date']));
			// there are multiple values on a day
			if(array_key_exists($dStr, $sortedArr)){
				$k = $sortedArr[$dStr].", ".$k;
			}
			$sortedArr[$dStr] = $k;
		}
		ksort($sortedArr);
		return $sortedArr;
	}
}
?>