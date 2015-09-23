<?php 
$this->load->view('includes/top', array('accesslevel' => 'lecturer')); ?>

<!--// start page specific javascript -->
<script language="javascript" type="text/javascript">

$(document).ready(function()
{		

	$("a.reportlink").click(function()
	{
		// close all;
		$(".sublist").css('visibility', 'visible');
		$(".sublist").hide();
		// open only this one
		var url=$(this).attr('title');
		//alert(url);
		var thisdiv = $(this).parent().parent().find(".sublist");
		
		var dataString = 'one=two';	
		$.ajax
		({
			type: "POST", url: url, data: dataString, cache: false, success: function(html)
			{
				thisdiv.html(html);
				//alert(html);
			}
		});
		thisdiv.slideDown('slow');		
	
	});	
	
	
	
});
</script> 
 
 
<!--// end page specific javascript -->
<?php 
$this->load->view('includes/htmltop', array('title' => 'Reports: Main')); ?>


<br/>
<h1 class="legend">Item Clickthrough - Last 7 Days:</h1>
  
  <div class="fieldset">
<table border="0"  id=\"booklisttable\" style="border-collapse:collapse;">
<!--
<tr style="border-bottom:double 3px red;">
<th>&nbsp;&nbsp;&nbsp;</th>
</tr>
-->
<?php

$typeicon = array('icon_type_book.png', 'icon_type_article.png', 'icon_type_web.png'); 
$modTR = 0; 
		
print"<thead><tr>";		
print"<th class=\"col_head\"><strong>type</strong></th>";		
//print"<th class=\"col_head\"><strong>id</strong></th>";			
print"<th class=\"col_head\"><strong>username</strong></th>";			
print"<th class=\"col_head\"><strong>list</strong></th>";			
print"<th class=\"col_head\"><strong>list-item</strong></th>";		
//print"<th class=\"col_head\"><strong>source</strong></th>";			
//print"<th class=\"col_head\"><strong>url</strong></th>";			
//print"<th class=\"col_head\"><strong>catalogue</strong></th>";			
//print"<th class=\"col_head\"><strong>essential</strong></th>";			
print"<th class=\"col_head\"><strong>time</strong></th>";			
//print"<th class=\"col_head\"><strong>ipaddress</strong></th>";			
//print"<th class=\"col_head\"><strong>platform, browser, version, win32, ismob</strong></th>";					
print"</tr></thead><tbody>";
$ct = 0;
foreach($clickthroughs as $c){
		$modTD = 0; 
		$colour = 'white';
		if($c['usertype'] != 'STUDENT'){
			$colour = 'pink';
		}else{
			$c['usertype'] = '';
		}
		
		print"<tr class=\"row_".(($modTR++)%2)."\" >";	
		print"<td class=\"col_" . (($modTD++)%2)." \" style=\"padding: 0px;\"><img src=\"/".$this->config->item('path')."img/".$typeicon[$c['type'] - 1]."\" alt=\"".$typeicon[$c['type'] - 1]."\" style=\"position: relative; left: -6px\" /></td>";
		//print"<td style=\"background-color: $colour; border: solid 1px black;\">ssss<a href=\"".base_url()."reports/deleteclick/".$c['id']." target=\"_blank\">".$c['id']."</a></td>";
		print"<td class=\"col_" . (($modTD++)%2)." \">".$c['username']."</td>";
		print"<td class=\"col_" . (($modTD++)%2)." \"><a href=\"/".$this->config->item('path')."/lists/fetch/rlists_module/".$c['list']."\" target=\"_blank\" style=\"text-decoration: none\">".$c['modulename']."</td>";
		print"<td class=\"col_" . (($modTD++)%2)." \"><a href=\"".$c['url']."\" target=\"_blank\" style=\"text-decoration: none\">".$c['title']."</a><div style=\"background-color: #c0c0c0; border: solid 1px gray; border-radius: 3px;\">".$c['teachers']."</div></td>";
		//print"<td class=\"col_" . (($modTD++)%2)." \">".$c['source']."</td>";
		//print"<td class=\"col_" . (($modTD++)%2)." \">url</td>";
		//print"<td class=\"col_" . (($modTD++)%2)." \">".$c['catalogue']."</td>";
		//print"<td class=\"col_" . (($modTD++)%2)." \">".$c['essential']."</td>";
		print"<td nowrap=\"nowrap\" style=\"font-size: x-small; padding: 0px;\" class=\"col_" . (($modTD++)%2)." \">".date('j M H:i',strtotime($c['timestamp']))."</td>";
		//print"<td class=\"col_" . (($modTD++)%2)." \">".$c['ipaddress'].", </td>";			
		//print"<td class=\"col_" . (($modTD++)%2)." \">".$c['platform'].", ".$c['browser'].", ".$c['version'].", ".$c['win32'].", ".$c['ismob']."</td>";
		print"</tr>";
	$ct++;
}
print"</tbody>";
   	

?>
<!--
<tr style="border-top:double 3px red;">
<th> </th>
</tr>
-->
</table>
</div>

<?php $this->load->view('includes/bottom'); ?>
