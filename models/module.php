<?php
class module extends CI_Model {
    function module()
    {
        // Call the Model constructor 
        parent::__construct();
		$this->load->database();
    }
	
    function searchxxx($searchterm, $callback){							
		#$searchterm = $_GET['term'];
		#$searchterm = $this->uri->segment(3);
		if(strlen($searchterm) >2){
			$searchterms = explode(" ", $searchterm);
			$sqlstring = "select m.mid, d.dname, d.did, m.modulename, m.teachers, m.MOODLE_INTERNAL_ID from departments d, modules m where m.Did = d.did and (";
			#$sqlstring .= "and MATCH(m.modulename) AGAINST('".$searchterm."')  and (";
			$ct = 0;
			foreach($searchterms as $ter){
				if($ter != ""){
					if($ct == 0){
						$sqlstring .= "lcase(CONCAT(m.modulename, ' ', IFNULL(m.teachers, ''))) like lcase('%".$ter."%') ";
					}else{
						$sqlstring .= "or lcase(CONCAT(m.modulename, ' ', IFNULL(m.teachers, ''))) like lcase('%".$ter."%') ";
					}		
					$ct++;
				}
			}
			$sqlstring .= ") ";
			$sqlstring .= "ORDER BY ";			
			$ct = 0;			
			#relevancy ranking
			foreach($searchterms as $ter){
				if($ter != ""){
					if($ct == 0){
						$sqlstring .= "case when lcase(CONCAT(m.modulename, ' ', m.teachers)) like lcase('%".$ter."%') then 1 else 0 end ";
					}else{
						$sqlstring .= "+ case when lcase(CONCAT(m.modulename, ' ', m.teachers)) like lcase('%".$ter."%') then 1 else 0 end ";
					}		
					$ct++;
				}
			}			
			$sqlstring .= "desc limit 20 ";
			$query = $this->db->query($sqlstring);					
			$object=array('modules'=>
					$query->result_array()
			);
			//echo $callback.'('.json_encode($object).')';		
			#echo $callback.'(alert(\''.$sqlstring.'\'))';			
		}
	}
	
	function module_books_count($module_id){
		// this gets the number of items for the module in question.  If there are 0 books then we can delete the module.
		
		$querystring = 'SELECT count(*) as items from books b, books_modules bm, l_type where b.bid = bm.bid and l_type.type_id = b.type and bm.mid  = ' . $module_id . ';';
		if($module_id != 'NO_DATA'){
			$query = $this->db->query($querystring);
			//$query->result_array();
			$row = $query->row();
			return $row->items;
	
		}else{
			return 0;
		}
	}
	
	function delete_module($module_id, $email){
		#logfile("invoking function", 'model', __FUNCTION__, __FILE__);
		// this gets the number of items for the module in question.  If there are 0 books then we can delete the module.
		$count = $this->module_books_count($module_id);
		if($count == 0){
			// Are you sure you want to delete the module
			$sql = "delete from modules where mid = $module_id;";
			$query = $this->db->query($sql);
			#logfile($sql, 'model', __FUNCTION__, __FILE__);
			$sql2 = "delete from users_modules where mid = $module_id and email = '".$email."';";
			$query2= $this->db->query($sql2);
			#logfile($sql, 'model', __FUNCTION__, __FILE__);
			
		}else{
			// You can't delete the module as there are still $count items in it.
		}
	}
	
	
	
	
	function unique($field, $value, $table){
		#logfile("invoking function", 'model', __FUNCTION__, __FILE__);
		$sql = "SELECT COUNT(*) AS dupe FROM $table WHERE $field = '$value'";
		$query = $this->db->query($sql);
		//echo "<br/><strong>Unique: </strong>SELECT COUNT(*) AS dupe FROM $table WHERE $field = '$value'<br/>";
		$row = $query->row();
		#logfile("Query: $sql", 'model', __FUNCTION__, __FILE__);
		//print("Dupe::: = " . $row->dupe. "<br/>\n");
		return ($row->dupe > 0) ? FALSE : TRUE;
	}
	
