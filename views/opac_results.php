<?php 

if($source == 'witcat'){ 
	$resultsHeading = 'In The Library Collection';
	$messageNoResult = '<i class="fa fa-warning"></i>&nbsp;No Search Results The WIT Library Shelves';
}else{	
	$resultsHeading = $source.' Search Results';
	$messageNoResult = '<i class="fa fa-warning"></i>&nbsp;No Results From '. $source;
}
if($aCount == 0){ $resultsHeading = $messageNoResult;}
?>

<div class="panel panel-primary fieldset">
  <div class="panel-heading" style="text-transform: capitalize;"><?php echo($resultsHeading); ?></div>
  <div class="panel-body" style="background-color:#d9edf7; ">
  
  
  
    <form action="" method="POST">
      <div class="input-group">
        <input autofocus="autofocus" type="text" id="searchFormInput" class="form-control" value="<?php echo($searchterm); ?>" />
        <div class="input-group-btn">
          <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Search: <span class="caret"></span></button>
          <ul class="dropdown-menu" id="searchFormInputDropdown">
            <li value="witcat"><a href="#" >Search Library</a></li>
            <li value="copac"><a href="#" >Search COPAC</a></li>
            <li value="google"><a href="#" >Search Google Books</a></li>
            <li class="divider"></li>
            <li value="isbndb"><a href="#" >ISBNdb</a></li>
          </ul>
        </div>
        <!-- /btn-group -->
      </div>
      <!-- /input-group -->
    </form>
    <br/>
    <?php if($aCount > 0){ 	?>
    <div  id="resultsPane"  style="border: none; overflow:scroll; max-height: 700px;">
      <div class="panel-group" id="accordion">
        <?php 
    $count = 0;
    foreach($opac_resultset as $d):
        if(isset($d['highlighted_title'])){
            $highlighted_title = $d['highlighted_title'];
        }else{
            $highlighted_title = "<strong>No Can Do!</strong><pre>";
        } ?>
        <div class="panel panel-default">
          <div class="panel-heading" onMouseover="this.style.cursor='pointer'; this.style.backgroundColor='lightblue';"  onMouseout="this.style.backgroundColor='#f5f5f5';" title="<?php echo $d['Title']; ?>">
            <h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo($count); ?>num" class="collapsed" style="font-size: 12px;"><?php echo $highlighted_title; ?></a></h4>
          </div>
          <div id="collapse<?php echo($count); ?>num" class="panel-collapse collapse">
            <div class="panel-body">
              <div><strong>Title: </strong><span class="opac_resultset_detail_title"><?php echo $d['Title']; ?></span></div>
              <div><strong>Record: </strong><span class="opac_resultset_detail_recordid"> <?php print("<a href=\"http://witcat.wit.ie/record=".substr($d['recordID'], 0,8)."~S0\" target=\"_blank\">".$d['recordID']."</a>"); ?> </span></div>
              <div><strong>Type: </strong><span class="opac_resultset_detail_type"><?php echo $d['type']; ?></span></div>
              <div><strong>Author: </strong><span class="opac_resultset_detail_author"><?php echo $d['Author']; ?></span></div>
              <div><strong>Pub: </strong><span class="opac_resultset_detail_publoc"><?php echo $d['Publisher_Location']; ?></span></div>
              <div><strong>Pub: </strong><span class="opac_resultset_detail_publisher"><?php echo $d['Publisher']; ?></span></div>
              <div><strong>Pub: </strong><span class="opac_resultset_detail_pubdate"><?php echo $d['Publication_Date']; ?></span></div>
              <div><strong>URL: </strong><span class="opac_resultset_detail_url"><a href="<?php echo $d['url']; ?>" target="_blank"><?php echo $d['url']; ?></a></span></div>
              <div><strong>ISBN: </strong><span class="opac_resultset_detail_isbn"><?php echo $d['ISBN']; ?></span></div>
              <!--
                        <div><strong>Type: </strong><span class="opac_resultset_detail_mattytpe">< ?php echo $d['type']; ?></span></div>
                        <div><strong>Bcode3: </strong><span class="opac_resultset_detail_bcode3">< ?php echo $d['bcode3']; ?></span></div>
                        -->
              <div>
                <button type="button" class="transferdetails btn btn-success"><i class="fa fa-mail-reply-all"></i> Copy Across To Form</button>
              </div>
              <!-- <span class="" style="position: relative; left: -50px; margin-top: 4px;" onMouseover="this.style.cursor='pointer'; " ><img src="/<?php echo($this->config->item('path')); ?>img/arrow.png" class="copytoforrm" /></span> --> 
            </div>
          </div>
        </div>
        <?php
            $count++;
	endforeach;
	?>
      </div>      <!-- / accordion group --> 
      <br/>
      <br/>
      <br/>
      <br/>
      <br/>
      <br/>
      <br/>
      <br/>
      <br/>
      <br/>
      <br/>
      <br/>
      <br/>
      <br/>
      <br/>
      <br/>
      <br/>
      <br/>
      <br/>
      <br/>
      <br/>
      <br/>
      <br/>
      <br/>
      <br/>
      <br/>
      <br/>
      <br/>
      <br/>
      <div class="alert alert-success"><i class="fa fa-info-circle"></i> End of Results</div>
      <div class="panel-footer"> </div>
    </div> <!-- / end of resultsPane div --> 
    <?php }else{  ?>
  </div>
  <!-- end panel-body -->
  <div class="panel-footer"> </div>
</div>
<?php } ?>
</div>
