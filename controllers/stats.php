<?php

class Stats extends CI_Controller {

	var $startdate;
	var $enddate;
	var $timeinterval;
	function Stats()
	{
		parent::__construct();	
		/* timestamps in Javascript are in milliseconds and in PHP, they are in seconds.  Therefore, the conversion factor is 1000. */
		
		$this->startdate = (time()-604800)*1000;
		$this->enddate = time()*1000;
		$this->timeinterval = 'minute';
		if(isset($_POST['hiddenstartdate'])){ $this->startdate = $_POST['hiddenstartdate'];	}
		if(isset($_POST['hiddenenddate'])){	$this->enddate = $_POST['hiddenenddate'];	}
		if(isset($_POST['timeinterval'])){	$this->timeinterval = $_POST['timeinterval'];	}
	}
	private function return_session($sess){
		if($this->session->userdata($sess)){
			return $this->session->userdata($sess);
		}else{
			return 0;
		}
	}
	function index(){
		//
		$this->load->model('ezproxy/ezproxy');
		$data['view'] = 'index';
		$data['startdate'] = $this->startdate;
		$data['enddate'] = $this->enddate;
		$data['timeinterval'] = $this->timeinterval;
		$data['timeintervalselect'] = $this->ezproxy->returnTimeIntervalSelect($this->timeinterval);
		$data['allplatforms'] = $this->ezproxy->getAllPlatforms();
		$data['platforms'] = $this->ezproxy->getPlatforms();
		$this->load->view('ezproxy/ezproxyoverview', $data);
	}
	
	function visits(){		
		
		/*
			Structure of URL
			assume visits..
			/how/what/when/
			/pie|bar|line/minute/2015-04
		*/
		
		# time format:
		# 2015-04-01-14-30:2015-04-01-14-30
		// eg. https://library.wit.ie/readinglists  /stats/visits/together3/day4/2015-04-10:2015-04-15
		//											/stats/visits/platforms3	/interval4	/timerange5
	
		$data['view'] = 'chart';
		
		$data['startdate'] = $this->startdate;
		$data['enddate'] = $this->enddate;
		$data['timeinterval'] = $this->timeinterval;
		$platforms 	= $this->uri->segment(3); // values:[together|separate|ebsco-webofscience-emerald]
		$interval	= $this->uri->segment(4);
		$timerange 	= $this->uri->segment(5);
		if($platforms == '' || $interval == '' || $timerange == ''){
			$data['view'] = 'index';
			$platforms = 'together';
		}
		//log_message('error', 'Entering visits');
		$this->load->model('ezproxy/ezproxy');										//$id, $platforms, $timerange, $ival
		$gv = $this->ezproxy->getVisits('ezproxyusage', $platforms, $timerange, $interval);
		#print_r($gv);
		$charthtml = $this->ezproxy->google_chart($gv);
		$data['allplatforms'] = $this->ezproxy->getAllPlatforms();
		$data['platforms'] = $platforms;
		//$data['platformstogetherorseparate'] = $this->ezproxy->getPlatforms();
		$data['timeintervalselect'] = $this->ezproxy->returnTimeIntervalSelect($this->timeinterval);
		$data['charthtml'] = $charthtml[0];
		$data['onload_queue'] = $charthtml[1];
		$data['sql'] = $this->ezproxy->sql;
		#if($this->return_session('admin') == 'TRUE'){
			$this->load->view('ezproxy/ezproxyoverview', $data);
		#}else{
		#	print "you are not authorised to view this page";
		#}
    }

	
}



/* End of file Lists.php */
/* Location: ./system/application/controllers/Lists.php */
?>
