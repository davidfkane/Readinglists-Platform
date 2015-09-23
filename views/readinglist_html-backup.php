<?php
$editessential = "";
$pointer = "";
$typeicon = array('book', 'article', 'web'); 
$resultCount = count($results['results']);
$adminCount = count($module_staff);

//print "Is Teaching On: " . $teacheson;
$module_title == 'NO_DATA'?$module_display_title = '<i class="fa fa-ban"></i>':$module_display_title = $module_title;

if($results['action'] == 'active'){
	$items_hidden = $itemcount - $resultCount;
	$items_visible = $resultCount;
	$items_visible == 1?$items_plural = 's':$items_plural = '';
	$deleted = 'visible';
	$paneltype = 'info';
	$link = 'delete';	
	$legend = "<span id='change_module_title'>".$module_display_title."</span>";
}else{
	$items_visible = $itemcount - $resultCount;
	$items_hidden = $resultCount;
	$items_hidden == 1?$items_plural = '':$items_plural = 's';
	$deleted = 'hidden';
	$paneltype = 'danger';
	$link = 'undelete';
	$legend = "<span id='change_module_title'>".$module_display_title."</span> <span class=\"badge\" style=\"background-color: #990000; margin-left: 10px;\"><strong><i class=\"fa fa-warning\"></i> HIDDEN ITEMS</strong></span>";
}
		
		echo "<div class=\"fieldset panel panel-".$paneltype."\" id=\"fieldset_readinglist\">\n";
		echo "<div class=\"panel-heading\">$legend ";
		 

if(!isset($viewonly) && $module_title != 'NO_DATA'){   
	if($this->session->userdata('authorised') == 'TRUE'){ 	
		if($results['action'] == 'active'){
				print("<a style=\"float: right; cursor: pointer;\" id=\"viewdeleted\" ><span><i class=\"fa fa-eye-slash\"></i> <span id=\"displayedItemCount\" title=\"$itemcount\">$items_hidden</span> hidden items</span></a>");
		}else{ 
				print("<a style=\"float: right; cursor: pointer;\" id=\"backtolistview\"><span><i class=\"fa fa-eye\"></i> <span id=\"displayedItemCount\" title=\"$itemcount\">$items_visible</span> visible items</span></a>");
		}
	} 
}
		echo "</div>";
		echo "<div class=\"panel-body\">";
		echo "<button id=\"backtolistview\" class=\"buttonspan\"  type=\"button\" style=\"display: none;\">Back To Reading List</button>\n";
		echo "<button id=\"viewdeleted\" class=\"buttonspan\"  type=\"button\" style=\"display: none;\">View deleted items for this Module</button>\n";
		// FORM
		echo "<form id=\"booklistform\" action=\"/".$this->config->item('path')."lists/movebook/\" method=\"post\">\n";
		//echo "<table id=\"booklisttable\" style=\"border-collapse:collapse\">";
		?>
