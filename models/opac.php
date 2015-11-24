<?php
class opac extends CI_Model {
    function opac()
    {
        // Call the Model constructor
        parent::__construct();
		$this->load->database();
    }
function searchArray($terms){
	
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
	foreach($terms as $searchterm){
		array_push($searcharray, array($bgcolor[$count], $searchterm));
		$count = ($count == 11)?$count=0:$count + 1;
	}
	return $searcharray;
}
function sanitisedSearchTerm($ti){  // gets the search term and cleans it up.
	//$raw_searchterm = urldecode(str_replace("lists/library/", "", uri_string())); 
	if($ti != ''){		
		$stopwords  = array('/ the /i', 
							'/ and /i', 
							'/ a /i', 
							'/ i /i', 
							'/ [A-Z]{1} /i', 
							'/ as /i', 
							'/ an /i', 
							'/ the /i', 
							'/ in /i', 
							'/ it /i', 
							'/ is /i', 
							'/ \D{1} /i', 
							'/ no /i', 
							'/ on /i', 
							'/ to /i', 
							'/ or /i', 
							'/ for /i', 
							'/ of /i', 
							'/[ -,;:\.]+/'
							);
		return trim(preg_replace($stopwords, " ", " ".$ti." ")); 
	}else{
		return '';
	}
}
function DownloadUrl($Url){
	// is curl installed?
	if (!function_exists('curl_init')){ 
		die('CURL is not installed!');
	}
	// create a new curl resource
	$ch = curl_init();
	/*
	*   Here you find more options for curl:
	*   http://www.php.net/curl_setopt
	*/
	curl_setopt($ch, CURLOPT_URL, $Url);	// set URL to download
	curl_setopt($ch, CURLOPT_REFERER, "http://www.google.com/");	// set referer:
	curl_setopt($ch, CURLOPT_USERAGENT, "MozillaXYZ/1.0");	// user agent:
	curl_setopt($ch, CURLOPT_HEADER, 0);	// remove header? 0 = yes, 1 = no
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);	// should curl return or print the data? true = return, false = print
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);	// timeout in seconds
	$output = curl_exec($ch);	// download the given URL, and return output
	curl_close($ch);	// close the curl resource, and free system resources
	return $output;	// print output
}
function queryCOPAC($cleaned_searchterm){  

	$resultset = array();
	logfile("COPAC  - - : \nhttp://copac.ac.uk/search?format=BibTeX&ti=" . urlencode($cleaned_searchterm), "YELLOW");
	$homepage = file_get_contents('http://copac.ac.uk/search?format=BibTeX&ti=' . urlencode($cleaned_searchterm));
	logfile("COPAC  - - : \n$homepage", "cyan");
	$books = explode("@book", $homepage);
	$matches  = array('/[ \{\}]+/i', '/[ ,;:\.]+$/', '/^[ ,;:\.]/');
	//return trim(preg_replace($stopwords, " ", " ".$raw_searchterm." ")); 
	foreach($books as $book){
		$lines = explode("\n", $book);
		
		// make an empty array that is filled with values if available from COPAC
		// it is certain that there are going to be titles as the search was done on the title field
		$entry = array('author'=>'','title'=>'','publocation'=>'','publisher'=>'','pubdate'=>'','isbn'=>'','idwitbooks'=>'', 'type'=>'book');
		foreach($lines as $line){
			if(preg_match('/^@/', $line)){
					$entry['type'] = trim($line[1]);
			}
			if(preg_match('/^title|^address|^publisher|^year|^isbn|^author/', $line)){
				$line = explode('=', preg_replace($matches, " ", $line));
				//$line = trim(preg_replace($matches, " ", $line));
				if(preg_match('/^author/', $line[0]))
					$entry['author'] = trim($line[1]);
				if(preg_match('/^title/', $line[0]))
					$entry['title'] = trim($line[1]);				
				if(preg_match('/^address/', $line[0]))
					$entry['publocation'] = trim($line[1]);
				if(preg_match('/^publisher/', $line[0]))
					$entry['publisher'] = trim($line[1]);
				if(preg_match('/^year/', $line[0]))
					$entry['pubdate'] = trim($line[1]);
				if(preg_match('/^isbn/', $line[0]))
					$entry['isbn'] = trim($line[1]);
			}
		}
		$resultset[$entry['title']] = $entry;
	}
	logfile(print_r($resultset, true), 'cyan');
	return $resultset;
}
function queryGoogle($cleaned_searchterm){  
	$resultset = array();
	// json_decode()
	$homepage = file_get_contents('https://www.googleapis.com/books/v1/volumes?q=' . urlencode($cleaned_searchterm));
	//print $homepage;
	$results=json_decode($homepage, TRUE);
	// print("<pre>");	print_r($results);	print("</pre>");
	if(isset($results['items'])){
		foreach($results['items'] as $item){
			// make an empty array that is filled with values if available from COPAC
			// it is certain that there are going to be titles as the search was done on the title field
			$entry = array('author'=>'','title'=>'','publocation'=>'','publisher'=>'','pubdate'=>'','isbn'=>'','idwitbooks'=>'');
			$entry['author'] = isset($item['volumeInfo']['authors'][0]) ? trim($item['volumeInfo']['authors'][0]) : '' ;
			$entry['title'] = isset($item['volumeInfo']['title']) ? trim($item['volumeInfo']['title']) : '' ;
			isset($item['volumeInfo']['subtitle']) ? $entry['title'] .= " " + trim($item['volumeInfo']['subtitle']) : '' ;
			$entry['publocation'] = isset($item['volumeInfo']['publocation']) ? trim($item['volumeInfo']['publocation']) : '';
			$entry['publisher'] = isset($item['volumeInfo']['publisher']) ? trim($item['volumeInfo']['publisher']) : '' ;
			$entry['pubdate']  = isset($item['volumeInfo']['publishedDate']) ? trim($item['volumeInfo']['publishedDate']) : '' ;
			$entry['isbn']  = isset($item['volumeInfo']['industryIdentifiers'][0]['identifier']) ? trim($item['volumeInfo']['industryIdentifiers'][0]['identifier']) : '' ;
			$resultset[$entry['title']] = $entry;
		}
	}
	return $resultset;
}
// START NEWER SUMMON FUNCTIONS (TAKEN FROM TEST.PHP)
function url_tools__request($url, $timeout=10, $headers=array()) {
  /* Returns results of calling a given $url with a $timeout and optional $headers. */
   
  // make cURL request; return results.
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //suppress output.
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  if(!$ch_exec = curl_exec($ch)){
	  logfile("CURL ERROR: ". curl_error($ch), "CYAN");
  }
  curl_close($ch);
  return $ch_exec;
}
 
