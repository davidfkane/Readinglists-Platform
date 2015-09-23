<?php
class ProxyPage extends CI_Model {

	/* new code */
	function scrapingprameters($url){
		// returns an array of jQuery selectors to be used client-side in the code recruited by the bookmarklet;
		$scrapingparameters = array(  // default settings unless overridden.
			'author' => array('$("head title").text()', 'javascript'),
			'publisher' => NULL, 
			'publicationplace' => NULL, 
			'isbn' => NULL, 
			'year' => NULL, 
			'title' => array('$("head title").text()', 'javascript'), 
			'frames' => NULL
		);
		$querystring = 'SELECT idscraping_parameters, regex, site from scraping_parameters;';		
		$query = $this->db->query($querystring);
		$idscraping_parameters = NULL;
		foreach($query->result_array() as $regex){
			if(preg_match($regex['regex'], $url) == 1){
				$idscraping_parameters = $regex['idscraping_parameters'];
				break;	
			}
		}
		if($idscraping_parameters != NULL){	// we found a match
			$querystring2 = 'SELECT '; 
			$querystring2 .= 'Author as author, Publisher as publisher, place as publicationplace, isbn, Year as year, Title as title, frames '; 
			$querystring2 .= 'from scraping_parameters '; 
			$querystring2 .= 'where idscraping_parameters = ' . $idscraping_parameters;	
			
			$query2 = $this->db->query($querystring2); 
			$scrapingparameters = $query2->row_array(); 
			foreach($scrapingparameters as $key => $value){
				if($scrapingparameters[$key] != NULL && $scrapingparameters[$key] != 'url'){
					$scrapingparameters[$key] = unserialize($value);
				}
			}			
		}
		$scrapingparameters['url'] = array($url, 'literal');		
		return $scrapingparameters;
	}
	
	private function allowCrossOrigin($url){
		// just check the headers to see if it is restricitve
		$ret = true;
		if($url != ''){
			foreach(get_headers($url) as $header){
				$head = explode(':', $header);
				if(strtolower(trim($head[0])) == 'x-frame-options'){
					$ret = false;
				}
			}
		}
		return $ret;
	}
    function proxy($page){
		// most pages can just be served through the iframe, if they are not HTTPS.
		$page = urldecode(base64_decode($page));
		//stream_context_set_default(array('http' => array('method' => 'HEAD')));
		if($this->allowCrossOrigin($page)){
			header("Location: " . $page);
		}else{
			# header('X-Frame-Options: SAMEORIGIN'); // stops iframe proxy
			$pagecontents = file_get_contents($page);
			# $base = '<base href="http://www.amazon.co.uk/" target="_blank"></head>';
			# $pagecontents = str_replace('</head>', $base, $pagecontents);
			echo $pagecontents;
		}
	}
	
}
	