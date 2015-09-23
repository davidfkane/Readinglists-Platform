<?php 
	print("\t\t\t\t<option value=\"0\" >NOT SELECTED:</option>\n");
	foreach($selected_module_dropdown as $result){
		print("\t\t\t\t<option value=\"".$result['mid']."\" >" . $result['sname'] . " => " . $result['dname'] . " => " . $result['modulename'] . "</option>\n");
	}

/*
if($this->session->userdata('email') == 0){
	foreach($results as $result){// $mid, $modulename, $did, $sid
		print("\t\t\t\t<option value=\"".$result[0]."\" ".$result[1] . " >". $result[2]."</option>\n");
	}
}else{
	foreach($results as $result){ // $mid, $modulename, $did, $sid
		if(in_array($this->session->userdata('email'), $result[3])){
			print("\t\t\t\t<option style=\"background-color: #600\" ".$result['mid'] . " value=\"".$result[0]."\" >". $result[2]."</option>\n");
		}else{
			print("\t\t\t\t<option value=\"".$result[0]."\" ".$result[1] . " >". $result[2]."</option>\n");
		}		
	}
}
*/
?>
