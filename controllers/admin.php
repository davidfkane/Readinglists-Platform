<?php

class Admin extends CI_Controller {

	function Admin()
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
}



/* End of file Lists.php */
/* Location: ./system/application/controllers/Lists.php */
?>
