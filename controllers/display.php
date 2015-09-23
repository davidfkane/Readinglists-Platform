<?php

class Displday extends CI_Controller {

	function Disdplay()
	{
		parent::__construct();	
		$this->load->library('email');
	}
	
	function search(){  // when we change the value of the department dropdown
        $this->load->model('Module');
        $data['results'] = $this->Module->search($_REQUEST['name_startsWith'], $_REQUEST['callback']);
		// $this->load->view('modules_select', $data); # not bothering with a view for now.
        print($data['results'][0]);
	}
}
/* End of file Lists.php */
/* Location: ./system/application/controllers/Lists.php */
?>