<?php // if no results 
if($resultCount > 0){ // show the table;
?>

<div class="table-responsive">
<table class="table table-striped">
<?php
		echo "<thead>\n";

## table header ... 
		echo("<!-- the table headers -->");
		echo "\t<tr>\n";
	#	if($this->session->userdata('authorised') == 'TRUE'){
	#		echo "\t\t<th ></th>\n";
	#	} 	
		echo "\t\t<th></th>\n";		
		echo "\t\t<th></th>\n";		
		echo "\t\t<th></th>\n";		
		if(($this->session->userdata('authorised') == 'TRUE' && $teacheson == 1) || $this->session->userdata('admin') == 'TRUE'){
			echo "\t\t<th></th>\n";	
		}
		if($results['action'] == 'active'){			echo "\t\t<th></th>\n";		};	
		if(($this->session->userdata('authorised') == 'TRUE' && $teacheson == 1) || $this->session->userdata('admin') == 'TRUE'){
			if($results['action'] == 'active'){echo "\t\t<th></th>\n";}
			else{echo "\t\t<th></th>\n\t\t<th></th>\n";}
			$editessential = "editessential ";
			$pointer = "cursor:pointer;";
		}
		
			if(($this->session->userdata('authorised') == 'TRUE' ) || $this->session->userdata('admin') == 'TRUE'){
				// MOVE
		echo "\t\t<th style=\"text-align: center;\"><input type=\"checkbox\" name=\"togglemove\"  id=\"toggleselectall\" value=\"ignorethisvalue\" /></th>\n";
		echo "\t\t<th></th>\n";
			}
		echo "\t</tr>\n";
		echo "\t</thead>\n";
		
		
		
		
		
		echo "\t<tbody onmouseover=\"$(this).sortable({	helper: fixHelper, handle : '.handle', update : newSequenceToServer }).disableSelection();\">\n";
		echo("<!-- /the table headers -->");

## end table headers ... 
		$libraryroot = "http://witcat.wit.ie/record=";
		foreach ($results['results'] as $row)
		{				
			$title = stripslashes(stripslashes($row['Title']));
			echo "\t<tr title=\"mid=$module_id\">\n";
			echo "\t\t<td class=\" \" >";
			
			if($row['type'] == 1){ // book
				echo "<a class=\"btn btn-block btn-social btn-bitbucket\" onclick=\"return false;\" style=\"cursor: default;\"><i class=\"fa fa-book\"></i> ".$typeicon[$row['type'] - 1]."&nbsp;&nbsp;</a>";
			}else if($row['type'] == 2){ // article
				echo "<a class=\"btn btn-block btn-social btn-facebook\" onclick=\"return false;\" style=\"cursor: default;\"><i class=\"fa fa-file-text\"></i> ".$typeicon[$row['type'] - 1]."&nbsp;&nbsp;</a>";
			}else if($row['type'] == 3){  // web
				echo "<a class=\"btn btn-block btn-social btn-twitter\" onclick=\"return false;\" style=\"cursor: default;\"><i class=\"fa fa-globe\"></i> ".$typeicon[$row['type'] - 1]."&nbsp;&nbsp;</a>";
			}else{
				echo "<a class=\"btn btn-block btn-social btn-pinterest\" onclick=\"return false;\" style=\"cursor: default;\"><i class=\"fa fa-film\"></i> Other&nbsp;&nbsp;</a>";
			}
			
			echo "</td>";
			echo "\t\t<td class=\"r_title width500px\" id=\"title_".$row['bid'] ."\" style=\"line-height: 120%; white-space: normal\">";
			if($row['libid'] != ''){
				echo "<a href=\"".$libraryroot . substr($row['libid'], 0,8) . "~S0\" title=\"\" class=\"truncated\" style=\"text-decoration: none; font-weight: bold; color: #08c\" target=\"_blank\">".$title."</a>\n";
			}else{
				echo "<a class=\"truncated\" href=\"#\" style=\"cursor: arrow; text-decoration: none; color: black; font-weight: bold; \">$title</a>\n";
			}
			echo "<span style=\"font-weight: 500\">".stripslashes($row['Author'])."</smaller> <em>".stripslashes($row['Publisher']).", [".$row['Year']."]</em>";
			echo "</td>\n";
			
			echo "<td class=\"\" style=\"padding-left:5px;padding-right:5px;\">";
			if(isset($row['url']) && $row['url'] != ''){  // LINK to URL
				echo "<a href=\"".$row['url']."\" style=\"text-decoration: none; font-weight: bold\" target=\"_blank\">";
				echo "<button type=\"button\" class=\"btn btn-success btn-circle\"><i class=\"fa fa-link\"></i></button>";
				echo "</a>";
			}else{
				echo "<strong>&nbsp; &nbsp;</strong>\n";
			}
			echo "</td>\n";
					
			/*  STATUS LOOP */
			if(($this->session->userdata('authorised') == 'TRUE' && $teacheson == 1) || $this->session->userdata('admin') == 'TRUE'){
				
				
				// this is the edit button
						echo "\t\t<td class=\" r_confirmed\"  align=\"center\" style=\"padding-left:5px;padding-right:5px;\">";
						echo "<span class=\"check_catalogue\" style=\" cursor: pointer\"  value=\"".$row['bid']."\" ";
						echo " title=\"".$row['type']."\">";
						echo "<span>";
					echo "<button type=\"button\" class=\"btn btn-default btn-circle\"><i class=\"fa fa-edit\"></i></button>";
					echo "</span></span></td>\n";
			}
			/* END STATUS LOOP */
			
			// if we have the book ID from witcat we can create a link.
			if($results['action'] == 'active'){
					// Essential
				if($row['essential'] == 'ess'){
					echo "\t\t<td class=\" r_essential\" align=\"center\" >";
					echo "\t\t<img class=\"".$editessential." star_essential\" src=\"/".$this->config->item('path')."img/star_essential.png\" alt=\"".$row['bid'] ."\" style=\"".$pointer.";\"></td>";
				}else{
					echo "\t\t<td class=\" r_not_essential\" align=\"center\" bgcolor=\"transparent\" >";
					echo "\t\t<img class=\"".$editessential." star_supplimentary\" src=\"/".$this->config->item('path')."img/star_supplimentary.png\" alt=\"".$row['bid'] ."\" style=\"".$pointer.";\"></td>";
				}
				if(($this->session->userdata('authorised') == 'TRUE' && $teacheson == 1) || $this->session->userdata('admin') == 'TRUE'){
					// BASKET					
					echo "\t\t<td  class=\" r_delete\" align=\"center\">";
					echo "<a class=\"delete_restore\"  value=\"action=delete&bid=".$row['bid']."&mid=".$module_id."\" >";
					echo "<button type=\"button\" class=\"btn btn-default btn-circle\"><i class=\"fa fa-eye-slash\"></i></button>";
					echo "</a></td>\n";
				}
			}else{			
				if(($this->session->userdata('authorised') == 'TRUE' && $teacheson == 1) || $this->session->userdata('admin') == 'TRUE'){
					// RESTORE
					echo "\t\t<td  class=\" r_restore\" align=\"center\" >";
					echo "<a class=\"delete_restore\" value=\"action=undelete&bid=".$row['bid']."&mid=".$module_id."\" >";
					echo "<button type=\"button\" class=\"btn btn-default btn-circle\"><i class=\"fa fa-eye\"></i></button>";
					echo "</a></td>\n";
					// PURGE		
					echo "\t\t<td  class=\" r_restore\" align=\"center\" >";
					echo "<a class=\"delete_restore\" value=\"action=purge&bid=".$row['bid']."&mid=".$module_id."\" >";
					echo "<button type=\"button\" class=\"btn btn-danger btn-circle\"><i class=\"fa fa-trash-o\"></i></button>";
					echo "</a></td>\n";
				}
			}		
			if(($this->session->userdata('authorised') == 'TRUE' ) || $this->session->userdata('admin') == 'TRUE'){
				echo "\t\t<td style=\"text-align: center;\"><input class=\"bookmove\" type=\"checkbox\" name=\"bookstomove[]\" value=\"".$row['bid'] ."\"  /></td>\n";
				echo "\t\t<td><i class=\"handle fa fa-unsorted\" style=\"cursor: move;\"></i></td>\n";
			}	
			echo "\t</tr>\n";
		}
		
		
		
		echo "<tfoot>\n";

## table header ... 
		echo("<!-- the table footers -->");
		echo "\t<tr>\n";
		echo "\t\t<th></th>\n";		
		echo "\t\t<th></th>\n";		
		echo "\t\t<th></th>\n";		
		if(($this->session->userdata('authorised') == 'TRUE' && $teacheson == 1) || $this->session->userdata('admin') == 'TRUE'){
			echo "\t\t<th></th>\n";	
		}
		if($results['action'] == 'active'){			echo "\t\t<th></th>\n";		};	
		if(($this->session->userdata('authorised') == 'TRUE' && $teacheson == 1) || $this->session->userdata('admin') == 'TRUE'){
			if($results['action'] == 'active'){echo "\t\t<th></th>\n";}
			else{echo "\t\t<th></th>\n\t\t<th></th>\n";}
			$editessential = "editessential ";
			$pointer = "cursor:pointer;";
		}
		
			if(($this->session->userdata('authorised') == 'TRUE' ) || $this->session->userdata('admin') == 'TRUE'){
				// MOVE
		echo "\t\t<th style=\"text-align: center;\"><span class=\"btn btn-default btn-circle\" data-toggle=\"modal\" data-target=\"#myModal\" id=\"moveSelectedItems\"><i class=\"fa fa-copy\"></i></span></th>\n";
		echo "\t\t<th></th>\n";
			}
		echo "\t</tr>\n";
		echo "\t</tfoot>\n";
		
		echo "\t</tbody></table></div>\n";
		}else{
			if($module_title != 'NO_DATA'){  // module exists but no visible results;
				echo("<div class=\"alert alert-info\"><i class=\"fa fa-info-circle\"></i> There are no $deleted items.</div>");
			}else{  // module deleted;
				echo("<div class=\"alert alert-warning\"><i class=\"fa fa-warning\"></i> This list no longer exists.</div>");
			}
		}
