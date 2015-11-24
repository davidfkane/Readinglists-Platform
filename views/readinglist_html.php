<?php
$editessential = "";
$pointer = "";
//$typeicon = array('book', 'article', 'web'); 
$resultCount = count($results['results']);
$adminCount = count($module_staff);
$panelBodyColour = "#d9edf7";	
//print "Is Teaching On: " . $teacheson;
$module_title == 'NO_DATA'?$module_display_title = '<i class="fa fa-ban"></i>':$module_display_title = $module_title;

if($results['action'] == 'active'){
	$items_hidden = $itemcount - $resultCount;
	$items_visible = $resultCount;
	$items_visible == 1?$items_plural = 's':$items_plural = '';
	$deleted = 'visible';
	$panelstyle = 'info';
	$paneltype = 'primary';
	$link = 'delete';	
	$legend = "<span class=\"removeFromMobileView\">List: </span><span id=\"change_module_title\" style=\"font-style: italic; font-weight: normal;\">".stripslashes($module_display_title)."</span>";
}else{
	$items_visible = $itemcount - $resultCount;
	$items_hidden = $resultCount;
	$items_hidden == 1?$items_plural = '':$items_plural = 's';
	$deleted = 'hidden';
	$panelstyle = 'danger';
	$paneltype = 'danger';
	$link = 'undelete';
	$legend = "<span class=\"removeFromMobileView\">List: </span><span id=\"change_module_title\" style=\"font-style: italic; font-weight: normal;\">".stripslashes($module_display_title)."</span>";
	// $legend .= "<span class=\"badge\" style=\"background-color: #990000; margin-left: 10px;\"><strong><i class=\"fa fa-warning\"></i>NOW SHOWING HIDDEN ITEMS</strong></span>";
}
		
		echo "<div class=\"fieldset panel panel-".$paneltype."\" id=\"fieldset_readinglist\">\n";
		echo "<div class=\"panel-heading\"><h3 style=\"margin: 0px; font-weight:bold;\">".$legend." ";
		 

if(!isset($viewonly) && $module_title != 'NO_DATA'){   
	if($this->session->userdata('authorised') == 'TRUE'){ 	
	
	
		if($results['action'] == 'active'){  // Visible mode
				$panelBodyColour = "#d9edf7";	
				print("<a style=\"text-decoration: none; color: white;\" id=\"backtolistview\" title=\"the visible items are listed below\">");
				print("<i class=\"fa fa-eye\"></i>&nbsp;<sup><span id=\"nonDisplayedItemCount\" data-itemcount=\"$itemcount\" class=\"badge \" style=\"background-color: white; color: #428bca;\">");
				print("$items_visible</span></sup></a>&nbsp;");
				
				print("<a style=\"float: right; cursor: pointer; text-decoration: none; color: #2E618D;\" id=\"viewdeleted\" title=\"go to the hidden items for this list\">");
				print("<i class=\"fa fa-eye-slash\"></i>&nbsp;<sup><span id=\"displayedItemCount\" data-itemcount=\"$itemcount\" class=\"badge \" style=\"background-color: #2E618D; color: #428bca;\">");
				print("$items_hidden</span></sup></a>&nbsp;&nbsp;");
		
		}else{
				$panelBodyColour = "#F5E5E5";				
				print("<a style=\"text-decoration: none; color: #A94442;\" id=\"viewdeleted\"  title=\"the hidden items are listed below\">");
				print("<i class=\"fa fa-eye-slash\"></i>&nbsp;<sup><span id=\"nonDisplayedItemCount\" data-itemcount=\"$itemcount\" class=\"badge \" style=\"background-color: #A94442; color: #f2dede;\">");
				print("$items_hidden</span></sup></a>&nbsp;&nbsp;");
		
				print("<a style=\"float: right; cursor: pointer; text-decoration: none; color: #E0B2B2;\" id=\"backtolistview\" title=\"go to the visible items for this list\">");
				print("<i class=\"fa fa-eye\"></i>&nbsp;<sup><span id=\"displayedItemCount\" data-itemcount=\"$itemcount\" class=\"badge \" style=\"background-color: #E0B2B2; color: #f2dede;\">");
				print("$items_visible</span></sup></a>&nbsp;");
		}
	
	
	} 
}
		echo "</h3></div>";
		echo "<div class=\"panel-body\" style=\"background-color:$panelBodyColour; padding: 0px 4px 0px 4px;\">";
		echo "<button id=\"backtolistview\" class=\"buttonspan\"  type=\"button\" style=\"display: none;\">Back To Reading List</button>\n";
		echo "<button id=\"viewdeleted\" class=\"buttonspan\"  type=\"button\" style=\"display: none;\">View deleted items for this Module</button>\n";
		// FORM
		echo "<form id=\"booklistform\" action=\"/".$this->config->item('path')."lists/movebook/\" method=\"post\">\n";
		
		echo "<input type=\"hidden\" id=\"destinationmodule\" name=\"destinationmodule\" value=\"\" />";
		//echo "<table id=\"booklisttable\" style=\"border-collapse:collapse\">";
		?>
