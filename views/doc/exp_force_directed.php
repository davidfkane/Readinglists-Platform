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
  stroke: #fff;
  stroke-width: 1.5px;
}

.link {
  stroke: #999;
  stroke-opacity: .6;
}

</style>
<script type="text/javascript" src="<?php echo('/readinglists/js/d3.min.js'); ?>"></script>
</head>
<body bgcolor="#FF6666">

<script>

var width = 1500,
    height = 900;

var color = d3.scale.category20();
//var size = d3.scale.linear().domain(0,90).range(6,20);

var force = d3.layout.force()
    .charge(-20)
    .linkDistance(60)
    .size([width, height]);

var svg = d3.select("body").append("svg")
    .attr("width", width)
    .attr("height", height);

d3.json("<?php echo('/readinglists/lists/doc/json_forcedirected'); ?>", function(error, graph) {
//d3.json("miserables.json", function(error, graph) {
  force
      .nodes(graph.nodes)
      .links(graph.links)
      .start();

  var link = svg.selectAll(".link")
      .data(graph.links)
      .enter().append("line")
      .attr("class", "link")
      .style("stroke", function(d) { return d3.rgb(255-d.numberofcoauthoredworks*10, 4, d.numberofcoauthoredworks*10).toString()});

  var node = svg.selectAll(".node")
      .data(graph.nodes)
      .enter().append("circle")
      .attr("class", "node")
      .attr("r",  function(d) { return (Math.sqrt(d.numberofworks/Math.PI)*5);})
      .style("fill", function(d) { return d3.rgb(d.citesperpaper*8, 4, 255-d.citesperpaper*3).toString() })
      .call(force.drag);

  node.append("title")
      .text(function(d) { return d.name; });

  force.on("tick", function() {
    link.attr("x1", function(d) { return d.source.x; })
        .attr("y1", function(d) { return d.source.y; })
        .attr("x2", function(d) { return d.target.x; })
        .attr("y2", function(d) { return d.target.y; });

    node.attr("cx", function(d) { return d.x; })
        .attr("cy", function(d) { return d.y; });
  });
});

</script>
</body>
</html>
