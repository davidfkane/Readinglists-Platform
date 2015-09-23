<?php

class Lists extends CI_Controller {

	function Lists()
	{
		parent::__construct();	
		$this->load->library('email');
	}
	private function return_session($sess){
		if($this->session->userdata($sess)){
			return $this->session->userdata($sess);
		}else{
			return 0;
		}
	}
	
	function doc(){
		$type = $this->uri->segment(3);
		$data = array();
		$this->load->view("doc/$type", $data);
	}
	
	function edit_list(){
		/*
		This should supercede all instances of fetch(), below.  fetch() will be deprecated.
		*/
		if($this->return_session('authorised') == 'TRUE'){
			$this->load->model('Books');
			$type = $this->uri->segment(3);
			// go straight to something;
			// set the necessary session variables;
			
			if($type == 'module'){	
				$unserialized = unserialize(base64_decode($this->uri->segment(4, 0)));
				$mid = $unserialized['INTERNAL_ID'];
				if(!$this->session->userdata('user')){ # if not already logged in.
					$this->session->set_userdata('user', $unserialized['USERNAME']);
					$this->session->set_userdata('email', strtolower($unserialized['USER_EMAIL']));
				}
			}
			if($type == 'rlists_module'  || $type == 'web'){
				$mid = $this->uri->segment(4);	
				if(!$this->session->userdata('user')){ # if not already logged in.
					$this->session->set_userdata('user', 'WEB');
					$this->session->set_userdata('email', 'WEB@wit.ie');
				}
			}	
			// get parents of module and set userdata accordingly
			#$hierarchy = $this->Books->gotoModule($this->Books->getMID($mid));	
			#$this->session->set_userdata('module', $hierarchy['mid']);
			#$this->session->set_userdata('department', $hierarchy['did']);
			#$this->session->set_userdata('school', $hierarchy['sid']);
				
			$this->index();
		}else{
			$this->logout();
		}
	}	
	