function getSummonXML($query, $offset=0, $limit=10, $sort=False, $doctype="xml") {
  /* Returns response in $doctype format (xml|json) from Summon 2.0 API for a $query.
   
    - Results start at $offset for a total of $limit results.
    - Results are sorted by relevance ($sort = False) or date-descending ($sort = True).
    - Code based on Python code here: https://gist.github.com/lawlesst/1070641
    - See also: http://blog.humaneguitarist.org/2014/09/04/getting-started-with-the-summon-api-and-python/
  */
   
  // set API credentials.
 
    $api_id = "wit";
    $api_key = "5D8LNr40Hkg0a52QA42326TCmD2ubyvX";
     
  // set API host and path.
  $host = "api.summon.serialssolutions.com";
  $path = "/2.0.0/search";
   
  // create query string.
  $query = "s.q=" . urlencode($query) . "&s.pn=$offset&s.ps=$limit&s.ho=true";
   
  // set sort to date-descending if needed.
  if ($sort != False) {
    $query = $query . "&s.sort=PublicationDate:desc";
  }
   
  // sort and encode $query.
  $query_sorted = explode("&", $query);
  asort($query_sorted); 
  $query_sorted = implode("&", $query_sorted);
  $query_encoded = urldecode($query_sorted);
   
  // create request headers.
  $accept = "application/$doctype";
  $date = gmstrftime("%a, %d %b %Y %H:%M:%S GMT", time());
  $id_string = implode("\n", array($accept, $date, $host, $path, $query_encoded, ""));
  $digest = base64_encode(hash_hmac("sha1", utf8_encode($id_string), $api_key, True));
  $authorization = "Summon " . $api_id . ";" . $digest;
  $headers = array("Host:$host", "Accept:$accept", "x-summon-date:$date", "Authorization:$authorization");
   
  // call API; return response.
  $url = "http://$host$path?$query";
  logfile("URL: $url ", "red");
  $response = $this->url_tools__request($url, $timeout=10, $headers=$headers);
  #logfile("res: $response ", "magenta");
  return $response;
}