<?php // if no results 
if($resultCount > 0){ // show the table;
?>

<div class="table-responsive">
<table class="table table-striped" style="margin: 0px;" border="0">
<?php
		echo "<thead>\n";

## table header ... 
		echo("<!-- the table headers -->");
		echo "\t<tr>\n";
	#	if($this->session->userdata('authorised') == 'TRUE'){
	#		echo "\t\t<th ></th>\n";
	#	} 	
		echo "\t\t<th></th>\n";		
		echo "\t\t<th></th>";		
		if(($this->session->userdata('authorised') == 'TRUE' && $teacheson == 1) || $this->session->userdata('admin') == 'TRUE'){
			//echo "\t\t<th></th>\n";	
		}
		//if($results['action'] == 'active'){			echo "\t\t<th></th>\n";		};	
		if(($this->session->userdata('authorised') == 'TRUE' && $teacheson == 1) || $this->session->userdata('admin') == 'TRUE'){
		//	if($results['action'] == 'active'){echo "\t\t<th></th>\n";}
		//	else{echo "\t\t<th></th>\n\t\t<th></th>\n";}
			$editessential = "editessential ";
			$pointer = "cursor:pointer;";
		}
		
			if(($this->session->userdata('authorised') == 'TRUE' ) || $this->session->userdata('admin') == 'TRUE'){
				// MOVE
		echo "</th>\n";  // grab and move column
		echo "\t\t<th style=\"text-align: center;\"><input type=\"checkbox\" name=\"togglemove\" title=\"check this box to select all items in the list to copy to other lists\" id=\"toggleselectall\" value=\"ignorethisvalue\" /></th>\n";
			}
		echo "\t</tr>\n";
		echo "\t</thead>\n";
		
		echo "\t<tbody onmouseover=\"$(this).sortable({	helper: fixHelper, handle : '.handle', update : newSequenceToServer }).disableSelection();\">\n";
		echo("<!-- /the table headers -->");

## end table headers ... 
		$libraryroot = "http://witcat.wit.ie/record=";
		foreach ($results['results'] as $row)
		{				
			$title = stripslashes($row['Title']);
			echo "\t<tr data-title=\"mid=$module_id\"  id=\"row_".$row['bid']."\" >\n";
			echo "\t\t<td class=\"mattypeIcons\" style=\"white-space: normal\">";

			logfile("ROW_TYPE: " . $row['type_name'], 'BLUE');
			isset($typearray[strtolower($row['type_name'])])?$typename = strtolower($row['type_name']):$typename = 'unknown';
			echo "<span class=\"listAdminButtons removeFromMobileView btn btn-default\" title=\"".$typename."\" onclick=\"return false;\" style=\"color: white; margin-right: 10px; cursor: default; float: left; border: solid 1px black; padding: 6px; background-color: " . $typearray[$typename][0]." \"><i class=\"fa fa-".$typearray[$typename][2]."\"></i></span>";
			
			
			echo "<a class=\"truncated\" href=\"#\" style=\"cursor: arrow; text-decoration: none; color: black; font-weight: bold; \">$title</a>\n";
			echo "<span style=\"font-weight: 500\">".stripslashes($row['Author'])."</smaller> <em>".stripslashes($row['Publisher']).", [".$row['Year']."]</em>";
			echo "</td>\n";
			// START OF THE BUTTONS ROW 
			echo "<td class=\"\" style=\"padding-left:5px;padding-right:5px; white-space: normal; text-align: center; \"><span class=\"listAdminButtons\">";
			if(isset($row['url']) && $row['url'] != ''){  /* LINK to URL */ 
			logfile("THE URL FOR ".$title." IS: " . $row['url']);
				?><a href="<?php echo($row['url']); ?>" target="_blank"><?php //echo($row['url']); ?></a> <button title="open link in new tab" type="button" style=" vertical-align: middle; background-color: green; color: white; " onclick="window.open('<?php echo($row['url']); ?>', '_blank');" class="btn btn-default btn-circle"><i class="fa fa-link"></i></button><?php
			}
			
			/*  STATUS LOOP */
			if(($this->session->userdata('authorised') == 'TRUE' && $teacheson == 1) || $this->session->userdata('admin') == 'TRUE'){
				echo " <button type=\"button\" title=\"click to edit this item\" value=\"".$row['bid'].":".$row['type']."\" style=\" vertical-align: middle; cursor: pointer\" class=\"check_catalogue btn btn-default btn-circle\"><i class=\"fa fa-pencil-square-o\"></i></button>";
			}
			/* END STATUS LOOP */
			
			// if we have the book ID from witcat we can create a link.
			if($results['action'] == 'active'){
				if(($this->session->userdata('authorised') == 'TRUE' && $teacheson == 1) || $this->session->userdata('authorised') == 'TRUE'){
					echo " <button type=\"button\" title=\"mark as supplimentary\" value=\"".$row['bid']."\" style=\" vertical-align: middle; cursor: pointer\" class=\"editessential btn btn-default btn-circle\">";
					if($row['essential'] == 'ess'){
						echo "<i class=\"fa fa-star\"></i>";
					}else{
						echo "<i class=\"fa fa-star-o\"></i>";
					}
					echo "</button>";
					// BASKET					
					echo " <a style=\" vertical-align: middle;\" class=\"delete_restore\"  value=\"action=delete&bid=".$row['bid']."&mid=".$module_id."\" >";
					echo "<button type=\"button\" title=\"hide from view\" class=\"btn btn-default btn-circle\"><i class=\"fa fa-eye-slash\"></i></button>";
					echo "</a>";
				}
			}else{			
				if(($this->session->userdata('authorised') == 'TRUE' && $teacheson == 1) || $this->session->userdata('admin') == 'TRUE'){
					// RESTORE
					echo " <a style=\" vertical-align: middle;\" class=\"delete_restore\" value=\"action=undelete&bid=".$row['bid']."&mid=".$module_id."\" >";
					echo "<button type=\"button\" title=\"restore to view\" class=\"btn btn-default btn-circle\"><i class=\"fa fa-eye\"></i></button>";
					echo "</a>\n";
					// PURGE		
					echo " <a style=\" vertical-align: middle;\" class=\"delete_restore\" value=\"action=purge&bid=".$row['bid']."&mid=".$module_id."\" >";
					echo "<button type=\"button\" title=\"delete permanently\" class=\"delete_restore btn btn-danger btn-circle\"><i class=\"fa fa-trash-o\"></i></button>";
					echo "</a>\n";
				}
			}		
			if(($this->session->userdata('authorised') == 'TRUE' ) || $this->session->userdata('admin') == 'TRUE'){
				
				
				if($results['action'] == 'active'){		
					//echo "<i title=\"grab and drag to reorder\" class=\"handle fa fa-unsorted\" style=\" vertical-align: middle; cursor: move; padding: 10px;\"></i>\n</span></td>\n"; // reorder
					echo " <span title=\"grab and drag to reorder\" value=\"".$row['bid']."\" style=\" vertical-align: middle; cursor: move\" class=\"handle btn btn-default btn-circle\"><i class=\"fa fa-unsorted\"></i></span>";
				}
				
				
				echo "\t\t<td style=\"text-align: center;\"><input class=\"bookmove\" title=\"select this item to move it to another list\" style=\" vertical-align: middle;\" type=\"checkbox\" name=\"bookstomove[]\" value=\"".$row['bid'] ."\"  /></td>\n"; // copy
			}else{
				echo "</span></td>";
			}
			echo "\t</tr>\n";
		}
		echo "<tfoot>\n";

## table header ... 
		echo("<!-- the table footers -->");
		echo "\t<tr>\n";
		
		

		echo "\t\t<th></th>\n";		
		echo "\t\t<th></th>";		
		if(($this->session->userdata('authorised') == 'TRUE' && $teacheson == 1) || $this->session->userdata('admin') == 'TRUE'){
			//echo "\t\t<th></th>\n";	
		}
		//if($results['action'] == 'active'){			echo "\t\t<th></th>\n";		};	
		if(($this->session->userdata('authorised') == 'TRUE' && $teacheson == 1) || $this->session->userdata('admin') == 'TRUE'){
		//	if($results['action'] == 'active'){echo "\t\t<th></th>\n";}
		//	else{echo "\t\t<th></th>\n\t\t<th></th>\n";}
			$editessential = "editessential ";
			$pointer = "cursor:pointer;";
		}
		if(($this->session->userdata('authorised') == 'TRUE' ) || $this->session->userdata('admin') == 'TRUE'){
				// MOVE
		echo "</th>\n";  // grab and move column
		echo "\t\t<th style=\"text-align: center;\"><span class=\"btn btn-default btn-circle\" title=\"click me to move selected items to another list\" data-toggle=\"modal\" data-target=\"#myModal\" id=\"moveSelectedItems\"><i class=\"fa fa-copy\"></i></span></th>\n";
			}
			echo "\t</tr>\n";
			echo "\t</tfoot>\n";
			
			echo "\t</tbody></table></div>\n";
		}else{
			if($module_title != 'NO_DATA'){  // module exists but no visible results;
				$deleted != 'hidden'?$inlinestyle = 'style="background-color:#b0D4DD; border-color:#90B9DC; margin: 0px 20px 20px 20px;"':$inlinestyle = 'style="margin: 0px 20px 20px 20px;"';
				echo("<br/><div class=\"alert alert-$panelstyle\" $inlinestyle><i class=\"fa fa-info-circle\"></i> There are no $deleted items.</div>");
			}else{  // module deleted;
				echo("<br/><div class=\"alert alert-warning\"><i class=\"fa fa-danger\"></i> This list no longer exists.</div>");
			}
		}
		echo "</div><div class=\"panel-footer\" style=\"padding-top: 25px;\">";
