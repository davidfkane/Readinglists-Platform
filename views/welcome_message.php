<?php 
if ($_SERVER['SERVER_PORT']!=443){
	// this is to make sure that we stay on the secure port.
	$url = "https://". $_SERVER['SERVER_NAME'] . ":443".$_SERVER['REQUEST_URI'];
	header("Location: $url");
}
?>


<?php if($this->config->item('base_url') == 'https://researchscope.ie/readinglists/'){ ?>
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
<div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">WIT Reading Lists</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            
			<div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            About
                        </div>
                        <div class="panel-body">
                            <p>Welcome to WIT Readinglists</p>
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




<?php }else{ ?>

<!DOCTYPE html>
<!--[if lt IE 7 ]> <html class="no-js ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]>    <html class="no-js ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]>    <html class="no-js ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html class="no-js" lang="en">
<!--<![endif]-->
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<title>Waterford Institute of Technology Library</title>
<meta name="robots" content="noindex, nofollow">
<meta name="description" content="">
<meta name="author" content="">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="shortcut icon" href="/favicon.ico">
<link href="/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="/assets/css/boxes_tabs_subpages.php?theme=green" media="screen">
<link rel="stylesheet" type="text/css" href="/assets/css/main_page.php?theme=green" media="screen">
<link rel="stylesheet" type="text/css" href="/assets/css/modal.css" media="screen">
<link rel="stylesheet" type="text/css" href="/assets/css/jquery.mSimpleSlidebox.css" media="screen">
<link rel="stylesheet" type="text/css" href="/assets/js/smoothness/jquery-ui-1.9.2.custom.min.css" media="screen">

