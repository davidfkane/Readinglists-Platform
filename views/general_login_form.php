<?php 
if(!defined($this->session->userdata('authorised')) || $this->session->userdata('authorised') != 'TRUE'){ 
// if not logged in
	  if(!isset($logintype)){$logintype = '';}
	  if(!isset($invitationcode)){$invitationcode = '';}
// phpinfo();
//print "testing";
if ($_SERVER['SERVER_PORT']!=443){
	// this is to make sure that we stay on the secure port.
	$url = "https://". $_SERVER['SERVER_NAME'] . ":443".$_SERVER['REQUEST_URI'];
	header("Location: $url");
}
#if(!defined($this->session->userdata('authorised')) && $this->session->userdata('authorised') == 'TRUE'){ 	  // if not logged in
#	header("Location: /".$this->config->item('path')."lists/");
#}
	
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

	
$(document).ready(function()
{		
	$(function() {
		$("#username").focus();
	});
	$(window).keypress(function(e) {
		if(e.keyCode == 13) {
			//if($('form#loginform input').is(':visible')){
				$("#loginform").submit();
			//}
		}
	});

	
	$("input#rlsubmitbutton").click(function(){
		$("form#rlformtag").submit();	
	});
}); 
</script>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                       
                       <?php if($logintype = 'default'){ ?>
                       
                       
		  <?php if(!isset($_POST['generallogin'])){ ?>
              <div class="panel-heading"><h3 class="panel-title">Please log in first</h3></div>
                    <div class="panel-body">
              <div style="font-size: xx-small; font-style:italic; margin-bottom: 10px;">You will then be directed to the <br />
                <?php echo($this->config->item('instancename')); ?> management interface.</div>
          <?php }else{ // already logged in if variable is set. ?>
              <div class="panel-heading"><h3 class="panel-title">Please try again.</h3></div>
                    <div class="panel-body">
              <div style="font-size: xx-small; color:#660000; font-style:italic; margin-bottom: 10px;">Your details are incorrect.<br />
                Visit the <a href="<?php echo($this->config->item('base_url')); ?>" target="_blank">"<?php echo($this->config->item('instancename')); ?> page</a> for help.</div>
          <?php } ?>
          
                       <!-- <form role="form"> -->
                        <form id="loginform" method='post' action="/<?php echo($this->config->item('path')); ?>lists/authorise/">
                            <fieldset>
                                <div class="form-group"><input class="form-control" placeholder="Username" id="username" name="username" type="text" autofocus></div>
                                <div class="form-group"><input class="form-control" placeholder="Password" name="password" type="password" value=""></div>
                                <input name="invitationcode" type="hidden" value="<?php echo($invitationcode); ?>">
                                <input name="logintype" type="hidden" value="<?php echo($logintype); ?>">
                                <input name="forward" type="hidden" value="<?php echo($forward); ?>">
                                <a onClick="javascript:document.getElementById('loginform').submit()" class="btn btn-lg btn-success btn-block">Login</a>
                            </fieldset>
                        </form>
  					</div> <!-- end panel body-->
          <?php }else{ ?>
          
                  
                       
		
              <div class="panel-heading"><h3 class="panel-title">Please Log In</h3></div>
                    <div class="panel-body">
              <div style="font-size: xx-small; color:#660000; font-style:italic; margin-bottom: 10px;">Your details are incorrect.<br />
                Visit the <a href="<?php echo($this->config->item('base_url')); ?>" target="_blank">"<?php echo($this->config->item('instancename')); ?> page</a> for help.</div>
        
          
                       <!-- <form role="form"> -->
                        <form id="loginform" method='post' action="/<?php echo($this->config->item('path')); ?>lists/authorise/">
                            <fieldset>
                                <div class="form-group"><input class="form-control" placeholder="Username" id="username" name="username" type="text" autofocus></div>
                                <div class="form-group"><input class="form-control" placeholder="Password" name="password" type="password" value=""></div>
                                <input name="invitationcode" type="hidden" value="<?php echo($invitationcode); ?>">
                                <input name="logintype" type="hidden" value="<?php echo($logintype); ?>">
                                <input name="forward" type="hidden" value="<?php echo($forward); ?>">
                                <a onClick="javascript:document.getElementById('loginform').submit()" class="btn btn-lg btn-success btn-block">Login</a>
                            </fieldset>
                        </form>
  					</div> <!-- end panel body-->
          
          
          <?php } ?>
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
<?php }else{
	header('Location: /' . $this->config->item('path'));
}// if logged in ?>
	
    
