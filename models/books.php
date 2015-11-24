<?php
class Books extends CI_Model {
	/**
	BOOKS:  This is where all the action happens, edit, update and delete...
	
			|	 	ADD	 	| 	 EDIT 	|	EDIT/LIB| 	 DELETE 	 |
-----------------------------------------------------------------------
BOOK 		|		YES		|	yes 	|	no		|		N/A		 |
-----------------------------------------------------------------------
BOOK_MODULE |		YES		|			|			|				 |
-----------------------------------------------------------------------

Note on recording the creation of new items.  This should take the form of a 'create date' and 'create user' in both the BOOK and BOOK_MODULE tables.

This is necessary because it makes the recording of the audit trail less complex. It makes it easier to record new 

*/
    function Books()
    {
        // Call the Model constructor
        parent::__construct();
		$this->load->database();
		$this->load->helper('debug_helper');
    }
	function linkmodules($email, $mid, $action){
		
		logfile("\n===================\nlinkmodules action: " . $action . "\n" . $email . "\n" . $mid);
		if($action == 'link'){
			$query = "INSERT users_modules(mid, email, fullname, shortname) select mid, '".$email."' as email, modulename as fullname, modulename as shortname from modules where mid = ".$mid.";";
			logfile('unlinking: ' . $query);
			$q = $this->db->query($query);
  			$query3 = "update modules m set teachers = ( select group_concat(concat(firstname, \" \", lastname, \": (\", mc.email, \")\"), ' ') from users_modules mc, users me where mc.email = me.email and mc.mid = ".$mid.") where m.mid = ".$mid.";";
			$q2 = $this->db->query($query3);
			//$this->db->query($q);
			return "unlink";
		}else{			;
			$query = "delete from users_modules where mid = (select mid from modules where mid = ".$mid.") and email = '".$email."';";
			logfile('linking: ' . $query);
			logfile($query);
			$q = $this->db->query($query);
			//$this->db->query($q);
  			$query3 = "update modules m set teachers = ( select group_concat(concat(firstname, \" \", lastname, \": (\", mc.email, \")\"), ' ') from users_modules mc, users me where mc.email = me.email and mc.mid = ".$mid.") where m.mid = ".$mid.";";
			$q2 = $this->db->query($query3);
			return "link";
		}		
		logfile("linkedmodules function == > Query: " . $query . "\nAction: " . $action . "\n");
	}
	function substitute_module_list($module, $replacewithmodule){
		//logfile("\tFind:\t$module");
		//logfile("\tReplace:\t$replacewithmodule");
		if($module == $replacewithmodule){
			//logfile("\tmodules are the same");
		}elseif($module == 0){
			//logfile("\tchoose the module you want to overrwrite from the list above");
		}else{
			// do the normal stuff.
			$deletequery = "delete from books_modules where mid = ".$module.";";
			$this->db->query($deletequery);
			$replacequery = "INSERT books_modules(bid, mid, Did, inactive, date_updated, essential, notes, creator, date_created) SELECT bid, ".$module.", Did, inactive, date_updated, essential, NULL, '".$this->session->userdata('user')."', date_created from books_modules where mid = ".$replacewithmodule.";";
			//logfile("Deletequery: \t" . $replacequery);
			$this->db->query($replacequery);
		}	
	}
	function resequencelist($sequence, $mid){
		$seq = print_r($sequence, TRUE);
		echo "INTO RESEQUENCE FUNCTION: $mid \n $seq";
		
		// Method 2
		// If you want to rollback if there is an error
		$this->db->trans_start();
		$c = 0;
		foreach ($sequence as $seq){
			$values['sequence'] = $c;	
			// Using method 1, store the error id in an array
			if(!$this->db->where('mid', $mid)->where('bid', $seq)->update('books_modules', $values)){break;}
			$c++;
		}
	
		// Method 2
		// This will return TRUE or FALSE
		$this->db->trans_complete();
		return $this->db->trans_status();
		
	}
	function movebooks($destinationmodule, $books, $method = 'copy'){
	
		
		
		if($method == 'copy'){ // we are cloning bookmarks to a different module
			$query = "";
			$querystart = "REPLACE INTO books_modules(bid, mid, inactive, date_updated, essential, notes, creator, date_created) ";
			foreach($books as $book){
				$query = $querystart . " VALUES(". $book . ", ". $destinationmodule .", 0, NULL, 'supp', NULL, '".$this->session->userdata('user')."', NULL);";
				$this->db->query($query);
				//print($query . "\n<br/>");
				$query = "";
			}
		}
		
	}
	function is_linked($email, $mid){
		// just checks if a staff member is associated with a particular module
		$query = "select count(*) as linked from users_modules where mid = ". $mid ." and email = '". $email . "';";
		//logfile(">>===============================>>" . $query);
		$q = $this->db->query($query);
		$row = $q->row();
		logfile("==========>\n\n" . $query . "\n\n==========>" . $row->linked . " zz");
		if($row->linked >= 1){
			return 'unlink';
			logfile("return unlink");
		}else{
			return 'link';
			logfile("return  link");
		}
	}
    function gotoModule($mid)
    {
		/****
		 * resets session vars to staff module specific location.
		 * 
		 ****/
		// logfile('select m.mid, m.did, s.sid from users_modules c, modules m, departments d, schools s where c.MOODLE_INTERNAL_ID = m.MOODLE_INTERNAL_ID and d.did = m.did and d.sid = s.sid and mid = '.$mid.';');
        //$query = $this->db->query('SELECT distinct modules.modulename, modules.mid from modules, books where books.did = '.$did.' and books.Module = modules.mid;'); 4281
		if($mid == 'NO_DATA'){ $mid = "'NO_DATA'";}
		$querystring = 'select distinct m.mid, m.did, d.sid from modules m, departments d, schools s where d.did = m.did and m.mid = '.$mid.';';
        $query = $this->db->query($querystring);
		$data = array();  // what will be returned by the function			
		
		$data = array('mid' => 0, 'did' => 0, 'sid' => 0);
		foreach ($query->result_array() as $row){
			$data = array('mid' => $row['mid'], 'did' => $row['did'], 'sid' => $row['sid']);
		}
		return $data;		
    }

