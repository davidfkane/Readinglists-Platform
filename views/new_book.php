<?php if($Message == '0'){ ?>
<fieldset>
  <legend>Edit Book</legend>
<table width="100%">
<tr><td width="50%">
<div class="book_edit_form_container">
<h2>Edit Your Book Details Here</h2><br />
<em>You are editing: (<?php echo(stripslashes(stripslashes($Title))); ?>)</em>
  <form id="form1" name="form1" action="/<?php echo($this->config->item('path')); ?>lists/update_book/" method="post" action="">
    <p>
      <input name="book_title" type="text" id="book_title" value="<?php echo(stripslashes(stripslashes($Title))); ?>" size="80" />
      <label for="book_title">title</label>
      <input type="hidden" value="1" name="type" />
      <input type="hidden" value="" name="url"  />
    </p>
    <p>
      <input name="book_author" type="text" id="book_author" value="<?php echo(stripslashes(stripslashes($Author))); ?>" size="80" />
      <label for="book_author">author</label>
    </p>
    <p>
      <input name="book_year" type="text" id="book_year" value="<?php echo($Year); ?>" size="80" />
      <label for="book_year">year</label>
    </p>
    <p>
      <input name="book_publisher" type="text" id="book_publisher" value="<?php echo(stripslashes($Publisher)); ?>" size="80" />
      <input type="hidden" id="book_id" name="book_id" value="<?php echo($bid); ?>" />
      <input type="hidden" id="module_id" name="module_id" value="<?php echo($Module); ?>" />
      <input type="hidden" id="dept_id" name="dept_id" value="<?php echo($Did); ?>" />
      <label for="book_publisher">publisher</label>
    </p>
    <p>
      <input name="book_place" type="text" id="book_place" value="<?php echo($place); ?>" size="80" />
      <label for="book_place">place of publication</label>	
    </p>
    <p>
      <input name="book_isbn" type="text" id="book_isbn" value="<?php echo($isbn); ?>" size="80" />
      <label for="book_isbn">ISBN</label>
    </p>
    <p>
      <input name="book_libraryid" type="text" id="book_libraryid" disabled="disabled" style="background-color:#CCC" value="<?php echo($libid); ?>" size="80" />
      <label for="book_libraryid">Library ID</label>
      <br />
    </p>
    <p>
      <input type="radio" name="essential" value="ess" <?php echo($Essential); ?>  />Essential Reading<br />
      <input type="radio" name="essential" value="supp" <?php echo($Supplimentary); ?> />Supplimentary Reading<br />
    </p>
    <p><?php 
if($action == 'update'){
    echo"<span class=\"buttonspan\" id=\"editsubmit\" onMouseover=\"this.style.cursor='pointer'\" title=\"/".$this->config->item('path')."lists/update_book/update/\">Confirm Book Details</span>";
}
if($action == 'new'){
    echo"<span class=\"buttonspan\" id=\"editsubmit\" onMouseover=\"this.style.cursor='pointer'\" title=\"/". $this->config->item('path')."lists/update_book/add/\">add new book</span>";
}	
	?>
     
    </p>
  </form>
  </div></td><td width="50%">

  <div id="showcat" class="book_edit_form_container">
  
  
  
  </div>
  
  </td></tr></table>
  <br/>
  <br/>
</fieldset>

<?php }else{ 

print $Message;

}
?>