?>
<!-- /.table-responsive -->

<?php	if($module_title != 'NO_DATA'){  ?>
  <div class="row">
<div class="col-md-4">
<?php		if($module_type != 2){ ?>

<div class="panel panel-<?php echo($panelstyle); ?>">
    <div class="panel-heading">
       <h4><i class="fa fa-users"></i> List Administrators<?php if(count($adminCount) > 1){echo('s');} ?></h4>
    </div>
    <div class="panel-body">
  <div class="table-responsive">
    <table class="table" style="margin-bottom: 0px;" id="listAdminsTable">
      <tbody>
        <?php
	
			if($adminCount > 0){
				foreach($module_staff as $staff){
					echo "\t<tr >\n";
					if($staff['admin'] != 0){
						echo "\t\t<td ><strong>".$staff['firstname']." ".$staff['lastname']."</strong><br/><small>".$staff['email']."</small></td>\n";
					}else{
						// list pending items here;
						echo "\t\t<td style=\"vertical-align: middle\"><button title=\"uninvite this user\" type=\"button\" value=\"".md5(strtolower($staff['email']) . $module_id)."\" class=\"uninviteAdminButton btn btn-default btn-circle\" style=\"float: right;\"><i class=\"fa fa-trash-o\"></i></button><strong style=\"color: #808080; text-decoration: italic;\">INVITE&nbsp;SENT</strong><br/><small>".$staff['email']."</small></td>\n";
						
					}
					echo "\t</tr>\n";
				}
			}else{		
				echo "\t\t<td >No Administrators Assigned</td>\n";
				echo "\t</tr>\n";
				echo "\t<tr>\n\t\t<th> &nbsp; </th>\n\t</tr>";
			}
			echo "<tr class=\"addAuthorButtonTR\"><td colspan=\"2\"><button type=\"button\"  data-toggle=\"modal\" data-target=\"#addPendingUserModal\" class=\"inviteModuleAdmin btn btn-primary btn-xs\"><i class=\"fa fa-\"></i>Add</button></td></tr>";
	?>
    
    
      </tbody>
    </table>
  </div>
  <!-- /.table-responsive --> 
	</div>

	
    <!--<div class="panel-footer"></div>-->
</div>

<?php }	?>
</div>
<div class="col-md-8">
<div class="panel panel-<?php echo($panelstyle); ?>">
<?php	if($module_type != 3 && $module_type != 2){ // if this is a moodle module (type 1) ?>
    <div class="panel-heading"><h4><i class="fa fa-bar-chart-o"></i> List Statistics</h4></div>
    <div class="panel-body" style="border:"><div id="myfirstchart" style="height: 250px;"></div></div>
<?php }else{
		if($module_type == 3){	// module catalogue	?>
    <div class="panel-heading"><h4>Module Catalogue</h4></div>
    <div class="panel-body" style="border:">
    	<p>This is a readinglist generated by the module catalogue.  You can edit it just like any other list.  It is synchronised with the module catalogue.  Edits made here get transferred to the module catalogue descriptor.</p>
    </div>
<?php }elseif($module_type == 2){ // INBOX  ?>	  		
    <div class="panel-heading"><h4>Your INBOX</h4></div>
    <div class="panel-body" style="border:"><p>This is your INBOX.  It is the place where you can most easily add items from your browsing sessions on the web.</p>
    <p>The best way to use the INBOX is to browse the web using a bookmarklet.  If you do not have the bookmarklet installed, you can add it by dragging the button below, and drop it into the bookmarks toolbar on your browser.  The bookmarks toolbar is the place where your web shortcuts appear.</p>
    
                        <p><a class="btn btn-primary btn-lg" href="javascript:(function(){var%20jsCode=document.createElement('script');var%20scriptURL='<?php echo($this->config->item('base_url')); ?>lists/bookmarklet/'+(Math.random())+'/';jsCode.setAttribute('src',scriptURL);var%20jsCSS=document.createElement('link');document.body.appendChild(jsCode);})();">+2Readinglist</a>
    </div>
<?php 	  }
}?>
<!-- <div class="panel-footer"></div> -->
</div>
</div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel" style="text-align: center;">Copy Selected Items to Another List</h4>
      </div>
      <div class="modal-body">
        <div id="innerRLMessage"  style="text-align:center;"></div>
        <div id="innerRLFormDropdown" style="text-align:center;">
            
            
            <!-- destination module input field was here -->
<div class="btn-group">
  <button type="button" class="btn btn-danger">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Copy to&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button>
  <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">
    <span class="caret"></span>
    <span class="sr-only">Toggle Dropdown</span>
  </button>
  <ul class="dropdown-menu" role="menu"  style="text-align:left;">
	  <?php
        $type = 0; // starting out	
        $types = array('general', 'moodle', 'inbox', 'module catalogue', 'Personal Study Lists');	
		$list = '';
		$inbox = '';			   
        foreach($selected_module_dropdown as $module){
            if($type != $module['type']){ 
                $type != 0?$list .= "<li class=\"divider\"></li>":$list .= "";
                $type = $module['type'];
				$list .= "<li style=\"color: #666; font-weight: bold; padding-left: 5px;\"> " . $types[$type] . "</li>";
            }
            $list .= "<li style=\"cursor:pointer\" onmouseover=\"$('#destinationmodule').val('".$module['mid']."');  \" onclick=\"$('#booklistform').submit();\"><a href=\"#\">".$module['modulename']."</a></li>\n";
			$module['type'] == 2?$inbox = $list:print $list; // save inbox and print it last.
			$list = '';
        }
		print $inbox;
        ?>
  </ul>
</div>

            
            
            
            
            
            
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="$('#booklistform').submit();">Copy</button>
      </div>
    </div>
    <!-- /.modal-content --> 
  </div>
  <!-- /.modal-dialog --> 
</div>
<!-- /.modal -->
  </div>
<?php
		}
		
		echo "</form>\n";	
		
		
        echo "";
			//echo "</div><div class=\"panel-footer\">";
			if($module_title != 'NO_DATA'){
				if($results['action'] == 'active'){
					echo "<a class=\"removeFromMobileView btn btn-primary\" target=\"_blank\" onclick=\"/window.open('".$this->config->item('path')."lists/list_books/endnote/".$module_id."/', '_blank');\"><span>EndNote</span></a>&nbsp;&nbsp;";
					echo "<a class=\"removeFromMobileView btn btn-primary\" target=\"_blank\" onclick=\"/window.open('".$this->config->item('path')."lists/list_books/rss/".$module_id."/', '_blank');\"><i class=\"fa fa-rss\"></i>&nbsp; RSS</a>&nbsp;&nbsp;";
					
					// DELETE MODULE BUTTON
					if((($this->session->userdata('authorised') == 'TRUE' ) || $this->session->userdata('admin') == 'TRUE') && ($module_type != 2)){
						// delete module, if no books present.
						if($itemcount == 0){	// able to delete module
							echo "<button type=\"button\" class=\"btn btn-primary\" onclick=\"window.location.href = '/".$this->config->item('path')."lists/delete_module/".$module_id."';\">";
							echo "<span><i class=\"fa fa-trash-o\"></i><span class=\"removeFromMobileView\">&nbsp; Delete This List</span></span></button>&nbsp;&nbsp;";
						}else{
							echo "<button type=\"button\" class=\"btn btn-primary\" style=\"cursor:not-allowed\">";
							echo "<span><i class=\"fa fa-ban\"></i><span class=\"removeFromMobileView\">&nbsp; Can't delete $itemcount items</span></span></button>&nbsp;&nbsp;";
						}
					}
				}
				if($results['action'] == 'active' && (($this->session->userdata('authorised') == 'TRUE' && $teacheson == 1) || $this->session->userdata('admin') == 'TRUE')){
					echo "<a id=\"addnewbook\" class=\"btn btn-primary\" style=\"color: white\" value=\"$module_id\" style=\"float:right\" ><span><i class=\"fa fa-plus\"></i>&nbsp; Add New Item</span></a>";
				} 
			}else{ //  list at all.
			}
			echo "</div>";
		echo "</div>\n<!-- end fieldset -->\n";