	function multiunique($fields, $values, $table){
		
  	//$h = fopen($this->config->item('serverroot').$this->config->item('path').'php_log', 'a+');
		#print_r($fields);
		#print_r($values);
		$q = 	"SELECT COUNT(*) AS dupe FROM $table WHERE $fields[0] = '$values[0]' ";
		for($i = 1; $i < sizeof($fields); $i++){
			$q .= 	"AND $fields[$i] = '$values[$i]' ";
		}
		//error_log("    $q    ", 0);
		$query = $this->db->query($q);
		$row = $query->row();
		//error_log("  MULTIUNIQUE: Dupe::: = " . $row->dupe . "   ");
		if($row->dupe == 0){
			
			return TRUE;  // add user
			#print("<br/>Dupe: " . $row->dupe . "<br/>");
		}else{
			return FALSE; // we have duplicates do not add user
			#print("<br/>Dupe: " . $row->dupe . "<br/>");
		}
	//fclose($h);
	}
	function new_user($email, $fname, $lname, $username, $auth){
		
		
				//$password = $this->randomPassword();
				$password = 'randompass';
			# mail($email, "your password for readinglists", " User: $username <br/>\n Pass: $password");
				
				
		logfile("invoking function", 'model', __FUNCTION__, __FILE__);
		// checks if a user exists, and if they are not there, it adds them.	
			logfile("checking if $email is unique", 'red', __FUNCTION__, __FILE__);
		if($this->unique('email', $email, 'users') == TRUE){	
			logfile("NEW USER!", 'green', __FUNCTION__, __FILE__);
			if($auth == 'manual'){
				//$password = $this->randomPassword();
			#	mail($email, "your password for readinglists", " User: $username <br/>\n Pass: $password");
				//print "Password: " . $password;
			}else{
				$password = '';
				//print "no password needed";
			}
		
			$querystring = "insert into users ( email, username, firstname, lastname, auth, password, date_added) values('".strtolower($email)."',".$this->db->escape($username).",".$this->db->escape($fname).",".$this->db->escape($lname).",'".$auth."', '".$password."', NOW());";		
			print $querystring;
			logfile("\n$querystring\n");	
			$q = $this->db->query($querystring);
			$this->addUserDefaultList($username, $email);
		}else{
		#logfile("NOT NEW USER", 'red', __FUNCTION__, __FILE__);
		}
	}
	
	function create_new_list($datastruct){	
		$ds1 = base64_decode($datastruct);
  		$h = fopen($this->config->item('serverroot').$this->config->item('path').'php_log', 'a+');
		// we ignore everything else and just use the data structure;
		$datastructarray = unserialize(base64_decode($datastruct));
		!isset($datastructarray['TYPE'])?$type = 1:$type = $datastructarray['TYPE'];
		!isset($datastructarray['ACCOUNT'])?$account = 1:$account = $datastructarray['ACCOUNT'];
		!isset($datastructarray['MOODLE_MODULE_ANCESTOR_CATEGORIES'])?$ancestorcategories = array():$ancestorcategories = $datastructarray['MOODLE_MODULE_ANCESTOR_CATEGORIES'];
		
		$this->new_user(strtolower($datastructarray['MOODLE_USER']['email']), $datastructarray['MOODLE_USER']['firstname'], $datastructarray['MOODLE_USER']['lastname'], $datastructarray['MOODLE_USER']['username'], 'ldap');
		
		// check if a list exists; if it does not exist, create it 	
		if($this->multiunique(array('MOODLE_INTERNAL_ID'), array($datastructarray['MOODLE_INTERNAL_ID']), 'modules')  || $type == 4){	
		
			$query1 = "insert into modules (modulename, MOODLE_INTERNAL_ID, date_updated, note, countbooks, oldmodulename, linkedmid, unconfirmedbooks, account, type) ";
			$query1 .= "values (".$this->db->escape($datastructarray['MOODLE_MODULE_NAME']).",";
			$query1 .= "'".$datastructarray['MOODLE_INTERNAL_ID']."',";
			$query1 .= "NOW(), NULL, 0,".$this->db->escape($datastructarray['MOODLE_MODULE_NAME']).", ";
			$query1 .= "NULL, 0, $account, $type);";	
			$q = $this->db->query($query1);
			$moduleid = $this->db->insert_id();
		}else{		
			$moduleid = $this->getMIDfromMoodleID($datastructarray['MOODLE_INTERNAL_ID']);
			#fwrite($h, "mooid: external moodleid is " . $datastructarray['MOODLE_INTERNAL_ID'] ."\n");
			#fwrite($h, "mid: internal id is $moduleid;\n");
		}
		$this->assignStaffToModule($moduleid, $datastructarray['MOODLE_USER']['username'], strtolower($datastructarray['MOODLE_USER']['email']), $datastructarray['MOODLE_MODULE_NAME'], 'moodle');
		$this->manage_ancestor_tree($ancestorcategories, $moduleid, $datastructarray['MOODLE_MODULE_NAME']);
  		fclose($h);
    }
	
