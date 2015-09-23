<?php
// phpinfo();
//print "testing";
if ($_SERVER['SERVER_PORT']!=443){
	// this is to make sure that we stay on the secure port.
	$url = "https://". $_SERVER['SERVER_NAME'] . ":443".$_SERVER['REQUEST_URI'];
	header("Location: $url");
}

if(!defined($this->session->userdata('authorised')) || $this->session->userdata('authorised') != 'TRUE'){ 	  // if not logged in
	header("Location: /" . $this->config->item('path'). "lists/login");
}
	  
if(isset($sel_email)){
	$sel_email = $this->session->userdata('email');
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!-- For some inspiration:  http://www.noupe.com/php/beautiful-forms.html -->

<title>Reading List: Main</title>
<link type="text/css" href="/<?php echo($this->config->item('path')); ?>includes/style.css" rel="Stylesheet" />
<link type="text/css" href="/<?php echo($this->config->item('path')); ?>includes/buttons.css" rel="Stylesheet" />
<link type="text/css" href="/<?php echo($this->config->item('path')); ?>jquery/css/ui-lightness/jquery-ui-1.8.11.custom.css" rel="Stylesheet" />
<script type="text/javascript" src="/<?php echo($this->config->item('path')); ?>jquery/js/jquery-1.6.4.min.js"></script>
<script type="text/javascript" src="/<?php echo($this->config->item('path')); ?>jquery/js/jquery-ui-1.8.11.custom.min.js"></script>
<script type="text/javascript">

 
var Base64 = {
    // private property
    _keyStr : "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",
    // public method for encoding
    encode : function (input) {
        var output = "";
        var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
        var i = 0;
        input = Base64._utf8_encode(input);
        while (i < input.length) {
            chr1 = input.charCodeAt(i++);
            chr2 = input.charCodeAt(i++);
            chr3 = input.charCodeAt(i++);
            enc1 = chr1 >> 2;
            enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
            enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
            enc4 = chr3 & 63;
            if (isNaN(chr2)) {
                enc3 = enc4 = 64;
            } else if (isNaN(chr3)) {
                enc4 = 64;
            }
            output = output +
            this._keyStr.charAt(enc1) + this._keyStr.charAt(enc2) +
            this._keyStr.charAt(enc3) + this._keyStr.charAt(enc4);
        }
        return output;
    },
    // public method for decoding
    decode : function (input) {
        var output = "";
        var chr1, chr2, chr3;
        var enc1, enc2, enc3, enc4;
        var i = 0;
        input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");
        while (i < input.length) {
            enc1 = this._keyStr.indexOf(input.charAt(i++));
            enc2 = this._keyStr.indexOf(input.charAt(i++));
            enc3 = this._keyStr.indexOf(input.charAt(i++));
            enc4 = this._keyStr.indexOf(input.charAt(i++));

            chr1 = (enc1 << 2) | (enc2 >> 4);
            chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
            chr3 = ((enc3 & 3) << 6) | enc4;
            output = output + String.fromCharCode(chr1);
            if (enc3 != 64) {
                output = output + String.fromCharCode(chr2);
            }
            if (enc4 != 64) {
                output = output + String.fromCharCode(chr3);
            }
        }
        output = Base64._utf8_decode(output);
        return output;
    },
    // private method for UTF-8 encoding
    _utf8_encode : function (string) {
        string = string.replace(/\r\n/g,"\n");
        var utftext = "";
        for (var n = 0; n < string.length; n++) {
            var c = string.charCodeAt(n);
            if (c < 128) {
                utftext += String.fromCharCode(c);
            }
            else if((c > 127) && (c < 2048)) {
                utftext += String.fromCharCode((c >> 6) | 192);
                utftext += String.fromCharCode((c & 63) | 128);
            }
            else {
                utftext += String.fromCharCode((c >> 12) | 224);
                utftext += String.fromCharCode(((c >> 6) & 63) | 128);
                utftext += String.fromCharCode((c & 63) | 128);
            }
        }
        return utftext;
    },
    // private method for UTF-8 decoding
    _utf8_decode : function (utftext) {
        var string = "";
        var i = 0;
        var c = c1 = c2 = 0;
        while ( i < utftext.length ) {
            c = utftext.charCodeAt(i);
            if (c < 128) {
                string += String.fromCharCode(c);
                i++;
            }
            else if((c > 191) && (c < 224)) {
                c2 = utftext.charCodeAt(i+1);
                string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
                i += 2;
            }
            else {
                c2 = utftext.charCodeAt(i+1);
                c3 = utftext.charCodeAt(i+2);
                string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
                i += 3;
            }
        }
        return string;
    }
}

    // for sortable;
	var fixHelper = function(e, ui) {
		ui.children().each(function() {
			$(this).width($(this).width());
		});
		return ui;
	};
	var newSequenceToServer = function(e, ui) {
		dataArray = $(this).sortable('toArray'); 
		//alert(dataArray.length);
		for(var i = 0; i < dataArray.length; i++){
			var cla = '#'+dataArray[i];
			//alert(cla + "\nrow_"+i%2);
			$(cla).removeClass('row_0 row_1').addClass('row_'+i%2);
			//$(cla).removeclass('row_0 row_1');			
		}			
		dataString = $(this).sortable('serialize', { key: "sequence[]" }) + "&" + $('#'+dataArray[0]).attr('title'); 
		//alert(data);
		$.ajax
		({
			type: "POST", url: "/<?php echo($this->config->item('path')); ?>index.php/lists/resequencelist/", data: dataString, cache: false, success: function(html)
			{
				//$("#dept").html(html);
				//alert(html);	
			}
		});
	};
$(document).ready(function()
{		
	// populate the department dropdown...
	<?php
	if($sel_mod != 0){
		if($this->session->userdata('authorised') == 'TRUE'){	
			logfile("CURRENT USER: " . $this->session->userdata('user'));	
	
	}	?>
	
	<?php } ?>
	
	$("#mods").change(function(){	
		var id=$(this).val();	
		if(id == 0){ //if we change it to zero we must hide the books.			
				$("#booklist").html(' ');
		}else{
			showModuleList('active');	$("#backtolistview").hide();	$("#viewdeleted").show();	
		}
		is_linked();
		set_module_title();
	});	// shows active
	$('#viewactive').click(function(){		
		showModuleList('active');	$("#backtolistview").hide();	$("#viewdeleted").show();
	}); 	// shows active
	$('#backtolistview').click(function(){		
		showModuleList('active');	$("#backtolistview").hide();	$("#viewdeleted").show();	
	}); 	// shows active
	$('#viewdeleted').live('click', function(){		
		showModuleList('deleted');	$("#backtolistview").show();	$("#viewdeleted").hide();	
	}); 	// shows deleted

	function showModuleList(deleted){  // deleted can be 'active' or 'deleted', depending on whether we want to see the current (active) or the basket (deleted) list.
		
		var module=$("#mods").val();
		var url = "index.php/lists/list_books/" + deleted + "/" + module + "/" + Date.UTC() + "/";
		url = '<?php echo base_url(); ?>' + url;
		
		//window.location = url;
		$.ajax
		({
			type: "POST", url: url, data: '', cache: false, success: function(html)
			{
				$("#editbook").fadeOut("slow");
				$("#booklist").html(' ');
				$("#booklist").html(html);
				$("#booklist").fadeIn("slow");
			}
		});
		//hasDeletedItems();
	}
	
	$('.check_catalogue').live('click', function(){
		//var myvalue = $(this).html().replace(/^\s+|\s+$/g, '');  //gets the string value of the button
		var val = $(this).attr('value');
		var type = $(this).attr('title');
		var mid = $("#mods").val();
		var pleasewait = '<div align=\"center\">please wait ...<br/><img src="/<?php echo($this->config->item('path')); ?>img/ajax-loader.gif" /></div>';
		
		// this is the standard 'edit book' form that appears on the left
		var url = '<?php echo($this->config->item('base_url')); ?>index.php/lists/edit_book/';
		var postdata = "action=update&bid="+val+"&mid="+mid;
		
		// this is what appears on the right - either search results or a web page in an iframe.
		var url_wit_search_results = '<?php echo($this->config->item('base_url')); ?>index.php/lists/library/' + $(this).attr('value'); 
		var search_postdata = "source=witcat&bid=bookID_"+val+"&type="+type;
		url_edit_webpage_postdata = Base64.encode(search_postdata);
		var url_webpage_edit = '<?php echo($this->config->item('domain')); ?>index.php/lists/proxy/'+url_edit_webpage_postdata+"/3/"; 
		
		/* 	where attr.('value') takes this form: '4551/update/';
			depending on which function the value is processed differently.  In the first ajax call
			the numeric book id is used to retrieve specific book data
			to populate the edit form.  In the second call the number is used to retrieve the 
			book title, which is then used to search the local copy of the library database.
		*/
		//alert('value: ' +$(this).attr('value') +'\n');
		if('<?php echo($this->session->userdata('authorised')); ?>' == 'TRUE'){
			$("#booklist").hide();
			$(".search_results_container").html(pleasewait);
			$(".book_edit_form_container").html(' ');
			$("#editbook").show();  // show the entire pane, including the 'please wait'.
			$("#backtolistview").show();  // show the button of the same title
			$.ajax({
				type: "POST", url: url, data: postdata, cache: false, success: function(html){
					$(".book_edit_form_container").html(html);
					// the edit book form
					//prompt(html);
				}
			});
			if(type == 3){
						
						var iframe = '<h1 class="legend">These are the details on record for this book: </h1> <div class="fieldset" style="padding-left: 50px;">';
						var iframe = iframe + '<br/><iframe src="' + url_webpage_edit + '" width="100%" height="700px"></iframe>';
						
						
						var iframe = iframe + ' <br/>Use this box to search the WIT catalogue for the book you want. <br/> <br/> <br/>';
						var iframe = iframe + '<div style="border: transparent; height: 48px; background: url(/<?php echo($this->config->item('path')); ?>img/witcat_tab.gif) ';
						var iframe = iframe + 'left top no-repeat; padding-top: 24px;">';
						var iframe = iframe + '<input type="text" style="background-color: #FF6; border: 1px solid saddlebrown; border-left: none; height:24px; ';
						var iframe = iframe + 'padding-left: 0px; float: left; position:relative; left:15px; padding-right: 10px;" id="searchcat_input" size="60"';
						var iframe = iframe + ' contenteditable="true" value="" />';
						var iframe = iframe + '<a class="button" onClick="return false;">';
						var iframe = iframe + '<span id="searchcat" class="butonspan" onMouseOver="this.style.cursor=\'pointer\'">';
						var iframe = iframe + '&nbsp;&nbsp;&nbsp;&nbsp;SEARCH</span></a>';
						var iframe = iframe + '<br/> <br/> <br/> </div><br/> <br/> <br/> </div>';
						
						
						
						$(".search_results_container").html(iframe);
						
						
			}else{
				// iframe
				$.ajax({
					type: "POST", url: url_wit_search_results, data: search_postdata, cache: false, success: function(html){
						$(".search_results_container").html(html);
						//alert('html2: ');
						// the search results
					}
				});
			}
		}
		//alert('fadein');
	});
	// call this before this funciton on the button
	//onclick=\'$(\"#booklist\").hide(); $(\"#editbook\").show(); $(\"#backtolistview\").show();\'
	
	$('a.delete_restore').live('click', function(){
		var url = '/<?php echo($this->config->item('path')); ?>lists/edit_book/';
		var deldata = $(this).attr('value');
		var colour = '#FF0000'; 
		var txt = '<span>delete</span>';
		var m = /undelete/ig;
		if(String(deldata).match(m)){var colour = '#00FF00'; var txt = '<span>restore</span>';}
		$(this).html(txt);
		$(this).parent().parent().fadeOut("slow");
		$.ajax({
			type: "POST", url: url, data: deldata, cache: false, success: function(html){
				//$("#editbook").html(html);
				//alert(url + "\n" + deldata + "\n-------------------\n" + html);
			}
		});
		//alert('checking delted');
		//hasDeletedItems();
		//hasDeletedItems();
		
	});
 

	$('a#addnewbook').live('click', function(){
		addnewbook('new');
	});
	
	
	$("#staffemail").change(function()
	{
		// hide staffemail dropdown
		var dataString = "staffemail=" + $(this).val();
		
					//alert(/<?php echo($this->config->item('path')); ?>);
		$.ajax
			({
				type: "POST", url: "<?php echo base_url(); ?>index.php/lists/list_staff_modules/", data: dataString, cache: false, success: function(html)
				{
					$("#staffmodules").html(html);
				}
		});		
		//window.location.href = window.location;
	});
	
	$("#staffmodules").change(function()
	{
		// hide staffemail dropdown
		var dataString = "mid=" + $(this).val();
		//alert(dataString);
		$.ajax
			({
				type: "POST", url: "/<?php echo($this->config->item('path')); ?>lists/goto_staff_module/", data: dataString, cache: false, success: function(html)
				{
					//alert(html);
					//$("#staffmodules").html(html);
					window.location.href = "<?php echo($this->config->item('domain')); ?>lists/";
				}
		});		
		
	});
	$("#pdflink").click(function()
	{
		var url = "/includes/writepdf.php?email=" + $("#staffemail").val();
		var windowname = "pdfwindow";
		window.open(url, windowname);
	});
	
	$("#link_unlink").click(function()
	{	
		action = $(this).html();
		staffemail = $("#staffemail").val();
		mid = $("#mods").val();
		var dataString = "mid=" + mid + "&staffemail=" + staffemail + "&action=" + action;
		var urlstring = "<?php echo($this->config->item('domain')); ?>lists/link_staff_to_module/";
		//alert(urlstring + "?" + dataString);
		
		//if(confirm('you are about to link or unlink the lecturer from the module below. Do you really want to do this?')){
		$.ajax({
			type: "POST", url: urlstring, data: dataString, cache: false, success: function(html)
			{	
				//alert("Linked?: " + html);
				$("#link_unlink").html(html);
			}
		});
		if(action == 'link'){
			alert("You have made " + staffemail + " a teacher on this module");
		}else{
			alert("You have removed " + staffemail + " as a teacher from this module");
		}
			
	});
	
	
			
		
		//div_form.style = "border: solid 1px black; background-color: white;";
	});
	
				
	
	function is_linked(){
		var dataString = "mid=" + $("#mods").val() + "&staffemail=" + $("#staffemail").val();
		$.ajax
			({
				type: "POST", url: "/<?php echo($this->config->item('path')); ?>lists/is_linked/", data: dataString, cache: false, success: function(html)
				{
					$("#link_unlink").html(html);
				}
		});
	}
	
	$("#change_module_title").click(function()
	{
		var dataString = "mid=" + $("#mods").val() + "&newtitle=" + $("#module_title").html();
		$.ajax
			({
				type: "POST", url: "/<?php echo($this->config->item('path')); ?>lists/change_module_title/", data: dataString, cache: false, success: function(html)
				{
					//alert(html);
					$("#link_unlink").html(html);
				}
		});
	});
	function set_module_title(){
		//take title from modules dropdown and assign it to the div at the top of the modules list;
		var moduletitle = $("#mods option:selected").text();
		//alert(moduletitle);
		$("#module_title").html(moduletitle);
	}

	
		
	is_linked();
	set_module_title();
	
	});
});


