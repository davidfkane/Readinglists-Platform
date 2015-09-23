<?php 
$this->load->view('includes/stats_top', array('accesslevel' => 'lecturer')); 
$this->load->view('includes/htmltop', array('title' => 'Reports: Main')); 
?>

<!--// start page specific javascript -->

<br/>
<div class="panel panel-primary">
  <div class="panel-heading">
    <button type="button" data-toggle="modal" data-target="#addNewList" class="addNewList btn btn-primary btn-xs" style="float:right;"><i class="fa fa-user"></i> Add New List</button>
    <h3>Overview of Ezproxy Usage in the last while</h3>
  </div>
  <div class="panel-body" style="background-color:#ffffff">
    <?php  
if($view != 'index'){ 
	echo($onload_queue); 
}else{
?>
    To generate a chart, please choose a data range using the form below.
    <?php
}
?>
  </div>
</div>
<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Visits Report for EZproxy</h3>
  </div>
  <div class="panel-body"> 
    <form id="vistsPanel" method="post" action="" >
      <div class="row">
        <div class="col-md-4">
          <div class="well">
            <h3>Choose the reporting period</h3>
            <div class="alert alert-danger" style="display:none;" id="alert"> <strong>Oh snap!</strong> </div>
            <table class="table">
              <thead>
                <tr>
                  <th>Start date:
                    <button type="button" class="btn btn-default" id="dp4" data-date-format="yyyy-mm-dd" data-date="<?php echo(date('Y-m-d',($startdate/1000))); ?>">
					<?php echo(date('Y-m-d',($startdate/1000))); ?></button></th>
                  <th>End date:
                    <button type="button" class="btn btn-default"  id="dp5" data-date-format="yyyy-mm-dd" data-date="<?php echo(date('Y-m-d',($enddate/1000))); ?>">
					<?php echo(date('Y-m-d',($enddate/1000))); ?></button></th>
                </tr>
              </thead>
            </table>
            <input type="hidden" id="hiddenstartdate" name="hiddenstartdate" value="<?php echo($startdate); ?>" />
            <input type="hidden" id="hiddenenddate" name="hiddenenddate" value="<?php echo($enddate); ?>" />
            <input type="hidden" id="allorseparate" name="allorseparate" value="" />
          </div>
        </div>
        <div class="col-md-4">
          <div class="well">
            <h3>Choose a Platform</h3>
            <?php 
	  	$c = 1; 
		$len = count($allplatforms);
		$len1 = ceil($len/2);
		echo("<table border=\"0\"><tr><td>");
		foreach($allplatforms as $pl => $v){
		  echo("\n\t\t<label class=\"checkbox-inline\">");
		  echo("\n\t\t\t<input type=\"checkbox\" name=\"PlatformsChecked[]\" class=\"PlatformsChecked\" id=\"PlatformCheckbox$c\" value=\"$pl\" ");
			if($platforms == 'separate'){
				if($v == 0){echo("checked ");}
			}else{
				echo("disabled");
			}
		  echo("/>$pl&nbsp;&nbsp;</label><br/>\n"); 
		  if($c == $len1){
			  echo("</td>\n<td>");
		  }
		  $c++;
		}
		echo("</td>\n<td>");
		echo("\n\t\t<label class=\"checkbox-inline\">\n\t\t\t<input type=\"checkbox\" name=\"PlatformsAll\" id=\"PlatformCheckboxAll\"");
		if(isset($_POST['PlatformsAll'])){
			echo(" checked ");
		}
		echo("value=\"all\"/>All Platforms Together &nbsp;\n\t\t</label>\n\t\t<br/>\n"); 
		echo("</td></tr></table>");
		?>
          </div>
        </div>
        <div class="col-md-4">
          <div class="well">
            <h3>Choose Time Interval Duration for x-Axis</h3>
            <?php echo($timeintervalselect); ?>
          </div>
        </div>
      </div>
      <!-- end .row -->
      
      <div class="row">
        <div class="col-md-12">
          <input class="btn btn-default" type="submit" id="visitsReportSubmit" value="View Report">
          <!-- <a class="btn btn-primary btn-lg" href="#" role="button">Learn more</a> --> 
        </div>
      </div>
      <!-- end .row -->
    </form>
    <br/>
    <?php if($view != 'index'){ ?>
    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-default">
        
  <div class="panel-heading">
    <h3 class="panel-title">SQL:</h3>
  </div>
          <div class="panel-body" style="color: white;"><?php echo($sql); ?></div>
        </div>
      </div>
    </div> <!-- end .row -->
    
        <?php } ?>
  </div>