	function manage_ancestor_tree($ancestorarray, $module_id, $module_name){
		$account_id = 1;  // this will have to be checked against the HTTP referer in future, or we have separate dommains - but we will need to ensure security.
  		#$h = fopen($this->config->item('serverroot').$this->config->item('path').'php_log', 'a+');
		$s = sizeof($ancestorarray);
		$id_parent = 'NULL'; //the default value for that field in the database table, 'herarchy'.
		for($i = 0; $i < $s; $i++){
			
			#fwrite($h, "\tLOOP $i of ". $s ."\n");
			$id = $ancestorarray[$i]['id'];
			$name = $ancestorarray[$i]['name'];
			$inst = 1;
			$hid = 'NULL';
			// check if that tier exists;
			$q = "SELECT count(*) as ct FROM hierarchy ";
			$q .= "WHERE inst = " . $inst . " ";
			$q .= "AND id = " . $id . " ";
			//$q .= "AND id_parent = " . $id_parent . "; ";
			
			#fwrite($h, "\t\tUNIQUE??: $q\n\t\tID Parent: $id_parent;\n");
			
			// returns ct > 0 if already in table.
			$query = $this->db->query($q);
			$row = $query->row();
			$query->result_array();
			
			if ($row->ct > 0){
				$getid = "SELECT hid FROM hierarchy WHERE inst = ".$inst." AND id = " . $id . ";";
				$query2=$this->db->query($getid); 
				$hidrow = $query2->row();
				$hid = $hidrow->hid;  // get the ID for parentage
				
				if($i+1 == $s){
					$updatequery = "UPDATE hierarchy SET name = '".addslashes($module_name)."', id_parent = ".$id_parent." WHERE id = ".$id.";";
				}else{
					$updatequery = "UPDATE hierarchy SET name = '".addslashes($name)."', id_parent = ".$id_parent." WHERE id = ".$id.";";
				}
				$query3 = $this->db->query($updatequery);
					
			}else{
				// add this ancestor	
				if($id_parent == 'NULL'){
					$addancestor = "INSERT INTO hierarchy(inst, id, name) VALUES(".$inst.", " . $id . ", '" . $name . "');";  //default value is NULL in database.
				}else{
					if($i+1 == $s){// last in the array loop;
						$addancestor = "INSERT INTO hierarchy(inst, id, name, id_parent) VALUES(".$inst.", " . $id . ", '" . $module_name . "', " . $id_parent . ");";
					}else{
						$addancestor = "INSERT INTO hierarchy(inst, id, name, id_parent) VALUES(".$inst.", " . $id . ", '" . $name . "', " . $id_parent . ");";
					}
				}
					
				fwrite($h, "\t\tAdding Ancestor: $addancestor\n");	
				if($this->db->query($addancestor)){
					$hid = $this->db->insert_id();		
				}
			}
			//fwrite($h, "\t\tid_parent: $id_parent\n");
			$id_parent = $id;
		}// end ancestor loop;
		$qupdatemodule = "UPDATE modules SET parent = ".$id_parent.", account = ".$account_id.", ancestry = '". serialize($ancestorarray) ."' WHERE mid = ".$module_id.";";
		if($this->db->query($qupdatemodule)){
			// this is okay	
		}else{
			// this is not okay
		}
		// return $hid; // for inserting into modules table;
  	#fclose($h);
	}
	