//$("#copytoicon").live("click", function(){
//});
</script>
</head>

<body >
<header> </header>
<br/>

<!-- <h1>Don't touch the lists today<br/> - making some adjustments; <br/>(22nd June 2011).</h1> -->

<?php

if(base_url() != $this->config->item('domain')){
#if($_SERVER['HTTP_REFERER'] == "https://library.wit.ie/Services/reading-lists"){
		//print("<div style=\"border: dashed 1px black; background-color: #b0b0b0\"><pre>");		print_r($this->session->userdata);  		print("</pre></div>");
}

?>

<div id="container">
  <div id="list">
  	<h1 class="legend">Your Account</h1>
    <div class="fieldset"><br/>
      <?php 
  if(!isset($viewonly)){ 
	?>
      <form  id="loginform" method='post' class='nicebox'>
        <a class="button" href="/<?php echo($this->config->item('path')); ?>lists/logout/"><span><strong>Log OUT: <?php echo($this->session->userdata('user')); ?></strong></span></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a id="backtolistview" class="button_light" style="display: none; float: right; margin-left: 20px;"><span><img src="/<?php echo($this->config->item('path')); ?>img/buttons/readinglist.png"  align="left"/>Return To Reading List</span></a>
        <?php if($this->session->userdata('authorised') == 'TRUE'){	?>
        <a id="viewdeleted" class="button_light" style="display: none; float:right;"><span><img src="/<?php echo($this->config->item('path')); ?>img/buttons/blue_recycle_bi_full.png"  align="left"/>waste basket</span></a>
        <?php 
	 } 
	}
	
	
	  $linked = 0;
	if($this->session->userdata('user')){	  ?>
        Staff Member:
        <select name="staffemail" id="staffemail">
          <option class="default-selected" value="0">NOT SET.</option>
          <?php				
					foreach($emails as $result){
						if(isset($sel_email) & strtolower(trim($sel_email)) == strtolower(trim($result['email']))){	// staff emails	
							print("\t\t\t\t<option value=\"".$result['email']."\" selected=\"selected\">". strtoupper($result['lastname']).", ". $result['firstname']."</option>\n");	
						}else{
							print("\t\t\t\t<option value=\"".$result['email']."\" >". strtoupper($result['lastname'])." ". $result['firstname']."</option>\n");
						}
					}				
		?>
        </select>
        Modules:
        <select name="staffmodules" id="staffmodules">
          <?php 
	  if(isset($selected_module_dropdown)){  // if there is a staff email selected, this gets generated.
		print("\t\t\t\t<option value=\"0\" >NOT SELECTED:</option>\n");
		foreach($selected_module_dropdown as $result){
			if($sel_mod == $result['mid']){
				$linked = 1;
				print("\t\t\t\t<option value=\"".$result['mid']."\" selected=\"selected\">" . $result['sname'] . " => " . $result['dname'] . " => " . $result['modulename'] . "</option>\n");
			}else{
				print("\t\t\t\t<option value=\"".$result['mid']."\" >" . $result['sname'] . " => " . $result['dname'] . " => " . $result['modulename'] . "</option>\n");
			}
		}
	  }
	?>
          </span>
        </select>
        <?php
   } # end if($this->session->userdata('user')){
   ?>
        <br/>
        <br/>
        <?php 
		if($this->session->userdata('authorised') == 'TRUE' && $this->session->userdata('admin') == 'TRUE'){
			echo('<div style="">');
		}else{
			echo('<div style="border:solid 1px red; height: 2px; visibility: hidden">');
		}
			
	?>
        <?php  
	  if($this->session->userdata('authorised') == 'TRUE'){	
	  	if($linked == 1){
			print("<button id=\"link_unlink\" style=\"cursor: pointer\" name=\"link_unlink\">unlink</button>");
		}else{
			print("<button id=\"link_unlink\" style=\"cursor: pointer\" name=\"link_unlink\">link</button>");
		}
		print("<button id=\"pdflink\" style=\"cursor: pointer\" name=\"link_unlink\">Generate PDF</button>");
		print("<button id=\"duplicate_list_to\" style=\"cursor: pointer\" name=\"duplicate_list_to\">Duplicate list to..</button>");
		print("<a href=\"".$this->config->item('path')."admin\" target=\"_blank\">Reports</a>");
	}
	echo('</div>');
	  ?>
      </form>
      <?php 
		if($this->session->userdata('authorised') == 'TRUE' && $this->session->userdata('admin') == 'TRUE'){
			echo('<div style="">');
		}else{
			# echo('<div style="border:solid 1px red; height: 2px; visibility: hidden">');
			echo('<div style="">');
			# we normally hide the dropdown from non-admin or casual visitors, to avoid
			# them interfering with other courses.
		}
			
	?>
      <form name="dropdowns" id="dropdowns" style="display: block" >
      
      <!--- dropdowns for hierarchy --->
      
        <select name="school" class="multidropdown" id="school">
          <?php 
					$options = array('CHOOSE ONE','Humanities','Health Sciences','Engineering', 'Science', 'Education', 'Business', 'Other');
					for($i = 0; $i < count($options); $i++){  	// add the schools - this is hard coded as there are so few.
						if($sel_sch == $i){						// try and stick at the last remembered school (stored in session variable)
							print("\t\t\t\t<option value=\"".$i."\" selected=\"selected\"  class=\"default-selected\">".$options[$i]."</option>\n");
						}else{									// if no school ID in session variable, just default to value zero.
							print("\t\t\t\t<option value=\"".$i."\">".$options[$i]."</option>\n");
						}
					}
		?>
        </select>
        <select name="dept" id="dept">
          <!-- option selected="selected" class="default-selected" value="0">CHOOSE A DEPARTMENT</option -->
          <?php
					if($sel_sch != 0){	// add the departments if school is selected
						foreach($list_dep as $result){	print("\t\t\t\t<option value=\"".$result[0]."\" ".$result[1] . " >". $result[2]."</option>\n");	}						
					}				
		?>
        </select>
        
        <!--- dropdowns for hierarchy --->
        
        
        <?php
		#print"<pre>";
		##print_r($list_mod);
		#p#rint"</pre>";
        ?>
        <select name="mods" id="mods">
          <!-- option selected="selected" class="default-selected" value="0">CHOOSE A MODULE</option -->
          <?php
					if($sel_dep != 0){	// add the modules if department is selected
						foreach($list_mod as $result){
							/** 
							where 
							result[0] = mid
							result[1] = html for styling and select
							result[2] = module name
							result[3] = email array		
							
							What is happening here is that the background colour is being changed according to what 
							data we have in the modules.
							
							Red: - currently selected lecturer's module
							Pink: - other lecturers associated with module
							Grey: - no lecturer/module association in the $this->config->item('instancename') database.				
							**/
							if(isset($result[3])){
								$modcount = count($result[3]);
							}else{
								$modcount = 0;  // there isn't always an email array.
							}
							if(isset($sel_email)){
								if(isset($result[3]) && in_array($sel_email, $result[3])){
									$highlight = " style=\"background-color: #f66; font-weight: bold\" ";	// Red
								if($modcount == 2){
									$bracket = "[".($modcount - 1)." other teacher]";
								}else{		
									$bracket = "[".($modcount - 1)." other teachers]";
								}
								}elseif($modcount == 0){
									$highlight = " style=\"background-color: #ddd; font-weight: normal\" ";	 // Grey
									$bracket = "";
								}else{
									$highlight = " class=\"dropdown_pink\" "; // Pink
									if($modcount == 1){
										$bracket = "[".$modcount." teacher]";
									}else{
										$bracket = "[".$modcount." teachers]";
									}
										
								}									
							}
							
							if($sel_mod == $result[0]){
								print("\t\t\t\t<option value=\"".$result[0]."\" ".$highlight." selected=\"selected\" >".$result[2]."  \t".$bracket."</option>\n");	
							}else{
								print("\t\t\t\t<option value=\"".$result[0]."\" ".$highlight." >".$result[2]."  \t".$bracket."</option>\n");	
							}
						}
					}	
										
		?>
        </select>
        <?php 
		//print"<pre>"; print_r($list_mod); print"</pre>";
		?>
      </form>
      <?php 
		echo('</div>');
	?>
      <!-- <button id="addnewbook" class="buttonspan"  type="button"style="display: none;">Add new Book</button> --> 
    </div>
    <!-- end fieldset --> 
  </div>
  <div id="booklist" style="display: none;"> d</div>
  <div id="editbook" style="display: none;"> <a name="editbook"> </a>
    <div>
      <table >
        <tr>
          <td  style="vertical-align:top"><div class="book_edit_form_container"> 
              
              <!-- edit book form --> 
              
            </div></td>
          <td  style="vertical-align:top"><div id="showcat" class="search_results_container"> 
              
              <!-- catalogue results --> 
              
            </div></td>
        </tr>
      </table>
      <br/>
      <br/>
    </div>
    <!-- search  forms -->
    <div id="modal_form" title="Search for Books"><br/>
      <strong style="background-color:#FF6; text-decoration:underline;">Yellow Box:</strong> Quick search of WIT: - <br/>
      <em>&nbsp; <strong>C</strong>heck for the book you want.</em><br/>
      <br/>
      <div style="border: transparent; height: 48px; background: url(/<?php echo($this->config->item('path')); ?>img/witcat_tab.gif) left top no-repeat; padding-top: 24px;">
        <input type="text" 
        style="background-color: #FF6; border: 1px solid saddlebrown; border-left: none; height:24px; padding-left: 0px; float: left; position:relative; left:15px; padding-right: 10px;"
        id="searchcat_input" size="60" contenteditable="true" value="" />
        <a class="button" onClick="return false;"><span id="searchcat" class="butonspan" onMouseOver="this.style.cursor='pointer'">&nbsp;&nbsp;&nbsp;&nbsp;SEARCH</span></a> </div>
      <strong style="background-color:#FCF; text-decoration:underline;">Pink Box:</strong> We don't have the book: - <br/>
      <em>&nbsp; <strong>H</strong>elp us by getting the correct details elsewhere</em><br/>
      <br/>
      <div style="border: transparent; height: 30px; background: url(/<?php echo($this->config->item('path')); ?>img/copac_tab.gif) left top no-repeat; padding-top: 24px;">
        <input type="text" 
        style="background-color: #FFCCFF; border: 1px solid saddlebrown; border-left: none; height:24px; padding-left: 0px; float: left; position:relative; left:15px; padding-right: 10px;"
        id="searchcopac_input" size="60" value="" />
        <a class="button" onClick="return false;"><span id="searchCOPAC" class="buttonspan" onMouseOver="this.style.cursor='pointer'">&nbsp;&nbsp;&nbsp;&nbsp;SEARCH</span></a> </div>
      &nbsp; &nbsp; <strong style="background-color:#FCF; text-decoration:underline;">Choose other source => </strong> &nbsp; &nbsp;
      <select id="search_source" name="search_source">
        <option value="copac" selected="selected">COPAC</option>
        <option value="google">Google Books</option>
        <option value="isbndb">ISBNdb (searches by ISBN)</option>
       <!--<option value="z39wit">Z39.50 TCD Library</option>-->
      </select>
      <br/>
      <br/>
      <div style="padding: 4px; background-color: ivory; margin: 4px; margin-right:30px; margin-left:30px;"><strong style="text-decoration:underline">Note:</strong> If you are having trouble, change the keywords around.</div>
    </div>
    <!-- end search forms --> 
  </div>
  <br/>
</div>xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
<footer>dddddddddddddddddddddd
dddddddddddddddddddddddddddddddddddddddddddddddd</footer>
</body>
</html>
