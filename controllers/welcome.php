<?php

class Welcome extends CI_Controller {

	function Welcome()
	{
		parent::__construct();	
	}
	
	function index()
	{
		$this->load->view('welcome_message');
	}
	function book()
    {
        $this->load->model('Books');

        $data['query'] = $this->Books->get_last_ten_entries();

        $this->load->view('books', $data);
    }
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */