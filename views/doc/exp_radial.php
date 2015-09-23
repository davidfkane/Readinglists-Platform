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
<title>Radial Reingold–Tilford Tree</title>
<style>

.node circle {
  fill: #fff;
  stroke: steelblue;
  stroke-width: 1.5px;
}

.node {
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
<script>

var diameter = 2000;

var tree = d3.layout.tree()
    .size([360, diameter / 2 - 120])
    .separation(function(a, b) { return (a.parent == b.parent ? 1 : 2) / a.depth; });

var diagonal = d3.svg.diagonal.radial()
    .projection(function(d) { return [d.y, d.x / 180 * Math.PI]; });

var svg = d3.select("body").append("svg")
    .attr("width", diameter)
    .attr("height", diameter - 150)
  .append("g")
    .attr("transform", "translate(" + diameter / 2 + "," + diameter / 2 + ")");

 //d3.json("/readinglists/reports/moodletest", function(error, flare) {
//d3.json("<?php echo('/readinglists/reports/moodletest'); ?>", function(error, flare) {
d3.json("<?php echo('/readinglists/lists/doc/allmodules'); ?>", function(error, root) {
//d3.json("<?php echo('/readinglists/lists/doc/flarejson'); ?>", function(error, root) {
  var nodes = tree.nodes(root),
      links = tree.links(nodes);

  var link = svg.selectAll(".link")
      .data(links)
    .enter().append("path")
      .attr("class", "link")
      .attr("d", diagonal);

  var node = svg.selectAll(".node")
      .data(nodes)
    .enter().append("g")
      .attr("class", "node")
      .attr("transform", function(d) { return "rotate(" + (d.x - 90) + ")translate(" + d.y + ")"; })

  node.append("circle")
      .attr("r", 4.5);

  node.append("text")
      .attr("dy", ".31em")
      .attr("text-anchor", function(d) { return d.x < 180 ? "start" : "end"; })
      .attr("transform", function(d) { return d.x < 180 ? "translate(8)" : "rotate(180)translate(-8)"; })
      .text(function(d) { return d.name; });
});

d3.select(self.frameElement).style("height", diameter - 150 + "px");

</script>
</body>
</html>
