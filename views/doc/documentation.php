<?php 
$this->load->view('includes/top', array('accesslevel' => 'none')); ?>
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
<!--// end page specific javascript -->
<?php 
$this->load->view('includes/htmltop', array('title' => 'Reports: Main')); ?>
<div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">WIT Reading Lists</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            
			<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">Application Help</div>
                        <div class="panel-body">
                            <p>The WIT Readinglists application is designed to make it easy to manage accessible lists of resources for reuse in other applications.</p>
                        </div>
                    </div>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            
<div class="row">
                <div class="col-lg-12">
                    <div class="jumbotron">
                        <h1>Bookmarklet!</h1>
                        <p>Drag this bookmarklet to your browser bookmarks toolbar! </p>
                        <p><a class="btn btn-primary btn-lg" href="javascript:(function(){var%20jsCode=document.createElement('script');var%20scriptURL='<?php echo($this->config->item('base_url')); ?>lists/bookmarklet/'+(Math.random())+'/';jsCode.setAttribute('src',scriptURL);var%20jsCSS=document.createElement('link');document.body.appendChild(jsCode);})();">+2Readinglist</a>
                        </p>
                        <p>Now click on the link whenever you visit a web resource that you want to share with your students.</p>
                    </div>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            
            
            
            
    <!-- ===/ left column /=== -->
    
    
    
    <!-- ===/ end left column /=== --> 
    
  </div>

<?php $this->load->view('includes/bottom'); ?>



</body></html>