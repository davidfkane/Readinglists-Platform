<?php
$format = 'endnote';
if($format == 'endnote'){
	//header("Content-Type: application/x-endnote-refer");
	
	#header("Content-Type: application/x-inst-for-scientific-info");
	#header("Content-Disposition: attachment; filename=\"ReadingList.ciw\"");'
	
	header("Content-Type: application/x-research-info-systems;charset=utf-8");
	header("Content-Disposition: attachment; filename=\"ReadingList.ris\"");
	// http://www.13thnordic.aau.dk/ocs/rst.php?id=78&op=display_cite&cf=1
	#header("Content-Type: application/export");
	header("Pragma	no-cache");
	echo("PROVIDER: WIT READINGLISTS\nDATABASE: REFWORKS IMPORT\nCONTENT: TEXT/PLAIN; CHARSET=\"UTF-8\"\n");
	foreach ($results['results'] as $row){
		$type = strtoupper($row['type_name']);
		if($type == 'WEB'){$type = 'GEN';};
		if($type == 'EBOOK'){$type = 'BOOK';};
		echo "\n\nTY  - $type\n";
		echo "ID  - " . $row['bid']."\n";
		echo "A1  - ". $row['Author']."\n";
		echo "T1  - " . $row['Title']."\n";
		echo "Y1  - ". $row['Year']."\n";
		echo "PB  - ". $row['Publisher']."\n";
		echo "SN  - ". $row['isbn']."\n";
		echo "CY  - ". $row['place']."\n";
		#echo " - ". $row['']."\n";
		#echo " - ". $row['']."\n";
		// if we have the book ID from witcat we can create a link.
		if($row['libid'] != '' && $row['url'] == ''){
			echo "UR  - http://witcat.wit.ie/record=".substr($row['libid'], 0,8)."~S0\n";
		}else{
			echo "UR  - " . $row['url']."\n";
		}
		echo "ER  - \n\n";	
	}
}else if($format == 'bibtex'){
	header("Content-Type: text/x-bibtex ;charset=utf-8");
	// http://www.13thnordic.aau.dk/ocs/rst.php?id=78&op=display_cite&cf=1
	#header("Content-Type: application/export");
	#header("Content-Disposition: attachment; filename=\"ReadingList.enl\"");
	#header("Pragma	no-cache");
	foreach ($results['results'] as $row){
		$row['bid']."_z\">\n";
		echo "TY - BOOK\n";
		echo "T1 - " . $row['Title']."\n";
		echo "AU - ". $row['Author']."\n";
		echo "PY - ". $row['Year']."\n";
		echo "PB - ". $row['Publisher']."\n";
		// if we have the book ID from witcat we can create a link.
		if($row['libid'] != ''){
			echo "UR - http://witcat.wit.ie/record=".substr($row['libid'], 0,8)."~S0\n";
		}
		echo "\n\n";	
	}
	
}
/*
header("Content-Type: application/x-endnote-refer");
echo("<?xml version=\"1.0\"?>\n");
echo("<XML>\n");
echo("<RECORDS>");
foreach ($results['results'] as $row){
	//print_r($row);
	if($row['url'] == ''){$row['url'] = "UR - http://witcat.wit.ie/record=".substr($row['libid'], 0,8)."~S0";}
	
?>

<RECORD>
    <REFERENCE_TYPE>31</REFERENCE_TYPE>
    <REFNUM>0000000001</REFNUM>
    <AUTHORS>
        <AUTHOR><?php echo($row['Author']); ?></AUTHOR>
    </AUTHORS>
    <YEAR><?php echo($row['Year']); ?></YEAR>
    <TITLE><?php echo($row['Title']); ?></TITLE>
    <PLACE_PUBLISHED><?php echo($row['place']); ?></PLACE_PUBLISHED>
    <PUBLISHER><?php echo($row['Publisher']); ?></PUBLISHER>
    <ISBN><?php echo($row['isbn']); ?></ISBN>
    <CUSTOM6>custom 6</CUSTOM6>
    <ACCESSION_NUMBER><?php echo($row['libid']); ?></ACCESSION_NUMBER>
    <NOTES><?php echo($row['notes']); ?></NOTES>
    <URL><?php echo($row['url']); ?></URL>
</RECORD><?php
}
echo("\n</RECORDS>\n");
echo("</XML>\n");
		
		*/
?>