	function getMIDfromMoodleID($mid = NULL){
		if($mid != NULL){
			$query = $this->db->query("SELECT mid FROM modules WHERE MOODLE_INTERNAL_ID = $mid limit 1");
			$row = $query->row();
			return $row->mid;
		}
	}
	
	
	function addUserDefaultList($username, $email){	
		$type = 2; // this is the kind of list we are creating.
		logfile("invoking function", 'green', __FUNCTION__, __FILE__);	
		$fullname = $username . " - INBOX";
		$query1 = "insert into modules ( modulename, Did, MOODLE_INTERNAL_ID, date_updated, note, countbooks, oldmodulename, linkedmid, unconfirmedbooks, type) ";
		$query1 .= "values (".$this->db->escape($fullname).",18,NULL, NOW(), NULL, 0,'', NULL, 0, ".$type.");";	
		
		logfile("Query: $query1", 'green', __FUNCTION__, __FILE__);	
		$q = $this->db->query($query1);
		$this->assignStaffToModule($this->db->insert_id(), $username, $email, $fullname, $fullname, 'inbox');
		
		logfile("should have assigned staff to this new module ", 'green', __FUNCTION__, __FILE__);	
  #fclose($h);
    }
	
	
	function assignStaffToModule($mid, $username, $email, $fullname, $shortname, $action=NULL){ 
		// action can be: inbox, moodle, invite
		$moodle_id = 'NULL';
		
		if($action == 'invite'){
			$admin = ', NOW(), 0';  //invitation pending - not able to administer list yet
			$invitecode = ", '".md5(strtolower($email).$mid)."'";
		}elseif($action == 'inbox'){
			$admin = ', NOW(), 2';  //owner - not currently used
			$invitecode = ", NULL";
		}else{
			$admin = ', NOW(), 1';  //admin
			$invitecode = ", NULL";
		}
		// check if the course is not already linked to the user (if the user is not a student).
  		//$h = fopen('/var/www/php_log', 'a');
		// check this particular teacher is not assigned already
		
  	
		//#fwrite($h, "\nMOOOOOOOOOOOOOOOOOO: $moodle_id\n");
		if($this->multiunique(array('mid','email'), array($mid, $email), 'users_modules') && $username != 'STUDENT'){	
			$query2 = "insert into users_modules (mid, MOODLE_INTERNAL_ID, email, fullname, shortname, idnumber, date_added, admin, invitecode) ";
			$query2 .= "values(".$mid.", " . $moodle_id . ", " .$this->db->escape($email).",".$this->db->escape($fullname).",".$this->db->escape($shortname).", ".$this->db->insert_id().$admin.$invitecode.");";	
			//print "<br/><strong>assign staff to module: </strong> $query2";
			$q = $this->db->query($query2);
  			$query3 = "update modules m set teachers = ( select group_concat(concat(firstname, \" \", lastname, \": (\", mc.email, \")\"), ' ') from users_modules mc, users me where mc.email = me.email and mc.mid = ".$mid.") where m.mid = ".$mid.";";
			$q2 = $this->db->query($query3);
			//#fwrite($h, "\n\nASSIGN QUERY: $query2\n");
		}else{
			//#fwrite($h, 'NOT UNIQUE\n');
		}
	//fclose($h);
	}
	
	function getMooID($mid){
  	$h = fopen('/var/www/php_log', 'a');
		##fwrite($h, "QUERY: ==start============================\n");
		##fwrite($h, "MID: $mid\n");
		if($mid == 0){
			return "NOT SPECIFIED";			
		}else{
			#$q = "Select mid from modules where MOODLE_INTERNAL_ID = " . $moodle_internal_id . " limit 1;";
			$q = "Select MOODLE_INTERNAL_ID from modules where mid = " . $mid . " limit 1;";
			$query = $this->db->query($q);
			$row = $query->row();
			##fwrite($h, "QUERY ROW N: ".$query->num_rows()."\n");
			if($row->MOODLE_INTERNAL_ID != ''){
			//if($query->num_rows() > 0 || $row->MOODLE_INTERNAL_ID == ''){
				##fwrite($h, "QUERY: ".$row->MOODLE_INTERNAL_ID."\n");
				##fwrite($h, "QUERY: ============================== end some data\n");
				return $row->MOODLE_INTERNAL_ID;
			}else{
				return "NO_DATA";
				##fwrite($h, "QUERY: ============================== end not data\n");
			}
			//logfile($row->modulename);
			#print "==>" . $row->modulename . "<br/>";
		}
	##fwrite($h, "QUERY: ==ENDING ENDING ENDING============================ before data\n");
	fclose($h);
	}
	