# echo summon_tools__request("Good%20to%20Great", $offset=0, $limit=10, $sort=False, $doctype="xml");

// END NEWER SUMMON FUNCTIONS (TAKEN FROM TEST.PHP)#

// START OLDER SUMMON FUNCTIONS

function build_summon_request($pageNumber, $searchterms) {

	// build the header to send to Summon API
    $get_line = "GET ";
    $host_line = "Host: ";
    $accept_line = "Accept: ";
    $xsummondate_line = "x-summon-date: ";
    $xsummonsessionid_line = "x-summon-session-id: ";
    $authorization_line = "Authorization: Summon ";
	$connection_line = "Connection: close";
	

    $query_type = "/2.0.0/search";
    $host = "api.summon.serialssolutions.com";
    $application_type = "application/xml";
    $access_id = "wit";
    $secret_key = "5D8LNr40Hkg0a52QA42326TCmD2ubyvX";

    //
    // build the easy lines, or parts of them at least
    //

    // get line
	$pageSize = 50;
	$highlight = 'false';
	$search_terms = preg_replace("/\\\/", "", $searchterms);
	$content_type = "SourceType,Library Catalog,false";
	if ($pageNumber == "") {
		$pageNumber = "1";
	}
    $get_line .= $query_type . "?";
	$get_line .= "s.fvf=" . urlencode($content_type) . "&";
	$get_line .= "s.ps=" . urlencode($pageSize) . "&";
	//$get_line .= "s.hl=" .$highlight . "&";
	//$get_line .= "s.pn=" . urlencode($pageNumber) . "&";
	$get_line .= "s.q=" . urlencode($search_terms);
	$get_line .= " HTTP/1.1";
	
    #print "<br/>GET LINE = ".$get_line."<br/>";
    // host line
    $host_line .= $host;

    // date line
    $summon_date = date(DATE_RFC2822);
    $xsummondate_line .= $summon_date;

    // accept line
    $accept_line .= $application_type;


    // assemble the identication string
    $id_string = $application_type . "\n" . $summon_date . "\n" . $host . "\n" . $query_type . "\n";
	$id_string .= "s.fvf=" . $content_type;
	$id_string .= "&s.ps=" . $pageSize;
	//$id_string .= "&s.hl=" . $highlight;
	//$id_string .= "&s.pn=" . $pageNumber;
	$id_string .= "&s.q=" . $search_terms;
	$id_string .= "\n";
   # print "<br/>ID STRING = ".$id_string."<br/>";
    // the ID string must be UTF-8 encoded - necessary in PHP??

    // get the HMAN-SHA1 raw hash and encode using Base 64.
    $encoded_ID = base64_encode(hash_hmac('sha1', $id_string, $secret_key, TRUE));

    // authorization line
    $authorization_line .= $access_id . ";" . $encoded_ID;

    // assemble the header string
    $summon_header = $get_line . "\n" . $host_line . "\n" . $accept_line . "\n" . $xsummondate_line . "\n" . $authorization_line . "\n" . $connection_line . "\n\n";

    // print it out for testing
  #  echo "<pre>" . $id_string . "</pre><br />";
  #  echo "<pre>" . $summon_header . "</pre><br />";

	return $summon_header;

}


