<?php include(__DIR__ . '/includes/top.php'); ?>
<?php include(__DIR__ . '/includes/htmltop.php'); ?>
<?php
if($items > 0){ ?>
<h1 class="legend">You can't delete this list</h1>
  <div class="fieldset">
  <br/>
  <p>This list has <?php echo($items); ?> items, so you cannot delete this list.</p>
  <p>You need to purge all items from your list and from the wastebasket first</p>
  <p><a class="button" <?php echo("href=\"/".$this->config->item('path')."lists\""); ?>><span>BACK</span></a></p>
  <br/>
  </div>
<?php }else{ ?>
<h1 class="legend">Are you sure you want to delete this list?</h1>
  <div class="fieldset">
  <br/>
  <p>This list has <?php echo($items); ?> items, so you cannot delete this list.</p>
  <p>You need to purge all items from your list and from the wastebasket first</p>
  <p><a class="button" <?php echo("href=\"/".$this->config->item('path')."lists\""); ?>><span>BACK</span></a></p>
  <br/>
  </div>
<?php } ?>
<?php include(__DIR__ . '/includes/bottom.php'); ?>