	function assigndepartments($dept){
		// trys to give back a reasonable department number;
		/* use this code to get the departments in Moodle to create the array below.
		
		SELECT DISTINCT
		(SELECT t3.id FROM mdl_course_categories t3 WHERE t3.id = (SELECT t2.parent FROM mdl_course_categories t2 WHERE t2.id = t1.parent LIMIT 1) LIMIT 1) AS DID,  
		(SELECT t3.name FROM mdl_course_categories t3 WHERE t3.id = (SELECT t2.parent FROM mdl_course_categories t2 WHERE t2.id = t1.parent LIMIT 1) LIMIT 1) AS DEPARTMENT  
		FROM mdl_course_categories t1, mdl_course
		WHERE t1.id = mdl_course.category 
		AND t1.depth = 4
		ORDER BY DID ASC;
		
		*/
		
		$returnvalue = 18;  // 18 means unassigned or unsorted.
		$departments = explode('/', trim($dept, '/'));
		$assign = array(
			"47"=>"1",		#	'Languages, Tourism & Hospitality',
			"1618"=>"1",		#	'Languages, Tourism & Hospitality',
			"511"=>"2",		#	'Creative and Performing Arts',
			"1783"=>"2",		#	'Creative and Performing Arts',
			"175"=>"3",		#	'Applied Arts',
			"1699"=>"3",		#	'Applied Arts',
			"381"=>"4",		#	'Nursing',
			"1848"=>"4",		#	'Nursing',
			"127"=>"5",		#	'Exercise, Health & Sport Science',
			"1800"=>"5",		#	'Exercise, Health & Sport Science',
			"36"=>"6",		#	'Construction & Civil Engineering',
			"24"=>"7",		#	'Engineering Technology',
			"26"=>"8",		#	'Architecture',
			"1386"=>"8",		#	'Architecture',
			"25"=>"9",		#	'Trade Studies',				# ADDED IN NEW MOODLE
			"1403"=>"9",		#	'Trade Studies',				# ADDED IN NEW MOODLE
			"35"=>"10",		#	'Chemical & Life Sciences',
			"1158"=>"10",		#	'Chemical & Life Sciences',
			"32"=>"11",		#	'Computing, Maths & Physics',
			"1039"=>"11",		#	'Computing, Maths & Physics',
			"816"=>"12",	#	'Adult & Continuing Education',  # ADDED IN NEW MOODLE
			"1531"=>"12",	#	'Adult & Continuing Education',  # ADDED IN NEW MOODLE
			"1238"=>"13",		#	'Accounting & Economics',
			"19"=>"13",		#	'Accounting & Economics',
			"20"=>"14",		#	'Management & Organisation',
			"1277"=>"14",		#	'Management & Organisation',
			"1340"=>"15",		#	'Graduate Business',
			"21"=>"15",		#	'Graduate Business',
			#""=>"16",		#	'All Business Departments',
			"522"=>"17",		#	'Socrates & Erasmus'
			#"523"=>"17"		#	'Socrates & Erasmus'	# REMOVED IN NEW MOODLE
			"935"=>"12",		#	teaching and learning
			"1582"=>"12",		#	teaching and learning
			"934"=>"12",		#	Prof. dev
			"1555"=>"12",		#	Prof. dev
			"1915"=>"12",		#	Social Personal & Health Education # ADDED IN NEW MOODLE
			"439"=>"12",		#	Social Personal & Health Education # ADDED IN NEW MOODLE
			""=>""		#	'Socrates & Erasmus'
		);
		foreach($departments as $dep){
			if(isset($assign[$dep])){
				$returnvalue = $assign[$dep];
				break;
			}
		}
		return $returnvalue;
	}
}
?>