// Summon data is chunked - take file handle and receive it
// return a string of all the data
function request_response($fp, $summon_header, $not_chunked) {

	logfile("fp: \t" . $fp, "CYAN");
	logfile("SUMMON HEADER: " . $summon_header, "YELLOW");
	logfile("not_chunked: \t" . $not_chunked, "CYAN");
	$result = "";
    if (!$fp) {

       $result = "$errstr ($errno)";
       return $result;

    } 
    stream_set_timeout($fp, 5);

    // send the request
    fwrite($fp,$summon_header);

	if ($not_chunked) {		
	
		logfile("NOT CHUNKED", "RED");
		$result = stream_get_contents($fp);   
		return $result;	
	}
	else {	
		logfile("--- CHUNKED", "RED");
		// get the chunked response
		// start by getting the header, looking for \r\n
		while (strlen($line = stream_get_line($fp,2048,"\r\n")) != 0){
		   #@$result .= $line;
		   $result .= $line;
		}
	 
		// now read in the size of the first chunk
		while (($chunk_size = hexdec(stream_get_line($fp,2048,"\r\n")) ) != 0) {

		   //echo "<p>Chunk size (decimal): " . $chunk_size . " | " . date(DATE_RFC2822) . "</p>";
		   // now read in the chunk
		   $line = stream_get_line($fp,$chunk_size + 2,"\r\n");
		   $result .= $line;
		}

		$line = stream_get_line($fp,$chunk_size + 4);
		//echo "<p>final line: " . $line . "</p>";

		return $result;
	}

}


// split off HTTP header
function remove_http_header ($result) {

    $xml_start = strpos($result, "<?xml");
    $xml_end = strpos($result, "</response>") + 11;
	if (($xml_start != 0) && ($xml_end != 0)) {
		$xml_file = substr($result, $xml_start, $xml_end - $xml_start);
	}
	else {
		$xml_file = "";
	}
    return $xml_file;
}





// take XML object of search results and parse into a useful hash
function parse_search_results ($doc) {

    // create the empty hash
    $search_results = Array();

    // load the search results into a hash
    $documents = $doc->getElementsByTagName('document');

	// need to get element called response
	$response_tags = $doc->getElementsByTagName('response');
	foreach ($response_tags as $response_tag) {
		$search_results["response"]["pageCount"] = $response_tag->getAttribute('pageCount');
	}

	// need to get element called query
	$query_tags = $doc->getElementsByTagName('query');
	foreach ($query_tags as $query_tag) {
		$search_results["query"]["pageNumber"] = $query_tag->getAttribute('pageNumber');
		$search_results["query"]["pageSize"] = $query_tag->getAttribute('pageSize');
	}

	
    $doc_number = 0;
    foreach ($documents as $document) {

       // get useful document attributes
       $search_results[$doc_number]["link"] = $document->getAttribute('link');
       $search_results[$doc_number]["openUrl"] = $document->getAttribute('openUrl');
       $search_results[$doc_number]["hasFullText"] = $document->getAttribute('hasFullText');
       $search_results[$doc_number]["availabilityId"] = $document->getAttribute('availabilityId');
	   $search_results[$doc_number]["isFullTextHit"] = $document->getAttribute('isFullTextHit');
       $search_results[$doc_number]["inHoldings"] = $document->getAttribute('inHoldings');


       // get all the field values
       $fields = $document->getElementsByTagName('field');

       foreach ($fields as $field) {
 
          $key = $field->getAttribute('name');

          $values = $field->getElementsByTagName('value');

          $values_string = "";
          $count = 1;

          foreach ($values as $value) {
             if ($count > 1) {
                $values_string .= "; ";
             }
             $values_string .= $value->nodeValue;

             $count++;
          }


          $search_results[$doc_number][$key] = $values_string;

       }
       $doc_number++;
    }


return $search_results;

}



// print out some search results
function print_results($search_results) {
				
	$count = 0;

    echo "<pre>";
	print_r($search_results);
/*

*/
    echo "</pre>";

}