<link rel="stylesheet" type="text/css" href="/assets/css/menu.php?theme=green" media="screen">
<script src="/assets/js/jquery-1.9.0.min.js"></script>
<script src="/assets/js/jquery-ui-1.9.2.custom.js"></script>
<script src="/assets/js/index.js"></script>
<script src="/assets/js/jquery.simplemodal-1.4.2.js" type="text/javascript"></script>
<script src="/assets/js/jquery.mSimpleSlidebox.js" type="text/javascript"></script>
<!-- ===/ end main navigation /=== --> 
<style type="text/css">
div#box_maincontent{ padding: 0px;}
.ui-autocomplete-loading {
	background: white url('images/ui-anim_basic_16x16.gif') right center no-repeat;
}
#modulesearch{
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
                url: "https://library.wit.ie/readinglists/display/search",
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
			url = "https://library.wit.ie/readinglists/lists/fetch/rlists_module/" + ui.item.yoke;
			//window.open(url, '_blank');
 			//window.focus();
			$('#modulesearch').value = ui.item.label;
			$('#modulesearch').blur();
			$('#list-title').text(ui.item.label);
			$('#change-link').attr('href', 'https://library.wit.ie/readinglists/lists/fetch/web/' + ui.item.moodleid + '/EDIT');
			$('#moodle-link').attr('href', 'https://vle.wit.ie/course/view.php?id=' + ui.item.moodleid);
			$('#moodle-link img').attr('src', '/mod/readinglist/images/moodle-link-visible.png');
			$('#helpbox_trigger').attr('src', '/mod/readinglist/images/help_plus.png');
			//$('#helpbox_inner').css('display', 'none');
			$.ajax
			({
				type: "GET", url: "https://library.wit.ie/readinglists/index.php/lists/list_books/website/"+ui.item.moodleid+"/EDIT", success: function(html)
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


</head><body>
<div id="container">
  <div id="header" class="top-header" style="height:110px;">
   <!--  <hgroup id="top_h_text">
      <h1>Waterford Institute of Technology</h1>
      <h2>Institiúid Teicneolaíochta Port Láirge</h2>
    </hgroup> -->
    <div id="search_quicklinks">
    <br/>
      <nav>
        <ul class="quicklinks">
          <li><a href="https://outlook.com/wit.ie" title="Staff email link">Staff Email</a></li>
          <li><a href="http://www.wit.ie/about_wit/for_staff/for_staff" title="Staff information Section">Staff</a></li>
          <li><a href="http://www.wit.ie/current_students/student_life_and_learning/the_careers_centre" title="WIT Careers Centre">Careers Centre</a></li>
          <li><a href="http://vle.wit.ie/" title="WIT Elearning">Moodle</a></li>
          <li><a href="https://www.google.com/a/mail.wit.ie">Student Email</a></li>
          <li><a href="http://www.witcampusservices.ie/WITCard/" title="Campus Services WIT card">WITCard</a></li>
          <li><a href="http://www.wit.ie/news/" title="News and Events">News & Events</a></li>
          <li><a href="http://www.wit.ie/">WIT&nbsp;Home</a></li>
        </ul>
      </nav>
    </div>
    <img src="/assets/images/top_logo_transparent.png" alt="logo" id="logo-image">
    <div id="top_h_images"><img src="/assets/images/h1_text.png" alt="Waterford Institute of Technology" style="float: left; "><img src="/assets/images/h2_text.png" alt="Institiúid Teicneolaíochta Port Láirge" ></div>
  </div>
 <!-- ===/ main heading/branding /=== -->
<div id="bannerimage" style="background-color: white; vertical-align:text-top;"></div>
<!-- ===/ start main navigation /=== -->
<div id="main_nav"> <ul id='true' class='dropdown'>
   <li class="id_nav_home "><a href="/">Home</a></li>

<li class="selected id_nav_services"><a href="#">Services</a><ul>
   <li class="selected"><a href="/Services/borrowing">Borrowing</a></li>

   <li class=""><a href="/Services/using-the-library">Using the Library</a></li>

   <li class=""><a href="/Services/more-for-researchers">More for Researchers</a></li>

   <li class=""><a href="/Services/learning-support-programme">Learning Support Programme</a></li>

   <li class=""><a href="/Services/interlibrary-loans">Interlibrary Loans</a></li>

   <li class=""><a href="/Services/book-a-room">Book a Room</a></li>

   <li class=""><a href="/Services/information-service">Information Service</a></li>

   <li class=""><a href="/Services/off-campus-access">Off Campus Access</a></li>

   <li class=""><a href="https://library.wit.ie/readinglists/">Reading Lists</a></li>

   <li class=""><a href="https://library.wit.ie/Services/research-repository">Repository</a></li>
</ul></li>

   <li class="id_nav_electronic"><a href="#">Resources</a><ul>
   <li class=""><a href="/Resources/databases">Databases</a></li>

   <li class=""><a href="/Resources/multisearch">MultiSearch</a></li>

   <li class=""><a href="/Resources/journals">Journals</a></li>

   <li class=""><a href="/Resources/ebooks">eBooks</a></li>

   <li class=""><a href="/Resources/theses">Theses</a></li>

   <li class=""><a href="/Resources/special-collections">Special Collections</a></li>
</ul></li>

   <li class="id_nav_more"><a href="#">About WIT Libraries</a><ul>
   <li class=""><a href="/More/luke-wadding-library">Luke Wadding Library</a></li>

   <li class=""><a href="/More/college-street-library">College Street Library</a></li>

   <li class=""><a href="/More/staff-details">Staff Details</a></li>

   <li class=""><a href="/More/policies-and-regulations">Policies and Regulations</a></li>

   <li class=""><a href="/More/external-borrowers">External Borrowers</a></li>

   <li class=""><a href="/More/donations">Donations</a></li>

   <li class=""><a href="/More/Publications">Publications</a></li>
</ul></li>
</ul>
 </div>

<div id="breadcrumbscontainer">
  <div id="breadcrumbs"><span id="mylibraryaccount"><a href="http://witcat.wit.ie/">Connect to WITCat  </a></span><a href="/" style="color: black; text-decoration:none">Home</a> <span>
»  <a href="#" style="color: black; text-decoration: none;">Services</a>

»  <a href="/Services/using-the-library" style="color: black; text-decoration: none;">Using the Library</a>
</span></div>
</div>
 

<!-- ===/ end main navigation /=== --> 



<!-- ===/ start main content /=== -->
<div id="main_content" role="main" style="margin:0;	display:block;"> 
  <!-- ===/ left column /=== --> 
      
  
    <div id="content-left-column"> 
    <!--  Stuff goes here in the left column -->
    <div class="thinlinebox" style="padding: 0px;">
        <h2 class="boxtitle">Library Reading Lists</h2>
      <div style="background-color: white;" id="box_maincontent"> <h3><img src="<?php print($this->config->item('domain').$this->config->item('path')."/"); ?>img/readinglists/laptop.png" alt="" width="100" height="100" align="right">Students</h3>
        <p>This is a new service that provides access to electronic, and other resources that the library provides.  Each module can have a list of recommended and essential reading that supports what is taught in the lecture.  To make it easier to access these resources, they are gathered in one place - with direct links to any electronic resources.</p>
        <h3>Staff</h3>
        <p>It is easy to create an empty reading list in Moodle. It is done by simply following the process of adding an activity. There are no essential fields to complete when adding this activity. The newly created readinglist activity can then be edited using the change button, pictured below.<a href="<?php print($this->config->item('domain').$this->config->item('path')."/"); ?>lists" target="_blank"><img src="/mod/readinglist/images/edit.png" alt="" width="102" height="29" align="left" border="0"></a></p>
        <p>&nbsp;</p>
        <h3>Bookmarklet!</h3>
        <p>Add this link - <a class="btn btn-primary btn-lg" href="javascript:(function(){var%20jsCode=document.createElement('script');var%20scriptURL='https://library.wit.ie/readinglists/lists/bookmarklet/'+(Math.random())+'/';jsCode.setAttribute('src',scriptURL);var%20jsCSS=document.createElement('link');var%20cssURL='https://library.wit.ie/readinglists/lists/bookmarkletcss/'+(Math.random())+'/';jsCSS.setAttribute('rel','stylesheet');jsCSS.setAttribute('type','text/css');jsCSS.setAttribute('href',cssURL);document.body.appendChild(jsCode);document.body.appendChild(jsCSS);})();">Add to Readinglist</a> - to your browser favourites by dragging it up and dropping it upwards onto your browser favourites bar.</p>
<h3>Training</h3>
        <p>Training is provided as an add-on to some of the general moodle2 training that is delivered by the eLearning Support Unit. We can be contacted directly at 2838, to arrange individual training sessions, we can be emailed at readinglists@wit.ie. </p>
        <p>The video below is an overview for Staff, who wish to see how the readinglists module is installed and used in Moodle2.</p>
        <p><iframe width="420" height="315" src="https://www.youtube.com/embed/4hyWyejI_6s" frameborder="0" allowfullscreen></iframe></p>
        <!-- insert code -->
        <h2 class="boxtitle" id="list-title" style="background-color: #AECB57;">find your reading list: </h2>

	  
	 
      
	  <div class="ui-widget">
<input id="modulesearch" onfocus="this.value = '';" size="180" style="padding: 4px; float: left; background-repeat: no-repeat; background-position: right; " value="Add keywords: teacher name, email, modulename ... "  /><a style="display: inline; text-decoration: none; color: black; cursor: pointer;" target="_blank" id="change-link" href="<?php print($this->config->item('domain').$this->config->item('path')."/"); ?>lists/"><img alt="Modify List" src="https://library.wit.ie/mod/readinglist/images/edit.png"></a><img style="cursor:pointer; margin: 0px;" src="/mod/readinglist/images/help_hidden.png" id="helpbox_trigger"><a style="display: inline; text-decoration: none; color: black; cursor: pointer;" target="_blank" id="change-link" href="https://vle.wit.ie/"><img alt="Modify List" src="/mod/readinglist/images/moodle-link-hidden.png"></a>
	  </div>

		<div id="list">
		<!-- 	Stuff about the readinglists -->
        <br /> 
		</div>
        <!-- end insert code -->
      </div>
    </div>
    
    <!-- ===/ end left column /=== -->
    
    <div style="clear: both;"></div>
  </div>
  
  <!-- ===/ start right column /=== -->
    <div id="content-right-column" class="main-tabs"> 
    <!-- Right column stuff --> 
    
        <div class="thinlinebox" id="box_newfeature">
      <h2 class="boxtitle">Contact Details</h2>
      <div class="inner askus" style="background-color: white;"><div class="needs-js"><img src="/assets/images/askus/askus_jscript.png" style="border: none" alt="To use chat, you need to have JavaScript enabled" /></div> <p>
	<strong>Information Service</strong><br />
	<strong>Telephone:</strong> (051) 302840<br />
	<strong>E-mail:</strong> <a href="mailto:libinfo@wit.ie">libinfo@wit.ie</a></p>
 </div>
    </div>
        
    
        <div class="thinlinebox" id="box_newfeature">
      <h2 class="boxtitle">Guides or Forms</h2>
      <div class="inner" style="background-color: white;"><br/>
         </div>
    </div>
        
     <br>
  </div>
    <!-- ===/ end right column /=== --> 

  
  <div style="clear: both"></div>
</div>
<!-- ===/ end main content /=== --> 
<!-- ===/ footer /=== -->
<div id="footer">

    <!-- quick links -->
    <!-- social media links -->
      
      <ul id="social_links" >
        <li class="Facebook"><a href="https://www.facebook.com/witlibraries" target="_blank"><img src="/assets/images/social_icons/facebook.png" alt="friend us on Facebook"></a></li>
        <li class="Facebook"><a href="http://pinterest.com/witlibraries" target="_blank"><img src="/assets/images/social_icons/pinterest.png" alt="friend us on Facebook"></a></li>
        <li class="twitter"><a href="http://twitter.com/#!/witlibraries" target="_blank"><img src="/assets/images/social_icons/twitter.png" alt="tweet us on twitter"></a></li>
        <li class="youtube"><a href="http://www.youtube.com/user/witlibrary" target="_blank"><img src="/assets/images/social_icons/youtube.png" alt="our YouTube channel"></a></li>
        <li class="flickr"><a href="http://www.flickr.com/photos/witlibraries/" target="_blank"><img src="/assets/images/social_icons/flickr.png" alt="our Flickr stream"></a></li>
        <li class="flickr"><a href="http://witlibrary.wordpress.com/feed/" target="_blank"><img src="/assets/images/social_icons/rss.png" alt="news"></a></li>
        <li class="youtube"><a href="http://witlibrary.wordpress.com/" target="_blank"><img src="/assets/images/social_icons/blogger.png" alt="our YouTube channel"></a></li>
      </ul>
    <div id="bottom_footer">
    <h2>Contact Details</h2>
    <p>Luke Wadding Library, Waterford Institute of Technology, Cork Road, Waterford, Ireland <strong>Tel: </strong>+353.51302823 <strong>email: </strong>libinfo@wit.ie</p>
    </div>
</div>

<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-5985058-2']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
<!-- ===/ end footer /=== -->
</div>

<!-- Place this script as near to the end of your BODY as possible. -->
<script type="text/javascript">
  (function() {
    var x = document.createElement("script"); x.type = "text/javascript"; x.async = true;
    x.src = (document.location.protocol === "https:" ? "https://" : "http://") + "eu.libraryh3lp.com/js/libraryh3lp.js?61"
    var y = document.getElementsByTagName("script")[0]; y.parentNode.insertBefore(x, y);
  })();
</script>
<?php
 	//		echo "<hr/><pre>";
 	//		echo "amazon.com\n";
	//		echo 'title  ' . serialize(array('$("head title").text()', 'javascript'))."\n"; 
	//		echo 'author  ' . serialize(array('$("#divsinglecolumnminwidth div.buying:nth-child(4) b:nth-child(1)").text();', 'jquery'))."\n"; 
 	//		echo "<pre>";

 		//	echo "<hr/><pre>";
 		//	echo "theguardian.com\n";
		//	echo 'title  ' . serialize(array('$("head title").text()', 'javascript'))."\n"; 
		//	echo 'author  ' . serialize(array('//div[@id="content"]/ul/li/div[@class="contributor-full"]', 'xpath'))."\n"; 
		//	echo 'year  ' . serialize(array('$this->myExplode($url, \'/\', 4)', 'literal'))."\n"; 
 		//	echo "<pre>";


?>


<?php }?>

</body></html>