	function fetch(){
		if($this->return_session('authorised') == 'TRUE'){
			$this->load->model('Books');
			$type = $this->uri->segment(3);
			// go straight to something;
			// set the necessary session variables;
			
			
			if($type == 'module'){	
				$unserialized = unserialize(base64_decode($this->uri->segment(4, 0)));
				$mid = $unserialized['MOODLE_INTERNAL_ID'];
				if(!$this->session->userdata('user')){ # if not already logged in.
					$this->session->set_userdata('user', $unserialized['MOODLE_USERNAME']);
					$this->session->set_userdata('email', strtolower($unserialized['MOODLE_USER_EMAIL']));
				}
			}
			if($type == 'rlists_module'  || $type == 'web'){
				$mid = $this->uri->segment(4);	
				if(!$this->session->userdata('user')){ # if not already logged in.
					$this->session->set_userdata('user', 'WEB');
					$this->session->set_userdata('email', 'WEB@wit.ie');
				}
			}	
			// get parents of module and set userdata accordingly
			$hierarchy = $this->Books->gotoModule($this->Books->getMID($mid));	
			$this->session->set_userdata('module', $hierarchy['mid']);
			$this->session->set_userdata('department', $hierarchy['did']);
			$this->session->set_userdata('school', $hierarchy['sid']);
			$this->index();
		}else{
			$this->logout();
		}
	}	
	
	
	function proxy(){
		if($this->return_session('authorised') == 'TRUE'){
			// proxys html for display into iframe;
			if(!$this->uri->segment(4, 0)){// then this is coming from the bookmarklet
				$url = $this->uri->segment(3, 0);
			}else{ // this is coming from within the application
				$postvars = base64_decode($this->uri->segment(3, 0));
				$postvrsarray = explode('&', $postvars);
				$pair1 = array(); 
				$pair2 = array(); 
				foreach($postvrsarray as $pair){
					$pair1 = explode('=', $pair);
					$pair2[$pair1[0]]=$pair1[1];
				}
				//print_r($postvrsarray);	
				$this->load->model('Books');
				$bid = str_replace('bookID_', '', $pair2['bid']);
				$bookdetails = $this->Books->bookdetails($bid);
				//print("<br/>\nBookdetails->url = " . $bookdetails->url ."<br/>\n");
				$url = base64_encode(urlencode($bookdetails->url));
			}
			$this->load->model('ProxyPage');
			$this->ProxyPage->proxy($url);
		}
	}	
	function bookmarklet(){
		// print($this->return_session('authorised'));
		if($this->return_session('authorised') == 'TRUE'){
			$data['loggedin'] = 1;		
		}else{
			$data['loggedin'] = 0;		
		}
		$this->load->model('Books');
		$this->load->model('ProxyPage');
		$data['modules'] = $this->Books->staff_modules($this->session->userdata('email'));
		$data['user'] = $this->session->userdata('user');
		isset($_SERVER['HTTP_REFERER'])?$data['referer'] = $_SERVER['HTTP_REFERER']:$data['referer'] = NULL;
		$data['scrapingprameters'] = $this->ProxyPage->scrapingprameters(urlencode($data['referer']));
		$this->load->view('bookmarklet', $data);
	}
	function bookmarkletcss(){
		$this->load->view('bookmarkletcss');
	}
	function bookmarkletlogin(){	
		$this->load->model('Books');
		$data['modules'] = $this->Books->staff_modules($this->session->userdata('email'));
		if($postdata = $this->uri->segment(3, 0)){
			$data['postdata'] = $postdata;
			$data['message'] = '';
		}else{
			//header('location: http://www.wit.ie/');	
			$data['postdata'] = 'http://www.wit.ie/';
			$data['message'] = '';			
		}
		$this->load->view('bookmarklet_login_form', $data);
	}
	function login(){	
		//$data = array('redirectonfailure' = $redirectonfailure);
		
		if($forward = $this->uri->segment(3, 0)){
			$data['forward'] = $forward;
		}else{
			$data['forward'] = '';
		}
		$data['logintype'] = 'default';
		$this->load->view('general_login_form', $data);
	}
	function bookmarklet_add(){
		if($this->return_session('authorised') == 'TRUE'){
			$this->load->model('Books');
			$this->load->model('users');
			$data['sel_email'] = $this->return_session('email'); 
			// this is similar to the default page, except we go straight to the 'add new item' page, with an iFrame.
			// set some session variables
			// print("<pre>");print_r($_POST);print("</pre>");
			if($_POST['staffmodules'] == 0){
				$staffmodule = $this->users->getModuleId($data['sel_email']);
				print "Staffmodule = " .$staffmodule;
				$hierarchy = $this->Books->gotoModule($staffmodule);
				$this->session->set_userdata('module', $hierarchy['mid']);
				$this->session->set_userdata('department', $hierarchy['did']);
				$this->session->set_userdata('school', $hierarchy['sid']);
				
				$data['metadata']['url'] = $_POST['biblio']['url']; 
				$data['metadata']['title'] = $_POST['biblio']['title']; 
				$data['metadata']['author'] = $_POST['biblio']['author']; 
				$data['metadata']['year'] = ''; 
				$data['metadata']['publisher'] = ''; 
				$data['metadata']['place'] = ''; 
				$data['metadata']['isbn'] = ''; 
				$data['metadata']['libraryid'] = ''; 
				$data['metadata']['type'] = '3'; 
				$data['metadata']['staffmodule'] = ''; 
				// these values should be added to with default null values;
				
				$this->Books->add_book_from_bookmarklet($hierarchy['mid'], $data);  // 18 is the dept code - hardcoded
			}else{
				// add the bookmarklet to a particular list and then leave
				$this->index('bookmarklet');
			}
		}else{
			$this->bookmarkletlogin();
		}
	}
	
	
	
	function index($functionality = 'default')
	{	
	
		$this->load->model('Books');
		$this->load->model('users');
		isset($_SERVER['HTTP_REFERER'])?$data['referer'] = $_SERVER['HTTP_REFERER']:$data['referer'] = NULL;
		
		$data['sel_email'] = $this->return_session('email'); 
		$data['sel_mod'] = $this->return_session('module');
		$data['firstname'] = $this->return_session('firstname');
		$data['lastname'] = $this->return_session('lastname');
		$data['functionality'] = $functionality;			
		//print "sldksssssssssssssssssssssssssssssssssssssssssssss";
		$data['selected_module_dropdown'] = $this->Books->staff_modules($data['sel_email']);	
		//print("<pre>" . $data['sel_email'] . "</pre>");
		if($functionality == 'bookmarklet' && isset($_POST['staffmodules'])){
			// set the module			
			$hierarchy = $this->Books->gotoModule($_POST['staffmodules']);
			$this->session->set_userdata('module', $_POST['staffmodules']);			
			$data['sel_mod'] = $_POST['staffmodules'];			
		}
		$this->load->view('default_form_multidropdown', $data);
	}
	
