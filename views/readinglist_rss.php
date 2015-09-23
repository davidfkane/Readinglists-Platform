<?php
header("Content-type: text/xml");
print("<?xml version=\"1.0\" encoding=\"UTF-8\" ?>");
$this->load->helper('xml');
?>

<rss version="2.0">

<channel>
  <title>Reading List for: <?php echo($results['module']); ?></title>
  <description>Reading List Links</description>
  <link>http://witcat.wit.ie/</link>
<?php 

foreach ($results['results'] as $row){
    //$row['bid']."_z\">\n";
    //echo "TY - BOOK\n";
    echo "\t<item>\n\t\t<title>" . stripslashes(stripslashes(xml_convert($row['Title'])))."</title>\n";
    if($row['libid'] != ''){
	    echo "\t\t<link>";
	    echo "http://witcat.wit.ie/record=".substr($row['libid'], 0,8)."~S0";
	    echo "</link>\n";
    }else{
	   // echo "http://witcat.wit.ie/";
    }
    echo "\t\t<description>" . stripslashes(xml_convert($row['Author'])) ." :: ". $row['Year'] ." :: ". stripslashes(xml_convert($row['Publisher'])) . "</description>\n";
    echo "\t</item>\n";
}

echo "\t<item>\n\t\t<title>CLICK TO DOWNLOAD IN ENDNOTE FORMAT</title>\n";

echo "\t\t<link>";
echo "" . base_url() . "index.php/lists/list_books/endnote/".$module_id."/";
echo "</link>\n";

#	echo "\t\t<description>Click on the link to download the EndNote file.</description>\n";
echo "\t</item>\n";

?>
</channel>

</rss>
