<!--<p>Your book: <strong><?php echo($report[0]['Title']); ?></strong>, is present in the following modules.  </p>
<p>Click to Edit...</p>
</div>-->
<table border="0"  id="booklisttable" style="border-collapse:collapse; margin: 20px;">
<tr style="border-bottom:double 3px red">
<th>Dep't</th>
<th>module</th>
<th>MOODLE ID</th>
<th>ess</th>
</tr>
<?php
$ct = 0;
foreach($report as $res){
   print"<tr class=\"rrow_".(($ct++)%2)."\">";
   print"<td class=\"col_1\">" .$res['dname']."</td>";
   print"<td class=\"col_0\"><a style=\"text-decoration: none; font-weight: bold; \" href=\"".$this->config->item('domain')."lists/fetch/rlists_module/".$res['mid']."\" target=\"_blank\">" .$res['modulename']."</a></td>";
   print"<td class=\"col_1\"><a href=\"http://tmoodle1.wit.ie/course/view.php?id=" .$res['MOODLE_INTERNAL_ID']."\" target=\"_blank\">".$res['MOODLE_INTERNAL_ID']."</td>";
   
   
  if($res['essential'] != 'ess'){
   	print"<td class=\"col_0\">&nbsp;</td>";
  }else{
   	print"<td class=\"col_1\" style=\"color: red; background-color: yellow;\">Essential</td>";
  }  
   
   
   
   print"</tr>";
}
?>
<tr style="border-top:double 3px red">
<th>Dep't</th>
<th>module</th>
<th>MOODLE ID</th>
<th>ess</th>
</tr>
</table>

