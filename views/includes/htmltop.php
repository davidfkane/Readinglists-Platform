</head>
<body>
<div id="wrapper" style="background-color: white;"> <!-- id was content -->


  <!-- ===/ start main content /===  --><div id="container">  
  <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0; background-color: white;">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.html">WIT Reading Lists</a>
            </div>
        <!--   /.navbar-header -->


        </nav>
     <!--  /.navbar-static-top -->

  <nav class="navbar-default navbar-static-side" role="navigation"style="background-color: white;">
            <div class="sidebar-collapse">
                <ul class="nav" id="side-menu">
                    <li><a href="/<?php echo($this->config->item('path')); ?>"><i class="fa fa-home fa-fw"></i> Home</a></li>
                    <li><a href="/<?php echo($this->config->item('path')); ?>lists/"><i class="fa fa-list-alt fa-fw"></i> Your Lists</a></li>
                    
<?php                    
if(defined($this->session->userdata('authorised')) && $this->session->userdata('authorised') == 'TRUE'){ 	  // if not logged in
?>
                    <li><a href="/<?php echo($this->config->item('path')); ?>lists/logout/"><i class="fa fa-sign-out fa-fw"></i> Log Out</a></li>
<?php
}else{
?>
                    <li><a href="/<?php echo($this->config->item('path')); ?>lists/logout/"><i class="fa fa-sign-out fa-fw"></i> Log In</a></li>
<?php
} 
?>
                    <li><a href="/<?php echo($this->config->item('path')); ?>lists/doc/documentation"><i class="fa fa-user-md fa-fw"></i> Help Pages</a></li>
                    <li><a title="Drag this bookmarklet up to the favourites toolbar on your browser.  When you visit a site you like, you can save its details to the readinglists application in a click." href="javascript:(function(){var%20jsCode=document.createElement('script');var%20scriptURL='<?php echo($this->config->item('base_url')); ?>lists/bookmarklet/'+(Math.random())+'/';jsCode.setAttribute('src',scriptURL);var%20jsCSS=document.createElement('link');document.body.appendChild(jsCode);})();"><i class="fa fa-asterisk"></i> +2Readinglist</a></li>
                    <li><a href="/buttons.html"> .</a></li>
                </ul>
               <!--   /#side-menu -->
            </div>
          <!--   /.sidebar-collapse -->
        </nav>
       <!--   /.navbar-static-side -->
         
   
        <div id="page-wrapper" style="background-color: white;">
        
     