    function staff_modules2($email)	//backup of the original function
    {
		/****
		 * This query is going to give us all the modules in a particular department.
		 * 
		 ****/
		//logfile('select m.mid, m.modulename, d.dname, s.sname from users_modules c, modules m, departments d, schools s where m.MOODLE_INTERNAL_ID = c.MOODLE_INTERNAL_ID and m.did = d.did and s.sid = d.sid and lcase(c.email) = '.$email.' order by sname, dname, modulename asc;');
        //$query = $this->db->query('SELECT distinct modules.modulename, modules.mid from modules, books where books.did = '.$did.' and books.Module = modules.mid;');
        $query = $this->db->query('select m.mid, m.modulename, d.dname, s.sname from users_modules c, modules m, departments d, schools s where m.mid = c.mid and m.did = d.did and s.sid = d.sid and lcase(c.email) = \''.$email.'\' order by sname, dname, modulename asc;');
		 
		$data = array();  // what will be returned by the function			
		foreach ($query->result_array() as $row){
			//logfile('\nmid => ' .$row['mid'] . '\nmodulename => '.$row['modulename']. '\ndname => '.$row['dname']. '\nsname => '.$row['sname']);
			array_push($data, array('mid' => $row['mid'],'modulename' => $row['modulename'], 'dname' => $row['dname'], 'sname' => $row['sname'], ));
		}
		//print_r($data);
		return $data;		
		
    }
	
    function staff_modules($email)
    {



		/****
		 * This query is going to give us all the modules in a particular department.
		 * 
		 ****/
		
//echo "Sort by keys\n";
#$smoothie = array('orange' => 1, 'apple' => 1, 'yogurt' => 4, 'banana' => 4);
#print_r($smoothie);
#uksort( $smoothie, 'strnatcmp');
#print_r($smoothie);
		$sqlquery = 'select mc.mid as mid,  m.modulename as modulename, m.ancestry, m.type from modules m, users_modules mc where mc.email = \''.$email.'\' and mc.mid = m.mid order by type, ancestry asc';
		//print($sqlquery);
        $query = $this->db->query($sqlquery);
		# print "£asdfadsa";
		$data = array();  // what will be returned by the function		
		$end = ':';	
		foreach ($query->result_array() as $row){
			$ancestry = unserialize($row['ancestry']);
		#	print"<pre style=\"background-color: white; color: black; font-weight: bold;\">";print_r($ancestry);print"</pre>";
		#	print"<pre style=\"background-color: pink; color: black; font-weight: bold; border-bottom: double 5px black;\">";print_r($row);print"</pre>";
			$ancestry_revised = '';
			if(isset($ancestry[0])){
				// print "<h3>Ancestry 1:".$ancestry[0].":</h3>";
				//print('count: ' . sizeof($ancestry) . ' ' . $row['modulename'] . ' <br/>');
				foreach($ancestry as $ancestor){
					$ancestry_revised .= $ancestor['name'].';;;;';
				}
			}else{
				//print "<h3>Ancestry does not exist</h3>";
			}
			$data[trim($ancestry_revised, ';') . $end] = array('mid' => $row['mid'],'modulename' => $row['modulename'], 'ancestry' => $ancestry, 'type' => $row['type']);						
			//array_push($data, );
			$end .= ':';
		}
		//uksort($data, 'strnatcmp');
		
		//print"<pre style=\"background-color: yellow; color: black; font-weight: bold;\">";print_r($data);print"</pre>";
		return $data;				
    }
	
