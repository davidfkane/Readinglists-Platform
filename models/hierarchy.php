<?php
class hierarchy extends CI_Model {
    function hierarchy()
    {
        // Call the Model constructor
        parent::__construct();
		$this->load->database();
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
	function flaretest(){
		$json =  '
{
 "name": "flare",
 "children": [
      {"name": "BetweennessCentrality", "size": 3534},
      {"name": "LinkDistance", "size": 5731},
      {"name": "MaxFlowMinCut", "size": 7840},
      {"name": "ShortestPaths", "size": 5914},
      {"name": "SpanningTree", "size": 3416}
     ]
}
';
	return $json;
		
	}
	function deb($st, $css="background-color: #c0c0c0; font-family: sans-serif;"){
		print("<pre style=\"$css\">$st</pre><br/>\n");
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	function getCorrectParent($siblings, $string){
		// return true if already present;
		$r = NULL; // return value;
		$c = 0; //count
		foreach($siblings as $si){
			#print("<span style=\"background-color: yellow\">$string == $si->name</span> Returns: <strong>".($c)."</strong><br/>\n");
			if($string == $si->name){
				$r = $c;
				break;
			}
			$c++;
		}
		#print("<span style=\"background-color: #ff6666;\"> Returns: </span><strong>".($r)."</strong><br/>\n");
		return $r;
	}
	
	
	
	
	
	
	function staff_modules($email){

		$sqlquery = 'select mc.mid as mid,  m.modulename as modulename, m.ancestry, m.type from modules m, users_modules mc where mc.mid = m.mid order by type, ancestry asc';
		#$sqlquery = 'select mc.mid as mid,  m.modulename as modulename, m.ancestry, m.type from modules m, users_modules mc where mc.email = \''.$email.'\' and mc.mid = m.mid order by type, ancestry asc';
        $query = $this->db->query($sqlquery);
		$data = $query->result_array();  
		
		(object)$tree = "";
		$tree->{'name'} = 'all lists';
		$tree->{'size'} = 0;
		$tree->{'children'} = array();
		$listnum = 0;
		foreach($query->result_array() as $row){
			$lastAncestorName = 'all lists';
			$lastchain = '';
			$chain = "\$tree";
			$ancestry = unserialize($row['ancestry']);
			$yyy = array('id'=>$row['mid'], 'name'=>$row['modulename']);
			@array_push($ancestry, $yyy);
			$parentname = '';
			$name = '';
			for($level=0; $level<count($ancestry); $level++){				
				$evstr2 = $chain . "->name;";
				eval("\$name = " . $evstr2 . ";");
				(object)$to = "";
				$to->{'name'} = $ancestry[$level]['name'];
				$to->{'size'} = $ancestry[$level]['id'];
				$to->{'children'} = array();
				$evstr = "\$ev = isset(".$chain."->name);";
				eval($evstr);
	
				if($ev){ // if there is an object at this level on the tree
					if($level == 0){
						$parentalsiblings = $tree->children;
					}else{
						$evstrsibs = "\$parentalsiblings = ".$lastchain."->children;";
						eval($evstrsibs);
						#$this->deb("CORRECT PARENTS: $evstrsibs");
					}
					$parentseq = $this->getCorrectParent($parentalsiblings, $lastAncestorName);
					if($level != 0){
						$chain = substr($chain, 0, -3)."[$parentseq]";
					}
					?>
                    <?php
					$sibx = "\$siblings = ".$chain."->children;";
					eval($sibx);
					if(is_null($this->getCorrectParent($siblings, $ancestry[$level]['name']))){
						$str="array_push(".$chain."->children, \$to);";
						eval($str);
					}	
					$parentname = $name;
					
					$lastchain = $chain;
					$chain .= "->children[0]";
					$lastAncestorName = $ancestry[$level]['name'];
				}
				#print"</blockquote>";
			}
		}
		return json_encode($tree);	
    }
		
	function moodle_modules($email){
		#$dsn = 'mysql://root:marigold@localhost/staff_moodle';

		$this->load->database('moodle', TRUE);
		ini_set('memory_limit', '-1');	
		$sqlquery = "
		SELECT mc.id as MID, mc.fullname as MNAME, mcc.path   
		FROM mdl_course_categories mcc, mdl_course mc 
		WHERE mcc.id = mc.category 
		AND mcc.path NOT LIKE '%1036%' 
		AND depth > 3 ORDER BY path asc;"; 
		$query = $this->db->query($sqlquery);
		$multiAncestry = array();
		$levelcount = 2;
		foreach($query->result_array() as $row){
		$parentname = '';
		  $arr = explode('/', trim($row['path'], '/'));
		  $arr1 = array();
		  
		  $levelcount = 2;
		  foreach($arr as $a){
			  if($levelcount == 3){$parentname = $a;}
			  $sqlquery2 = "select mcc.name FROM mdl_course_categories mcc where mcc.id = $a";
			  $name = $a;
			  $query2 = $this->db->query($sqlquery2);
			  if($r = $query2->row()){
				if($r->name != ''){
			  	$name = $r->name;
				}
			  }			  
			  array_push($arr1, array('id'=>$a, 'name'=>$name, 'type'=>'nodal', 'level'=>$levelcount, 'students'=>0, 'parentname'=>$parentname));
			  $levelcount++;
		  }  
		  
		//$this->load->database('default', TRUE);
		// get the number of students
		/*
		$stuentsSQL = "SELECT 1 as students;";
		*/
		$stuentsSQL = "SELECT count(*) as students 
		FROM `mdl_user` 
		INNER JOIN `mdl_role_assignments` ON `mdl_user`.id = mdl_role_assignments.userid 
		INNER JOIN mdl_context ON `mdl_role_assignments`.`contextid` = mdl_context.`id` 
		INNER JOIN mdl_course ON mdl_context.`instanceid` = mdl_course.`id` 
		INNER JOIN mdl_course_categories ON mdl_course.`category` = mdl_course_categories.`id` 
		WHERE mdl_role_assignments.`roleid` != 3  
		AND mdl_course.`id` = ".$row['MID'].";";
		$enrolled_students = 0;
		$studentsq = $this->db->query($stuentsSQL);
		  if($r = $studentsq->row()){
			if($r->students != ''){
			  $enrolled_students = $r->students;
			}
		  }			
		if($enrolled_students != 0){
			array_push($arr1, array('id'=>$row['MID'], 'name'=>$row['MNAME'], 'type'=>'terminal', 'level'=>'0', 'students'=>$enrolled_students, 'parentname'=>$parentname));
		}
		array_push($multiAncestry, $arr1);
		  
		}
		//$this->deb(print_r($multiAncestry, TRUE));
		
		(object)$tree = "";
		$tree->{'name'} = 'all lists';
		$tree->{'level'} = 1;
		$tree->{'students'} = 0;
		$tree->{'size'} = 0;
		$tree->{'type'} = 'nodal';
		$tree->{'parentname'} = '';
		$tree->{'children'} = array();
		$listnum = 0;
		foreach($multiAncestry as $ancestry){
			$lastAncestorName = 'all lists';
			$lastchain = '';
			$chain = "\$tree";
			//$ancestry = $row;
			#$yyy = $row;
			#@array_push($ancestry, $yyy);
			#$xxx  = print_r($ancestry, TRUE);
			#$this->deb($xxx);
			
			//$this->deb($xxx);
			$parentname = '';
			$name = '';
			for($level=0; $level<count($ancestry); $level++){
				
				$evstr2 = $chain . "->name;";
				eval("\$name = " . $evstr2 . ";");
				(object)$to = "";
				//$this->deb(print_r($ancestry, TRUE));
				$to->{'name'} = $ancestry[$level]['name'];
				$to->{'level'} = $ancestry[$level]['level'];
				$to->{'size'} = $ancestry[$level]['id'];
				$to->{'students'} = $ancestry[$level]['students'];
				$to->{'type'} = $ancestry[$level]['type'];
				$to->{'parentname'} = $ancestry[$level]['parentname'];
				$to->{'children'} = array();
				$evstr = "\$ev = isset(".$chain."->name);";
				eval($evstr);
	
				if($ev){ // if there is an object at this level on the tree
					if($level == 0){
						$parentalsiblings = $tree->children;
					}else{
						$evstrsibs = "\$parentalsiblings = ".$lastchain."->children;";
						eval($evstrsibs);
						#$this->deb("CORRECT PARENTS: $evstrsibs");
					}
					$parentseq = $this->getCorrectParent($parentalsiblings, $lastAncestorName);
					if($level != 0){
						$chain = substr($chain, 0, -3)."[$parentseq]";
					}
					?>
                    <?php
					$sibx = "\$siblings = ".$chain."->children;";
					eval($sibx);
					if(is_null($this->getCorrectParent($siblings, $ancestry[$level]['name']))){
						$str="array_push(".$chain."->children, \$to);";
						eval($str);
					}	
					$parentname = $name;
					
					$lastchain = $chain;
					$chain .= "->children[0]";
					$lastAncestorName = $ancestry[$level]['name'];
				}
				#print"</blockquote>";
			}
		}
		//$this->deb(print_r($tree));
		/*
		foreach ($tree->children as &$tree1) {
			if(count($tree1->children) == 0) {unset($tree1->children);}
			else{
				foreach($tree1->children as &$child){
				if(count($child->children) == 0) {unset($child->children);}
				else{
					foreach($child->children as &$child1){
						if(count($child1->children) == 0) {unset($child1->children);}
							else{
								foreach($child1->children as &$child2){
									if(count($child2->children) == 0) {unset($child2->children);}
										else{
											foreach($child2->children as &$child3){
												if(count($child3->children) == 0) {unset($child2->children);}
													else{
														foreach($child3->children as &$child4){
															if(count($child4->children) == 0) {unset($child4->children);}
															
															
							else{
								foreach($child4->children as &$child5){
									if(count($child5->children) == 0) {unset($child5->children);}
									
									
							else{
								foreach($child5->children as &$child6){
									if(count($child6->children) == 0) {unset($child6->children);}
									
									
							else{
								foreach($child6->children as &$child7){
									if(count($child7->children) == 0) {unset($child7->children);}
									else{
									
									}
								}
							}
									
								}
							}
									
									
								}
							}
															
														}
													}
											}
										}
								}
							}
						}
					}
				}
			}
		}
		*/
		return str_replace('  ',' ', json_encode($tree));	
		
    }
	
		
	
	
}
?>
