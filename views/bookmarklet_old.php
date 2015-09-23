<?php
if(!isset($_COOKIE['redirected'])){
	$ra = preg_replace('/([ ])/e', 'chr(rand(97,122))', '     ');
	header('Location: '. $this->config->item('domain') . 'lists/bookmarklet/' . $ra);
	header("Referer: https://library.wit.ie/");
	
	setcookie("redirected", "redirected");
}else{
	header("Content-type: text/javascript");
	header("Referer: https://library.wit.ie/");
	# bookmarklet javascript.
	# will give you nothing unless you are logged in.
	if($loggedin == 1){
		$title = "Logged in as " . $user;
		$phrase = "Choose a module";
	}else{
		$title = "Not Logged In";
		$phrase = "Click <a href='" . $this->config->item('domain') . "lists' target='_blank' onclick='location.reload();'>here</a> to log in.";
	}
	
?>
	if (!($ = window.jQuery)) { // typeof jQuery=='undefined' works too  
		script = document.createElement( 'script' );  
		script.src = 'http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js';  
		script.onload=submit_list;  
		document.body.appendChild(script);  
		
	}else{  
		submit_list();  
	}  
	function parsePage(url){
		var site = 'generic';
		if(url.indexOf("amazon.co.uk") > -1){			
			site = 'amazonuk';
		}else if(url.indexOf("amazon.com") > -1){		
			site = 'amazoncom';
		}else if(url.indexOf("books.google.ie") > -1){	
			site = 'googleie';
		}else if(url.indexOf("witcat.wit.ie") > -1){	 
			site = 'witcat';
		}    
		
		var title = $(document).attr('title');
		var formHTML = ' \
		 \
			<p>Type</p>	\
			<select id=\"book_type\" style=\"margin-bottom: 10px;\" name=\"type\"> \
			<option value=\"1\">Book</option> \
			<option value=\"2\">Journal Article</option> \
			<option value=\"3\">Website</option> \
			</select> \
	 \
			<p>Title:</p> \
			<input class=\"rlinput\" type=\"text\" name=\"book_title\" value=\"'+title+'\"/> \
	 \
			<p>URL:</p> \
			<input class=\"rlinput\"  type=\"text\" name=\"url\" value=\"'+url+'\"/> \
	 \
			<p>Note:</p> \
			<textarea name=\"book_notes\" rows="10"></textarea> \
	 \
			<p>Essential or Supplimentary?</p> \
			<input name=\"essential\" value=\"supp\" checked=\"checked\" type=\"radio\" style=\"display: inline; width:10px;\">&nbsp;Supplimentary \
			<input name=\"essential\" value=\"ess\" type=\"radio\" style=\"display: inline; width:10px;\"> &nbsp;Essential  \
		';
		
		<?php //echo($url); 
		?>
		if(site == 'generic'){
			// get page details
			// generate form html
		}
		return formHTML;
	}
	$('#rlsubmitbutton').on('click',function(){
		if($("#staffmodules").val() == 0){
			alert('Choose a module.');
			return false;
		}else{
			return true;
		}
	});
	$(document).ready(function() {
	/*
		var $dragging = null;
		$('body').on("mousedown", "div#outerRLForm", function(e) {
			$("div#outerRLForm").attr('unselectable', 'on').addClass('draggable');
			var el_w = $('.draggable').outerWidth(),
				el_h = $('.draggable').outerHeight();
			$('body').on("mousemove", function(e) {
				if ($dragging) {
					$dragging.offset({
						top: e.pageY - el_h / 2,
						left: e.pageX - el_w / 2
					});
				}
			});
			$dragging = $(e.target);
		}).on("mouseup", ".draggable", function(e) {
			$dragging = null;
			$(this).removeAttr('unselectable').removeClass('draggable');
		});
	*/
	});
	
	function submit_list() { 
		$('#outerRLForm').remove(); 
		var url = document.URL;
		var listform = " \
		<div id=\"innerRLForm\"> \
		<div id=\"innerRLFormTitle\"> \
		<button id=\"innerRLFormCloseButton\" onclick=\"$(\'#innerRLForm\').remove();\" >close</button> \
		<?php echo($title); ?> \
		</div> \
		 \
		<div id=\"innerRLFormContent\"> \
		<?php  	if($loggedin == 1){ ?> \
		<form action=\"<?php echo($this->config->item('domain')); ?>lists/update_book/\" method=\"post\" target=\"_blank\" id=\"rlformtag\"> \
			<label for=\"staffmodules\">Choose a Module:</label> \
			<select id=\"staffmodules\" name=\"module_id\" style=\"margin-bottom: 10px;\"> \
					<option value=\"0\">NONE SELECTED:</option> \
					<?php foreach($modules as $m){ print("<option value=\\\"".$m['mid']."\\\">".$m['modulename']."</option> "); } ?>   \
			</select> \
			"+parsePage(url)+" \
			<input type=\"hidden\" name=\"action\" value=\"new\" /><input id=\"rlsubmitbutton\" type=\"submit\" value=\"submit\" \  /> \
		</form><br/> \
		<?php  
		}else{ 
			echo $phrase;
		}  ?> \
		<br/></div> \
		</div>";	
		
		
		div_form = document.createElement( 'div' );
		div_form.setAttribute('id', 'outerRLForm');  
		div_form.style.position="absolute";
		div_form.style.left="0";
		div_form.style.top="0";
		div_form.style.zIndex="2000000";
		div_form.style.backgroundColor = "black";
		div_form.style.display="block";
		div_form.style.margin="33px";
		div_form.style.opacity="1";
		//div_form.firstChild.style.opacity="1";
		div_form.style = "border: solid 1px black; background-color: white;";
		div_form.innerHTML = listform;  
		document.body.appendChild(div_form);  
	}  
	
<?php
}
?>