function print_page_info($search_results) {
	
	$pageCount = $search_results["response"]["pageCount"];
	$pageNumber = $search_results["query"]["pageNumber"];
	$pageSize = $search_results["query"]["pageSize"];

	$next_pageNumber = $pageNumber + 1;
	$previous_pageNumber = $pageNumber - 1;
	
	print "<p align='right'>";
	print "Page $pageNumber of $pageCount ";
	
    $search_terms = $_GET['search_terms'];
	$search_terms = preg_replace("/\\\/", "", $search_terms); 
	/*
	if ($pageNumber > 1) {
		print "| <a href='//www.uwe.ac.uk/library/opac/index.php?search_terms=";
		echo htmlspecialchars($search_terms); 
		print "&submit=Search&pageNumber=" . $previous_pageNumber;
		print "'>Previous page</a> ";
	}
	if ($pageNumber < $pageCount) {
		print "| <a href='//www.uwe.ac.uk/library/opac/index.php?search_terms=";
		echo htmlspecialchars($search_terms); 
		print "&submit=Search&pageNumber=" . $next_pageNumber;
		print "'>Next page</a>";
	}
	*/
	print "</p>";
}

function insert_search_box() {

	$search_terms = $_GET['search_terms'];
	$search_terms = preg_replace("/\\\/", "", $search_terms);
	$encoded_search_terms = htmlspecialchars($search_terms, ENT_QUOTES);
	print "<div class=search_box>";
    print "<p><form action='index_wyatt.php' method='get' autocomplete='off'>";
	print "<p>Try a few words from the title or an author's last name...</p>";
    print "<input style='height: 16px; padding: 5px; width:500px; border-width: 1px; border-color: #2D6ABB;' type='text' name='search_terms' value='";
	//echo htmlentities($_GET['search_terms'], ENT_QUOTES, 'UTF-8', TRUE);
	print $encoded_search_terms;
	print "' />";
	print "<input type='hidden' name='pageNumber' value='1' />";
    print "<input style='height: 28px; width: 100px; background-color:#2D6ABB; border-width: 0px; color:#FFFFFF;' type='submit' name='submit' value='Search' />";
    print "<p>&nbsp;</p>";
	print "</form></p>";
    print "</div>";
	
}


function querySummon($terms){
		$result = $this->getSummonXML($terms);
/*
		$summon_header = $this->build_summon_request(1, $terms);
		$result = "";	   
		// connect to the Summon API and send the request
		$fp = stream_socket_client("tcp://api.summon.serialssolutions.com:80", $errno, $errstr, 5);		
		$result = $this->request_response($fp, $summon_header, FALSE);        
		fclose($fp);
		#echo "<PRE>String length: " . strlen($result) . "</PRE>";
		#echo "<PRE>Response: " . $result . "</PRE>";
		// split off the header
*/

		//$xml_file = $this->remove_http_header($result);
		// echo "<PRE>String length without header: " . strlen($xml_file) . "</PRE>";
		// load up the file into an XML DOM object
		$doc = new DOMDocument();
		//$doc->loadXML($xml_file);   
		$doc->loadXML($result);   
		// print out the entire XML
		//if ($debug && 0) {
		  // echo $doc->saveXML(); 
		//}
		// get the search results in a nice hash
		$search_results = $this->parse_search_results($doc);
		$count = 0;
		#logfile(print_r($search_results, true), "CYAN");
		$resultset = array();
		foreach($search_results AS $book){
			if(isset($book['link'])){
				$entry = array('author'=>'','title'=>'','publocation'=>'','publisher'=>'','pubdate'=>'','source'=>'','isbn'=>'','idwitbooks'=>'', 'url'=>'', 'type'=>'');
				
				#$querystring = "SELECT idwitbooks, title, author, isbn, publocation, publisher, pubdate, url, type from witbooks ";
				//print_r($book);
				@$entry['author'] = $book['Author'];
				@$entry['title'] = $book['Title'];
				@$entry['publocation'] = $book['PublicationPlace'];
				@$entry['publisher'] = $book['Publisher'];
				@$entry['pubdate'] = $book['PublicationYear'];
				@$entry['isbn'] = $book['ISBN'];
				@$entry['isbn'] = $book['ISSN'];
				@$entry['type'] = $book['ContentType'];
				@$entry['source'] = $book['Source'];
				@$entry['idwitbooks'] = $book['ExternalDocumentID'];
				if(trim($book['SourceType']) == "Library Catalog"){
					@$entry['url'] = "http://witcat.wit.ie/record=" . substr($book['ExternalDocumentID'],0,8);
					logfile("ID FOR ". $book['SourceType']. "\t " . $count."\t" . $book['Title'] ." IS: ".$book['ExternalDocumentID'], 'BLUE', __FUNCTION__, __FILE__);
					logfile("ID FOR ". $book['SourceType']. "\t " . $count."\t" . $book['Title'] ." IS: ".$book['ExternalDocumentID'], 'yellow', __FUNCTION__, __FILE__);
				}else{
					@$entry['url'] = $book['URI'];
					logfile("ID FOR ". $book['SourceType']. "\t " . $count."\t" . $book['Title'] ." IS: no id", 'GREEN', __FUNCTION__, __FILE__);
				}
				logfile("TITLE: " . $book['Title'], "GREEN");
				array_push($resultset, $entry);
			}
			$count++;
		} 
	logfile($result, 'magenta');
	//show_array($resultset);
	return $resultset;
}

