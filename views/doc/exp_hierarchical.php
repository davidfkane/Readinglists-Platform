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
<title>D3 Test</title>
<style>
.node {
	cursor: pointer;
}
.node circle {
	fill: #fff;
	stroke: steelblue;
	stroke-width: 1.5px;
}
.node text {
	font: 10px sans-serif;
}
.link {
	fill: none;
	stroke: #ccc;
	stroke-width: 1.5px;
}
</style>
<script type="text/javascript" src="/readinglists/js/d3.min.js"></script>
</head>
<body>
<div class="row">
  <div class="col-lg-12">
    <div class="jumbotron" style="margin: 0px; border-bottom: solid 1px #c0c0c0; width: 100%;">
      <h1>Find Your Module Below!</h1>
      <p>Explore the interactive menu below with your mouse to find your module:</p>
      <p>Modules are marked with a red circle.
        <svg  height="16" width="16">
          <circle r="7" cx="8" cy="8" style="cursor: pointer; fill: #ff0000; stroke: steelblue; stroke-width: 1.5px;"></circle>
        </svg>
      </p>
      <blockquote style="margin: 0px;"><em> - <a href="http://library.wit.ie/" target="_blank" style="text-decoration: none; color: black;">WIT Libraries</a></em></blockquote>
    </div>
  </div>
  <!-- /.col-lg-12 --> 
</div>
<script type="text/javascript">
            var margin = {top: 20, right: 120, bottom: 20, left: 120},
    width = 1500 - margin.right - margin.left,
    height = 5000 - margin.top - margin.bottom;
    
var i = 0, duration = 750, root;
var tree = d3.layout.tree().size([height, width]);
var diagonal = d3.svg.diagonal().projection(function(d) { return [d.y, d.x]; });

var svg = d3.select("body").append("svg")
    .attr("width", width + margin.right + margin.left)
    .attr("height", height + margin.top + margin.bottom)
  .append("g")
    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");
 //d3.json("/readinglists/reports/moodletest", function(error, flare) {
//d3.json("<?php echo('/readinglists/reports/moodletest'); ?>", function(error, flare) {
d3.json("<?php echo('/readinglists/lists/doc/allmodules'); ?>", function(error, flare) {
//d3.json("<?php echo('/readinglists/lists/doc/flarejson'); ?>", function(error, flare) {
  root = flare;
  root.x0 = height / 2;
  root.y0 = 0;

  function collapse(d) {
    if (d.children) {
      d._children = d.children;
      d._children.forEach(collapse);
      d.children = null;
    }
  }
  function collapse1(d) {
    if (d.children) {
		d.children.forEach(collapse);
    }
  }
  function collapse2(d) {
    if (d.children) {
		d.children.forEach(collapse1);
    }
  }

  function collapse3(d) {
    if (d.children) {
		d.children.forEach(collapse2);
    }
  }

  root.children.forEach(collapse3);
  update(root);
});

d3.select(self.frameElement).style("height", "800px");

