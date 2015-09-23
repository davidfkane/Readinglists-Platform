<?php

class Reports extends CI_Controller {

	function Reports()
	{
		parent::__construct();	
	}
	private function return_session($sess){
		if($this->session->userdata($sess)){
			return $this->session->userdata($sess);
		}else{
			return 0;
		}
	}
	
	function getallclicks()
    {		
		
		$this->load->model('report');
		$data['clickthroughs'] = $this->report->getallclicks();
		
		$data['title'] = "All Click-Throughs";
		$data['report_type'] = "table";
		
		$this->load->view('tablereport', $data);
    }
	function getallvisits()
    {		
		$this->load->model('report');
		$data['clickthroughs'] = $this->report->getallclicks();
		
		$data['title'] = "All Click-Throughs";
		$data['report_type'] = "table";
		
		$this->load->view('tablereport', $data);
    }
	function deleteclick()
    {		
		$this->load->model('report');
		$data['clickthroughs'] = $this->report->deleteclicks();
		
		$data['title'] = "All Click-Throughs";
		$data['report_type'] = "table";
		
		$this->load->view('tablereport', $data);
    }
	function index()
    {		
  
  
		$this->load->model('report');
		$data['report'] = $this->report->missing();
		#if($this->return_session('admin') == 'TRUE'){
			$this->load->view('report', $data);
		#}else{
		#	print "you are not authorised to view this page";
		#}
    }
	function books_modules()
    {		
		$this->load->model('report');
		$data['report'] = $this->report->books_modules($this->uri->segment(3));
		$this->load->view('books_modules', $data);
    }
	function overview(){
		$this->load->model('Books');
		$this->load->model('report');
      	$staffmodules = $this->Books->staff_modules($this->session->userdata('email'));
		$a = array();
		foreach($staffmodules as $s){
			print("<hr/>".$s['mid']."\n");
			$a['mid'] = $s['mid'];
			$a['modulename'] = $s['modulename'];
			$a['moduledata'] = $this->report->modules_clickthroughs($s['mid']);
			print_r($this->report->modules_clickthroughs($s['mid']));
		}
		$data['clickthroughs'] = $a;
		$this->load->view('overview', $data);
	}
	function flaretest(){
		$this->load->model('hierarchy');
		//$data['flarejson'] = $this->hierarchy->flaretest();
		$data['flarejson'] = $this->hierarchy->staff_modules('dkane@wit.ie');
		//$this->load->view('doc/managelists', $data);
		print $data['flarejson'];
	}
	function moodletest(){
		$this->load->model('hierarchy');
		//$data['flarejson'] = $this->hierarchy->flaretest();
		$data['flarejson'] = $this->hierarchy->moodle_modules('dkane@wit.ie');
		//$this->load->view('doc/managelists', $data);
		print $data['flarejson'];
	}
	
	
}



/* End of file Lists.php */
/* Location: ./system/application/controllers/Lists.php */
?>
