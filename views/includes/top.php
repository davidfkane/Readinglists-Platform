<?php
// phpinfo();
//print "testing";
if ($_SERVER['SERVER_PORT']!=443){
	// this is to make sure that we stay on the secure port.
	$url = "https://". $_SERVER['SERVER_NAME'] . ":443".$_SERVER['REQUEST_URI'];
	header("Location: $url");
}
$login_link = "/" . $this->config->item('path'). "lists/login/";
#print($_SERVER['REQUEST_URI'] .  " ");
#print($login_link);
if($accesslevel != 'teacher' && (!defined($this->session->userdata('authorised')) || $this->session->userdata('authorised') != 'TRUE')){ 	  // if not logged in
	if($_SERVER['REQUEST_URI'] != $login_link){
		header("Location: " . $login_link . base64_encode("https://". $_SERVER['SERVER_NAME'] . ":443".$_SERVER['REQUEST_URI']));
	}
}

if(isset($sel_email)){
	$sel_email = $this->session->userdata('email');
}
if(preg_match('/(?i)msie [7-9]/',$_SERVER['HTTP_USER_AGENT'])){  ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<? }else{ ?>
<!DOCTYPE html>
<? } ?>
<html>
<head>

<meta charset="utf-8">
<title>Reading Lists</title>
<link type="text/css" href="/<?php echo($this->config->item('path')); ?>includes/jquery.tagsinput.css" rel="Stylesheet" />

<meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Core CSS - Include with every page -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/font-awesome/css/font-awesome.css" rel="stylesheet">

    <!-- Page-Level Plugin CSS - Dashboard -->
    <link href="/css/plugins/morris/morris-0.4.3.min.css" rel="stylesheet">
    <link href="/css/plugins/social-buttons/social-buttons.css" rel="stylesheet">

    <!-- SB Admin CSS - Include with every page -->
    <link href="/css/sb-admin.css" rel="stylesheet">
        
<!--
<script type="text/javascript" src="/<?php echo($this->config->item('path')); ?>jquery/js/jquery-1.6.4.min.js"></script>
<script type="text/javascript" src="/js/jquery-ui-1.10.4/ui/jquery.ui.core.js"></script>
<script type="text/javascript" src="/<?php echo($this->config->item('path')); ?>jquery/js/jquery-ui-1.8.11.custom.min.js"></script>
-->
<!--
<script type="text/javascript" src="/<?php echo($this->config->item('path')); ?>includes/Chart.min.js"></script>
-->
<script type="text/javascript"
          src="https://www.google.com/jsapi?autoload={
            'modules':[{
              'name':'visualization',
              'version':'1',
              'packages':['corechart', 'line']
            }]
          }"></script>
<?php 
// if Javascript needs to be added here.
if(isset($charthtml)){
	echo $charthtml;
}

 ?>          
<script type="text/javascript" src="/js/jquery-ui-1.10.4/js/jquery-1.10.2.js"></script>
<script type="text/javascript" src="/js/jquery-ui-1.10.4/js/jquery-ui-1.10.4.custom.js"></script>
<script type="text/javascript" src="/js/jquery-ui-1.10.4/css/jquery-bootstrap-datepicker/datepicker.css"></script>
<script type="text/javascript" src="/js/jquery-ui-1.10.4/css/jquery-bootstrap-datepicker/datepicker.js"></script>
<script type="text/javascript" src="/<?php echo($this->config->item('path')); ?>jquery/js/jquery.tagsinput.js"></script>
<!--
<script type="text/javascript" src="/<?php echo($this->config->item('path')); ?>js/raphael.js"></script>

<script type="text/javascript" src="/<?php #echo($this->config->item('path')); ?>js/g.raphael.js"></script>
<script type="text/javascript" src="/<?php #echo($this->config->item('path')); ?>js/g.dot.js"></script>
<script type="text/javascript" src="/<?php #echo($this->config->item('path')); ?>js/g.line.js"></script>
<script type="text/javascript" src="/<?php #echo($this->config->item('path')); ?>js/g.bar.js"></script>
<script type="text/javascript" src="/<?php #echo($this->config->item('path')); ?>js/g.pie.js"></script>
-->


	<!-- Core Scripts - Include with every page    <script src="<?php echo($this->config->item('domain')); ?>js/jquery-1.10.2.js"></script>
-->
    <script src="<?php echo($this->config->item('domain')); ?>js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="<?php echo($this->config->item('domain')); ?>js/bootstrap.min.js"></script>
    
    
    
    <!-- Page-Level Plugin Scripts - Dashboard
    <script src="<?php #echo($this->config->item('domain')); ?>js/plugins/morris/raphael-2.1.0.min.js"></script>
    <script src="<?php #echo($this->config->item('domain')); ?>js/plugins/morris/morris.js"></script>
     -->
    
    
    <!-- SB Admin Scripts - Include with every page 
-->
    <script src="<?php echo($this->config->item('domain')); ?>js/sb-admin.js"></script>
    
    
    
    <!-- Page-Level Demo Scripts - Dashboard - Use for reference 
    <script src="<?php echo($this->config->item('domain')); ?>js/demo/dashboard-demo.js"></script>
-->

<style type="text/css">
 /* Media Queries */
form#booklistform span.listAdminButtons{
	float:right;
	white-space: nowrap;
}
.listAdminButtons i{
	font-size:large;
	text-align: center;
	width: 20px;
}

	.breadcrumb>li+li:before {
		font-weight: bold;
		content:"\003E";
	}
@media screen and (max-width: 1040px) {
	.removeFromMobileView {
		display: none;
	}
	.breadcrumb>li+li:before{
		content:normal;
	}
	div#list ol{
		list-style: none;	
	}	
	/* force 'nowrap' on the booklist table icons, even though they are in the same table field until the max-width is less than 480px; 
	Also  make a few things like buttons smaller here.
	*/
}
@media screen and (max-width: 480px) {
	div#fieldset_readinglist > div{
		padding: 0px;
	}
	form#booklistform div{
		padding: 0px;
	}
	form#booklistform span.listAdminButtons{
		white-space: normal;
	}
}
/*
.table-striped>tbody>tr:nth-child(odd)>td, .table-striped>tbody>tr:nth-child(odd)>th{
	background-color: red;
}
*/
div#booklist div.panel-danger .table-striped>tbody>tr:nth-child(even)>td, div#booklist div.panel-danger .table-striped>tbody>tr:nth-child(even)>th{
	background-color: #F9F1F1;
}
div#booklist div.panel-primary .table-striped>tbody>tr:nth-child(even)>td, div#booklist div.panel-danger .table-striped>tbody>tr:nth-child(even)>th{
	background-color: #F0F8FC;
}

div.panel-heading h3, div.panel-heading h4, div.panel-heading h2{
	margin: 0px; font-weight: bold; 
}
div#box_maincontent {
	padding: 0px;
}
.ui-autocomplete-loading {
	background: white url('images/ui-anim_basic_16x16.gif') right center no-repeat;
}
#modulesearch {
	width: 25em;
} 

.inset-text {
   /*
   -moz-box-shadow:    inset 5px 5px 2px #000000;
   -webkit-box-shadow: inset 5px 5px 2px #000000;
   box-shadow:         inset 1px 1px 3px #000000;
   */
   border: solid 1px #D0D0D0;
}


/*form validation */

  input:required:invalid, input:focus:invalid {
 
  }
  input:required:valid {
    
  }
</style>