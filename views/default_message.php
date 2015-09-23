<?php 
if ($_SERVER['SERVER_PORT']!=443){
	// this is to make sure that we stay on the secure port.
	$url = "https://". $_SERVER['SERVER_NAME'] . ":443".$_SERVER['REQUEST_URI'];
	header("Location: $url");
}
?>
<!DOCTYPE html>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!-- For some inspiration:  http://www.noupe.com/php/beautiful-forms.html -->

<title>Reading List: Main</title>
<link type="text/css" href="/<?php echo($this->config->item('path')); ?>includes/style.css" rel="Stylesheet" />
<link type="text/css" href="/<?php echo($this->config->item('path')); ?>includes/buttons.css" rel="Stylesheet" />
<link type="text/css" href="/<?php echo($this->config->item('path')); ?>jquery/css/ui-lightness/jquery-ui-1.8.11.custom.css" rel="Stylesheet" />
<script type="text/javascript" src="/<?php echo($this->config->item('path')); ?>jquery/js/jquery-1.6.4.min.js"></script>
<script type="text/javascript" src="/<?php echo($this->config->item('path')); ?>jquery/js/jquery-ui-1.8.11.custom.min.js"></script>
<style type="text/css">
div#box_maincontent {
	padding: 0px;
}
.ui-autocomplete-loading {
	background: white url('images/ui-anim_basic_16x16.gif') right center no-repeat;
}
#modulesearch {
	width: 25em;
}
</style>
<script>
function monkeyPatchAutocomplete() {
      // don't really need this, but in case I did, I could store it and chain
      var oldFn = $.ui.autocomplete.prototype._renderItem;
      $.ui.autocomplete.prototype._renderItem = function( ul, item) {
		  var terms = this.term.split(" ");
		  var t = item.label;
		  alert(terms.length);
		  for (var i = 0; i < terms.length; i++) {
          	var re = new RegExp(this.term, 'i') ;
          	t = t.replace(re,"<span style='font-weight:bold;color:Blue;'>" + terms[i] + "</span>");
		  }
          return $( "<li></li>" )
              .data( "item.autocomplete", item )
              .append( "<a>" + t + "</a>" )
              .appendTo( ul );
      };

  }
$(function () {
    monkeyPatchAutocomplete();
    $("#modulesearch").autocomplete({
        source: function (request, response) {
            $.ajax({
                url: "<?php echo($this->config->item('base_url')); ?>display/search",
                dataType: "jsonp",
                data: {
                    featureClass: "P",
                    style: "full",
                    maxRows: 12,
                    name_startsWith: request.term
                },
                success: function (data) {
                    response($.map(data.modules, function (item) {
                        return {
                            label: item.modulename,
                            value: item.modulename,
			    yoke: item.mid, 
			    teachers: item.teachers,
			    moodleid: item.MOODLE_INTERNAL_ID
                        }
                    }));
                }
            });
        },
        minLength: 2,
		change: function( event, ui ) {
			$('#modulesearch').css("background-image", "none");
		},
		response: function( event, ui ) {
			$('#modulesearch').css("background-image", "url(/mod/readinglist/images/ajax-loader.gif)");
		},

        select: function (event, ui) {
			url = "<?php echo($this->config->item('base_url')); ?>lists/fetch/rlists_module/" + ui.item.yoke;
			//window.open(url, '_blank');
 			//window.focus();
			$('#modulesearch').value = ui.item.label;
			$('#modulesearch').blur();
			$('#list-title').text(ui.item.label);
			$('#change-link').attr('href', '<?php echo($this->config->item('base_url')); ?>lists/fetch/web/' + ui.item.moodleid + '/EDIT');
			$('#moodle-link').attr('href', 'http://library.wit.ie/moodle/view.php?id=' + ui.item.moodleid);
			$('#moodle-link img').attr('src', '/mod/readinglist/images/moodle-link-visible.png');
			$('#helpbox_trigger').attr('src', '/mod/readinglist/images/help_plus.png');
			//$('#helpbox_inner').css('display', 'none');
			$.ajax
			({
				type: "GET", url: "<?php echo($this->config->item('base_url')); ?>index.php/lists/list_books/website/"+ui.item.moodleid+"/EDIT", success: function(html)
				{
					//alert(dataString);
					$("#list").html(html);	
				}
			});
			
			// $url = $readinglistserver . "readinglists/index.php/lists/list_books/moodle/" . $course->id . "/" . $USER->username;
			// $HTML = file_get_contents($url);
        },
        open: function () {$(this).removeClass("ui-corner-all").addClass("ui-corner-top");},
        close: function () {$(this).removeClass("ui-corner-top").addClass("ui-corner-all");}
    }) 
	.data( "autocomplete" )._renderItem = function( ul, item ) {
		return $( "<li>" )
		.data( "item.autocomplete", item )
		.append( "<a>" + item.label + "<br><span style='font-size: xx-small; color: #303030'>" + item.teachers + "</span></a>" )
		.appendTo( ul );
	};
});



$(document).ready(function() {

	$("input#modulesearch").click(function() {
		//$('#modulesearch').css("background-image", "url(/mod/readinglist/images/ajax-loader.gif)");
		$("html, body").animate({
			//scrollTop: $('#scrollto').offset().top + "px"
			//scrollTop: "300px"
		}, {
			duration: 500,
			easing: "swing"
		});
		return false;
	});

});

</script>
</head>
<body>
<header> <a href="/<?php echo($this->config->item('path')); ?>">Home</a> <a href="/<?php echo($this->config->item('path')); ?>lists/">Your Lists</a> <a href="/<?php echo($this->config->item('path')); ?>">Admin</a> </header>
<div id="content"> 
  
  <!-- ===/ start main content /=== -->
  <div id="container"> 
    <!-- ===/ left column /=== -->
    
    <div id="list"> 
      <!--  Stuff goes here in the left column -->
      <h1 class="legend">Drag this bookmarklet to your browser bookmarks toolbar!</h1>
      <div class="fieldset">
        <div class="actionbutton"><a href="javascript:(function(){var%20jsCode=document.createElement('script');var%20scriptURL='<?php echo($this->config->item('base_url')); ?>lists/bookmarklet/'+(Math.random())+'/';jsCode.setAttribute('src',scriptURL);var%20jsCSS=document.createElement('link');document.body.appendChild(jsCode);})();">+2Readinglist</a></div>

       
        <p>Now click on the link whenever you visit a web resource that you want to share with your students.</p>
      </div>
        <h1 class="legend">Moodle </h1>
      <div class="fieldset">
      <br/>
        <p>This demonstration is integrated with a demonstration version of Moodle (<a href="/moodle/" target="_blank" style="font-weight: bold; color:#039">click here</a>).
        </div>
        <h1 class="legend">Search for lists below.</h1>
      <div class="fieldset">
      <br/>
        <div class="ui-widget">
          <input id="modulesearch" onfocus="this.value = '';" size="180" style="padding: 4px; float: left; background-repeat: no-repeat; background-position: right; " value="Add keywords: teacher name, email, modulename ... "  />
          <img style="cursor:pointer; margin: 0px;" src="/mod/readinglist/images/help_hidden.png" id="helpbox_trigger"><a style="display: inline; text-decoration: none; color: black; cursor: pointer;" target="_blank" id="change-link" href="https://vle.wit.ie/"><img alt="Modify List" src="/mod/readinglist/images/moodle-link-hidden.png"></a> </div>
      </div>
      <!-- end insert code --> 
    </div>
    
    <!-- ===/ end left column /=== --> 
    
  </div>
</div>
<!-- ===/ end main content /=== -->

</div>
</body>
</html>