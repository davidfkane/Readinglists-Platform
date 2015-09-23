<div class="panel panel-primary fieldset">
  <div class="panel-heading"> Edit Item Details Below: </div>
  <div class="panel-body" style="background-color:#d9edf7; ">
    <?php 
//print_r($book);
if(!isset($book['Message']) || $book['Message'] == '0'){
$buttoncode = '';
$buttontext = 'Add ... ';
if($action == 'update'){ 
	$buttontext = ' Confirm Item Details';
}elseif($action == 'new'){
	$buttontext = ' Add This Item';
}elseif($action == 'bookmarklet'){ $action = 'new';
	$buttontext = ' Add This Web Resource';
}

//$buttoncode .= "<button type=\"button\" class=\"btn btn-primary\"><a class=\"button\" style=\"color: white;\" id=\"editsubmit\" onclick=\"javascript:document.getElementById('editbookform').submit(); alert('submitting\');\"><span><i class=\"fa fa-plus\"></i>$buttontext</span></a></button>";
$buttoncode .= "<button type=\"button\" class=\"btn btn-primary\" id=\"editsgubmit\" onclick=\"document.getElementById('editbookform').submit();\"><span><i class=\"fa fa-plus\"></i>$buttontext</span></button> ";
$buttoncode .= "<a class=\"btn btn-default\" href=\"/".$this->config->item('path')."lists/\"><i class=\"fa fa-ban\"></i> Cancel</a>";

?>
<?php echo($buttoncode); ?>
<?php 
/*
	button codes
	 .displ-ctrl-title1
	 .displ-ctrl-title2
	 .displ-ctrl-url
	 .displ-ctrl-authed
	 .displ-ctrl-pfrmrs
	 .displ-ctrl-year
	 .displ-ctrl-edn
	 .displ-ctrl-vol
	 .displ-ctrl-iss
	 .displ-ctrl-pp
	 .displ-ctrl-pub
	 .displ-ctrl-pubplace
	 .displ-ctrl-stdnum
	 .displ-ctrl-libid
	 .displ-ctrl-notes
	 .displ-ctrl-ess

*/
?>
     
      
       <br /> <br /> 
    <div class="form-group">
    <form id="editbookform" name="editbookform" action="/<?php echo($this->config->item('path')); ?>lists/update_book/<?php  echo($action); ?>" class="form-horizontal" method="post">
    
      <div class="control-group">
        <label  class="control-label" for="type">Type</label>
        <select name="type" id="book_type" onchange="changeEditFormFields(this.value);" class="form-control input-large">
          <option value="1" <?php if($book['type'] == 1){echo('selected=\"selected\"');} ?> >Book</option>
          <option value="2" <?php if($book['type'] == 2){echo('selected=\"selected\"');} ?> >Journal</option>
          <option value="3" <?php if($book['type'] == 3){echo('selected=\"selected\"');} ?> >Website</option>
          <option value="4" <?php if($book['type'] == 4){echo('selected=\"selected\"');} ?> >Report</option>
          <option value="5" <?php if($book['type'] == 5){echo('selected=\"selected\"');} ?> >Audio</option>
          <option value="6" <?php if($book['type'] == 6){echo('selected=\"selected\"');} ?> >Video</option>
        </select>
      <br />
      </div>
      <div class="control-group displ-ctrl-title1">
        <label  class="control-label" for="book_title">title</label>
        <div class="controls">
        <input class="form-control input-large" required="required" name="book_title" type="text" id="book_title" value="<?php echo(stripslashes(stripslashes($book['Title']))); ?>" size="45" />
        </div>
        <!--<a href="http://witcat.wit.ie/search/X?SEARCH=< ?php echo(urlencode(stripslashes(stripslashes($book['Title']))))?>" target="_blank">
    <img src="/< ?php echo($this->config->item('path')); ?>img/magnifying_glass.png" border="0" align="absbottom" />
    </a>--> 
      <br />
      </div>
      <div class="control-group displ-ctrl-title2">
        <label  class="control-label" for="book_title_sub">sub title</label>
        <div class="controls">
        <input class="form-control input-large" name="book_title_sub" type="text" id="book_title_sub" value="<?php echo(stripslashes(stripslashes($book['Title']))); ?>" size="45" disabled />
        </div>
      <br />
      </div>
      <div class="control-group displ-ctrl-url">
        <label  class="control-label" for="url">URL</label>
        <div class="controls">
        <input class="form-control input-large" name="url" type="text" pattern="https?://.+" id="book_url" value="<?php echo($book['url']); ?>" size="45" />
        </div>
      <br />
      </div>
      <div class="well well-sm displ-ctrl-authed">
        <h4>Add or Edit Authors and Editors:</h4>
        <p>(<em style="color:black;">Lastname, Firstname I.</em> separated by <em style="color:black;">';'</em> E.g. Kennedy, John F.; Ford, Gerald)</p>
            <div class="panel panel-info">
                <div class="panel-heading">
                    Authors
                </div>
                <div class="panel-body" id="addAuthorFormPanel" style="padding: 0px;">
                    <input class="form-control input-large" style="border: none;" name="book_author" type="text" id="book_author" value="<?php echo(stripslashes(stripslashes($book['Author']))); ?>" size="45" />
                </div>
            </div>
            <div class="panel panel-info">
                <div class="panel-heading">
                    Editors
                </div>
                <div class="panel-body" id="addEditorFormPanel" style="padding: 0px;">
                    <input class="form-control input-large" style="border: none;" name="book_editor" type="text" id="book_editor" value="" size="45" disabled="disabled" />
                </div>
            </div>
      </div>
      <!--
      <div class="well well-sm displ-ctrl-pfrmrs">
        <h4>Add or Edit Performers</h4>
        <p>(<em style="color:black;">Lastname, Firstname I.</em> separated by <em style="color:black;">';'</em> E.g. Kennedy, John F.; Ford, Gerald)</p>
        <div class="panel panel-info">
            <div class="panel-heading">
                Performers
            </div>
            <div class="panel-body" id="addPerformerFormPanel" style="padding: 0px;">
				<input class="form-control input-large" style="border: none;" name="performer" type="text" id="performer" value="" size="45" disabled="disabled" />
            </div>
        </div>
      </div>
      -->
      
      <div class="control-group displ-ctrl-year">
        <label  class="control-label" for="book_year">year</label>
        <div class="controls">
        <input class="form-control input-large"t name="book_year" type="number" id="book_year" value="<?php echo($book['Year']); ?>" size="45" />
        </div>
      <br />
      </div>
      <div class="control-group displ-ctrl-edn">
        <label  class="control-label" for="book_edition">edition</label>
        <div class="controls">
        <input class="form-control input-large"t name="book_edition" type="text" id="book_edition" value="" size="45" disabled="disabled" />
        </div>
      <br />
      </div>
      <div class="control-group displ-ctrl-vol">
        <label  class="control-label" for="volume">volume</label>
        <div class="controls">
        <input class="form-control input-large"t name="volume" type="text" id="volume" value="" size="45" disabled="disabled" />
        </div>
      <br />
      </div>
      <!--
      <div class="control-group">
        <label  class="control-label" for="volumes">volumes</label>
        <div class="controls">
        <input class="form-control input-large"t name="volumes" type="text" id="volumes" value="" size="45" disabled="disabled" />
        </div>
      </div>
      <br />
		-->
      <div class="control-group displ-ctrl-iss">
        <label  class="control-label" for="issue">issue</label>
        <div class="controls">
        <input class="form-control input-large"t name="issue" type="text" id="issue" value="" size="45" disabled="disabled" />
        </div>
      <br />
      </div>
      <div class="control-group displ-ctrl-pp">
        <label  class="control-label" for="pages">pages</label>
        <div class="controls">
        <input class="form-control input-large"t name="pages" type="text" id="pages" value="" size="45" disabled="disabled" />
        </div>
      <br />
      </div>
      <div class="control-group displ-ctrl-pub">
        <label  class="control-label" for="book_publisher">publisher</label>
        <div class="controls">
        <input class="form-control input-large" name="book_publisher" type="text" id="book_publisher" value="<?php echo(stripslashes($book['Publisher'])); ?>" size="45" />
        </div>
      <br />
      </div>
      <div class="control-group displ-ctrl-pubplace">
        <label  class="control-label" for="book_place">place of publication</label>
        <div class="controls">
        <input class="form-control input-large" name="book_place" type="text" id="book_place" value="<?php echo($book['place']); ?>" size="45" />
        </div>
      <br />
      </div>
      <div class="control-group displ-ctrl-stdnum">
        <label  class="control-label" for="book_isbn">ISBN</label>
        <div class="controls">
        <input class="form-control input-large" name="book_isbn" type="text" id="book_isbn" value="<?php echo($book['isbn']); ?>" size="45" />
        </div>
      <br />
      </div>
      <!--
      <div class="control-group displ-ctrl-libid">
        <label  class="control-label" for="book_libraryid">Library ID:</label>
        <div class="controls"> -->
        <input class="form-control input-large" name="book_libraryid" type="hidden" id="book_libraryid" readonly="readonly" style="background-color:#CCC" value="<?php echo($book['libid']); ?>" size="45" />
        <!--
        </div>
      </div>
      <br />
     -->
      <input type="hidden" id="book_bcode3" name="book_bcode3" value="" />
      <input type="hidden" id="book_mattype" name="book_mattype" value="" />
      <input type="hidden" id="book_id" name="book_id" value="<?php echo($book['bid']); ?>" />
      <input type="hidden" id="module_id" name="module_id" value="<?php echo($book['Module']); ?>" />
      <input type="hidden" id="dept_id" name="dept_id" value="<?php echo($book['Did']); ?>" />
      <input type="hidden" id="action" name="action" value="<?php echo($action); ?>" />
      <div class="control-group displ-ctrl-notes">
        <label for="book_notes">Special Notes:</label>
        <div class="controls">
        <textarea class="form-control input-large" name="book_notes" id="book_notes" ><?php echo($book['Notes']); ?></textarea>
        </div>
      <br />
      </div>
      <?php
    	if($action == 'new'){
			$book['Supplimentary'] = "checked=\"checked\"";
		}
	?>

      <div class="well well-sm  displ-ctrl-ess">
        <h4>Essential Reading?</h4>
        <div class="radio">
          <label>
            <input type="radio" name="essential" value="supp" <?php echo($book['Supplimentary']); ?>  />
            Supplimentary <br />
          </label>
        </div>
        <div class="radio">
          <label>
            <input type="radio" name="essential" value="ess" <?php echo($book['Essential']); ?>  />
            Essential </label>
        </div>
      </div>
      </div>
<?php echo($buttoncode); ?>
    </form>
  </div>
  <!-- / form-group --> 
  <!-- start of search forms -->  
  <?php
		$ti = stripslashes(stripslashes($book['Title']));
        if(isset($Title)){$ti = $Title;}
        ?>
  <script language="javascript">
  changeEditFormFields(<?php echo($book['type']); ?>);
  </script>
  <?php 
}else{ 
	print $Message;
}
?>
<!-- <div class="panel-footer"> </div> -->
</div>
</div>
