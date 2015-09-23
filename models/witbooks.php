<?php
class witbooks extends CI_Model {
    function witbooks()
    {
        // Call the Model constructor
        parent::__construct();
	$this->load->database();
    }
  


    function show()
    {
		/***
		 * This function provides a way of getting around the AJAX restrictions vis-a-vis 
		 * JavaScript.  
		 * 
		 * Any page may be invoked thus:
		 * <?php echo($this->config->item('domain')); ?>lists/library/
		 * 
		 * .. where http://witcat.wit.ie/ is the domain being accessed.
		 * it is then easy for AJAX to make local requests
		 * 
		 ***/


// should implement a simple search function like this.
// http://www.phpfreaks.com/tutorial/simple-sql-search


		$array = array();
		$this->load->helper('url');
		$searchterm = str_replace("/".$this->config->item('path')."lists/library/", "", uri_string());
		
		 
		$query = $this->db->query('SELECT idwitbooks, title, author from departments where sid like \'%'.$searchterm.'%\';');
		
		foreach ($query->result_array() as $row){
			$array[$row['title']] =  array(
				'Author' => $row['author'], 
				'recordID' => $row['idwitbooks'], 
				'Title' => $row['title'],
				'ISBN' => '',
				'Publication_Info' => ''
			);
		}
		#print "<pre>";
		#print_r($array);
		#print "</pre>";
		ksort($array); 
		return $array;  // returns multidimensional array containing attributes.
				// should be same result for all z39 functions as well.
	}
	function recordlink($uri){
		$array = array();
		$array['recordID'] = $this->clean(str_replace($repl,'',$ht->find('a#recordnum',0)->href));
		$count = 0;
		$limit = 4;
		foreach($ht->find('span#detailstab table.bibDetail table tr') as $r){
			$count += 1;
			$key =  $r->find('td', 0)->plaintext;
			$value = $this->clean($r->find('td', 1)->plaintext);
			$array[str_replace(' ', '_', $key)] = $value;
			if($count > $limit){break;}
		}
		// here we make sure that we have nice data for the view
		// if one of these crucial variables is not defined for any reason, we give 
		// it a default value of nothing.
		if(!isset($array['Author'])){$array['Author'] = '';}
		if(!isset($array['Title'])){$array['Title'] = '';}
		if(!isset($array['recordID'])){$array['recordID'] = '';}
		if(!isset($array['ISBN'])){$array['ISBN'] = '';}
		if(!isset($array['Publication_Info.'])){$array['Publication_Info.'] = '';}
		//print"<br/></div>\n";
		return $array;
			
	}
	function clean($string){		
		$pattern = '/(^[\n\t\r".;]*)(.*)([\n\t\r".;]*$)/i';
		$replacement = '$2';
		return preg_replace($pattern, $replacement, $string);
	}
		



}
?>
