<?php
class ezproxy extends CI_Model {
	public $sql = "";
	public $intervals = array(
			# interval => array(format, interval, sql);
			'year'=>array('Y',			'year',		'year ', 																		''), 
			'month'=>array('Y-m', 		'month', 	'concat(year, \'-\', LPAD(month, 2, \'0\'), \'-\', LPAD(day, 2, \'0\')) ', 		''), 
			'day'=>array('Y-m-d',		'day',		'concat(year, \'-\', LPAD(month, 2, \'0\'), \'-\', LPAD(day, 2, \'0\')) ', 		''), 
			'hour'=>array('m-d H:00',	'hour',		'concat(LPAD(month, 2, \'0\'), \'-\', LPAD(day, 2, \'0\'), \' \', LPAD(HOUR(date), 2, \'0\'), \':00\') ', ''),  
			'minute'=>array('H:i',		'minute',	'concat(LPAD(HOUR(date), 2, \'0\'), \':\', LPAD(MINUTE(date), 2, \'0\')) ', 	''), 
			'monday'=>array('H:i',		'minute',		'concat(LPAD(HOUR(date), 2, \'0\'), \':\', LPAD(MINUTE(date), 2, \'0\')) ', 	' and DAYNAME(date) = \'Monday\' '), 
			'tuesday'=>array('H:i',		'minute',		'concat(LPAD(HOUR(date), 2, \'0\'), \':\', LPAD(MINUTE(date), 2, \'0\')) ', 	' and DAYNAME(date) = \'Tuesday\' '), 
			'wednesday'=>array('H:i',	'minute',		'concat(LPAD(HOUR(date), 2, \'0\'), \':\', LPAD(MINUTE(date), 2, \'0\')) ', 	' and DAYNAME(date) = \'Wednesday\' '), 
			'thursday'=>array('H:i',	'minute',		'concat(LPAD(HOUR(date), 2, \'0\'), \':\', LPAD(MINUTE(date), 2, \'0\')) ', 	' and DAYNAME(date) = \'Thursday\' '), 
			'friday'=>array('H:i',		'minute',		'concat(LPAD(HOUR(date), 2, \'0\'), \':\', LPAD(MINUTE(date), 2, \'0\')) ', 	' and DAYNAME(date) = \'Friday\' '), 
			'saturday'=>array('H:i',	'minute',		'concat(LPAD(HOUR(date), 2, \'0\'), \':\', LPAD(MINUTE(date), 2, \'0\')) ', 	' and DAYNAME(date) = \'Saturday\' '), 
			'sunday'=>array('H:i',		'minute',		'concat(LPAD(HOUR(date), 2, \'0\'), \':\', LPAD(MINUTE(date), 2, \'0\')) ', 	' and DAYNAME(date) = \'Sunday\' '), 
			//'second'=>array('H:i:s'
			);
			
		
    function ezproxy(){
        // Call the Model constructor
        parent::__construct();
		$this->load->database();
    }
	function createTimeLabels($from, $to, $series, $interval){
		#print"<br>$from<br>$to";
		$labels = array();		
		$from = DateTime::createFromFormat("Y-m-d-H-i-s", $from);
		$to = DateTime::createFromFormat("Y-m-d-H-i-s", $to);
		while($to >= $from){	
		#	print "<br/><h2>INTERVAL IS: " . $from->getTimestamp(). " $interval </h2><br/>F: " . $from->getTimestamp(). " <br/>T: " . $to->getTimestamp();	
			//print date($this->intervals[strtolower($interval)][0], $from->getTimestamp())."<br/>";		
			$labels[date($this->intervals[strtolower($interval)][0], $from->getTimestamp())] = $series;
			#print " Interval: " . $this->intervals[strtolower($interval)][1];
			$from->modify('+1 ' . $this->intervals[strtolower($interval)][1]);
			#$from->modify('+1 day');
		}
		#print "<h1 style=\"color: blue\">".$this->intervals[strtolower($interval)][0]." Labels array</H1>\n<pre>";print_r($labels);print " ff ff ff ff fffffff </pre>\n";
		return $labels;
	}
	function returnTimeIntervalSelect($interval){
	
		$v = "\t<select id=\"timeinterval\" name=\"timeinterval\" class=\"form-control\">\n";
		foreach($this->intervals as $k => $va){
			if($k != $interval){
				$v .= "\t\t<option value=\"$k\" >$k</option>\n";
			}else{
				$v .= "\t\t<option value=\"$k\" selected=\"selected\" >$k</option>\n";
			}
		}
		$v .= "\t</select>\n";
		return $v;
	}
	function getAllPlatforms(){
		$platforms = array();
		$platz = array();
		$otherdb = $this->load->database('stats', TRUE); // the TRUE paramater tells CI that you'd like to return the database object.
		$plats = $otherdb->query("select distinct platform from stats2015 where platform != '' and platform!= '-' group by platform");
		if (isset($_POST['PlatformsChecked'])) {$platz = $_POST['PlatformsChecked'];}
		foreach($plats->result() as $p){
			if(in_array($p->platform, $platz)){
				$platforms[$p->platform] = 0;
			}else{
				$platforms[$p->platform] = 1;
			}
		}
		
		return $platforms;
	}
	function getPlatforms(){
		$platformSql = "";
		if(isset($_POST['PlatformsChecked'])) {
			$platformsArray = $_POST['PlatformsChecked'];
			$platformSql = " and platform in('".implode('\',\'',$platformsArray)."') "; 
		}elseif(isset($_POST['PlatformsAll'])) {
			$platformsArray = array('All Platforms');
		}
		$platforms = array();
		$otherdb = $this->load->database('stats', TRUE); // the TRUE paramater tells CI that you'd like to return the database object.
		$plats = $otherdb->query("select distinct platform from stats2015 where platform != '' and platform!= '-' $platformSql group by platform");
		foreach($plats->result() as $p){
			$platforms[$p->platform] = 0;
		}
		return $platforms;
	}