	function change_module_title($mid, $newtitle){
		// takes mid and newtitle as an arguement
		$q = "UPDATE module SET modulename = '$newtitle' where mid = $mid;";
		$this->db->query($q);
			//logfile("function changeEssential($bid, $status)  ".$q);
		//$Message = '$oldtitle';
		//logfile($q);
		
	}
    function department_modules($did, $mid)
    {
		/****
		 * This query is going to give us all the modules in a particular department.
		 * 
		 ****/
		 
        //$query = $this->db->query('SELECT distinct modules.modulename, modules.mid from modules, books where books.did = '.$did.' and books.Module = modules.mid;');
        $query = $this->db->query('SELECT distinct modulename, mid, MOODLE_INTERNAL_ID, type from modules where Did = '.$did.' order by modulename;');
		$data = array();  // what will be returned by the function			
		if($mid == 0){
			array_push($data, array(0, "selected=\"selected\" class=\"default-selected\"", 'SELECT A MODULE'));
		}else{
			array_push($data, array(0, "", 'SELECT A MODULE'));
		}	
		foreach ($query->result_array() as $row){
			
			// FETCH THE EMAILS AND ADD TO A SMALL ARRAY;
			$emails = $this->db->query('SELECT email from users_modules where mid = '.$row['mid'].';');
			$email_list = array();
			foreach($emails->result_array() as $em){
				foreach($em as $e){
					array_push($email_list, $e);
				}
			}
			//print_r($email_list); print"\n--\n";
			if($mid == $row['mid']){
				array_push($data, array($row['mid'], "selected=\'selected\' class=\'default-selected\'", $row['modulename'], $email_list));
			}else{
				array_push($data, array($row['mid'], "style=\'background-color: blue\'", $row['modulename'], $email_list));
			}
		}
		return $data;		
    }
	