	function logout()
	{	
		$this->session->unset_userdata('admin');
		$this->session->set_userdata('authorised', 'FALSE');
		$this->session->unset_userdata('user');
		$this->session->unset_userdata('email');
		$this->session->unset_userdata('module');
		$this->login();
	}	
	function authorised(){		
		if($this->return_session('authorised') != 'TRUE'){
			print(0);
			// return 0;
		}else{
			print(1);
			// return 1;
		}
	}
	function authorise(){
		if(!isset($_POST['username'])){
			// no post variables - not a valid request
			// could consider banning all requests with referers outside the domain, using that http header
			$this->index(); 
		}else{
			// ok, what kind of username do we have?
			// need to ask the question here whether we use email addresses and whether they are student, or external.
			$this->username = $_POST['username'];
			$this->password = $_POST['password'];
			if($this->username =='' || $this->password ==''){  // user enters nothing
				// blank username and/or password
				$data['Message'] = 'Please enter username and/or password';
				$this->session->set_userdata('admin', 'FALSE');
				$this->session->set_userdata('authorised', 'FALSE'); 
				if(isset($_POST['bookmarkletlogin'])){
					$this->bookmarkletlogin();
				}else{
					$this->login();
				}
			}else{
				// proper request with username/password
				$this->load->model('Users');
				$this->load->model('Module');
				
				//if($this->password == 'Password1!' || ($this->password == 'modcat' && $this->username == 'bosullivan') || $this->Users->check_user($this->username,$this->password,$this->return_session('email'))){	// valid user!			#uncomment for live	
				$usercredentials = $this->Users->check_user($this->username, $this->password); 
				if($usercredentials['loggedin'] == 'LOGGED_IN'){	// valid user!			#uncomment for live	
					// If the user doesn't exist on the system make sure that they are created.
					$ldapdomain = 'wit.ie'; 
					$fname = $lname = ''; 
					$auth = 'ldap'; 
					logfile("\ninvoking function", 'controller', __FUNCTION__, __FILE__); 
					$this->Module->new_user($usercredentials['email'], $usercredentials['givenname'], $usercredentials['familyname'], $this->username, $auth);  // create user if doesn't exist
					
					// get actual user details
					$userdetails = $this->Users->userdetails($this->username);
					
					// so, we have authenticated - now accept invitation;
					if(isset($_POST['invitationcode'])){
						// confirm invitation;		
						$this->load->model('Users');
						$this->Users->acceptInviteToList($_POST['invitationcode'], $usercredentials['email'], $this->username);	
					}
					
					$data['result'] = $this->Users->userdetails($this->username);	
					//print_r($data['result']);
					if($data['result']['authorised'] == 0){
						$this->login();
					}else{
						$this->session->set_userdata('email', $this->Users->email($this->username)); 	#uncomment for live
					//	$this->session->set_userdata('email', $data['result']['email']); 		# uncomment for testing	
						$this->session->set_userdata('user', $data['result']['username']);
						$this->session->set_userdata('firstname', $data['result']['firstname']);
						$this->session->set_userdata('lastname', $data['result']['lastname']);
						$this->session->set_userdata('authorised', 'TRUE');
						
						if($this->username == 'pmchale' || $this->username == 'admin' || $this->username == 'dkadne'){
							$this->session->set_userdata('admin', 'TRUE');  # we get special admin priveleges
						}
						$data['sel_email'] = $this->return_session('email');
						if(isset($_POST['bookmarkletlogin'])){
							header("Location: " . $_POST['postdata']);
						}else{
							if($_POST['forward'] == ''){
								$this->index();
							}else{
								header("Location: " . base64_decode($_POST['forward']));
							}
						}
					}
				}else{
					if(isset($_POST['bookmarkletlogin'])){
						$this->bookmarkletlogin();
					}else{
						$this->login();
					}
				}
			}
		}
		//phpinfo();
		#print("<div style=\"border: dashed 1px black; background-color: #b0ffb0\"><pre>");		print_r($this->session->userdata);  		print("</pre></div>");
	}
	function list_hierarchy(){ 
		$subscriber_id = 1;
		if(!$parent_id = $this->uri->segment(3, 0)){
			$parent_id = 'NULL';
		}		
    	$this->load->model('Departments');
		$dropdownarray =  $this->Departments->hierarchy($subscriber_id, $parent_id);
		if(sizeof($dropdownarray) > 1){ // if the array is full, then we are still moving down the hierarchy. 
    		$data['results'] = $dropdownarray;
			$data['next'] = 'true';
		}else{ // if the selected ID has no children, then it is time to skip to a module
			$data['results'] =  $this->Departments->hierarchy_modules($subscriber_id, $parent_id);
			print("Naaatin");
		}
			$dd = print_r($data['results'], TRUE);
			//print("<pre>".$dd."</pre>");
		$this->load->view('departments_select', $data);
    }
/*
*/
	function list_departments(){ // when we change the value of the school dropdown.
    	$this->load->model('Departments');
		$this->session->unset_userdata('department');
		$this->session->unset_userdata('module');
    	$data['results'] = $this->Departments->school_departments($_POST['sid'], $this->return_session('department'));
		$this->session->set_userdata('school', $_POST['sid']);
		$this->load->view('departments_select', $data);
    }
	function send_email($to, $message, $subject){
		if(defined($this->return_session('authorised')) && $this->return_session('authorised') == 'TRUE'){	
			$config = array();
			$config['useragent']           = "WIT Readinglists Mailer";
			$config['mailpath']            = "/usr/bin/sendmail"; // or "/usr/sbin/sendmail"
			$config['protocol']            = "smtp";			
			if($this->config->item('base_url') == 'https://library.wit.ie/readinglists/'){
				//$config['smtp_host'] = "witexchange.wit.ie";
				$config['smtp_host'] = "127.0.0.1";
			}else{
				$config['smtp_host'] = "127.0.0.1";
			}				
			$config['smtp_port'] = "25";
			$config['mailtype'] = 'html';
			$config['charset']  = 'utf-8';
			$config['newline']  = "\r\n";
			$config['wordwrap'] = TRUE;	
			$this->load->library('email');
			$this->email->initialize($config);
			$this->email->from('dkane@wit.ie', 'david kane');
			$this->email->to($to);
			$this->email->subject($subject);
			$this->email->message($message);
			if($this->email->send()){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	
	
	function list_modules(){  // when we change the value of the department dropdown
		if($this->return_session('authorised') == 'TRUE'){
			$this->load->model('Books');
			$this->session->unset_userdata('module');
			$data['results'] = $this->Books->department_modules($_POST['did'], $this->return_session('module'));
			$this->session->set_userdata('department', $_POST['did']);  // set the department value
			$this->load->view('modules_select', $data);
		}
	}
	function delete_module($module_id){
		if($this->return_session('authorised') == 'TRUE'){
			#logfile("invoking delete module", 'controller', __FUNCTION__, __FILE__);
			$data = array();
        	$this->load->model('Module');
			if(($this->session->userdata('authorised') == 'TRUE' ) || $this->session->userdata('admin') == 'TRUE'){
				logfile("we are authorised", 'controller', __FUNCTION__, __FILE__);
				// you can delete module
				//echo("you are admin or teacher");
				$data['accesslevel'] = 'teacher';
				$data['items'] = $this->Module->module_books_count($module_id);
				// just go ahead and delete the items;
				$this->Module->delete_module($module_id, $this->session->userdata('email'));
				
				header('Location: '.$this->config->item('base_url').'lists/');
			}
		}else{
				$this->logout();
		}
	}
	function is_linked(){  // shows new staff_modules dropdown		
		if($this->return_session('authorised') == 'TRUE'){					
			$this->load->model('Books');
			$linked = $this->Books->is_linked($_POST['staffemail'], $_POST['mid']);	
			logfile('linked = = x = >' . $linked);	
			echo $linked;  // 0 or 1 , true, false
		}else{
			print('LOG_OUT');
		}
	}
	
	function link_staff_to_module(){  // shows new staff_modules dropdown
									// happens at same time as choosing new staff member - on page load
									
		if($this->return_session('authorised') == 'TRUE'){		
			$this->load->model('Books');
			$response = $this->Books->linkmodules($_REQUEST['staffemail'], $_REQUEST['mid'], $_REQUEST['action']);
			echo $response;  // 0 or 1 , true, false
		}
	}
	function change_module_title(){
		if($this->return_session('authorised') == 'TRUE'){
			$this->load->model('Books');
			$this->Books->change_module_title($_POST['mid'], $_POST['newtitltle']);
			echo $_POST['newtitltle'];
		}else{
			print('LOG_OUT');
		}
	}
	function resequencelist(){
		if($this->return_session('authorised') == 'TRUE'){
			$this->load->model('Books');
			$this->Books->resequencelist($_POST['sequence'], $_POST['mid']);
		}else{
			print('LOG_OUT');
		}
	}
	function list_staff_modules(){  // shows new staff_modules dropdown
									// happens at same time as choosing new staff member - on page loadlist_staff_moduleslist_staff_modules
		if($this->return_session('authorised') == 'TRUE'){
			$this->load->model('Books');
			$this->session->set_userdata('email', $_POST['staffemail']);
			$data['selected_module_dropdown'] = $this->Books->staff_modules($_POST['staffemail']);
			$this->load->view('staff_modules_select', $data); // $mid, $modulename, $did, $sid
		}
	}

	function substitute_module_list(){
		if($this->return_session('authorised') == 'TRUE'){
			$this->load->model('Books');
			print($this->Books->substitute_module_list($_POST['mid'],$_POST['replacewithmid']));
		}else{
			print('LOG_OUT');
		}
	}
	#
	#function changeStaffMember(){
#		#$this->index();	
#	}
	
	
	
	function goto_staff_module(){  // when we change the value of the new staff_modules dropdown
		if($this->return_session('authorised') == 'TRUE'){
			$this->load->model('Books');
			$mid = $_POST['mid'];
			$this->session->unset_userdata('module');
			$results = $this->Books->gotoModule($mid);
			$rres = print_r($results, TRUE);
			$this->session->set_userdata('module', $results['mid']);  // set the department value
		}
	}
	
	function has_deleted_items(){  // when we change the value of the department dropdown
        $this->load->model('Books');
        print($this->Books->countInactiveModules($_POST['mid']));
	}
	function library(){
		if($this->return_session('authorised') == 'TRUE'){
			$data['searchterm'] = '';
			$source = $_POST['source'];
			$this->load->model('Opac');
			if(!isset($_POST['ti'])){  // then we are given a titleID rather than an actual title.
				$ti = 'bookID_'.$_POST['bid'];
				$term = $this->Opac->getTitle($ti) . ' ' . $this->Opac->getAuthor($ti);
			}else{
				$ti = $_POST['ti'];
				$term = $ti;
			}
			$data = $this->Opac->show($source, $term);
			$data['aCount'] = count($data['opac_resultset']);
			$data['source'] = $source;
			$data['searchterm'] = $term;
			if(count($data['aCount']) > 0){
				$data['Message']="0";
			}else{
				$data['Message']="No Results";
			}
			$this->load->view('opac_results', $data);
		}else{
			print('LOG_OUT');
		}
	}
	function movebook(){
		if($this->return_session('authorised') == 'TRUE'){
			// moves book to new module
			$this->load->model('Books');		
			$data = $this->Books->movebooks($_POST['destinationmodule'],$_POST['bookstomove'], 'copy');
			$this->index();
		}
	}


	function list_books()  /*  soon to be deprecated in favour of list_items(), below */
    {
			// this function lists books for a particular module, either deleted or active records are shown.
			// the action may be one of ('active', 'endnote', 'rss', 'deleted');
			//$module_id = $this->return_session('module');
			//print "LISTING LISTING LISTING";
			if(!isset($action)){
				$action = $this->uri->segment(3, 0);
			}else{
				$action = 'active';
			}
			
			if($action == 'moodle'){
				// coming from a moodle readinglist instance;
				
				$unserialized = unserialize(base64_decode($this->uri->segment(4, 0)));
				
				$module_id = $unserialized['MOODLE_INTERNAL_ID'];
				$username = $unserialized['MOODLE_USERNAME'];
				$data['module_section_id'] = $unserialized['MOODLE_SECTION_ID'];
				$data['sel_email'] = $unserialized['MOODLE_USER_EMAIL'];
				#$data['serialized'] = $this->uri->segment(4, 0);
				$data['serialized'] = $unserialized;
			}else{
				$module_id = $this->uri->segment(4,0);
				$username = $this->uri->segment(5,0);
			}
			//print($action);
			$data['oldMid'] = $module_id;
			$this->load->model('Books');
			$this->load->model('Users');
			//logfile("URL segment: " . $module_id);
			#$ses = print_r($this->session->userdata, TRUE);
			
			#print"<br/>Module ID: $module_id, $ses<br/>\n";
			if($action == 'moodle' || $action == 'mymodule' || $action == 'website'){
				//special case: start using associated Moodle ID to access data
				$data['rlists_id'] = $module_id;
				$module_id = $this->Books->getMID($module_id);
				if($module_id == 'NO_DATA'){
					print 'No Data for this module';
					exit;
				}
			}
			$this->session->set_userdata('module', $module_id);
			$data['libraryroot'] = "http://witcat.wit.ie/record=";		
			$data['results'] = $this->Books->module_books($module_id, $action);
			$data['module_id'] = $module_id;
			$data['sel_email'] = $this->return_session('email'); 
			$data['module_title'] = $this->Books->getModuletID($module_id);
			$data['module_staff'] = $this->Books->getModuleStaff($module_id);
			$data['module_type'] = $this->Books->getModuleType($module_id);
			$data['teacheson'] = $this->Users->teachesOn($this->session->userdata('user'), $module_id);
			
	
	
			if($action == 'moodle' || $action == 'website'){
				$data['username'] = $username;
				//$data['data'] = $data;
				// $data['module_title'] = $this->Books->getMoodleTitle($module_id);
				if($module_id == 'NO_DATA'){
					$this->load->view('readinglist_moodle_nodata', $data);
				}else{
					if($action == 'website'){
						$this->load->view('readinglist_website', $data);
					}else{
						$this->load->view('readinglist_moodle', $data);
					}
						
				}
			}elseif($action == 'pdf'){
				/*
				$data['username'] = $username;
				// $data['module_title'] = $this->Books->getMoodleTitle($module_id);
				if($module_id == 'NO_DATA'){
					$this->load->view('readinglist_moodle_nodata', $data);
				}else{
					$this->load->view('readinglist_pdf', $data);
				}
				*/
			}elseif($action == 'rss'){
				$this->load->view('readinglist_rss', $data);
			}elseif($action == 'endnote'){
				$this->load->view('readinglist_endnote', $data);
			}elseif($action == 'mymodule'){ //temporary for the pdf files
				// set session data.
				$data['sel_mod'] = $module_id;		
				$data['viewonly'] = 'VIEW_ONLY';
				if($data['sel_sch'] != 0){			//add departments to data
					$this->load->model('Departments');
					$data['list_dep'] = $this->Departments->school_departments($data['sel_sch'], $data['sel_dep']);
				}
				if($data['sel_dep'] != 0){			//add modules to data
					$this->load->model('Books');
					$data['list_mod'] = $this->Books->department_modules($data['sel_dep'], $data['sel_mod']);
				}	
				$this->load->view('default_form', $data);
			}else{  // where action is either 'active' or 'deleted';
			
			if(defined($this->return_session('authorised')) && $this->return_session('authorised') == 'TRUE'){
				// get the individual modules for a staff member
				$this->load->model('Module');
				$data['selected_module_dropdown'] = $this->Books->staff_modules($data['sel_email']);
				$data['itemcount'] = $this->Module->module_books_count($module_id);
				  $this->load->view('readinglist_html', $data);
			}else{
				print('LOG_OUT');
			}
		}
		
    }
	
	function list_items()
    {
		/*		
		this function lists items associated with a particular list
		sample url would be like: 
		https://[DOMAIN]/[BASEPATH]/lists/list_items/[ACCOUNT]/[INSTANCE]/[ID]/[FORMAT]/
		E.g.  https://researchscope.ie/readinglists/lists/1/2/500001/json
		*/
		if($this->return_session('authorised') == 'TRUE'){
			$listvars = array( 'ACCOUNT' => 'xxxxx', 'INSTANCE' => 'xxxxx', 'ID' => 'xxxxx', 'FORMAT' => 'xxxxx', 'USERNAME' => 'xxxxx', 'EMAIL' => 'xxxxx');
			$listvars['ACCOUNT'] = $this->uri->segment(3);
			$listvars['INSTANCE'] = $this->uri->segment(4);
			$listvars['ID'] = $this->uri->segment(5);
			$listvars['FORMAT'] = $this->uri->segment(6);
			
			
			//print("<pre>"); print_r($listvars); print("</pre>");
			$action = $listvars['FORMAT'];
			$this->load->model('Books');
			$this->load->model('Users');
			$module_id = $listvars['ID'];
			$username = $listvars['USERNAME'];
			$data['sel_email'] = $listvars['EMAIL'];
			$data['rlists_id'] = $module_id;
			$data['module_id'] = $module_id;
			$module_id = $this->Books->getMID($module_id, $listvars['INSTANCE'], $listvars['ACCOUNT']);
			if($module_id == 'NO_DATA'){
				if($listvars['FORMAT'] == 'html'){
					$data['module_title'] = 'Does not exist yet';
					$data['username'] = 'guest';
					$data['results'] = array();
					//$this->load->view('readinglist_modcat', $data);		
								
				}else{
					//print_r($listvars);
					print 'No Data for this module';
					exit;
				}
					
			}
			
			// need to build $data['serialized'] for edit link;
			$serialized = array(
				'INTERNAL_ID' => $module_id, 
				'USERNAME' => $listvars['USERNAME'], 
				'INSTANCE' => $listvars['INSTANCE'], 
				'ACCOUNT' => $listvars['ACCOUNT']
				//['USER_AUTH'] => 'manual', 
				//['SECTION_ID'] => 139, 
				//['LIST_NAME'] => 'Library Reading List'
			);
			$data['serialized'] = $serialized;
			$this->session->set_userdata('module', $module_id);
			$data['libraryroot'] = "http://witcat.wit.ie/record=";		
			$data['results'] = $this->Books->module_books($module_id, $action);
			$data['sel_email'] = $this->return_session('email'); 
			$data['module_title'] = $this->Books->getModuletID($module_id);
			$data['module_staff'] = $this->Books->getModuleStaff($module_id);
			
			$data['username'] = $username;
			
			if($listvars['FORMAT'] == 'json'){
				// give json	
				header("Content-type: application/json");
				//print_r($data['results']);
				echo(base64_encode(json_encode($data['results'])));
			}elseif($listvars['FORMAT'] == 'web'){
				// give json	
				header("Content-type: text/html");
				//print_r($data['results']);			
				$this->load->view('readinglist_website', $data);				
			}elseif($listvars['FORMAT'] == 'html'){
				// all else, for now.
				$data['titlefromsvg'] = urldecode($this->uri->segment(7));
				$this->load->view('readinglist_modcat', $data);				
			}else{
				// all else, for now.
				$data['titlefromsvg'] = $this->uri->segment(6);
				$this->load->view('readinglist_modcat', $data);				
			}
		}
    }
		
	
	function edit_book(){
		if($this->return_session('authorised') == 'TRUE'){
			$action = $_POST['action']; //fetch the action from the 3rd segment of the URI
			$this->load->model('Books');
			$module = $_POST['mid'];
			//logfile("module: " . $module);
			$data['book'] = $this->Books->populate_edit_book_form($action, $module); #print_r($_POST); 
			print "<!--"; print "-->";
			//logfile("\nURL: " . $data['book']['url'] . "\nType" . $data['book']['type']);
			$data['action'] = $action;
			if($action != 'delete' && $action != 'unelete'){
				$this->load->view('edit_book', $data);
			}
		}else{
			print('LOG_OUT');
		}
	}
	
	function newpersonallist(){
		if($this->return_session('authorised') == 'TRUE'){
			$username = explode('@', $this->return_session('email'));
			$username = $username[0];
			!isset($_REQUEST['modulename'])?$modname = 'Unnamed Module':$modname = $_REQUEST['modulename'];
			$modname = addslashes(urldecode($modname));
			if($modname != ''){ //not blank
				$newmod = array('ACCOUNT'=>1, 'TYPE'=>4, 'MOODLE_INTERNAL_ID'=>'NULL', 'MOODLE_MODULE_NAME'=>$modname, 'MOODLE_USER'=>array('email'=>$this->return_session('email'), 'firstname'=>'unknown', 'lastname'=>'', 'username'=>$username));
				$newmodser = base64_encode(serialize($newmod));
				$this->load->model('module');
				$this->module->create_new_list($newmodser);
			}
			#print_r($newmod);
		}else{
			print('LOG_OUT');
		}
	}
	
	function newlistrequest(){	
	//	if($this->return_session('authorised') == 'TRUE'){
		//print "sdlkfjsdklfsdklfjsklf";
			$this->load->model('module');
			$this->module->create_new_list($_REQUEST['datastruct']);
	//	}
	}
	
	function assignStaffToModule($mid, $username, $email, $fullname, $shortname){
		$this->load->model('module');		
		$this->module->assignStaffToModule($mid, $username, $email, $fullname, $shortname);
	}
	
	function update_book(){		
		if($this->return_session('authorised') == 'TRUE'){
			logfile("we are in lists/update_book", 'view', __FUNCTION__, __FILE__);
			if(!isset($_POST['action'])){
				$action = $this->uri->segment(3, 0);
			}else{
				$action = $_POST['action'];
			}		
			$this->load->model('Books');
			$this->Books->update_book($action);
		}
	}
	function change_status(){
		if($this->return_session('authorised') == 'TRUE'){
			logfile("change status");
			$this->load->model('Books');
			$data = $this->Books->changeEssential($_POST['bid'], $_POST['status'], $_POST['mid']);
		}else{
			print('LOG_OUT');
		}
	}
	function managelistadmins(){		
		if(defined($this->return_session('authorised')) && $this->return_session('authorised') == 'TRUE'){
		//if($this->return_session('authorised') == 'TRUE'){
			//phpinfo();
			$this->load->model('Users');
			$action = $_POST['action'];
			$module = $_POST['mid'];
			$email = strtolower(trim($_POST['email']));
			$inviteeMessage = $_POST['inviteeMessage'];
			$inviter = $_POST['inviteeInviter'];
			$inviteeListName = $_POST['inviteeListName'];
			$ldapdomain = 'wit.ie';
			$fname = '';
			$lname = '';		
			$emailparts = explode('@', $email);
			$username = $emailparts[0];
			if($emailparts[1] == $ldapdomain){
				$auth = 'ldap';
			}else{
				$auth = 'manual';
			}
			$this->load->model('Module');
			
			if($action == 'invite'){
				// first check if this is the right kind of list
				//if($_POST['type'] != 2){  // go ahead and invite user if not inbox (inbox type is 2).
					// inviting user so do thie invitations
					if($this->Module->multiunique(array('mid','email'), array($module, $email), 'users_modules')){ //no dupes
						//$this->Module->new_user($email, $fname, $lname, '', $auth);  // create user if doesn't exist
						$code = md5(strtolower($email) . $module);
						$inviteURL = "" . $this->config->item('base_url') . "\r\nlists/acceptinvitationlogin/\r\n" . $code;
						// new user already created... 
						$subject = $inviter . ' has invited you to share a Reading List.';
						$message = "Welcome to lists: click on this .";
						$message = '
							<html>
							<head>
							  <title>WIT Readinglists</title>
							</head>
							<body>
							  <h2>' .$inviter . ' has invited you to collaborate on: \''. ucwords($inviteeListName).'\'</h2>
							  <p>'.$inviteeMessage.'</p>
							  <p>Click on the link below to accept the invitation</p>
							  <p><a href=\''.$inviteURL.'\' style="color: white; background-color: #3276b1; border: solid 1px #285e8e; border-radius: 4px; text-transform: lowercase; text-decoration: none; padding: 4px;">Accept Invitation</a></p>
							</body>
							</html>
						';
						
						//error_log("sending email to $email", 0);
						$this->send_email($email, $message, $subject);
						$this->send_email('dkane@wit.ie', $message, $subject);
						$result = $this->Users->inviteToList($email, $module, $action); // make the invitation email 
						$this->Module->assignStaffToModule($module, $username, $email, '', '', 'invite'); // create the invitation in the module
						print($result['returnhtml']); // and show the pending invite on the list of admins
					//}else{
					//	print('');
					//}
				}
			}else{
				print('');
			}
		}else{
			print('LOG_OUT');
		}
	}
	
	function acceptinvitationlogin(){
		// act of email invitation acceptance
		
		//if($this->return_session('authorised') == 'TRUE'){
			$this->session->unset_userdata('admin'); 
			$this->session->set_userdata('authorised', 'FALSE'); 
			$this->session->unset_userdata('user'); 
			$this->session->unset_userdata('email'); 
			$this->session->unset_userdata('module'); 
			$data['forward'] = '';
			$data['logintype'] = 'invitation'; 
			$data['invitationcode'] = $this->uri->segment(3, 0); 
			//$data['actionurl'] = "https://" . $this->config->item('base_url') . "lists/acceptinvitation/";
			$this->load->view('general_login_form', $data); 
		//}
	}
	
	function declineinvitation(){
		if($this->return_session('authorised') == 'TRUE'){
			$invitationcode = $this->uri->segment(3, 0);
			// already logged in to do this;
			$this->load->model('Users');
			$this->Users->declineInviteToList($invitationcode);
			//$this->load->view('index');
		}else{
			print('LOG_OUT');
		}
	}
}

/* End of file Lists.php */
/* Location: ./system/application/controllers/Lists.php */
?>