?>

<?php  if($module_title != 'NO_DATA'){
			if($module_type != 2){ 
?>
<!-- Modal -->
<div class="modal fade" id="addPendingUserModal" tabindex="-1" role="dialog" aria-labelledby="addPendingUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="addPendingUserModalLabel">Invite New Editor To: <?php echo($module_display_title); ?></h4>
      </div>
      
      <div class="modal-body">      
            <div class="form-group">
                <input class="form-control" placeholder="Invitee E-mail" id="inviteeEmail" name="inviteeEmail" value="" type="email" autofocus> 
            </div>  
            <div class="form-group">
                <textarea name="inviteeMessage" id="inviteeMessage" class="form-control" rows="10">Hi,   &#13;&#10;&#13;&#10;I wanted you to join me in creating this reading list: <?php echo(stripslashes($module_display_title)); ?>.  You can search various online resources as well as use the bookmarklet to preserve links to great web pages, videos, etc.  &#13;&#10;&#13;&#10;All you have to do is click on the link below and log in, using your institutional username and password, to get started.  &#13;&#10;&#13;&#10;Yours,  &#13;&#10;&#13;&#10;<?php echo($this->session->userdata('firstname')); ?></textarea>
            </div>
                <input class="form-control" id="inviteeMid" name="inviteeMid" value="<?php echo($module_id); ?>" type="hidden">          
                <input type="hidden" name="inviteeInviter" id="inviteeInviter" value="<?php echo($this->session->userdata('firstname') . ' ' . $this->session->userdata('lastname')); ?>" />       
                <input type="hidden" name="inviteeListName" id="inviteeListName" value="<?php echo(stripslashes($module_display_title)); ?>" />
                
      </div>
      
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" id="userInviteModalButton" class="btn btn-primary">Invite</button>
      </div>
      
    </div>
    <!-- /.modal-content --> 
  </div>
  <!-- /.modal-dialog --> 

</div>
<!-- /.modal -->






<?php
	} 
}
?>

