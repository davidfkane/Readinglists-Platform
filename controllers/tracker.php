<?php

class Tracker extends CI_Controller {

	function Tracker()
	{
		parent::__construct();	
		//$this->load->library('email');
	}
	function resource(){  // when we change the value of the department dropdown
       // $this->load->model('');
		$location = unserialize(base64_decode($this->uri->segment(3)));
		$browser = get_browser(null, true);
	   $query = "insert into clickthroughs (`username`, `usertype`, `list`, `list-item`, `type`, `source`, `url`, `catalogue`, `essential`, `timestamp`, ipaddress, platform, browser, version, win32, ismobiledevice) ";
	   
	   $query .= 'values(\'' . $location['username'] . '\', \'' . $location['usertype'] . '\', ' . $location['list'] . ', ' . $location['list-item'] . ', ' . $location['type'] . ', \'' . $location['source'] . '\', \'' . base64_decode($location['url']) . '\', ' . $location['catalogue'] . ', ' . $location['essential'] . ', UTC_TIMESTAMP(), INET_ATON(\''.$_SERVER['REMOTE_ADDR'].'\'), \''.$browser['platform'].'\', \''.$browser['browser'].'\', \''.$browser['version'].'\', \''.$browser['win32'].'\', \''.$browser['ismobiledevice'].'\' ); ';
		$this->db->query($query);
		#print "<pre>";		print "\n$query\n\n\n";		print_r($browser);		print "</pre>";
		header('Location: ' . base64_decode($location['url']));
	}
}
/* End of file Lists.php */
/* Location: ./system/application/controllers/Lists.php */
?>
