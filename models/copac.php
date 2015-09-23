<?php
class copac extends CI_Model {
    function copac()
    {
        // Call the Model constructor
        parent::__construct();
		$this->load->database();
    }
	function showCOPAC(){
		$array = array();	
		
		return $array;
	}



function show() {
	$array = array();
	$resultset = array();
	$this->load->helper('url');
	$raw_searchterm = urldecode(str_replace($this->config->item('')."lists/library/", "", uri_string())); 
	//$raw_searchterm = str_replace(",", " ", $raw_searchterm); 
	//$searchterm = 'FOOD';
	if($raw_searchterm != ''){
	
		$stopwords  = array('/ the /i', '/ and /i', '/ an /i', '/ the /i', '/ in /i', '/ it /i', '/ is /i', '/ \D{1} /i', '/ no /i', '/ on /i', '/ to /i', '/ for /i', '/ of /i', '/[ ,;:\.]+/');
		$cleaned_searchterm = trim(preg_replace($stopwords, " ", " ".$raw_searchterm." "));
		$querystring = "SELECT idwitbooks, title, author, isbn, publocation, publisher, pubdate from witbooks ";
		$querystring .= "where MATCH(title) AGAINST('".addslashes($cleaned_searchterm)."');";
		$query = $this->db->query($querystring . ';');
		$searchterms = array_unique(explode(' ', $cleaned_searchterm));
		//print_r($searchterms);
		//$array['searchterms'] = $searchterms;
		#$query = $this->db->query('SELECT idwitbooks, title, author, isbn, publocation, publisher, pubdate from witbooks where title like	 \'%'.$searchterm.'%\';');
			
		$bgcolor = array(
			'background:#fff000;',
			'background:#80c0ff;',
			'background:#aad1f7;',
			'background:#f9ca69;',
			'background:#f7a700;',
			'background:#efba4a;',
			'background:#d2f854;',
			'background:#9ec70c;',
			'background:#ecc9f7;',
			'background:#54b70b;',
			'background:#b688cf;',
			'background:#ff4300;'
		);
		$searcharray = array();
		$count = 0;
		foreach($searchterms as $searchterm){
		    array_push($searcharray, array($bgcolor[$count], $searchterm));
		    $count++;
		}
		
		foreach ($query->result_array() as $row){
			$matchcount = 0;
			$highlighted_title = '';
			$highlighted_title_array = explode(' ', $row['title']);
			foreach($searcharray as $searchterm){
			  $highlighted_searchterm = "<span style='" . $searchterm[0] ."'>" . $searchterm[1] . "</span>";
			  $regex = "/".$searchterm[1]."/i";
			  if(preg_match($regex, $row['title'])){	
			    ## make the highlighted_title an array to avoid the overlap.
			    ## then you'll get over the problem of 'apple' and 'app' overlapping.
			    for($i = 0; $i < count($highlighted_title_array); $i++){
				  if(!preg_match('/<\/span>/', $highlighted_title_array[$i])){
					$highlighted_title_array[$i] = str_ireplace($searchterm, $highlighted_searchterm, $highlighted_title_array[$i]);
				  }
			    }
			    $matchcount++;
			  }
			}
			$highlighted_title = implode(' ', $highlighted_title_array);
		  
		  
			$resultset[30-$matchcount . "##" . $highlighted_title] =  array(
				'recordID' => $row['idwitbooks'],
				'highlighted_title' => $highlighted_title,
				'Title' => $row['title'],
				'Author' => $row['author'],
				'ISBN' => $row['isbn'],
				'Publisher_Location' => $row['publocation'],
				'Publisher' => $row['publisher'],
				'Publication_Date' => $row['pubdate']
			);
		}
		#print "<pre>";
		#print_r($array);
		#print "</pre>";
		
		
		ksort($resultset); 
		$array['opac_resultset'] = $resultset;  // returns multidimensional array containing attributes.
			// should be same result for all z39 functions as well.
	}
  // print("<pre>");print_r($array);print("</pre>");
		return $array;
}


?>