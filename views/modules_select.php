<?php /**
if(!defined($this->session->userdata('email')) || $this->session->userdata('email') == 0){
	foreach($results as $result){
		print("\t\t\t\t<option value=\"".$result[0]."\" ".$result[1] . " >". $result[2]."</option>\n");
	}
}else{
	foreach($results as $result){
		print("\t\t\t\t<option value=\"".$result[0]."\" ".$result[1] . " >". $result[2]."</option>\n");
	}
}

<? $this->load->view('modules_select'); ?>

**/

if($this->session->userdata('email') == 0){
	logfile("session email is not set: " . $this->session->userdata('email'));
	foreach($results as $result){
		print("\t\t\t\t<option value=\"".$result[0]."\" ".$result[1] . " >". $result[2]."</option>\n");
	}
}else{
	logfile("session email is: " . $this->session->userdata('email'));
	foreach($results as $result){
		if(in_array($this->session->userdata('email'), $result[3])){
			logfile("result 3 contains: " . $this->session->userdata('email'));
			#print("\t\t\t\t<option style=\"background-color: #900\" value=\"".$result[0]."\" ".$result[1] . " >=================================".$result[2] . " " . $em ."</option>\n");
			print("\t\t\t\t<option style=\"background-color: #600\" ".$result[1] . " value=\"".$result[0]."\" >". $result[2]."</option>\n");
		}else{
			#print("\t\t\t\t<option value=\"".$result[0]."\" ".$result[1] . " >". $result[2]."</option>\n");
		}		
	}
}
?>
