<?php
header("Content-type: text/html");
if($module_title != 'NO_DATA'){
?>
<div class="readinglist_html">
<?php 

echo "<!-- <h2 style=\"margin-top: 20px; padding: 10px; background-color: #EAF1F4; padding-left: 20px; margin-bottom: 0px; font-family: sans-serif; s\">";
if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == "https://library.wit.ie/Services/reading-lists"){  // only if we are coming from the website.	
	echo "<img style=\"float:right; cursor: pointer;\" onclick=\"$('#editloginform').toggle('fast')\" src=\"https://library.wit.ie/mod/readinglist/images/edit.png\" alt=\"Modify List\" />";
}else{ // normal
	echo "<a href=\"".base_url()."lists/fetch/module/" . $rlists_id . "/" . $username . "\" target=\"_blank\" style=\"text-decoration: none; color: black; cursor: pointer;\"><img style=\"float:right\" src=\"https://library.wit.ie/mod/readinglist/images/edit.png\" alt=\"Modify List\" /></a>";
}

echo "<em>Resources Available from WIT Libraries for:</em><br /><strong>" . $module_title . "</strong></h2> -->";
	// phpinfo();
	if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] == "".$this->config->item('domain')."Services/readinglists"){  // only if we are coming from the website.
		
	echo "<div id=\"editloginform\"><br/><form class=\"nicebox\" action=\"/".$this->config->item('path')."lists/authorise/\" method=\"post\" id=\"loginform\" target=\"_new\">
      <a onclick=\"javascript:document.getElementById('loginform').submit()\" class=\"button\"><span><strong>staff login</strong></span></a>
      <input type=\"text\" onclick=\"this.value='';\" value=\"username\" name=\"username\">
      <input type=\"password\" onclick=\"this.value='';\" value=\"password\" name=\"password\" pwfprops=\",\">
      <input type=\"hidden\" value=\"".base_url()."lists/fetch/module/" . $rlists_id . "/" . $username . "\" name=\"destination_url\" />
		<input type=\"submit\" value=\"submit\" /></form></div>";
		//print("<div style=\"border: dashed 1px black; background-color: #b0b0b0\"><pre>");		print_r($this->session->userdata);  		print("</pre></div>");
	}else{
	}
	