function update(source) {

  // Compute the new tree layout.
  var nodes = tree.nodes(root).reverse(),
      links = tree.links(nodes);

  // Normalize for fixed-depth.
  nodes.forEach(function(d) { 
	     if(d.level == 1){  d.y = d.depth * 70; }
	else if(d.level == 2){  d.y = d.depth * 80; }
	else if(d.level == 3){  d.y = d.depth * 90; }
	else if(d.level == 4){  d.y = d.depth * 100; }
	else if(d.level == 5){  d.y = d.depth * 170; }
	else if(d.level == 6){  d.y = d.depth * 170; }
	                 else{  d.y = d.depth * 169; }
  });
  // Update the nodes�
  var node = svg.selectAll("g.node")
      .data(nodes, function(d) { return d.id || (d.id = ++i); });

  // Enter any new nodes at the parent's previous position.
  var nodeEnter = node.enter().append("g")
      .attr("class", "node")
      .attr("transform", function(d) { return "translate(" + source.y0 + "," + source.x0 + ")"; })
      .on("click", click);

  nodeEnter.append("circle")
      .attr("r", 1e-6)
      .style("fill", function(d) {	
		if(d.type != 'terminal'){
			if(d._children){ return "lightsteelblue";}
			else{ return "#fff";}
		}else{
			return "#f00";
		}
	});

  nodeEnter.append("text")
      .attr("x", function(d) { return d.children || d._children ? -10 : 10; })
      .attr("dy", ".35em")
      .attr("text-anchor", function(d) { return d.children || d._children ? "end" : "start"; })
      .text(function(d) { return d.name + '(' + d.level + ')'; })
      .style("fill-opacity", 1e-6);

  // Transition nodes to their new position.
  var nodeUpdate = node.transition()
      .duration(duration)
      .attr("transform", function(d) { return "translate(" + d.y + "," + d.x + ")"; });

  nodeUpdate.select("circle")
      .attr("r", 7)
     // .style("fill", function(d) { return d._children ? "lightsteelblue" : "#fff"; });
      .style("fill", function(d) {	
						if(d.type != 'terminal'){
							if(d._children){ return "lightsteelblue";}
							else{ return "#fff";}
						}else{
							return "#f00";
						}
					});

  nodeUpdate.select("text")
      .style("fill-opacity", 1);
  // Transition exiting nodes to the parent's new position.
  var nodeExit = node.exit().transition()
      .duration(duration)
      .attr("transform", function(d) { return "translate(" + source.y + "," + source.x + ")"; })
      .remove();

  nodeExit.select("circle")
      .attr("r", 1e-6);

  nodeExit.select("text")
      .style("fill-opacity", 1e-6);

  // Update the links�
  var link = svg.selectAll("path.link")
      .data(links, function(d) { return d.target.id; });

  // Enter any new links at the parent's previous position.
  link.enter().insert("path", "g")
      .attr("class", "link")
      .attr("d", function(d) {
        var o = {x: source.x0, y: source.y0};
        return diagonal({source: o, target: o});
      });

  // Transition links to their new position.
  link.transition()
      .duration(duration)
      .attr("d", diagonal);

  // Transition exiting nodes to the parent's new position.
  link.exit().transition()
      .duration(duration)
      .attr("d", function(d) {
        var o = {x: source.x, y: source.y};
        return diagonal({source: o, target: o});
      })
      .remove();

  // Stash the old positions for transition.
  nodes.forEach(function(d) {
    d.x0 = d.x;
    d.y0 = d.y;
  });
}

	function OpenInNewTab(url){
	  var win=window.open(url, '_blank');
	  win.focus();
	}

// Toggle children on click.
function click(d) {
  if(d.type == 'nodal'){
	  if (d.children) {
		d._children = d.children;
		d.children = null;
	  } else {
		d.children = d._children;
		d._children = null;
	  }
  }else{
	  OpenInNewTab('/readinglists/lists/list_items/1/1/'+d.size+'/html/'+encodeURI(d.name));
  }
  update(d);
}
        </script>
<div class="row">
  <div class="col-lg-12">
    <div class="jumbotron" style="margin: 0px; border-top: solid 1px #c0c0c0; width: 100%;">
      <h1>Bookmarklet!</h1>
      <p>Drag this bookmarklet to your browser bookmarks toolbar! </p>
      <p><a class="btn btn-primary btn-lg" href="javascript:(function(){var%20jsCode=document.createElement('script');var%20scriptURL='<?php echo($this->config->item('base_url')); ?>lists/bookmarklet/'+(Math.random())+'/';jsCode.setAttribute('src',scriptURL);var%20jsCSS=document.createElement('link');document.body.appendChild(jsCode);})();">+2Readinglist</a> </p>
      <p><img src="/readinglists/includes/witlogo.png" alt="Waterford Institute of Technology Crest" width="258" height="54" style="float:right;" title="Waterford Institute of Technology Libraries"/>Click on it whenever you visit a web resource that you want to share with your students.</p>
    </div>
  </div>
  <!-- /.col-lg-12 --> 
</div>
</body>
</html>
