<?php
class departments extends CI_Model {
    function departments()
    {
        // Call the Model constructor
        parent::__construct();
		$this->load->database();
    }

    function hierarchy($account_id, $id = "NULL") 
    {	// if there is no parent id ($id) then the value defaults to NULL and it gets the root element(s).
		$data = array();  // what is returned by the function eventually
		if($id == 'NULL'){
			$qu = 'SELECT * from hierarchy where inst = '.$account_id.' and id_parent is NULL;';
		}else{
			$qu = 'SELECT * from hierarchy where inst = '.$account_id.' and id_parent = '.$id.';';
		print("<a href=\"javascript:history.go(-1);\">BACK</a></br>\n");
		}
			
		//print $qu;
        $query = $this->db->query($qu);
		array_push($data, array('NULL', "selected=\"selected\" class=\"default-selected\"", 'CHOOSE ONE'));
		foreach ($query->result_array() as $row) 
		{ 
			array_push($data, array($row['id'], "", $row['name']));
			print("<a href=\"".$this->config->item('path')."lists/list_hierarchy/".$row['id']."\">".$row['name']."</a></br>\n");
		} 
		return $data; 
    }

    function hierarchy_modules($account_id, $id) 
    {	// gets a list of modules 
		$data = array();  // what is returned by the function eventually
		//$qq = 'SELECT * FROM modules WHERE parent = (SELECT hid FROM hierarchy WHERE id = '.$id.' AND inst = '.$account_id.');';
		$qq = 'SELECT * FROM modules WHERE parent = '.$id.' and account = '.$account_id.';';
		//print $qq;
        $query = $this->db->query($qq);
		array_push($data, array('NULL', "selected=\"selected\" class=\"leaf\"", 'CHOOSE ONE'));
			print("<a href=\"javascript:history.go(-1);\">BACK</a></br>\n");
		foreach ($query->result_array() as $row)
		{
			array_push($data, array($row['mid'], "", $row['modulename']));
			print("<a href=\"".$this->config->item('path')."lists/list_hierarchy/".$row['mid']."\">".$row['modulename']."</a>  ");
			print("<a href=\"http://moodle/course/view.php?id=".$row['MOODLE_INTERNAL_ID']."\" target=\"_blank\">GO</a></br>\n");
		}
		return $data;
    }

    function school_departments($sid, $did)
    {	
		$data = array();  // what is returned by the function eventually
        $query = $this->db->query('SELECT dname, did from departments where sid = '.$sid.';');
		if($did == 0){
			array_push($data, array(0, "selected=\"selected\" class=\"default-selected\"", 'SELECT A DEPARTMENT'));
		}else{
			array_push($data, array(0, "", 'SELECT A DEPARTMENT'));
		}	
		foreach ($query->result_array() as $row)
		{
			if($did == $row['did']){
				array_push($data, array($row['did'], "selected=\"selected\" class=\"default-selected\"", $row['dname']));
			}else{
				array_push($data, array($row['did'], "", $row['dname']));
			}
		}
		return $data;
    }
}
?>