$helpmessage = "<div id=\"helpbox_inner\" style=\"font-family: sans-serif; padding: 10px; background-color: white; display:none\" onclick=\"$(this).hide('slow');\" > <p><strong>This is a list of resources that are, for the most part, available from the WIT Library.</strong></p>
<p>Items marked with <img src=\"/mod/readinglist/images/required_ess.gif\" alt=\"core\" align=\"absmiddle\" />  are essential reading materials. The remaining  materials are marked with additional readings, marked with a <img src=\"/mod/readinglist/images/required_supp.gif\" alt=\"supplimentary\" align=\"absmiddle\" />. It is not necessary or expected that you read these; it would take up more time than you have. However, they may be useful if you want to go more deeply into a topic that you are not sure of, or are interested in.</p> 
<p>Consult with your lecturer to see exactly what role these materials play in your course so that you dont end up reading large amounts of material that is not very relevant to your study.</p>
<p>If your readinglist is not here, it is because it has not been created yet by your lecturer. Try getting in touch with them, or with a member of the library staff.</p>
</div>";	
$nolisthelpmessage = "<div id=\"helpbox_inner\" style=\"font-family: sans-serif; padding: 10px; background-color: white; display:none\" onclick=\"$(this).hide('slow');\" > <h2>How to get your list:</h2>
<p>There's no list here because it's either not been created at all or it does exist, but has no valid items in it.</p> 
<p>Ask your lecturer about it, or get in touch with us in the library at libinfo@wit.ie</p>
<p>Through your reading list, you can click straight through to e-books, journal articles and websites recommended by your lecturer.  If we have the books on our shelves, a single click can bring you to the library record for the book where you can check its availability and physical location.</p>
</div>";
$getintouch = $nolisthelpmessage;
if($username == 'STUDENT'){
	// STUDENT
	$getintouch .= "<h2>There is no list for this module right now - ask your lecturer about it</h2>
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
	
	$getintouch .= "<h2>There is no list for this module</h2>
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

echo "<div  style=\"margin: 0px; padding: 20px; margin-top: 0px; \">";
$typecolour = array('pink', 'beige', 'yellow'); 
$typecolourborder = array('#990000', '#E0B841', '#E0B841'); 
$typeicon = array('icon_type_book.png', 'icon_type_article.png', 'icon_type_web.png'); 
$displaycount = 0;
		
$rlist_items = "";

		echo"<br clear=\"both\" />";
foreach ($results['results'] as $row){	
	$title = "<strong>" . stripslashes(stripslashes($row['Title'])) . "</strong>";
	$ischecked = "";
	$displaycount++;	
    if($row['libid'] == ''){  // if it is not in the library catalogue
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
    }else{ // if it is in the library catalogue, then make the title a link.
	    $titlestring = "<a target=\"_blank\" href=\"http://witcat.wit.ie/record=".substr($row['libid'], 0,8)."~S0\">" . $title . "</a>";
    }
	
	// START RENDERING READINGLIST ITEM
    $rlist_items .= "<div style=\"".$ischecked."font-family: sans-serif; padding: 1px; min-height: 60px; background-color: transparent; border-bottom: double 3px white; border: solid 1px white; \">";
	
	//$rlist_items .= "<img src=\"/mod/readinglist/images/".$row['essential']."_text.png\" align=\"right\" alt=\"Essential Reading\"/>";		
	$rlist_items .= "<span><img align=\"left\" style=\" position: relative; \" width=\"72px\" height=\"60px\" src=\"/mod/readinglist/images/".$typeicon[$row['type'] - 1]."\" alt=\"".$row['type_name']."\" /></span>";  // show resource type icon.
	$rlist_items .= "<img src=\"/mod/readinglist/images/required_".$row['essential'].".gif\" alt=\"".$row['essential']."\" align=\"absmiddle\" style=\"margin-top: 5px;\" /> "; 
	$rlist_items .=  $titlestring;	
	$rlist_items .= "<br/><span style=\"font-style: italic\">&nbsp;&nbsp;&nbsp;" . stripslashes($row['Author']) ." :: ". $row['Year'] ." :: ". stripslashes($row['Publisher']) . "</span><br/>";
    if($row['url'] != ''){  // if the item has a URL, then we display that..
		$rlist_items .= "<span style=\"color: black; \">";
		$rlist_items .= "&nbsp;&nbsp;&nbsp;[online: ";
		$rlist_items .= "<a style=\"text-decoration: none; color: #44f;\" href=\"".$row['url']."\" target=\"_blank\" style=\"font-weight: normal;\">".$row['url']."";
		$rlist_items .= "</a>]</span><br/>";
	}
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
echo "<div  style=\"margin-top: 0px; \">";
if($displaycount != 0){
	// if there is a list, render the endnote download image and link
	echo "<span style='padding:10px;'>";
	echo "<a href=\"" . base_url() . "index.php/lists/list_books/endnote/".$module_id."/\" style=\"border:none;\">";
	echo "<img src=\"/mod/readinglist/images/actions_endnote.png\" border=\"0\" alt=\"Download List in EndNote Format\" /></a>";
	echo "</span>";
	
	// Back to Moodle
	echo "<span style='padding:10px;'>";
	echo "<a href=\"https://vle.wit.ie/course/view.php?id=".$oldMid."\" target=\"_new\">";
	echo "<img src=\"/mod/readinglist/images/actions_moodle.png\" border=\"0\" alt=\"Link to this module in Moodle\" /></a>";
	echo "</span>";

}

echo"</div>";
// end the bottom 'actions' panel.
?>


</div>
<?php
}
?>
<script type="text/javascript">

$('#helpbox_trigger').click(function() {
	//display = $('#helpbox_inner').css('display');
  	//if(display == 'none'){
	//	$('#helpbox_trigger').attr('src', '/mod/readinglist/images/help_close.png');
  		$('#helpbox_inner').show('slow');
  		//$('#helpbox_inner').stop();
	//}else{
	//	$('#helpbox_trigger').attr('src', '/mod/readinglist/images/help_plus.png');
  	//	$('#helpbox_inner').hide('slow');
	//}
	//return false;
});
</script>