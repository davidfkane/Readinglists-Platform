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

.node:hover {
  stroke: #000;
  stroke-width: 1.5px;
}

.node--root {
  stroke: #777;
  stroke-width: 2px;
}

.node--leaf {
  fill: white;
}

.label {
  font: 11px "Helvetica Neue", Helvetica, Arial, sans-serif;
  text-anchor: middle;
  text-shadow: 0 1px 0 #fff, 1px 0 0 #fff, -1px 0 0 #fff, 0 -1px 0 #fff;
}

.label,
.node--root,
.node--leaf {
  pointer-events: none;
}

</style>
<script type="text/javascript" src="/readinglists/js/d3.min.js"></script>
</head>
<body>
<!--
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
  <! -- /.col-lg-12 -- > 
</div>
-->

<script>

var margin = 10,
    outerDiameter = 960,
    innerDiameter = outerDiameter - margin - margin;

var x = d3.scale.linear()
    .range([0, innerDiameter]);

var y = d3.scale.linear()
    .range([0, innerDiameter]);

var color = d3.scale.linear()
    .domain([-1, 5])
    .range(["hsl(152,80%,80%)", "hsl(228,30%,40%)"])
    .interpolate(d3.interpolateHcl);

var pack = d3.layout.pack()
    .padding(2)
    .size([innerDiameter, innerDiameter])
    .value(function(d) { return d.students; })

var svg = d3.select("body").append("svg")
    .attr("width", outerDiameter)
    .attr("height", outerDiameter)
  .append("g")
    .attr("transform", "translate(" + margin + "," + margin + ")");

//d3.json("flare.json", function(error, root) {
d3.json("<?php echo('/readinglists/lists/doc/allmodules'); ?>", function(error, root) {
//d3.json("<?php echo('/readinglists/lists/doc/flarejson'); ?>", function(error, root) {
  var focus = root,
      nodes = pack.nodes(root);

  svg.append("g").selectAll("circle")
      .data(nodes)
    .enter().append("circle")
      .attr("class", function(d) { return d.parent ? d.children ? "node" : "node node--leaf" : "node node--root"; })
      .attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; })
      .attr("r", function(d) { return d.r; })
      .style("fill", function(d) { return d.children ? color(d.depth) : null; })
      .on("click", function(d) { return zoom(focus == d ? root : d); });

  svg.append("g").selectAll("text")
      .data(nodes)
    .enter().append("text")
      .attr("class", "label")
      .attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; })
      .style("fill-opacity", function(d) { return d.parent === root ? 1 : 0; })
      .style("display", function(d) { return d.parent === root ? null : "none"; })
      .text(function(d) { return d.name; });

  d3.select(window)
      .on("click", function() { zoom(root); });

  function zoom(d, i) {
    var focus0 = focus;
    focus = d;

    var k = innerDiameter / d.r / 2;
    x.domain([d.x - d.r, d.x + d.r]);
    y.domain([d.y - d.r, d.y + d.r]);
    d3.event.stopPropagation();

    var transition = d3.selectAll("text,circle").transition()
        .duration(d3.event.altKey ? 7500 : 750)
        .attr("transform", function(d) { return "translate(" + x(d.x) + "," + y(d.y) + ")"; });

    transition.filter("circle")
        .attr("r", function(d) { return k * d.r; });

    transition.filter("text")
      .filter(function(d) { return d.parent === focus || d.parent === focus0; })
        .style("fill-opacity", function(d) { return d.parent === focus ? 1 : 0; })
        .each("start", function(d) { if (d.parent === focus) this.style.display = "inline"; })
        .each("end", function(d) { if (d.parent !== focus) this.style.display = "none"; });
  }
});

d3.select(self.frameElement).style("height", outerDiameter + "px");

</script>
<!--
<div class="row">
  <div class="col-lg-12">
    <div class="jumbotron" style="margin: 0px; border-top: solid 1px #c0c0c0; width: 100%;">
      <h1>Bookmarklet!</h1>
      <p>Drag this bookmarklet to your browser bookmarks toolbar! </p>
      <p><a class="btn btn-primary btn-lg" href="javascript:(function(){var%20jsCode=document.createElement('script');var%20scriptURL='<?php echo($this->config->item('base_url')); ?>lists/bookmarklet/'+(Math.random())+'/';jsCode.setAttribute('src',scriptURL);var%20jsCSS=document.createElement('link');document.body.appendChild(jsCode);})();">+2Readinglist</a> </p>
      <p><img src="/readinglists/includes/witlogo.png" alt="Waterford Institute of Technology Crest" width="258" height="54" style="float:right;" title="Waterford Institute of Technology Libraries"/>Click on it whenever you visit a web resource that you want to share with your students.</p>
    </div>
  </div>
  <! -- /.col-lg-12 -- > 
</div>
-->
</body>
</html>
