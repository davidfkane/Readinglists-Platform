<?php



class z3v950 extends CI_Model {
    function copac()
    {
        // Call the Model constructor
        parent::__construct();
		$this->load->database();
    }

	function marcSearch($host, $query){
		header ("Content-Type:text/html");  
		#$host=$_REQUEST[host];
		#$query=$_REQUEST[query];
		//phpinfo();
		error_reporting(E_ALL);
		ini_set('display_errors', '1');
		#$host='z3950.copac.ac.uk:210/COPAC'; 
		#$host="www.lyit.ie:210/innopac";
		$host="library.itb.ie:210/innopac";
		#$host="dkitlibs.dkit.ie:210/innopac";
		#$host="witcat.wit.ie:210/innopac";
		$query='management';
		
		#$num_hosts = count($host);
		#print "testing this";
		
		//require('./classes/class.dkMARC.php');
		//require('./classes/class.displayItem.php');
		if (empty($query)){
			echo '<form method="get">
			
			<input type="checkbox"    name="host[]" value="www.lyit.ie:210/innopac" checked />			Letterkenny <br />
			<input type="checkbox"    name="host[]" value="library.itb.ie:210/innopac" checked />        DIT test  <br />
			<input type="checkbox"    name="host[]" value="dkitlibs.dkit.ie:210/innopac" checked />        WIT test <br /> 
			<input type="checkbox"    name="host[]" value="witcat.wit.ie:210/innopac" checked />        WIT test <br />  
			<br />
			RPN Query:
			<input type="text" size="30" name="query" />
			<input type="submit" value="Search" />
			</form>
			';
		} else {
		
			$ya = yaz_connect('library.cfataglogue.tcd.ie:210/innopac');
			yaz_syntax($ya, "marc 21");
			yaz_range($ya, 1, 10);
			yaz_search($ya, "rpn", "@attr 1=4 $query");
			yaz_wait();
		  
			print "<html><head></head><body>hello world";
		
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
				$z = new dkMARC($rec, $p, $query);
			
				print $z->display_all();
				print $z->displayItemXML();
				unset($z);
			}
			print "</html></body>";
		}
	}
}
?>
