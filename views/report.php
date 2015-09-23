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
    <style media="screen">
    holder#drawing_board {
        height: 480px;
        width: 640px;
        border:1px solid #000000;
    }
    </style>
 <script>
            window.onload = function () {
                var r = Raphael("holder"),
                    xs = [
					0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 
					0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 
					0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 
					0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 
					0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 
					0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 
					0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23],
                    ys = [
					7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 
					6, 6, 6, 6, 6, 6, 6, 6, 6, 6, 6, 6, 6, 6, 6, 6, 6, 6, 6, 6, 6, 6, 6, 6, 
					5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 5, 
					4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 
					3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 
					2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 
					1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1],
                    data = [
					294, 300, 204, 255, 348, 383, 334, 217, 114, 33, 44, 26, 41, 39, 52, 17, 13, 2, 0, 2, 5, 6, 64, 153, 
					294, 313, 195, 280, 365, 392, 340, 184, 87, 35, 43, 55, 53, 79, 49, 19, 6, 1, 0, 1, 1, 10, 50, 181, 
					246, 246, 220, 249, 355, 373, 332, 233, 85, 54, 28, 33, 45, 72, 54, 28, 5, 5, 0, 1, 2, 3, 58, 167, 
					206, 245, 194, 207, 334, 290, 261, 160, 61, 28, 11, 26, 33, 46, 36, 5, 6, 0, 0, 0, 0, 0, 0, 9, 
					9, 10, 7, 10, 14, 3, 3, 7, 0, 3, 4, 4, 6, 28, 24, 3, 5, 0, 0, 0, 0, 0, 0, 4, 
					3, 4, 4, 3, 4, 13, 10, 7, 2, 3, 6, 1, 9, 33, 32, 6, 2, 1, 3, 0, 0, 4, 40, 128, 
					212, 263, 202, 248, 307, 306, 284, 222, 79, 39, 26, 33, 40, 61, 54, 17, 3, 0, 0, 0, 3, 7, 70, 199],
					hrefs=[
					"233","","","","","","","","","","","","","","","","","","","","","","","",
					"testing/123/","","","","","","","","","","","","","","","","","","","","","","","",
					"","","","","","","","","","","","","","","","","","","","","","","","",
					"","","","","","","","","","","","","","","","","","","","","","","","",
					"","","","","","","","","","","","","","","","","","","","","","","","",
					"","","","","","","","","","","","","","","","","","","","","","","","",
					"google/","","","","","","","","","","","","","","","","","","","","","","","",
					], 
                    axisy = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
                    axisx = ["12am", "1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12pm", "1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11"];

                r.dotchart(10, 10, 620, 260, xs, ys, data, {symbol: "o", max: 20, heat: false, href: hrefs, axis: "0 0 1 1", axisxstep: 23, axisystep: 6, axisxlabels: axisx, axisxtype: "t", axisytype: " ", axisylabels: axisy}).hover(function () {
                    this.marker = this.marker || r.tag(this.x, this.y, this.value, 0, this.r + 2).insertBefore(this);
                    this.marker.show();
                }, function () {
                    this.marker && this.marker.hide();
                }).click(function(){
					//console.dir(this);
					//console.dir(this.attr('href'));
					//this.attr({href: "http://myurl.com/"+this.bar.x+"/"+this.bar.y+"/"+this.bar.value});
					window.location = ("./day/"+this.attr('href'));
				});

                var a = Raphael("holder2"),
                    xs = [
					0, 1, 2, 3, 4, 5, 6],
                    ys = [
					1, 1, 1, 1, 1, 1, 1],
                    data = [
					212, 263, 202, 248, 307, 306, 284],
					hrefs=[1, 2, 3, 4, 5, 6, 7], 
                   axisx = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
                   //  axisx = [],
                    axisy = [];

                a.dotchart(10, 30, 620, 50, xs, ys, data, {symbol: "o", max: 20, heat: false, href: hrefs, axis: "0 0 1 1", axisxstep: 6, axisystep: 1, axisxlabels: axisx, axisxtype: "t", axisytype: " ", axisylabels: axisy}).hover(function () {
                    this.marker = this.marker || r.tag(this.x, this.y, this.value, 0, this.r + 2).insertBefore(this);
                    this.marker.show();
                }, function () {
                    this.marker && this.marker.hide();
                }).click(function(){
					//console.dir(this);
					//console.dir(this.attr('href'));
					//this.attr({href: "http://myurl.com/"+this.bar.x+"/"+this.bar.y+"/"+this.bar.value});
					window.location = ("./day/"+this.attr('href'));
				});
            };
        </script>
<!--// end page specific javascript -->
<?php 
$this->load->view('includes/htmltop', array('title' => 'Reports: Main')); ?>


<br/>
<h1 class="legend">Item Clickthrough - Last 7 Days:</h1>
  <div class="fieldset">
  <div id="holder"></div>
</div>

<h1 class="legend">Lists Created</h1>
  <div class="fieldset">
  <div id="holder2"></div>
  </div>
<h1 class="legend">Items Added</h1>
  <div class="fieldset">
  </div>
<h1 class="legend">Item Popularity</h1>
  <div class="fieldset">
<table border="0" style="border-collapse:collapse;">
<tr style="border-bottom:double 3px red;">
<th>&nbsp;&nbsp;&nbsp;</th>
<th>Book</th>
<th>module</th>
<th>essential in mod</th>
<th>&nbsp;&nbsp;&nbsp;</th>
</tr>
<?php
$ct = 0;
foreach($report as $res){
	
   	print"<tr class=\"rrow_".(($ct++)%2)."\">";
    print"<td nowrap=\"nowrap\" class=\"col_0\"> </td>";
   ?>
   <td class="col_1">
   <strong><a title="/<?php echo($this->config->item('path')); ?>admin/books_modules/<?php echo($res['BID1']); ?>" href="javascript: return false" style="text-decoration: none; font-weight:bold; " class="reportlink"><?php echo($res['Title']); ?>.</a></strong><br/>
   <em><?php echo($res['Author']); ?></em>(<?php echo($res['Year']); ?>)
   <div class="sublist" style="text-align:center; visibility:hidden;">&nbsp; </div>
   </td>
   
   
   <?
   print"<td class=\"col_0\" nowrap=\"nowrap\">in " .$res['mods']." mods</td>";
   if($res['essential_in_mods'] == 0){
   	print"<td class=\"col_1\">" .$res['essential_in_mods']."</td>";
   }else{
   	print"<td class=\"col_1\" style=\"color: red; background-color: yellow;\">" .$res['essential_in_mods']."</td>";
   }  

    print"<td nowrap=\"nowrap\" class=\"col_0\"> </td></tr>";
}



?>
<tr style="border-top:double 3px red;">
<th> </th>
<th>Book</th>
<th>modules</th>
<th>essential in mods</th>
<th>&nbsp;&nbsp;&nbsp;</th>
</tr>
</table>
</div>

<?php $this->load->view('includes/bottom'); ?>
