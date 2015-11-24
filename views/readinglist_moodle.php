<?php
header("Content-type: text/html");
$serialized = base64_encode(serialize($serialized));
$imagepath = "https://library.wit.ie/mod/readinglist/images/";
if($module_title != 'NO_DATA'){
?>
<div class="readinglist_html">
<script language="javascript">
$('div#page div.navbar div.navbutton form[action|=https://vle.wit.ie/course/mod.php]').attr('action', '<?php echo("".base_url()."lists/fetch/module/" . $serialized . ""); ?>');
</script>
<?php 

echo "<h2 style=\"margin: 20px; padding: 10px; background-color: #EAF1F4; padding-left: 20px; margin-bottom: 0px; font-family: sans-serif; s\">";
if(@$_SERVER['HTTP_REFERER'] == "https://library.wit.ie/Services/reading-lists"){  // only if we are coming from the website.	
	echo "<img style=\"float:right; cursor: pointer;\" onclick=\"$('#editloginform').toggle('fast')\" src=\"https://library.wit.ie/mod/readinglist/images/edit.png\" alt=\"Modify List\" />";
}else{ // normal
	echo "<a href=\"".base_url()."lists/fetch/module/" . $serialized . "\" target=\"_blank\" style=\"text-decoration: none; color: black; cursor: pointer;\"><img style=\"float:right\" src=\"https://library.wit.ie/mod/readinglist/images/edit.png\" alt=\"Modify List\" /></a>";
}
echo "<em>Resources Available from WIT Libraries for:</em><br /><strong>" . $module_title . "</strong>";
	//phpinfo();
	if(@$_SERVER['HTTP_REFERER'] == "https://library.wit.ie/Services/reading-lists"){  // only if we are coming from the website.
		
	echo "<div id=\"editloginform\"><br/><form class=\"nicebox\" action=\"/".$this->config->item('path')."lists/authorise/\" method=\"post\" id=\"loginform\">
      <a onclick=\"javascript:document.getElementById('loginform').submit()\" class=\"button\"><span><strong>staff login</strong></span></a>
      <input type=\"text\" onclick=\"this.value='';\" value=\"username\" name=\"username\">
      <input type=\"password\" onclick=\"this.value='';\" value=\"password\" name=\"password\" pwfprops=\",\">
      <input type=\"hidden\" value=\"".base_url()."lists/fetch/module/" . $serialized . "\" name=\"destination_url\" />
		<input type=\"submit\" value=\"submit\" /></form></div>";
		
		//print("<div style=\"border: dashed 1px black; background-color: #b0b0b0\"><pre>");		print_r($this->session->userdata);  		print("</pre></div>");
	}else{
	}
	
if($username == 'STUDENT'){
	// STUDENT
	$getintouch = "<h2>There is no list for this module right now - ask your lecturer about it</h2>
		<p>WIT Libraries have an extensive range of materials available in the library and through 
		online subscription-based resources, which can be directly linked to, via the ".$this->config->item('instancename')." module.</p>
		
		<p>This readinglist module gives you a <em>convenient</em> way of accessing the wide variety of 
		resources available to help them with your studies.  
		<ul>
			<li><strong>Books: </strong>follow the link to see if it's out, how many copies there 
			are and when it is due back, also see reviews, previews, and recommendations</li>
			<li><strong>Journal Articles: </strong>are just a click away through through your library resource list</li>
		</ul></p>
	<p>Discuss with your lecturer what library resources you would like to see on the list.</p>
	
	<p style=\"margin: 15px; padding: 10px; border: solid 1px black; background-color: lightblue\">
	<strong>Remember:</strong> the items that you see on a readinglist are not always essential.  
	Often material is suggested to 'back up' what has has been discussed in class.  As your time 
	is a limited, be selective in deciding what you need to read.</p>";
}else{
	// NON-STUDENT (STAFF)  
	// echo "<a href=\"" .  base_url() . "index.php/lists/fetch/module/".$oldMid."/".$username."\" target=\"_blank\" style=\"text-decoration: none; color: black\">In the Library: " . $module_title . "</a>";
	
	$getintouch = "<h2>There is no list for this module</h2>
	<p>WIT Libraries have an extensive range of materials available in the library as well as online 
	subscription-based resources, which can be directly linked to, via the ".$this->config->item('instancename')." module.</p>
	<p>This readinglist module gives students a <em>convenient</em> way of accessing the wide 
	variety of resources available to help them with their studies.  
	<ul>
		<li><strong>Books: </strong>follow the link to see if it's out, how many copies 
		there are and when it is due back, also see reviews, previews, and recommendations</li>
		<li><strong>Journal Articles: </strong>are just a click away through through your library resource list</li>
	</ul></p>
	<p>Get in touch with a member of the library staff to discuss what library resources you 
	would like to see on the list.  Send an email to <a style=\"color: blue; text-decoration: 
	underline; cursor: pointer\" mailto=\"".$this->config->item('adminemail')."\">".$this->config->item('adminemail')."</a>. </p>";
}

echo "</h2><div  style=\"border-top: solid 1px #F7EAEA; margin: 20px; padding: 20px; margin-top: 0px; background-color: #F9F9F9\">";
$typecolour = array('pink', 'beige', 'yellow'); 
$typecolourborder = array('#990000', '#E0B841', '#E0B841'); 
$typeicon = array('icon_type_book.png', 'icon_type_article.png', 'icon_type_web.png'); 
$displaycount = 0;
		
$helpmessage = "";
//<div id=\"helpbox\" style=\"font-family: sans-serif; border:solid #9CF 1px; margin-bottom: 20px;\"> <h3 style=\"cursor:pointer; padding: 3px; margin: 0px;\">Help +</h3> <div id=\"helpbox_inner\" style=\"font-family: sans-serif; padding: 10px; background-color: white; display:none\" > <p><strong>This is a list of resources that are, for the most part, available from the WIT Library.</strong></p><p>Items in <span style=\"border-bottom: double 3px white; background-image: url(/mod/readinglist/images/ess_background.gif); padding 2px;\">pink</span> are essential reading materials. Most of the materials will be marked in <span style=\"border-bottom: double 3px white; background-image: url(/mod/readinglist/images/supp_background.gif); padding 2px;\">blue</span> and are merely additional readings. It is not necessary or expected that you read these; it would take up more time than you have. However, they may be useful if you want to go more deeply into a topic that you are not sure of, or are interested in.</p> <p>Consult with your lecturer to see exactly what role these materials play in your course so that you dont end up reading large amounts of material that is not very relevant to your study.</p> </div> </div>";
$rlist_items = "";
		
		

foreach ($results['results'] as $row){	

	$statsdata = array();
	$statsdata['username'] = $username;
	if($username == 'STUDENT'){
		$statsdata['usertype'] = 'STUDENT';
	}else{
		$statsdata['usertype'] = 'TEACHER';
	}
	$statsdata['list'] = $module_id;
	$statsdata['list-item'] = $row['bid'];
	$statsdata['type'] = $row['type'];
	$statsdata['source'] = 'moodle';
	$statsdata['catalogue'] = 1; // true or false
	if($row['essential'] == 'ess'){
		$statsdata['essential'] = 1;
	}else{
		$statsdata['essential'] = 0;
	}
	
	
	$title = "<strong>" . stripslashes(stripslashes($row['Title'])) . "</strong>";
	$ischecked = "";
	$displaycount++;
	if($row['type'] == 1){  // Status
		if($row['date_updated'] != ''){  // Status
			$titlestring = $title . "<span style=\"text-size: small; color: red; font-style: italic; \"> (Not In library)</span>\n";
		}else{
			$titlestring = $title . "<span style=\"text-size: small; color: orange; font-style: italic; \"> (Please check with Library to see if this is on our shelves.)</span>\n";
			if($username == 'STUDENT'){
				$ischecked = " display: none; visibility: hidden; ";  // HIDE FROM STUDENTS
				$displaycount--;
			}
		}
	}
	$titlestring = $title;
#	
	// START RENDERING READINGLIST ITEM
    $rlist_items .= "<div style=\"".$ischecked."font-family: sans-serif; padding: 1px; border-bottom: dashed 1px gray; margin-bottom: 10px;\">";
	$rlist_items .= "<span><img align=\"left\" style=\" position: relative; left: 0px; top: 0px; \" src=\"".$imagepath.$typeicon[$row['type'] - 1]."\" alt=\"".$row['type_name']."\" /></span>";  // show resource type icon.
	$rlist_items .= "<img src=\"https://library.wit.ie/mod/readinglist/images/required_".$row['essential'].".gif\" align=\"left\" alt=\"Essential Reading\" style=\"margin-right: 4px;\"/> ";		
	$rlist_items .=  $titlestring;	
	logfile("TITLESTRING: $titlestring", 'green');
	$rlist_items .= "<br/><span style=\"font-style: italic\">&nbsp;&nbsp;&nbsp;" . stripslashes($row['Author']) ." :: ". $row['Year'] ." :: ". stripslashes($row['Publisher']) . "</span><br/>";
    if($row['url'] != ''){  // if the item has a URL, then we display that..
			$statsdata['url'] = base64_encode($row['url']);
			$statsdata['catalogue'] = 0; // true or false
			$link = $this->config->item('domain').$this->config->item('path')."tracker/resource/" . base64_encode(serialize($statsdata));

		$rlist_items .= "<span style=\"color: green; font-style: italic;\">";
		$rlist_items .= "&nbsp;&nbsp;&nbsp;";
		$rlist_items .= "(Url: <a style=\"text-decoration: none; color: #90f;\" href=\"".$link."\" target=\"_blank\" style=\"font-weight: normal;\">".$row['url']."</a>)";
		$rlist_items .= "</span></br>";
		
	}else{
		$rlist_items .= "</br>";
	}
	$statsdata = array();
	if(!isset($row['notes']) || $row['notes'] == '' || $row['notes'] == NULL){  // also show notes, if present.
		// no notes;
	}else{
		// render note;
		$rlist_items .= "<span style=\"font-style: italic; background-color: #eee\">&nbsp;&nbsp;&nbsp;<strong>Note:</strong> " . $row['notes'] . "&nbsp;&nbsp;&nbsp;</span>";
	}
	$rlist_items .= "<br/></div>";
	// END RENDERING READINGLIST ITEM
}
if($displaycount == 0){
	// if there is nothing in the list or nothing to dsiplay
	print $getintouch;
}else{
	print $helpmessage;
	print $rlist_items;
}
echo "</div>";

// start the bottom 'actions' panel.
echo "<h2 style=\"margin: 20px; padding: 10px;  padding-left: 20px; background-color: #EAF1F4; margin-bottom: 0px; font-family: sans-serif\">Links</h2>";
echo "<div  style=\"border-top: solid 1px #F7EAEA; margin: 20px; padding: 20px; margin-top: 0px; background-color: #F9F9F9\">";

if($displaycount != 0){
	// if there is a list, render the endnote download image and link
	echo "<span>";
	echo "<a href=\"" . base_url() . "index.php/lists/list_books/endnote/".$module_id."/\" style=\"border:none;\">";
	echo "<img src=\"/mod/readinglist/images/actions_endnote.png\" border=\"0\" alt=\"Download List in EndNote Format\" /></a>";
	echo "</span>";
}
	// endnote download image and link
	echo "<span>";
	echo "<a href=\"http://library.wit.ie/\" style=\"border:none;\" target=\"_blank\">";
	echo "<img src=\"/mod/readinglist/images/actions_library.png\" border=\"0\" alt=\"Download List in EndNote Format\" /></a>";
	echo "</span>";

echo"</div>";
// end the bottom 'actions' panel.
?>


</div>
<?php
}
?>
<script type="text/javascript">
$('#helpbox').click(function() {
	if($('#helpbox > h3').text() == 'Help +'){
		$('#helpbox > h3').text('Help -');
	}else{
		$('#helpbox > h3').text('Help +');
	}
  	$('#helpbox_inner').slideToggle('slow', function() {
  	});
});
</script>
<style>
pre{display: none;}
</style>