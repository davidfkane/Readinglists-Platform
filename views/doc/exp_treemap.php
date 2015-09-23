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

body {
  font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
  margin: auto;
  position: relative;
  width: 960px;
}

form {
  position: absolute;
  right: 10px;
  top: 10px;
}

.node {
  border: solid 1px white;
  font: 10px sans-serif;
  line-height: 12px;
  overflow: hidden;
  position: absolute;
  text-indent: 2px;
}

</style>
<script type="text/javascript" src="/readinglists/js/d3.min.js"></script>
</head>
<body>

<script>

var margin = {top: 0, right: 0, bottom: 0, left: 0},
    width = 1600 - margin.left - margin.right,
    height = 900 - margin.top - margin.bottom;

var color = d3.scale.category20c();

var treemap = d3.layout.treemap()
    .size([width, height])
    .sticky(true)
    .value(function(d) { return d.students; });

var div = d3.select("body").append("div")
    .style("position", "relative")
    .style("width", (width + margin.left + margin.right) + "px")
    .style("height", (height + margin.top + margin.bottom) + "px")
    .style("left", margin.left + "px")
    .style("top", margin.top + "px");

//d3.json("flare.json", function(error, root) {
//d3.json("<?php echo('/readinglists/reports/moodletest'); ?>", function(error, flare) {
d3.json("<?php echo('/readinglists/lists/doc/allmodules'); ?>", function(error, root) {
//d3.json("<?php echo('/readinglists/lists/doc/flarejson'); ?>", function(error, flare) {
  var node = div.datum(root).selectAll(".node")
      .data(treemap.nodes)
    .enter().append("div")
      .attr("class", "node")
      .call(position)
      .style("background", function(d) { return d.children ? color(d.parentname) : null; })
      .text(function(d) { return d.children ? null : d.name; });

  d3.selectAll("input").on("change", function change() {
    var value = this.value === "count"
        ? function() { return 1; }
        : function(d) { return d.size; };

    node
        .data(treemap.value(value).nodes)
      .transition()
        .duration(1500)
        .call(position);
  });
});

function position() {
  this.style("left", function(d) { return d.x + "px"; })
      .style("top", function(d) { return d.y + "px"; })
      .style("width", function(d) { return Math.max(0, d.dx - 1) + "px"; })
      .style("height", function(d) { return Math.max(0, d.dy - 1) + "px"; });
}

</script>
</body>
</html>
