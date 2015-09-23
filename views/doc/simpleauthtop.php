<?php
// phpinfo();
//print "testing";
if ($_SERVER['SERVER_PORT']!=443){
	// this is to make sure that we stay on the secure port.
	$url = "https://". $_SERVER['SERVER_NAME'] . ":443".$_SERVER['REQUEST_URI'];
	header("Location: $url");
}

if($accesslevel != 'teacher' && (!defined($this->session->userdata('authorised')) || $this->session->userdata('authorised') != 'TRUE')){ 	  // if not logged in
	header("Location: /" . $this->config->item('path'). "lists/login/" . base64_encode("https://". $_SERVER['SERVER_NAME'] . ":443".$_SERVER['REQUEST_URI']));
}

?>