	function bookdetails($bid){
	//function bookmarktype($bid){
		// logfile("Inside segment: " . $module_id);
		// this gets the list of books for the module in question.
		// this should actually return an array.  There should be no HTML in this page really 
		// HTML should only be in the view, not the model, which this is.
		$querystring = 'SELECT * from books where bid = '.$bid.';';		
		$query = $this->db->query($querystring);
		return $query->row();
		
	}
	function module_books($module_id, $action){
		// logfile("Inside segment: " . $module_id);
		// this gets the list of books for the module in question.
		// this should actually return an array.  There should be no HTML in this page really 
		// HTML should only be in the view, not the model, which this is.
		if($action == 'deleted'){$and = 1;}else{$and = 0;}
		$querystring = 'SELECT b.bid, b.Author, b.Year, b.Title, b.Publisher, b.place, b.isbn, b.libid, bm.essential, b.date_created, b.date_updated, b.url, b.type, l_type.type_name, bm.notes, bm.sequence ' ;
		$querystring .= 'from books b, books_modules bm, l_type ' ;
		$querystring .= 'where b.bid = bm.bid ';
		$querystring .= 'and l_type.type_id = b.type ';
		$querystring .= 'and bm.mid  = ' . $module_id . ' ';
		$querystring .= 'and bm.inactive = ' . $and . ' ';
		if($action == 'website'){
			$querystring .= 'and b.date_updated IS NOT NULL ';
		}
		$querystring .= 'order by bm.sequence asc;';
		
		if($module_id != 'NO_DATA'){
			$query = $this->db->query($querystring);
			$query->result_array();
			$results = Array();
			foreach ($query->result_array() as $tuple){
				array_push($results, $tuple); 
			}
	
			$data['module'] = $this->getModuletID($module_id); 
			$data['results'] = $results; 
			$data['action'] = $action; 
			$data['module'] = $module_id; 
			//logfile("BOOKS FOR MODULE: " . print_r($data, true));
			return $data;
		}else{
			return array('module' => $this->getModuletID($module_id), 'action' => $action);
		}
	}
	function populate_edit_book_form($action, $module){ //where action is either add, update, delete, or undelete
		if(!isset($id) && isset($_POST['bid'])){
			$id = str_replace('bookID_', '', $_POST['bid']);
		}
		if(!isset($action)){
			$action = $_POST['action'];
		}
		if(!isset($module)){
			$action = $_POST['mid'];
		}
		$data = array();
		if($action == 'new' || $action == 'bookmarklet'){
			// in cases of a new book, we create the array for the values but give them a null
				$data['Message'] = '0';		
				$data['Title'] = '';
				$data['Author'] = '';
				$data['Year'] = '';
				$data['Publisher'] = '';				
				$data['bid'] = '';
				$data['Did'] = '';
				
				$data['place'] = '';
				$data['isbn'] = '';
				$data['libid'] = ''; 
				$data['url'] = '';
				$data['type'] = '';
				$data['Essential'] = '';
				$data['Notes'] = '';
				$data['Supplimentary'] = '';
				
				$data['Module'] = $_POST['mid'];
				$data['action'] = $action;
				if($action == 'bookmarklet'){
					if(isset($_POST['biblio'])){
						if(isset($_POST['biblio']['title'])){	$data['Title'] = 	$_POST['biblio']['title'];	}
						if(isset($_POST['biblio']['url'])){		$data['url'] = 		urldecode($_POST['biblio']['url']);	}
						if(isset($_POST['biblio']['author'])){	$data['Author'] = 	$_POST['biblio']['author'];	}
						if(isset($_POST['biblio']['year'])){	$data['Year'] = 	$_POST['biblio']['year'];	}
						if(isset($_POST['biblio']['isbn'])){	$data['isbn'] = 	$_POST['biblio']['isbn'];	}
						if(isset($_POST['biblio']['publisher'])){	$data['Publisher'] = 	$_POST['biblio']['publisher'];	}
						if(isset($_POST['biblio']['publicationplace'])){	$data['place'] = 	$_POST['biblio']['publicationplace'];	}
						if(isset($_POST['biblio']['libid'])){	$data['libid'] = 	$_POST['biblio']['libid'];	}
						$data['type'] = '3';
					}
				}
				
		}/***/
		if($action == 'update'){
			// this is what normally happens - generates the fields to fill out the form for a particular book
			$querystring = 'SELECT ';
			$querystring .= 'books.bid, books.Author, books.Year, books.Title, books.Publisher, books.place, books.isbn, books.libid, ';
			$querystring .= 'books_modules.essential, books_modules.date_updated, books_modules.notes, books_modules.Did, books_modules.mid, books.url, books.type ';
			$querystring .= 'from books, books_modules ' ;
			$querystring .= 'where books.bid = ' . $id  . ' ';
			$querystring .= 'and books.bid = books_modules.bid ';
			$querystring .= 'and books_modules.mid  = ' . $_POST['mid'] . ';';
			logfile($querystring);
			
			$query = $this->db->query($querystring);
			$query->result_array();
			if ($query->num_rows() > 0){
				$data['Message'] = '0';
				$row = $query->row_array();			
				$data['Title'] = $row['Title'];
				$data['Author'] = $row['Author'];
				$data['Year'] = $row['Year'];
				$data['Publisher'] = $row['Publisher'];
				$data['place'] = $row['place'];
				$data['isbn'] = $row['isbn'];
				$data['libid'] = $row['libid'];
				$data['url'] = $row['url'];
				$data['type'] = $row['type'];
				
				$data['bid'] = $row['bid'];
				$data['Did'] = $row['Did'];
				$data['Notes'] = $row['notes'];
				//logfile("Notes = " . $row['notes']);
				$data['Module'] = $row['mid'];
				$data['Essential'] = '';
				$data['Supplimentary'] = ''; 
				$data['action'] = $action;
				if($row['essential'] == 'ess'){$data['Essential'] = 'checked=\"checked\"';}
				if($row['essential'] == 'supp'){$data['Supplimentary'] = 'checked=\"checked\"';}
			}else{
				$data['Message'] = '<fieldset><legend>Edit Book</legend>Error: no book exists for that ID.  Contact the library</fieldset>';
			}
		}
		if($action == 'delete'){
			$q = "UPDATE books_modules SET inactive = 1 where bid = $id and mid = $module;";
			//logfile("populate_edit_book_form()action is 'delete': \n".$q);
			$this->db->query($q);
			//print $q;
			$data['Message'] = '<fieldset><legend>Delete Book</legend>You have successfully deleted this book <em>(id: '.$id.')</em><br/><br/><span id="viewdeleted" class="buttonspan" onMouseover="this.style.cursor=\'pointer\'">View Deleted Items for this Module</span> </fieldset>';
		}
		if($action == 'undelete'){
			$q = "UPDATE books_modules SET inactive = 0 where bid = $id and mid = $module;";
			//logfile("\n\npopulate_edit_book_form()action is 'undelete': \n".$q);
			$this->db->query($q);
			$data['Message'] = '<fieldset><legend>Delete Book</legend>You have successfully <strong>UN</strong>deleted this book <em>(id: '.$id.')</em><br/></fieldset>';
		}
		if($action == 'purge'){
			$q = "DELETE FROM books_modules where bid = $id and mid = $module;";
			//logfile("\n\npopulate_edit_book_form()action is 'undelete': \n".$q);
			$this->db->query($q);
			$data['Message'] = '<fieldset><legend>Purge Book</legend>You have <strong style="color:red;">PURGED</strong> this book forever from the database.<em>(id: '.$id.')</em><br/></fieldset>';
		}
		return $data;
	}
	function changeEssential($bid, $status, $mid){
		logfile('changeEssential()');
		$q = "UPDATE books_modules SET essential = '$status' where bid = $bid and mid = $mid;";
		logfile("function changeEssential($bid, $status)  ".$q);
		$this->db->query($q);
		$Message = '<fieldset><legend>Delete Book</legend>You have successfully <strong>UN</strong>deleted this book <em>(id: '.$bid.')</em><br/></fieldset>';
		logfile($q);
	}
	function getModuleType($mid){
		if($mid == 0 || $mid == "NO_DATA"){
			return "NOT SPECIFIED";
		}else{
			$q = "Select type from modules where mid = " . $mid . ";";
			
			$query = $this->db->query($q);
			$row = $query->row();
			if($query->num_rows() > 0){
				return $row->type;
			}else{
				return "NO_DATA";
			}
			//logfile($row->modulename);
			#print "==>" . $row->modulename . "<br/>";
		}
	}
	function getModuletID($mid){
		if($mid == 0 || $mid == "NO_DATA"){
			return "NOT SPECIFIED";
		}else{
			$q = "Select modulename from modules where mid = " . $mid . ";";
			
			$query = $this->db->query($q);
			$row = $query->row();
			if($query->num_rows() > 0){
				return $row->modulename;
			}else{
				return "NO_DATA";
			}
			//logfile($row->modulename);
			#print "==>" . $row->modulename . "<br/>";
		}
	}
	function getMID($moodle_internal_id, $instance = 1, $account = 1){
		if($moodle_internal_id == 0){
			return "NOT SPECIFIED";
		}else{
			#$q = "Select mid from modules where MOODLE_INTERNAL_ID = " . $moodle_internal_id . " limit 1;";
			$q = "Select mid from modules where MOODLE_INTERNAL_ID = " . $moodle_internal_id . ";"; # and account = $account and type = $instance limit 1;";
			#print $q . " <br/>";
			//logfile($q);
			$query = $this->db->query($q);
			$row = $query->row();
			if($query->num_rows() > 0){
				return $row->mid;
			}else{
				return "NO_DATA";
			}
			//logfile($row->modulename);
			#print "==>" . $row->mid . "<br/>";
		}
	}
	function getMoodleTitle($moodle_internal_id){
		if($moodle_internal_id == 0){			return "NOT SPECIFIED";		}
		else{
			$q = "Select modulename from modules where MOODLE_INTERNAL_ID = " . $moodle_internal_id . ";";			
			$query = $this->db->query($q);
			$row = $query->row();
			if($query->num_rows() > 0){
				return $row->modulename;
			}else{
				return "NO_DATA";
			}
		}
	}
	
