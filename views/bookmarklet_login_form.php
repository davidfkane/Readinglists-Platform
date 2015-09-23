<?php
// phpinfo();
//print "testing";
if ($_SERVER['SERVER_PORT']!=443){
	// this is to make sure that we stay on the secure port.
	$url = "https://". $_SERVER['SERVER_NAME'] . ":443".$_SERVER['REQUEST_URI'];
	header("Location: $url");
}
if(isset($sel_email)){
	$sel_email = $this->session->userdata('email');
}
?>
<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title>Reading List: Login</title>

    <!-- Core CSS - Include with every page -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/font-awesome/css/font-awesome.css" rel="stylesheet">

    <!-- SB Admin CSS - Include with every page -->
    <link href="/css/sb-admin.css" rel="stylesheet">
<script type="text/javascript" src="/js/jquery-ui-1.10.4/js/jquery-1.10.2.js"></script>
<script type="text/javascript">

$(document).ready(function(){	
	$(function() {
		$("#username").focus();
	});

	$(window).keypress(function(e) {
		if(e.keyCode == 13) {
			//if($('form#loginform input').is(':visible')){
				$("#rlformtag").submit();
			//}
		}
	});
}); 
</script>
</head>
<body>

<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="login-panel panel panel-default">



   
    
      <?php 
	  if(!defined($this->session->userdata('authorised')) || $this->session->userdata('authorised') != 'TRUE'){ 
	  // if not logged in
	  ?>
		  <?php if(!isset($_POST['bookmarkletlogin'])){ ?>
               
        <div class="panel-heading"><h3 class="panel-title" style="text-align:center">Please log in first</h3></div>
        <div class="panel-body">
          <div style="font-size: xx-small; font-style:italic; margin-bottom: 10px; text-align:center;">You will then be returned to the page <br />
            to try the bookmarklet a second time.</div>
          <?php }else{ // already logged in if variable is set. ?>
               
        <div class="panel-heading"><h3 class="panel-title" style="text-align:center">Please try again</h3></div>
        <div class="panel-body">
          <div style="font-size: xx-small; color:#660000; font-style:italic; margin-bottom: 10px; text-align:center;">Your details are incorrect.<br />
            Visit the <a href="<?php echo($this->config->item('domain')); ?>" target="_blank">"<?php echo($this->config->item('instancename')); ?> page</a> for help.</div>
          <?php } ?>
          
      	<form id="rlformtag" autocomplete="off" method='post' action="/<?php echo($this->config->item('path')); ?>lists/authorise/"  class='nicebox'>
        
        <div class="form-group">
        	<input class="form-control" type="text" placeholder="Username"  autocomplete="off" name="username" id="username" value="username" onClick="this.value='';" />
        </div>
        <div class="form-group">
        	<input class="form-control" type="password" placeholder="Password"  autocomplete="off" name="password" value="password" onClick="this.value='';" />
        </div>
        
        <input type="hidden" value="bookmarkletlogin" name="bookmarkletlogin" />
        
        <?php if(isset($_POST['postdata'])){ ?>
        <input type="hidden" value="<?php echo $_POST['postdata']; ?>" name="postdata" />
        <?php }else{ ?>
        <input type="hidden" value="<?php echo $_SERVER['PHP_SELF']; ?>" name="postdata" />
        <?php } ?>
        <!-- Change this to a button or input when using this as a form -->
                        <a onClick="javascript:document.getElementById('rlformtag').submit()" class="btn btn-lg btn-success btn-block">Log In</a>
      	</form>
        </div> <!-- closing panel body -->
        
      <?php }else{ // if logged in ?>
       
          	<div class="panel-heading"><h3 class="panel-title" style="text-align:center;">Add this URL to your lists</h3></div>
            <div class="panel-body">
            
            <div style="font-size: xx-small; font-style:italic; margin-bottom: 10px; text-align:center">Now you need to choose which <br />list you want to add this to.</div>  
      
      
        <form action="<?php echo($this->config->item('base_url')); ?>lists/bookmarklet_add/" method="post" id="rlformtag">
            
            <div class="form-group">
                <select name="staffmodules" class="form-control" id="staffmodules">
                    <option value="0" >Quick Add</option> 
                    <?php
                    foreach($modules as $result){
                        print("\t\t\t<option value=\"".$result['mid']."\" >" . $result['modulename'] . "</option> \n");
                    }
                    ?>
                </select>
            </div>
                    
            <input type="hidden" value="1" name="bookmarklet" id="bm_bookmarklet">
            <input type="hidden" value="postdata" name="page" id="page">
			<?php 
            $postvars = explode('&', base64_decode($this->uri->segment(3, 0)));
            foreach($postvars as $var){
                $ar = explode('=', $var);
                if(isset($ar[1])){
                    echo("\t\t<input type=\"hidden\" value=\"$ar[1]\" name=\"" . $ar[0] . "\" id=\"" . str_replace(array('[',']'),'',$ar[0]) . "\">\n");
                }
            }
            ?>
           		<!-- Change this to a button or input when using this as a form -->
                                <a onClick="javascript:document.getElementById('rlformtag').submit()" class="btn btn-lg btn-success btn-block">Add!</a>
        </form>
        </div>
        </div>
        <!-- <br style="clear:both"/>
        [<a href="/<?php echo($this->config->item('path')); ?>lists/" target="_blank"><?php echo($this->config->item('instancename')); ?> page</a>]
        </div></div> -->
    <?php } // end if logged in
	 ?>
     
						
        	</div>    
        </div>
    </div>
</div>   

<a href="/readinglists/lists">.</a>
    <!-- Core Scripts - Include with every page -->
    <script src="/js/jquery-1.10.2.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/plugins/metisMenu/jquery.metisMenu.js"></script>

    <!-- SB Admin Scripts - Include with every page -->
    <script src="/js/sb-admin.js"></script>

</body>

</html>  
