<?php 
$this->load->view('includes/top', array('accesslevel' => 'teacher')); ?>
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

<?php
header("Content-type: text/html");
print("<html>\n<head><title>Refreshme</title></head>\n<body>\n");
$serialized = base64_encode(serialize($serialized));
if($module_title != 'NO_DATA'){
	?>
	<div class="readinglist_html">
	<script language="javascript">
	$('div#page div.navbar div.navbutton form[action|=https://vle.wit.ie/course/mod.php]').attr('action', '<?php echo("".base_url()."lists/edit_list/module/" . $serialized . ""); ?>');
	</script>
	<?php 
	$typecolour = array('pink', 'beige', 'yellow'); 
	$typecolourborder = array('#990000', '#E0B841', '#E0B841'); 
	$typeicon = array('icon_type_book.png', 'icon_type_article.png', 'icon_type_web.png'); 
	$displaycount = 0;		
	$helpmessage = "";
	$rlist_items = "";
	if($module_title == 'NOT SPECIFIED'){
		$module_title = $titlefromsvg;
	}
	?>
<div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">Module: <?php echo($module_title); ?></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            
			<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            &nbsp;
                        </div>
                        <div class="panel-body"> <?php
						//print_r($results);
						if(array_key_exists('results', $results)){
							foreach ($results['results'] as $row){	
								$displaycount++;
								$var = 	print_r($row, true);
								print('<pre>'.$var.'</pre>');
								// END RENDERING READINGLIST ITEM
								print("<hr/>");
							}
						}else{
							?><p>The list for this module may not exist yet, or at least may have no items in it.</p><?php
	// echo "<a class=\"btn btn-primary btn-sm\" href=\"".base_url()."lists/edit_list/module/" . $serialized . "\" target=\"_blank\">EDIT LINK</a>";
						}
?>
                        </div>
                    </div>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            
           

<?php
}else{
	// module doesn't exist
	?>
<div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">No Readinglist for this Module</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
    <?php
}

           ?> 

            
            
            
            
    <!-- ===/ left column /=== -->
    
    
    
    <!-- ===/ end left column /=== --> 
    
  </div>

<?php $this->load->view('includes/bottom'); ?>