	/*
	function countInactiveModules($mid){ // gets the number of deleted items in a module;
		if($mid == 0){
			return 0;
		}else{
			$q = "Select count(*) as ct from books where Module = " . $mid . " 	and inactive = 1;";
			$query = $this->db->query($q);
			$row = $query->row();
			return $row->ct;		
			//logfile($row->modulename);
		}
	}
	*/
	
	function getModuleStaff($mid){ // gets the number of deleted items in a module;
		$staff = array();
		if($mid != 'NO_DATA'){
			$q = "select me.email, me.firstname, me.lastname, mc.admin ";
			$q .= "from users_modules mc, users me, modules m ";
			$q .= "where m.mid = " . $mid . " ";
			$q .= "and m.mid = mc.mid ";
			$q .= "and mc.email = me.email order by admin desc; ";
			#print $q;
			$query = $this->db->query($q);
			foreach ($query->result_array() as $row){
				logfile($q.'\n');
				#logfile($q.'\nmid => ' .$row['mid'] . '\nmodulename => '.$row['modulename']. '\ndname => '.$row['dname']. '\nsname => '.$row['sname']);
				array_push($staff, array('email' => $row['email'],'firstname' => $row['firstname'], 'lastname' => $row['lastname'], 'admin' => $row['admin']));
			}
		}
		return $staff;		
	}
	private function isLibraryID($libid = 'XXXX', $bid){
		$query_operator = '!=';
		if(!defined($bid) || $bid = ''){$bid = 'NULL'; $query_operator = 'is not';}
	#	logfile("### Invoking isLibraryID($libid, $bid)", 'green', __FUNCTION__, __FILE__);
		// this function checks if a book from the library is already recorded in the books database.
		// This is because where a libid exists it needs to be unique within the books table.  
		// returns new bookID if the libraryID already exists else it returns an empty string.
		if($libid == ""){
			//do nothing
			logfile("### This book does not have a library id, so we do nothing", 'green', __FUNCTION__, __FILE__);
			return "";
		}else{
			logfile("### Libid has a value", 'green', __FUNCTION__, __FILE__);
			$querystring = "select bid from books where libid = '" . $libid . "' and bid ".$query_operator." " . $bid . ";";
			logfile("### Query string is: $querystring", 'green', __FUNCTION__, __FILE__);
			$query = $this->db->query($querystring);
			$query->result_array();
			if ($query->num_rows() > 0){
				$row = $query->row_array();		
				logfile("=====================================invoking: isLibraryID($libid) = " .$row['bid']);
				if($bid != $row['bid']){
					return $row['bid'];
					logfile("### There is another book with this library ID", 'green', __FUNCTION__, __FILE__);
				}else{
					logfile("### Only this book has this library ID", 'green', __FUNCTION__, __FILE__);
					return "";
				}
			}else{
				logfile("=====================================invoking: isLibraryID($libid) = BLANK");
				logfile("### There are no other books with this library ID", 'green', __FUNCTION__, __FILE__);
				return "";
			}
		}
	}
	private function isAlreadyInModule($libid, $mid){
		$query_operator = '!=';
		$querystring = "select count(*) as ct from books, books_modules ";
		$querystring .= "where books_modules.bid = books.bid ";
		$querystring .= "and books.libid = '$libid' ";
		$querystring .= "and books.libid != '' ";
		$querystring .= "and books_modules.mid = $mid;";
		// logfile("xxx Query string is: $querystring");
		$query = $this->db->query($querystring);
		$query->result_array();
		if ($query->num_rows() > 0){
			$row = $query->row_array();		
			//logfile("=====================================invoking: isLibraryID($libid) = " .$row['bid']);
			if($row['ct'] > 0){
				return true;
			}else{
				return false;
			}
		}else{
			//logfile("=====================================invoking: isLibraryID($libid) = BLANK");
			logfile("xxx Big Problem", 'green', __FUNCTION__, __FILE__);
			return "";
		}
	}
	function add_book_from_bookmarklet($module, $metadata){
	
		$data = array();
		$bid = $this->input->post('book_id');
		$libid = $this->input->post('book_libraryid');
		$where = "`bid` = " . $bid;	
				
		print("<pre>"); print_r($metadata); print("</pre>");
		$message = "";
		$str = "INSERT INTO books (Author, Year, Title, Publisher, place, isbn, libid, date_updated, creator, type, date_created, url) \n";
		$str .= "VALUES (";
		$str .= "'".addslashes($metadata['metadata']['author'])."', ";
		$str .= "'".$metadata['metadata']['year']."', ";
		$str .= "'".addslashes($metadata['metadata']['title'])."', ";
		$str .= "'".addslashes($metadata['metadata']['publisher'])."', ";
		$str .= "'".addslashes($metadata['metadata']['place'])."', ";
		$str .= "'".$metadata['metadata']['isbn']."', ";
		$str .= "'".$metadata['metadata']['libraryid']."', ";
		$str .= "'".date("Y-m-d H:i:s")."', ";										
		$str .= "'".$this->session->userdata('user')."', ";
		$str .= $metadata['metadata']['type'].", "; 
		$str .= "'".date("Y-m-d H:i:s")."', ";
		$str .= "'".addslashes(urldecode($metadata['metadata']['url']))."');"; 
	
		if($this->db->query($str)){
			// only make this insert if the first one worked out okay because they are joined tables.
			$insert_id = $this->db->insert_id();
			$str = "INSERT INTO books_modules (bid, mid, Did, inactive, date_updated, essential, notes, creator, date_created) \n";
			$str .= "VALUES (";
			$str .= "".$insert_id.", ";
			$str .= "".$module.", ";
			$str .= "18, ";
			$str .= "0, "; 	
			$str .= "'".date("Y-m-d H:i:s")."', ";
			$str .= "'supp', ";
			$str .= "'', ";
			$str .= "'".$this->session->userdata('user')."', ";
			$str .= "'".date("Y-m-d H:i:s")."');";
			if(!$this->db->query($str)){	
				$message .= "<br />There was a problem with the insert into the books_modules table - the add was not successful";
			}
		}else{
			$message = "There was a problem with the insert into the books table - the add was not successful";
		}
		if($message == ""){
			print $message;
			header("Location: " . urldecode($metadata['metadata']['url']));
		}
		
	}
	