?>
<!-- /.table-responsive -->
<?php 
			if($module_title != 'NO_DATA'){
            ?>
<div class="col-md-4">

<div class="panel panel-primary">
    <div class="panel-heading">
        Module Administrators<?php if(count($adminCount) > 1){echo('s');} ?>
    </div>
    <div class="panel-body">
  <div class="table-responsive">
    <table class="table" style="margin-bottom: 0px;">
      <tbody>
        <?php
	
			if($adminCount > 0){
				foreach($module_staff as $staff){
					echo "\t<tr >\n";
					echo "\t\t<td ><strong>".$staff['firstname']." ".$staff['lastname']."</strong></td>\n";
					echo "\t\t<td >".$staff['email']."</td>\n";
					echo "\t</tr>\n";
				}
			}else{		
				echo "\t\t<td >No Administrators Assigned</td>\n";
				echo "\t</tr>\n";
				echo "\t<tr>\n\t\t<th> &nbsp; </th>\n\t</tr>";
			}
	?>
      </tbody>
    </table>
  </div>
  <!-- /.table-responsive --> 
	</div>

	
    <div class="panel-footer">
        
    </div>
</div>
</div>

<div class="col-md-8">
<div class="panel panel-primary">
    <div class="panel-heading">
        Module Statistics
    </div>
    <div class="panel-body" style="border:">
        <div id="myfirstchart" style="height: 250px;"></div>
    </div>
    <div class="panel-footer">
        
    </div>
</div>
</div>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Copy Selected Items to Another List</h4>
      </div>
      <div class="modal-body">
        <div id="innerRLMessage"></div>
        <div id="innerRLFormDropdown">
          <?php
										   
			echo "			<select class=\"form-control\" name=\"destinationmodule\">\n";
			foreach($selected_module_dropdown as $module){
				echo"				<option value=\"".$module['mid']."\" >".$module['modulename']."</option>\n";
			}
			echo"			</select>.\n";
			?>
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

<?php
			}
		
		echo "</form>\n";	
		
		
        echo "</div>";
			echo "<div class=\"panel-footer\">";
			if($module_title != 'NO_DATA'){
				if($results['action'] == 'active'){
					echo "<a class=\"btn btn-primary\" target=\"_blank\" onclick=\"/window.open('".$this->config->item('path')."lists/list_books/endnote/".$module_id."/', '_blank');\"><span>EndNote</span></a>&nbsp;&nbsp;";
					echo "<a class=\"btn btn-primary\" target=\"_blank\" onclick=\"/window.open('".$this->config->item('path')."lists/list_books/rss/".$module_id."/', '_blank');\"><i class=\"fa fa-rss\"></i>&nbsp; RSS</a>&nbsp;&nbsp;";
					
					// DELETE MODULE BUTTON
					if(($this->session->userdata('authorised') == 'TRUE' ) || $this->session->userdata('admin') == 'TRUE'){
						// delete module, if no books present.
						if($itemcount == 0){	// able to delete module
							echo "<button type=\"button\" class=\"btn btn-primary\" onclick=\"window.location('/".$this->config->item('path')."lists/delete_module/".$module_id."/');\">";
							echo "<span><i class=\"fa fa-trash-o\"></i>&nbsp; Delete This List</span></button>&nbsp;&nbsp;";
						}else{
							echo "<button type=\"button\" class=\"btn btn-primary\" style=\"cursor:not-allowed\">";
							echo "<span><i class=\"fa fa-ban\"></i>&nbsp; Can't delete $itemcount items</span></button>&nbsp;&nbsp;";
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
<script language="javascript">
    $( "#booklisttable" ).sortable("refresh");
	function refreshMainStatsChart(){
		$("#myfirstchart").html('');
		/*
		new Morris.Line({
		  element: 'myfirstchart',
		  data: [    { year: '2008', value: 20 },    { year: '2009', value: 10 },    { year: '2010', value: 5 },    { year: '2011', value: 5 },    { year: '2012', value: 20 }  ],
		  xkey: 'year',  ykeys: ['value'],  labels: ['Value']
		});
		*/
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
	}
	refreshMainStatsChart();
</script>