</div>
<script>
			/* We build a URL like this one: 
			
			/readinglists/stats/visits/separate/hour/2015-04-01-00:2015-04-29-10
			
				var platforms = 'separate';
				var interval = 'hour';
				var timerange = '2015-04-01-00:2015-04-29-10'; 
			
			*/
			
			
			
			$("#visitsReportSubmit").mouseleave(function(){
				$('#vistsPanel').attr("action");
			});
			$("#visitsReportSubmit").hover(function(){
				
				if($('#PlatformCheckboxAll').prop('checked')){
					$('#allorseparate').val('together');
				}else{
					$('#allorseparate').val('separate');
				}
				
				var platforms = $('#allorseparate').val();
				var interval = $('#timeinterval').val();
				var timefrom = $('#dp4').text();
				var timeto = $('#dp5').text(); 
				actionURL = '/readinglists/stats/visits/'+ platforms+ '/'+ interval+ '/'+ timefrom+ ':'+ timeto;
				$('#vistsPanel').attr("action", actionURL);
				
			});
			
			$("#PlatformCheckboxAll").click(function(){
				if(!$('#PlatformCheckboxAll').prop('checked')){
					$('#allorseparate').val('separate');
					$('.PlatformsChecked').each( function() {		
						$(this).prop("checked", true);	
						$(this).prop("disabled", false);
					});
				}else{
					$('#allorseparate').val('together');
					$('.PlatformsChecked').each( function() {
						$(this).prop("checked", false);
						$(this).prop("disabled", true);
					});
				}
			});
			$('.PlatformsChecked').click( function() {
				if($(this).prop("checked")){
					$('#PlatformCheckboxAll').prop('checked', false);
				}
			});
			$('#dp4').datepicker();
			$('#dp5').datepicker();
			
			var startDate = new Date(<?php echo($startdate); ?>);
			var endDate = new Date(<?php echo($enddate); ?>);
			$('#dp4').datepicker().on('changeDate', function(ev){
				if (ev.date.valueOf() > endDate.valueOf()){
					$('#alert').show().find('strong').text('The start date ('+ev.date.toDateString()+') can not be greater then the end date('+endDate.toDateString()+') ');
				} else {
					$('#alert').hide();
					startDate = new Date(ev.date);
					//$('#startDate').text($('#dp4').data('date'));
					$('#dp4').text($('#dp4').data('date'));
					$('#hiddenstartdate').val(startDate.getTime());
				}
				$('#dp4').datepicker('hide');
			});
			$('#dp5').datepicker().on('changeDate', function(ev){
				if (ev.date.valueOf() < startDate.valueOf()){
					$('#alert').show().find('strong').text('The end date can not be less then the start date');
				} else {
					$('#alert').hide();
					endDate = new Date(ev.date);
					//$('#endDate').text($('#dp5').data('date'));
					$('#dp5').text($('#dp5').data('date'));
					$('#hiddenenddate').val(endDate.getTime());
				}
				$('#dp5').datepicker('hide');
			});
        </script> 
<!-- <script>
	window.onload = function(){
		<?php echo($onload_queue); ?>
	}
	</script>
-->
<?php $this->load->view('includes/bottom'); ?>
