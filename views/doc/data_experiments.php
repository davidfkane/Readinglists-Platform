<?php $this->load->view('doc/simpleauthtop', array('accesslevel' => 'none')); ?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Reading Lists</title>
<link type="text/css" href="/readinglists/includes/jquery.tagsinput.css" rel="Stylesheet" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="image_src" href="http://library.wit.ie/readinglists/includes/svgmenu_screenshot.jpg" / >
<!-- Core CSS - Include with every page -->
<link href="/css/bootstrap.min.css" rel="stylesheet">
<link href="/font-awesome/css/font-awesome.css" rel="stylesheet">

<!-- Page-Level Plugin CSS - Dashboard -->
<link href="/css/plugins/morris/morris-0.4.3.min.css" rel="stylesheet">
<link href="/css/plugins/social-buttons/social-buttons.css" rel="stylesheet">

<!-- SB Admin CSS - Include with every page -->
<link href="/css/sb-admin.css" rel="stylesheet">
<meta charset="utf-8">
<title>Data Visualisation Experiments</title>

</head>
<body>
<div class="row">
  <div class="col-lg-12">
    <div class="jumbotron" style="margin: 0px; border-bottom: solid 1px #c0c0c0; width: 100%;">
      <h1>Data Visualisation</h1> 
<nav class="navbar navbar-default" role="navigation"> 
  <div class="container-fluid"> 
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header"> 
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span> 
        <span class="icon-bar"></span> 
        <span class="icon-bar"></span> 
        <span class="icon-bar"></span> 
      </button>
      <a class="navbar-brand" href="#">Experiments:</a> 
    </div> 

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li><a href="/<?php echo($this->config->item('path')); ?>lists/doc/exp_hierarchical">Hierarchical Tree</a></li>
        <li><a href="/<?php echo($this->config->item('path')); ?>lists/doc/exp_radial">Radial Tree</a></li>
        <li><a href="/<?php echo($this->config->item('path')); ?>lists/doc/exp_packedcircles">Zoomable Packed Circles</a></li>
        <li><a href="/<?php echo($this->config->item('path')); ?>lists/doc/exp_treemap">Treemap</a></li>
        <li><a href="/<?php echo($this->config->item('path')); ?>lists/doc/exp_force_directed">Co-Authorship Network</a></li>
        
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
    </div>
  </div>
  <!-- /.col-lg-12 --> 
</div>

<div class="row">
  <div class="col-lg-12" style="padding: 50px;">
    <div class="panel panel-default">
      <div class="panel-body">
        <h3>Hirearchical Tree:</h3>
        <p>Showing only nodes that have students in them</p>
        <p>&nbsp;</p>
      </div>
    </div>
    
    <div class="panel panel-default">
      <div class="panel-body">
        <h3>Radial Tree</h3>
        <p>As above</p>
      </div>
    </div>
    
    <div class="panel panel-default">
      <div class="panel-body">
        <h3>Zoomable Packed Circles</h3>
        <p>Only terminal nodes with students attached</p>
      </div>
    </div>
    
    <div class="panel panel-default">
      <div class="panel-body">
        <h3>Treemap</h3>
        <p></p>
      </div>
    </div>
    
    <div class="panel panel-default">
      <div class="panel-body">
        <h3>Force Directed</h3>
        <p></p>
      </div>
    </div>
    
  </div>
</div>


<div class="row">
  <div class="col-lg-12">
    <div class="jumbotron" style="margin: 0px; border-top: solid 1px #c0c0c0; width: 100%;">
      <p><img src="/readinglists/includes/witlogo.png" alt="Waterford Institute of Technology Crest" width="258" height="54" style="float:right;" title="Waterford Institute of Technology Libraries"/>WIT Libraries</p>
    </div>
  </div>
  <!-- /.col-lg-12 --> 
</div>
</body>
</html>