	function update_book($action){
	
		logfile("\n updating book....\t" . $this->input->post('book_title'), 'model', __FUNCTION__, __FILE__);
		logfile("\n url ....\t\t" . $this->input->post('url'), 'model', __FUNCTION__, __FILE__);
		logfile("\n type ....\t\t" . $this->input->post('type'), 'model', __FUNCTION__, __FILE__);
		
		$data = array();
		$bid = $this->input->post('book_id');
		$libid = $this->input->post('book_libraryid');
		$where = "`bid` = " . $bid;	
		
		########### UPDATE CODE ########### 
		if($action == 'update'){		
		    logfile('updating a book: action is ' . $action . ' 5 july', 'YELLOW', __FUNCTION__, __FILE__);		
			$vars = array(  // BOOKS TABLE 
				'Title' => addslashes($this->input->post('book_title')), 
				'Author' => addslashes($this->input->post('book_author')), 
				'Year' => addslashes($this->cleanyear($this->input->post('book_year'))), 
				'Publisher' => addslashes($this->input->post('book_publisher')), 
				'place' => addslashes($this->input->post('book_place')), 
				'url' => addslashes($this->input->post('url')), 
				'type' => addslashes($this->input->post('type')), 
				'isbn' => addslashes($this->cleanISBN($this->input->post('book_isbn'))), 
				'libid' => addslashes($this->input->post('book_libraryid')), 
				'date_updated' => date("Y-m-d H:i:s")
			);				
			
			#logfile("\n url ....\t\t" . $this->input->post('url'), 'model', __FUNCTION__, __FILE__);
			#logfile("\n urlslashes\t" . addslashes($this->input->post('url')), 'model', __FUNCTION__, __FILE__);
			// do an update
			$str = $this->db->update_string('books', $vars, $where); 	
			logfile("\n\nWe are updating\n\n\t$str\n\n", 'model', __FUNCTION__);
			#foreach ( $_POST as $ind=>$val ){	
			#	logfile("\t" .$ind . "\033[01;37m " . $val, 'model', __FUNCTION__, __FILE__);
			#}
			$bid2 = $this->isLibraryID($libid, $bid);

				if($this->db->query($str)){
					$data['Message'] = "You Successfully updated the book";
					
					
				}else{ 
					$data['Message'] = "There was a problem with updating the books table - the update was not successful";
					// logfile($data['Message']);
				}	
#			}
			$vars2 = array( // BOOKS_MODULES TABLE
				'date_updated' => date("Y-m-d H:i:s"), 
				'essential' => addslashes($this->input->post('essential')), 
				'notes' => addslashes($this->input->post('book_notes')) 
			);
			# UPDATE BOOKS_MODULES ##########
			if($bid2 != ""){ 
				$where2 = "`bid` = " . $bid2 ." and `mid` = " . $this->input->post('module_id');
			}else{
				$where2 = "`bid` = " . $bid." and `mid` = " . $this->input->post('module_id');
			}				
			$str2 = $this->db->update_string('books_modules', $vars2, $where2); 
			// logfile("\n\n\nUPDATE BOOKS MODULES::::::::::::: \n$str2\n");	
			
			if($this->db->query($str2)){
				
			}else{
				$data['Message'] .= "<br/>There was a problem with updating the books_modules table - the update was not successful";
			}
		}
		########### END UPDATE CODE ##########
		
		######## BEGIN ADD NEW BOOK #####
		//logfile("Action: " . $action . "\nBook being created");
		//phpinfo();
		//debug_var($this->input->post('module_id'));
		if($action == 'new'){// add a new book
			logfile("we are adding a new book", 'GREEN', __FUNCTION__, __FILE__);
			$insert_id = "";
			$relevant_book_id = "";
			if($this->isAlreadyInModule($libid, $this->input->post('module_id'))){		
				//do nothing;
				header('Location: ' . base_url() . 'index.php/lists/#editbook');
				logfile("is already in module($libid,  );", 'magenta', __FUNCTION__, __FILE__);
			}else{
				logfile("Not already in module.  So, let's test islibraryid", 'magenta', __FUNCTION__, __FILE__);
				$xxx = $this->isLibraryID($libid, $bid);
				logfile("isLibraryID($libid, $bid) = " . $xxx, 'magenta', __FUNCTION__, __FILE__);
				
				//proceed;
				if($this->isLibraryID($libid, $bid) == ""){		
					// OK to proceed;
					logfile("OK to add new book", 'magenta', __FUNCTION__, __FILE__);
					$str = "INSERT INTO books (Author, Year, Title, Publisher, place, isbn, libid, date_updated, creator, type, date_created, url) \n";
					$str .= "VALUES (";
					$str .= "'".addslashes($this->input->post('book_author'))."', ";
					$str .= "'".$this->input->post('book_year')."', ";
					$str .= "'".addslashes($this->input->post('book_title'))."', ";
					$str .= "'".addslashes($this->input->post('book_publisher'))."', ";
					$str .= "'".addslashes($this->input->post('book_place'))."', ";
					$str .= "'".$this->input->post('book_isbn')."', ";
					$str .= "'".$this->input->post('book_libraryid')."', ";
					$str .= "'".date("Y-m-d H:i:s")."', ";										
					$str .= "'".$this->session->userdata('user')."', ";
					$str .= $this->input->post('type').", "; 
					$str .= "'".date("Y-m-d H:i:s")."', ";
					$str .= "'".$this->input->post('url')."');"; 
					
			logfile("\n url ....\t\t" . $this->input->post('url'), 'model', __FUNCTION__, __FILE__);
			logfile("\n urlslashes\t" . addslashes($this->input->post('url')), 'model', __FUNCTION__, __FILE__);
				
					//$str .= "'".$this->input->post('book_mattype')."', "; 
					//$str .= "'".$this->input->post('book_bcode3')."');"; 
					
					$data['Message'] = "You have added a new book to this module;";
					logfile($str, 'blue', __FUNCTION__, __FILE__);
					if($this->db->query($str)){
						$insert_id = $this->db->insert_id();
						$data['Message'] = "You Successfully added the book using: \n" . $this->db->last_query();
					}else{
						$data['Message'] = "There was a problem with the insert into the books table - the add was not successful";
					}
					$relevant_book_id = $insert_id;
				}else{
					logfile("relevant_book_id: $relevant_book_id changed to: ", 'blue', __FUNCTION__, __FILE__);
					$relevant_book_id = $this->isLibraryID($libid, $bid);
					// logfile("$relevant_book_id -----");
				}
				$str = "INSERT INTO books_modules (bid, mid, inactive, date_updated, essential, notes, creator, date_created) \n";
				$str .= "VALUES (";
				$str .= "".$relevant_book_id.", ";
				$str .= "".$this->input->post('module_id').", ";
				$str .= "0, "; 	
				$str .= "'".date("Y-m-d H:i:s")."', ";
				$str .= "'".$this->input->post('essential')."', ";
				$str .= "'".$this->input->post('book_notes')."', ";
				$str .= "'".$this->session->userdata('user')."', ";
				$str .= "'".date("Y-m-d H:i:s")."');";
				$data['Message'] = "You have added a new book to this module;";	
				logfile("NEW BOOK: You have updated books_modules: $str", 'blue', __FUNCTION__, __FILE__);
				if($this->db->query($str)){
					$data['Message'] = "You Successfully added the book";					
				}else{
					$data['Message'] .= "<br />There was a problem with the insert into the books_modules table - the add was not successful";
				}
			}
		}	
		########### END ADD NEW BOOK ##########
		
		
		header('Location: ' . base_url() . 'index.php/lists/');
	}
	
	
	private function cleanyear($year){
		return preg_replace('/^.*(\d{4}).*$/', '$1', $year);
	}
	private function cleanISBN($isbn){
		return preg_replace('/[\- ]*/', '', preg_replace('/^[^\d]*([\dXx\- ]*)[^\d]*$/', '$1', $isbn));
	}
	public function XMLClean($strin) {
		//tx to phil at lavin dot me dot uk
		$strout = null;
		for ($i = 0; $i < strlen($strin); $i++) {
			$ord = ord($strin[$i]);
			if (($ord > 0 && $ord < 32) || ($ord >= 127)) {
				$strout .= "&amp;#{$ord};";
			}
			else{
				switch ($strin[$i]) {
					case '<':
						$strout .= '&lt;';		break;
					case '>':
						$strout .= '&gt;';		break;
					case '&':
						$strout .= '&amp;';		break;
					case '"':
						$strout .= '&quot;';	break;
					default:
						$strout .= $strin[$i];
				}
			}
		}
		return trim($strout);
	}
	function books_modules_history(){
		// keeps tabs on the books in a module
		// to be used wherever a book in a module is 
		// 	i	changed
		// 	ii	deleted
		// 	iii	added
		// etc...
		
	}
	function modules_history(){
		// keeps tabs on the modules
		// to be used wherever a module is 
		// 	i	created
		// 	ii	deleted
		// etc...
		
	}
}
?>
