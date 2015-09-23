<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title>Reading List: Login</title>

    <!-- Core CSS - Include with every page -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">

    <!-- SB Admin CSS - Include with every page -->
    <link href="css/sb-admin.css" rel="stylesheet">
    <script type="text/javascript" src="/js/jquery-ui-1.10.4/js/jquery-1.10.2.js"></script>
<script type="text/javascript">

$(document).ready(function(){	
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
}); 
</script>

</head>

<body>




    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><?php		echo($Message);	?></h3>
                    </div>
                    <div class="panel-body">
                       <!-- <form role="form"> -->
                           <form method=post action="/<?php echo($this->config->item('path')); ?>lists/authorise/"  name="ReadingList" id="rlformtag">
                            
                            
          
                            
                            
                            
                            <fieldset>
          
                                <div class="form-group">
                                    <input class="form-control" placeholder="E-mail" name="username" type="text" autofocus>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Password" name="password" type="password" value="">
                                </div>
                                <!-- <div class="checkbox">
                                    <label>
                                        <input name="remember" type="checkbox" value="Remember Me">Remember Me
                                    </label>
                                </div>
                                -->
                                <!-- Change this to a button or input when using this as a form -->
                                <a href="index.html" class="btn btn-lg btn-success btn-block">Login</a>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


     

    <!-- Core Scripts - Include with every page -->
    <script src="js/jquery-1.10.2.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/plugins/metisMenu/jquery.metisMenu.js"></script>

    <!-- SB Admin Scripts - Include with every page -->
    <script src="js/sb-admin.js"></script>

</body>

</html>