// END SUMMON FUNCTIONS
function queryISBN($isbn){	
	$this->load->helper('xml');
	// the search link in this case will appear next to the ISBN field.
	$resultset = array();  // this gets returned in the end
	// alternative key is : U8DHQF9L // 14/07/2011
	$key = 'UCBODYWN';
	$xmlDoc = new DOMDocument();
	$path = 'http://isbndb.com/api/books.xml?access_key='.$key.'&results=details&index1=isbn&value1=' . $isbn;
	#$raw = file_get_contents($path);
	#$xmlDoc->load($raw);
	@$xmlDoc->loadHTMLFile($path);
	#print "<a href=\"" . $path . "\" target=\"_blank\">$path</a>";
	$x = $xmlDoc->documentElement->firstChild->firstChild->firstChild;	
	$count = 0;
	#print "<textarea>"; 
	
	foreach($x->childNodes AS $book){
		$entry = array('author'=>'','title'=>'','publocation'=>'','publisher'=>'','pubdate'=>'','isbn'=>'','idwitbooks'=>'');
		$entry['isbn'] = $isbn;
		foreach($book->childNodes as $line){
	#		print $line->nodeName . " ======== " . $line->nodeValue . " AAAAAAAAAAA\n";
			
		    if($line->nodeName == 'authorstext'){ 			$entry['author'] = $line->nodeValue;}
		    if($line->nodeName == 'title'){ 	$entry['title'] = $line->nodeValue;}
		    if($line->nodeName == 'titlelong' && $line->nodeValue != ''){ 			$entry['title'] = $line->nodeValue;}
		    if($line->nodeName == 'publishertext'){ 
				$matches = array();
				preg_match_all('/\d{4}/', $line->nodeValue, $matches);
				if(isset($matches[0])){$entry['pubdate'] = $matches[0][0];}
				$val = str_replace(';',':', $line->nodeValue);	
				$data = explode(':', $val);
				if(isset($data[1])){
					$entry['publocation'] = $data[0];
					$entry['publisher'] = $data[1];
				}else{					
					$entry['publisher'] = $line->nodeValue;
				}
			}
		    
		}
		$resultset[$entry['title']] = $entry;
		$count++;
	} 
	#print "</textarea>"; 
	#show_array($resultset);
	return $resultset;
}