	function getVisits($id, $platforms, $timerange, $ival){
		$series = array(); //the series array, with zero as default values
		$data = array();
		$label = array();
		$ival = strtolower($ival);
		$sql = '';
		$sqla;
		$sqlb = "group by xaxis ";
		$sqlc = "order by xaxis asc ";
		if(!isset($this->intervals[$ival]) || !is_array($this->intervals[$ival])){
			$this->intervals[$ival] = array('year', '');
		}
		
		
		$timefromto = array();
		if($timerange){
			$timefromto = explode(':', $timerange);
			$c = 0;
			foreach($timefromto as $tft){
				$tft = explode('-', trim($tft, '-'));
				for($i=0; $i<6; $i++){
					if(!isset($tft[$i])){
						$tft[$i] = '00';
					}
				}
				$timefromto[$c] = implode($tft, '-');
				$c++;
			}
		}
		$otherdb = $this->load->database('stats', TRUE); // the TRUE paramater tells CI that you'd like to return the database object.
		if($platforms == 'separate'){
			$series = $this->getPlatforms();
			//print("SERIES: "); print_r($series);
		}else{ // if($plaforms == 'together'){
			$series = array('All Platforms'=>0);
		}
		
		foreach($data as $key=>$value){
				$data[$key]=array($series);  // pad out the date values with zeros.
		}
		
		switch(sizeof($timefromto)){
			case 1:
				$sqla  = "select distinct count(*) as yaxis, ".$this->intervals[$ival][2]." as xaxis ";
				$sqla .= "from stats2015 ";
				$sqla .= "where date between STR_TO_DATE('".$timefromto[0]."','%Y-%m-%d-%H-%i-%s') and DATE_ADD(STR_TO_DATE('".$timefromto[1]."','%Y-%m-%d-%H-%i-%s'), INTERVAL 1 ".$interval_range." ";
				$sqla .= $this->intervals[$ival][3]; 
				$data = $this->createTimeLabels($timefromto[0], $timefromto[1], $series, $ival);
				break;
			default: //case 2;			
				// https://library.wit.ie/readinglists/stats/index/line/visits/2015-04-10:2015-04-15
				$sqla  = "select distinct count(*) as yaxis, ".$this->intervals[$ival][2]." as xaxis ";
				$sqla .= "from stats2015 ";
				$sqla .= "where date between STR_TO_DATE('".$timefromto[0]."','%Y-%m-%d-%H-%i-%s') and STR_TO_DATE('".$timefromto[1]."','%Y-%m-%d-%H-%i-%s') ";
				$sqla .= $this->intervals[$ival][3]; 
				$data = $this->createTimeLabels($timefromto[0], $timefromto[1], $series, $ival);
				break;
		}
		if($platforms == 'separate'){	// overwite the padded data with real data, where applicable.
			foreach($series as $key=>$val){	
				$sql = $sqla . "and platform = '".$key."' " . $sqlb . $sqlc;
				$rez = $otherdb->query($sql);
				#print_r($data);
				foreach($rez->result() as $seriesresult){
					if(array_key_exists($seriesresult->xaxis, $data)){
						$data[$seriesresult->xaxis][$key] = $seriesresult->yaxis;

					}else{	
						//print($seriesresult->xaxis . "[" . $key . "] => " . $seriesresult->yaxis . "</br>\n");
						# print("<h1>ELSE</h1>");				
					}
				}
			}
		}else{		
			$sql = $sqla . $sqlb . $sqlc;
			$query = $otherdb->query($sql);
			foreach($query->result() as $row){	
				$data[$row->xaxis]['All Platforms'] = $row->yaxis;
			}
		}
		$this->sql = $sql;
		#print "<h1 style=\"color: red;\">data array</H1>\n<pre>";print_r($data);print " ff ff ff ff fffffff </pre>\n";
		return array('id'=>$id,  'data'=>$data, 'series'=>$series);
		
		
	}
	/*	
	$onload_queue = "";
	$g_lightness = 60;
	$g_saturation = 60;
	*/
	 function get_rgb($iH, $iS=-1, $iV=-1) 
	 {
		global $g_lightness;
		global $g_saturation;
		if($iS==-1)
		{
			$iS = $g_saturation;
		}
		if($iV==-1)
		{
			$iV = $g_lightness;
		}
	 
		if($iH < 1) $iH = 1; // Hue:
		if($iH > 359) $iH = 359; // 0-360
		if($iS < 0) $iS = 0; // Saturation:
		if($iS > 100) $iS = 100; // 0-100
		if($iV < 0) $iV = 0; // Lightness:
		if($iV > 100) $iV = 100; // 0-100
		$dS = $iS/100.0; // Saturation: 0.0-1.0
		$dV = $iV/100.0; // Lightness: 0.0-1.0
		$dC = $dV*$dS; // Chroma: 0.0-1.0
		$dH = $iH/60.0; // H-Prime: 0.0-6.0
		$dT = $dH; // Temp variable
		while($dT >= 2.0) $dT -= 2.0; // php modulus does not work with float
		$dX = $dC*(1-abs($dT-1)); // as used in the Wikipedia link
		switch($dH) {
		case($dH >= 0.0 && $dH < 1.0):
		$dR = $dC; $dG = $dX; $dB = 0.0; break;
		case($dH >= 1.0 && $dH < 2.0):
		$dR = $dX; $dG = $dC; $dB = 0.0; break;
		case($dH >= 2.0 && $dH < 3.0):
		$dR = 0.0; $dG = $dC; $dB = $dX; break;
		case($dH >= 3.0 && $dH < 4.0):
		$dR = 0.0; $dG = $dX; $dB = $dC; break;
		case($dH >= 4.0 && $dH < 5.0):
		$dR = $dX; $dG = 0.0; $dB = $dC; break;
		case($dH >= 5.0 && $dH < 6.0):
		$dR = $dC; $dG = 0.0; $dB = $dX; break;
		default:
		$dR = 0.0; $dG = 0.0; $dB = 0.0; break;
		}
		$dM = $dV - $dC;
		$dR += $dM; $dG += $dM; $dB += $dM;
		$dR *= 255; $dG *= 255; $dB *= 255;
		
		$dR = str_pad(dechex(round($dR)), 2, "0", STR_PAD_LEFT);
		$dG = str_pad(dechex(round($dG)), 2, "0", STR_PAD_LEFT);
		$dB = str_pad(dechex(round($dB)), 2, "0", STR_PAD_LEFT);
		
		return "#".$dR.$dG.$dB;
	} 
	/*
	//arr = array of values
	//labels = comma separated string of labels
	//id = id for creation of html elements and javascropt data use _ format, not hypens please
	function create_bar_chart($arr, $id){	
		global $onload_queue;
		$code  = "<div class=\"bar-chart\">\r\n\t<canvas id=\"$id\" width=\"300\" height=\"300\"/>\r\n</div>";
	
		$f_color = $this->get_rgb(1, 60, 60);
		$s_color = $this->get_rgb(1, 60, 50);
		$hf_color = $this->get_rgb(1, 60, 80);
		$hs_color = $this->get_rgb(1, 60, 70);
	
		$data = implode(",", $arr);
		
		$labels = "";
		$data = "";
		$i = 0;
		while (list($key, $val) = each($arr)){
			if($i > 0){
				$labels .= ",";
				$data .= ",";
			}			
			$labels .= "\"$key\"";
			$data .= $val;			
			$i++;
		}
		$code .= "<script>var " . $id . "_data = {
			labels : [$labels],
			datasets : [
				{
					label: \"\",
					fillColor: \"$f_color\",
					strokeColor: \"$s_color\",
					highlightFill: \"$hf_color\",
					highlightStroke: \"$hs_color\",
					data: [$data]
				}
			]
		}</script>";	
		//$onload_queue = "var $id = document.getElementById(\"$id\").getContext(\"2d\");\r\n	var " . $id . "_bar = new Chart($id).Bar(" . $id . "_data);\r\n";		
		
		return array($code, $onload_queue);
	}
	
	
	//arr = array of values
	//labels = comma separated string of labels
	//id = id for creation of html elements and javascropt data use _ format, not hypens please
	function create_line_chartx($arr, $id){	
		//global $onload_queue;
		$code  = "<div class=\"bar-chart\">\r\n\t<canvas id=\"$id\" width=\"300\" height=\"300\"/>\r\n</div>";
		$f_color = $this->get_rgb(1, 60, 60);
		$s_color = $this->get_rgb(1, 60, 50);
		$hf_color = $this->get_rgb(1, 60, 80);
		$hs_color = $this->get_rgb(1, 60, 70);
	
		$data = implode(",", $arr);
		
		$labels = "";
		$data = "";
		$i = 0;
		while (list($key, $val) = each($arr)){
			if($i > 0){
				$labels .= ",";
				$data .= ",";
			}			
			$labels .= "\"$key\"";
			$data .= $val;			
			$i++;
		}
		$code .= "<script>var " . $id . "_data = {
			labels : [$labels],
			datasets : [
				{
					label: \"\",
					fillColor: \"$f_color\",
					strokeColor: \"$s_color\",
					highlightFill: \"$hf_color\",
					highlightStroke: \"$hs_color\",
					data: [$data]
				}
			]
		}</script>";	
		$onload_queue = "var $id = document.getElementById(\"$id\").getContext(\"2d\");\r\n	var " . $id . "_bar = new Chart($id).Bar(" . $id . "_data);\r\n";		
		
		return array($code, $onload_queue);
	}
	*/
	function google_chart($datarray){
		$id = $datarray['id'];
		$data = $datarray['data'];  //array of arrays
		$series = $datarray['series'];		
		$charthtml = "<script type=\"text/javascript\">
		google.setOnLoadCallback(drawChart);
		function drawChart() {
			var data = google.visualization.arrayToDataTable([
		  		['Labels'";
				foreach($series as $ser=>$val){					
					$charthtml .= ", '$ser'";
				}
				$charthtml .= "],";
		$labelsize = sizeof($data);
		$comma = ",";
		$i = 0;
		foreach($data as $key=>$val){
			if($labelsize -1 == $i){$comma = '';}
			
			$charthtml .= "\n\t\t\t\t['".$key."'";
			foreach($data[$key] as $k=>$v){
				$charthtml .= ",\t".$v;
			}
			$charthtml .= "]".$comma." ";
			$i++;
		}
		$charthtml .="
			]); 
			var options = {
				haxis: 'Time',
				vaxis: 'Visits',
				title: 'Ezproxy Visits by Platform',
				legend: { position: 'right' }
			}; 
			var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
			chart.draw(data, options);
      }
    </script>
	";
	$onload_queue = "<div id=\"curve_chart\" style=\"width: 1200px; height: 500px\"></div>";
	return array($charthtml, $onload_queue);
	
		
	}
	function create_chart($query, $chartype){  // charttypes: line, pie, bar, doughnut
		$canvasswidth = 1000;
		$canvassheight = 300;
		$id = $query['id'];
		$labels = $query['label'];
		$arr = $query['data'];
		//$arr, $labels, $id
		
		$json = array(
			'labels' => $labels,
			'datasets' => array()
		);
		if($chartype == 'pie'  || $chartype == 'doughnut'){
			$arr = $arr[0];
			$json = array();
		}
		$i = 1;
		$multiplier = 360/sizeof($arr);
		foreach($arr as $series){
			$hue = ($i*$multiplier);
			$colour= $this->get_rgb($hue, 50, 50);
			$highlight = $this->get_rgb($hue, 50, 60);		
			
			$fillColor = $this->get_rgb($hue, 60, 60);
			$strokeColor = $this->get_rgb($hue, 60, 50);
			$pointColor = $this->get_rgb($hue, 60, 30);
			$pointStrokeColor = $this->get_rgb($hue, 60, 40);
			
			$highlightFill = $this->get_rgb($hue, 60, 80); // pointhighlightfill
			$highlightStroke = $this->get_rgb($hue, 60, 70); // pointhighlightstroke
			switch($chartype){
				case 'pie':
					$json[] = array(
						'value'=>$arr[$i-1], 
						'color'=>$fillColor, 
						'highlight'=>$highlightFill, 
						'label'=>$labels[$i-1]
					);
					break;
				case 'bar':
					$json['datasets'][] = array(
						'label'=>"Series $i", 
						'fillColor'=>$fillColor, 
						'strokeColor'=>$strokeColor, 
						'highlightFill'=>$highlightFill, 
						'highlightStroke'=>$highlightStroke, 
						'data'=>$series
					);
					break;
				case 'line':
					$json['datasets'][] = array(
						'label'=>"Series $i", 
						'fillColor'=>$fillColor, 
						'strokeColor'=>$strokeColor, 
						'point'=>$pointColor, 
						'pointStroke'=>$pointStrokeColor, 
						'pointHighlightFill'=>$highlightFill, 
						'pointHighlightStroke'=>$highlightStroke, 
						'data'=>$series
					);
					break;
			}
			$i++;
		}
		
		$returnval  = "<div class=\"$chartype-chart\">\r\n\t<canvas id=\"$id\" width=\"$canvasswidth\" height=\"$canvassheight\"/>\r\n</div>"; 
		$returnval .= "<script>var " . $id . "_data = "; 
		$returnval .= json_encode($json);			
		$returnval .= "</script>"; 
		$options = "pointDot : false";
		$onload_queue = "var $id = document.getElementById(\"$id\").getContext(\"2d\");\r\n var " . $id . "_$chartype = new Chart($id).".ucfirst($chartype)."(" . $id . "_data, {".$options."});\r\n";
		
		return array($returnval, $onload_queue);
	}


	//pass in associative array with label as key and value
	function create_pie_chart($arr, $id){
		//global $onload_queue;
		$code  = "";	
		$code .= "<div class=\"pie-chart\">\r\n\t<canvas id=\"$id\" width=\"300\" height=\"300\" />\r\n</div>";
		$code .= "\r\n<script>\r\nvar " . $id . "_data = \r\n			[";
			
		$i = 1;
		$multiplier = 360/sizeof($arr);
		while (list($key, $val) = each($arr)){
			$hue = ($i*$multiplier);
			$colour= $this->get_rgb($hue, 50, 50);
			$highlight = $this->get_rgb($hue, 50, 60);		
			if($i > 1){
				$code .= ",";
			}		
			$code .= "{
				value: $val,
				color:\"" . $colour . "\",
				highlight: \"" . $highlight . "\",
				label: \"" . $key . "\"
			}";		
			$i++;
		}
		$code .= "];\r\n</script>";
		$onload_queue = "var $id = document.getElementById(\"$id\").getContext(\"2d\");\r\nvar " . $id . "_pie = new Chart($id).Pie(" . $id . "_data);\r\n";
		return array($code, $onload_queue);
	}
	function returnData(){
		
		$resultData = array();
		
		
	}

}


?>