<script language="javascript">
    $( "#booklisttable" ).sortable("refresh");
	
	<?php if($module_type == 1 || $module_type == 4){ ?>
	var chart = null;
  
  $(function () {
    // handle clicking the "Toggle chart button"
	$(window).on('resize', function() { 
		if (chart == null && $('#myfirstchart').is(':visible')) { chart = refreshMainStatsChart();  }
	});	
	$('body').on('click','.check_catalogue', function(){
   		if (chart == null && $('#myfirstchart').is(':visible')) { chart = refreshMainStatsChart();  }
    });
  });
  
	
	
	function refreshMainStatsChart(){
		$("#myfirstchart").html('');
		new Morris.Line({
		  element: 'myfirstchart',
		  data: [    { year: '2008', value: 20 },    { year: '2009', value: 10 },    { year: '2010', value: 5 },    { year: '2011', value: 5 },    { year: '2012', value: 20 }  ],
		  xkey: 'year',  ykeys: ['value'],  labels: ['Value']
		});
		/*
		Morris.Bar({
		  element: 'myfirstchart',
		  data: [
			{ y: '2006', a: 100, b: 90 },
			{ y: '2007', a: 75,  b: 65 },
			{ y: '2008', a: 50,  b: 40 },
			{ y: '2009', a: 75,  b: 65 },
			{ y: '2010', a: 50,  b: 40 },
			{ y: '2011', a: 75,  b: 65 },
			{ y: '2012', a: 100, b: 90 }
		  ],
		  xkey: 'y',
		  ykeys: ['a', 'b'],
		  labels: ['Series A', 'Series B']
		});
		*/
	}
	refreshMainStatsChart();
	
	<?php } ?>
</script>