function queryZ39(){
	header ("Content-Type:text/html");  
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
	$host="witcat.wit.ie:210/innopac";
	$query='management';

			echo "<pre>"; print($host); echo "</pre><hr/><br/>\n";
	if (empty($query)){
		echo 'no dice';
	} else {
	
		$ya = yaz_connect($host);
		yaz_syntax($ya, "marc 21");
		yaz_range($ya, 1, 10);
		yaz_search($ya, "rpn", "@attr 1=4 $query");
		yaz_wait();	  
		print "<html><head></head><body>query results: ";	
		$error = yaz_error($ya);		
		if (!empty($error)) {
			echo "Error: $error";
		} 
		else {
			$hits = yaz_hits($ya);
		}
		for ($p = 1; $p <= 1000; $p++) {
			$rec = yaz_record($ya, $p, "string");
			if (empty($rec)) continue;
			print $rec;
		}
		print "</html></body>";
	}
}
function queryWitBooks($cleaned_searchterm){  // makes a query to copac and returns an array of the matches
	$limit = 50;
	#$querystring = "SELECT idwitbooks, title, author, isbn, publocation, publisher, pubdate, url, type from witbooks ";
	$querystring = "SELECT idwitbooks, title, author, isbn, publocation, publisher, pubdate, url from witbooks ";
	$querystring .= "where MATCH(title) AGAINST('".addslashes($cleaned_searchterm)."') limit " . $limit . ";";
	//logfile($querystring);
	$query = $this->db->query($querystring . ';');
	return $query->result_array();
	//print_r($query->result_array());
}
function getTitle($ti){
	// if the title is actually a book ID, then 
	// we need to fetch the actual title.
	if(preg_match('/bookID_/', $ti)){
		$q = "Select Title from books where bid = " . str_replace('bookID_', '', $ti) . ";";
		$query = $this->db->query($q);
		$row = $query->row();
		$ti = preg_replace('/[^A-Za-z0-9]+/', " ", $row->Title);	
	}
	return $ti;
}
function getAuthor($ti){
	// if the title is actually a book ID, then 
	// we need to fetch the actual title.
	if(preg_match('/bookID_/', $ti)){
		$q = "Select Author from books where bid = " . str_replace('bookID_', '', $ti) . ";";
		$query = $this->db->query($q);
		$row = $query->row();
		$ti = preg_replace('/[^A-Za-z0-9]+/', " ", $row->Author);	
	}
	return $ti;
}
function show($source, $ti) {
	//$ti = $this->getTitle($ti);
	$array = array(
		'opac_resultset' => array(), 
		'ti' => '', 
		'source' => '');
	$resultset = array();
	$returnedresultset = array();
	$searchterm = $this->sanitisedSearchTerm($ti);
	if($searchterm != ''){
		$searchterms = array_unique(explode(' ', $searchterm));		
		$searcharray = $this->searchArray($searchterms);	// make the colour highlighting reference array for the search terms;		
		if($source == 'copac'){
			$resultset = $this->queryCOPAC($searchterm);
		}
		if($source == 'witcat'){
			$resultset = $this->querySummon($searchterm);
			//$resultset = $this->queryWitBooks($searchterm);
		}
		if($source == 'isbndb'){
			// possibly add a function here to clean up ISBN.
			$resultset = $this->queryISBN($searchterm); // search term is always to be an ISBN.
		}
		if($source == 'google'){
			$resultset = $this->queryGoogle($searchterm);
		}
		if($source == 'z39wit'){
			$resultset = $this->queryZ39($searchterm);
			
		}
		$rowct = 0;
		foreach ($resultset as $row){
			
			#echo "<pre>"; print_r($row); echo "</pre><hr/><br/>\n";
			if($rowct == 9){
				$rowct = 0;
			}
			$matchcount = 0;
			$highlighted_title = '';
			$highlighted_title_array = explode(' ', $row['title']);
			foreach($searcharray as $searchterm){
			  $highlighted_searchterm = "<span class=\"badge\" style='" . $searchterm[0] ."color:black;'>" . $searchterm[1] . "</span>";
			  $regex = '/'.str_replace('/','\/', $searchterm[1]).'/i';
			  if(preg_match($regex, $row['title'])){	
			    for($i = 0; $i < count($highlighted_title_array); $i++){
				  if(!preg_match('/<\/span>/', $highlighted_title_array[$i])){
					$highlighted_title_array[$i] = str_ireplace($searchterm, $highlighted_searchterm, $highlighted_title_array[$i]);
				  }
			    }
			    $matchcount++;
			  }
			}
			$highlighted_title = implode(' ', $highlighted_title_array);
			# logfile("\t=>\t" . $row['pubdate'] . "\n"); 
			if($row['title'] != ''){			
				//if(!isset($row['type'])){$row['type'] = '';}
				if(!isset($row['url'])){$row['url'] = '';}
				//if(!isset($row['bcode3'])){$row['bcode3'] = '';}		
				//logfile("\tv=>\t" . $row['title']); 
				$typeicon = $this->generateTypeIcon($row['type'], $format='search_results');
										
				$returnedresultset[30-$matchcount . "#".$rowct."#" . $row['title']] =  array(
					'recordID' => $row['idwitbooks'],
					'highlighted_title' => "$typeicon <span class=\"badge\" style=\"color: #990000; border: dashed 1px #990000; background-color: white;\">".$row['pubdate']."</span> ".$highlighted_title, 
					'Title' => $row['title'],
					'Author' => $row['author'],
					'ISBN' => $row['isbn'],
					'Publisher_Location' => $row['publocation'],
					'Publisher' => $row['publisher'],
					'Publication_Date' => $row['pubdate'],
					'url' => $row['url'],
					'type' => $row['type']
					//'bcode3' => $row['bcode3']
				);	
			}
			$rowct++;
		}
		ksort($returnedresultset); 
		#foreach($returnedresultset as $k => $v){
			#logfile(" = = = \t" . $k);
		#}
		//print_r($returnedresultset);
		$array['opac_resultset'] = $returnedresultset;  // returns multidimensional array containing attributes.
		$array['ti'] = $ti;  // returns multidimensional array containing attributes.
		$array['source'] = $source;  // returns multidimensional array containing attributes.
	}
	#print(count($returnedresultset) . "count second resultset");
	//print_r($returnedresultset);
	return $array;
}
	function generateTypeIcon($type, $format="search_results"){
		$rowtypes = array(
			'book' => 					array('#003399', 'book'),
			'book review' =>			array('#999eee', 'book'), 
			'ebook' => 					array('#6666ff', 'book'), 
			'reference' => 				array('#3664bb', 'book'), 
			'journal article' => 		array('#003399', 'newspaper-o'), 
			'newspaper article' => 		array('#0066ff', 'newspaper-o'),
			'ejournal' =>		 		array('#6666ff', 'newspaper-o'),
			'web' => 					array('#6666ff', 'globe'), 
			'web resource' => 			array('#3664bb', 'globe'), 
			'archival material' => 		array('#3664bb', 'archive'), 
			'report' => 				array('#587094', 'file-text'),
			'government publication' => array('#587094', 'file-text'),
			'audio' => 					array('#4c6980', 'headphones'), 
			'conference proceeding' =>	array('#01296e', 'group'),
			'video' => 					array('#01296e', 'film'),
			'video recording' =>		array('#01296e', 'film')
			
		);
		$formats = array(
			'search_results' => array('color: white; margin-right: 10px; cursor: help; float: left; border: solid 1px black; padding: 0px;'),
			'readinglist_html' => array('color: white; margin-right: 10px; cursor: help; float: left; border: solid 1px black; padding: 0px;')
			);
		$type = strtolower($type);
		if(isset($type)){
			logfile("ROW_TYPE: start" . $type."end", 'yellow');
		}
		if(!isset($rowtypes[$type])){						
			$typeicon = "<span class=\"listAdminButtons removeFromMobileView btn btn-default\" title=\"".$type."\" onclick=\"return false;\" style=\"".$formats[$format][0]." background-color: yellow \"><i class=\"fa fa-info-circle\"></i></span>";
		}else{
			$typeicon = "<span class=\"listAdminButtons removeFromMobileView btn btn-default\" title=\"".$type."\" onclick=\"return false;\" style=\"".$formats[$format][0]." background-color: ".$rowtypes[$type][0]." \"><i class=\"fa fa-".$rowtypes[$type][1]."\"></i></span>";
		}
		return $typeicon;
	}
} 

?>
