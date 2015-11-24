<?php 
$this->load->view('includes/top', array('accesslevel' => 'all')); ?>
<script type="text/javascript">

 
var Base64 = {
    // private property
    _keyStr : "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",
    // public method for encoding
    encode : function (input) {
        var output = "";
        var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
        var i = 0;
        input = Base64._utf8_encode(input);
        while (i < input.length) {
            chr1 = input.charCodeAt(i++);
            chr2 = input.charCodeAt(i++);
            chr3 = input.charCodeAt(i++);
            enc1 = chr1 >> 2;
            enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
            enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
            enc4 = chr3 & 63;
            if (isNaN(chr2)) {
                enc3 = enc4 = 64;
            } else if (isNaN(chr3)) {
                enc4 = 64;
            }
            output = output +
            this._keyStr.charAt(enc1) + this._keyStr.charAt(enc2) +
            this._keyStr.charAt(enc3) + this._keyStr.charAt(enc4);
        }
        return output;
    } ,
    // public method for decoding
	/*
    decode : function (input) {
        var output = "";
        var chr1, chr2, chr3;
        var enc1, enc2, enc3, enc4;
        var i = 0;
        input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");
        while (i < input.length) {
            enc1 = this._keyStr.indexOf(input.charAt(i++));
            enc2 = this._keyStr.indexOf(input.charAt(i++));
            enc3 = this._keyStr.indexOf(input.charAt(i++));
            enc4 = this._keyStr.indexOf(input.charAt(i++));

            chr1 = (enc1 << 2) | (enc2 >> 4);
            chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
            chr3 = ((enc3 & 3) << 6) | enc4;
            output = output + String.fromCharCode(chr1);
            if (enc3 != 64) {
                output = output + String.fromCharCode(chr2);
            }
            if (enc4 != 64) {
                output = output + String.fromCharCode(chr3);
            }
        }
        output = Base64._utf8_decode(output);
        return output;
    },
	*/
	
    // private method for UTF-8 encoding
    _utf8_encode : function (string) {
        string = string.replace(/\r\n/g,"\n");
        var utftext = "";
        for (var n = 0; n < string.length; n++) {
            var c = string.charCodeAt(n);
            if (c < 128) {
                utftext += String.fromCharCode(c);
            }
            else if((c > 127) && (c < 2048)) {
                utftext += String.fromCharCode((c >> 6) | 192);
                utftext += String.fromCharCode((c & 63) | 128);
            }
            else {
                utftext += String.fromCharCode((c >> 12) | 224);
                utftext += String.fromCharCode(((c >> 6) & 63) | 128);
                utftext += String.fromCharCode((c & 63) | 128);
            }
        }
        return utftext;
    } // ,
    // private method for UTF-8 decoding
	
	/*
    _utf8_decode : function (utftext) {
        var string = "";
        var i = 0;
        var c = c1 = c2 = 0;
        while ( i < utftext.length ) {
            c = utftext.charCodeAt(i);
            if (c < 128) {
                string += String.fromCharCode(c);
                i++;
            }
            else if((c > 191) && (c < 224)) {
                c2 = utftext.charCodeAt(i+1);
                string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
                i += 2;
            }
            else {
                c2 = utftext.charCodeAt(i+1);
                c3 = utftext.charCodeAt(i+2);
                string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
                i += 3;
            }
        }
        return string;
    }
	*/
}

	function refreshURL(refreshurl){
		//alert('refresh to: ' + refreshurl);
		window.location.href = refreshurl;
	}

    // for sortable;
	var fixHelper = function(e, ui) {
		ui.children().each(function() {
			$(this).width($(this).width());
		});
		return ui;
	};
	//$('button.handle').draggable({cancel:false}); // gets rid of normal event bind for button thus allowing draggable.
	var newSequenceToServer = function(e, ui) {
		dataArray = $(this).sortable('toArray'); 
		//alert(dataArray.length);
		for(var i = 0; i < dataArray.length; i++){
			var cla = '#'+dataArray[i];
			//alert(cla + "\nrow_"+i%2);
			//$(cla).removeClass('row_0 row_1').addClass('row_'+i%2);
			//$(cla).removeclass('row_0 row_1');			
		}			
		//dataString = $(this).sortable('serialize', { key: "sequence[]" }) + "&" + $('#'+dataArray[0]).attr('title'); 
		dataString = $(this).sortable('serialize', { key: "sequence[]" }) + "&" + $('#'+dataArray[0]).data('title'); 
		//alert(data);
		$.ajax
		({
			type: "POST", url: "/<?php echo($this->config->item('path')); ?>lists/resequencelist/", data: dataString, cache: false, success: function(html)
			{
				//$("#dept").html(html);
				//alert(html);	
				if(html == 'LOG_OUT'){refreshURL('<?php echo($this->config->item('base_url')); ?>lists/login');}
			}
		});
	};
	currentmodule = 0;
	
	/*
	button codes
	 .displ-ctrl-title1			*			1, book
	 .displ-ctrl-title2			*			2, journal
	 .displ-ctrl-url			*			3, website
	 .displ-ctrl-authed			*			4, report
	 .displ-ctrl-pfrmrs			!			5, audio
	 .displ-ctrl-year			*			6, video
	 .displ-ctrl-edn			1
	 .displ-ctrl-vol			1,2,4
	 .displ-ctrl-iss			1,2,4
	 .displ-ctrl-pp				1,2,4
	 .displ-ctrl-pub			*
	 .displ-ctrl-pubplace		1
	 .displ-ctrl-stdnum			1,2,4
	 .displ-ctrl-libid			!
	 .displ-ctrl-notes			*
	 .displ-ctrl-ess			*

*/	
		
	
	function changeEditFormFields(val){	
		if(!val){val= 1;}
	
		$(".displ-ctrl-edn").show();
		$(".displ-ctrl-pubplace").show();
		$(".displ-ctrl-vol").show();
		$(".displ-ctrl-iss").show();
		$(".displ-ctrl-pp").show();
		$(".displ-ctrl-stdnum").show();
	
		//alert('change fields');
		if(val != 1){ // not a book
			$(".displ-ctrl-edn").hide();
			$(".displ-ctrl-pubplace").hide();
		}
		if((val != 1)&&(val != 2)&&(val != 4)){ // not a book, journal, or report
			$(".displ-ctrl-vol").hide();
			$(".displ-ctrl-iss").hide();
			$(".displ-ctrl-pp").hide();
			$(".displ-ctrl-stdnum").hide();
		}
			
	}
	
$(document).ready(function()
{		
	// populate the department dropdown...
	<?php 
	if($sel_mod != 0){
		if($this->session->userdata('authorised') == 'TRUE'){	
			logfile("CURRENT USER: " . $this->session->userdata('user'));	
	
	?> 
	$("#viewdeleted").fadeIn('slow');  
	<?php }	?>
	<?php 	if($functionality == 'bookmarklet'){?>
	addnewbook('<?php echo($functionality); ?>');
	$("#editsubmit span").text('Add This Web Resource');	
	<?php 	}else{ ?>
	// do something here to pre-load the moduleList chart?
	showModuleList('active'); 
	<?php 	} ?>
	<?php } ?>
	var loading_msg = "Loading ...";	
	var inputHasFocus = true;
	<?php if($functionality == 'bookmarklet'){?>
	addnewbook('<?php echo($functionality); ?>');
	<?php } 
	//if($functionality == 'bookmarklet'){
	?>
	// sortable



  
	// TAGS for AUTHORS and EDITORS
	//$(function() {
	$('body').on('click', '#book_author', function(){
		//alert('tagsinput');
		$('#book_author').tagsInput({width:'auto'});
	});
	$('body').on('click','#book_editor', function(){
		//alert('tagsinput');
		$('#book_editor').tagsInput({width:'auto'});
	});
	
	
	$('body').on('click', ".transferdetails", function(){	
		// moves stuff across to the edit form
		// take each form field and assign the new value to it.
		$('#book_title').val($(this).parent().parent().find('span.opac_resultset_detail_title').text());
		$('#addAuthorFormPanel').html();
		$('#addAuthorFormPanel').html("<input class=\"form-control\" style=\"border: none;\" name=\"book_author\" type=\"text\" id=\"book_author\" value=\""+$(this).parent().parent().find('span.opac_resultset_detail_author').text()+"\" size=\"45\" />");
		
		$('#book_year').val($(this).parent().parent().find('span.opac_resultset_detail_pubdate').text());
		$('#book_publisher').val($(this).parent().parent().find('span.opac_resultset_detail_publisher').text());
		$('#book_place').val($(this).parent().parent().find('span.opac_resultset_detail_publoc').text());
		$('#book_isbn').val($(this).parent().parent().find('span.opac_resultset_detail_isbn').text());
		$('#book_url').val($(this).parent().parent().find('span.opac_resultset_detail_url a').text());
		$('#book_libraryid').val($(this).parent().parent().find('span.opac_resultset_detail_recordid a').text());
		
		//hidden fields.
		$('#book_mattype').val($(this).parent().parent().find('span.opac_resultset_detail_mattytpe').text());
		$('#book_bcode3').val($(this).parent().parent().find('span.opac_resultset_detail_bcode3').text());
		
		$(this).parent().parent().hide('slow');
		
		var essentialval = $('input[name=essential]:checked').val();
		if(essentialval == 'ess' || essentialval == 'supp'){
			//do nothing;
		}else{
			$('#essentialAlert').css('background', 'url(/<?php echo($this->config->item('path')); ?>img/form_alert_background.png) top left no-repeat');
		}
	});	// shows active
	
	
	$('body').on('click', "#searchcat", function(){	// button to search the library catalogue by the
		//$( "#modal_form" ).dialog( "close" );
		searchCatalogue();
	});	
	
	/* EDIT BOOK FORM SUBMIT ON CLICK */
	$('body').on('keypress', '#editbookform input', function(e) {
		if(e.keyCode == 13) {
			//$("#loginform").submit();
			$('#editbookform').submit();
		}
	});
	/* SEARCH CATALOGUE FORM FUNCTIONS FOR PRIMARY SEARCH */
	$('body').on('keypress', '#searchcat_input', function(e) {
		if(e.keyCode == 13) {
			//$("#loginform").submit();
			searchCatalogue();
		}
	});
	var pleaseWaitHTML = '<h2>Please Wait ... </h2><div class="progress progress-striped active"><div class="progress-bar"  role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 45%"><span class="sr-only">45% Complete</span></div></div>';
	function searchCatalogue(){
		var searchterm = $('#searchcat_input').val();
		showSearchCat(searchterm, 'witcat');
	}
	/* SEARCH CATALOGUE FORM FUNCTIONS FOR SECONDARY SEARCHES */
	$('body').on('click', "#searchFormInputDropdown li", function(){
		if($(this).attr('value') != 'isbndb'){
			//confirm("Search Term: " + $('#searchFormInput').val());
			searchterm = $('#searchFormInput').val();
		}else{
		//	searchterm = $('#bookISBN').val();
			//confirm("Search ISBN Term: " + $('#bookISBN').val());
			searchterm = $('#searchFormInput').val();
		}
		//alert("SEARCH TERM: " + searchterm + "\nDATABASE: " + $(this).attr('value')); 
		showSearchCat(searchterm, $(this).attr('value'));
		
	});
	$('body').on('keypress', '#searchFormInput', function(e) {
		if(e.keyCode == 13) {
			$('#searchFormInputDropdown').show(); return false;
		}
	});	
	
	/* INVITE USERS TO LIST */
	
	
	function pendingUser(params){	
		//alert('add pending');
		// add pending author row before the button.<button type="button" class="btn btn-outline btn-warning">Warning</button>
		
		var loadurl = '<?php echo($this->config->item('base_url')); ?>lists/managelistadmins/';
		
		$.ajax
		({
			type: "POST", url: loadurl, data: params, cache: false, success: function(html)
			{
				//alert(params + '\n' + html);
				if(html == 'LOG_OUT'){refreshURL('<?php echo($this->config->item('base_url')); ?>lists/login');}
				else{
					$('#listAdminsTable tr:last').before(html);
				}
			}
		});
	}
	
	$('body').on('click', ".uninviteAdminButton", function(){	 // remove invitation
		// hide row;
		$(this).parent().parent().hide('slow');
		var code = $(this).val();
		var params = '';
		var loadurl = '<?php echo($this->config->item('base_url')); ?>lists/declineinvitation/'+code;
		$.ajax
		({
			type: "POST", url: loadurl, data: params, cache: false, success: function(html)
			{
				// alert(html);
				if(html == 'LOG_OUT'){refreshURL('<?php echo($this->config->item('base_url')); ?>lists/login');}
			}
		});
	});
	
	// ADD LIST MODAL SUBMIT FUNCTIONS
	$('body').on('keypress', '#newListName', function(e) {
		if(e.keyCode == 13) {
			newListModalSubmit();
		}
	});	
	$('body').on('click', "#newListModalButton", function(){	
		newListModalSubmit();
	});
	$('#addNewList').on('shown.bs.modal', function () {
		$('#newListName').focus();
	});
	function newListModalSubmit(){
		var newlistname = encodeURI($('#newListName').val());
		// close modal
		var params = 'modulename='+newlistname;
		var loadurl = '<?php echo($this->config->item('base_url')); ?>lists/newpersonallist/';
		//alert('url: ' +loadurl+'\ndata: ' + params + '\n');
		$.ajax
		({
			type: "POST", url: loadurl, data: params, cache: false, success: function(html)
			{
				if(html == 'LOG_OUT'){refreshURL('<?php echo($this->config->item('base_url')); ?>lists/login');}
			}
		});	
		refreshurl = '<?php echo($this->config->item('base_url')); ?>lists'
		//alert(refreshurl);	
		$('#addNewList').modal('hide');
    	setTimeout(function() { refreshURL(refreshurl)},800);
		//alert('refreshing');
	}
	
	
	// ADD ADMIN MODAL SUBMIT FUNCTIONS
	$('body').on('keypress', '#inviteeEmail', function(e) {
		if(e.keyCode == 13) {
			addAdminModalSubmit();
		}
	});	
	$('body').on('click', "#userInviteModalButton", function(){	
		addAdminModalSubmit();
	});
	$('body').on('shown.bs.modal', '#addPendingUserModal', function () {
		$('#inviteeEmail').focus();
	});
	function addAdminModalSubmit(){
		var email = $('#inviteeEmail').val();
		var mid = $('#inviteeMid').val();
		var inviteeMessage = $('#inviteeMessage').val();
		var inviteeInviter = $('#inviteeInviter').val();
		var inviteeListName = $('#inviteeListName').val();
		// close modal
		var re = /\S+@\S+\.\S+/;
		$('#addPendingUserModal').modal('hide');
		if(re.test(email)){
			pendingUser('action=invite&email='+email+'&mid='+mid+'&inviteeMessage='+inviteeMessage+'&inviteeInviter='+inviteeInviter+'&inviteeListName='+inviteeListName);
		}
	}
	
	
	
	/* SEARCH CATALOGUE FUNCTION */
	function showSearchCat(searchterm, source){	
		//alert("00 source: " + source + "\nrefined searchterm: " + searchterm);
		$("div#showcat").html(pleaseWaitHTML);	
		//alert("0 source: " + source + "\nrefined searchterm: " + searchterm);
		searchterm = escape(searchterm.replace(/[^A-Za-z0-9]+/gi, " "));
		//alert("1 source: " + source + "\nrefined searchterm: " + searchterm);
		var loadurl = '<?php echo($this->config->item('base_url')); ?>index.php/lists/library/';
		//alert("2 Loadurl: " + loadurl);
		title = 'source='+source+'&ti='+searchterm;
		
		//alert('3 searchcat: \t' + loadurl + '\nsearchcat: \t' + title);
		$.ajax
		({
			type: "POST", url: loadurl, data: title, cache: false, success: function(html)
			{
				if(html == 'LOG_OUT'){refreshURL('<?php echo($this->config->item('base_url')); ?>lists/login');}
				else{$("div#showcat").html(html);}
			}
		});
	}// shows active
	
	
	
	$('body').on('click', ".opac_resultset_item", function(){	
		//alert($(this).find("div.opac_resultset_details").css('display'));
		if($(this).find("div.opac_resultset_details").css('display') == 'block'){
			//$("#showcat").find("span.opac_resultset_details_smalltitle").css('color','black');
			$("#showcat").find("div.opac_resultset_details").hide();
		}else{
			$("#showcat").find("div.opac_resultset_details").hide();
			$(this).find("div.opac_resultset_details").show();
			//$(this).find("span.opac_resultset_details_smalltitle").css('color','black');
		}
		
		// this is the action for the button to search the library catalogue by the form	
		
	});	// shows active
			
	
	function is_linked(){
		var dataString = "mid=" + $("#modulevalue").val() + "&staffemail=" + $("#staffemail").val();
		$.ajax
			({
				type: "POST", url: "/<?php echo($this->config->item('path')); ?>lists/is_linked/", data: dataString, cache: false, success: function(html)
				{
				if(html == 'LOG_OUT'){refreshURL('<?php echo($this->config->item('base_url')); ?>lists/login');}
				else{$("#link_unlink").html(html);}
				}
		});
	}
	
	function set_module_title(){
		//take title from modules dropdown and assign it to the div at the top of the modules list;
		//var moduletitle = $("#modulevalue").val();
		//alert(moduletitle);
		$("#module_title").html($("#modulevalue").val());
	}
	
	$(".mods").click(function(){	
		//var id=$(this).attr('value');
		var id=$(this).attr('id');
		id = id.replace('modulenameListItem_', '');
		$("#modulevalue").val(id);
		//alert("the value" + $("#modulevalue").val());
		if(id == 0){ //if we change it to zero we must hide the books.			
				$("#booklist").html(' ');
		}else{
			showModuleList('active');	$("#backtolistview").parent().hide();	$("#viewdeleted").parent().show();	
		}
		is_linked();
		//alert("<?php echo($this->config->item('base_url')); ?>lists/");
		set_module_title();
		// just refresh the page to get around the ID problem.
		// window.location.href = "<?php echo($this->config->item('base_url')); ?>lists/";
		
	});	// shows active
	
	$('body').on('click', '#backtolistview', function(){		
		showModuleList('active');	$("#backtolistview").parent().hide();	$("#viewdeleted").parent().show();	
	}); 	// shows active
	$('body').on('click', '#viewdeleted', function(){	
		showModuleList('deleted');	$("#backtolistview").parent().show();	$("#viewdeleted").parent().hide();	
	}); 	// shows deleted

	function showModuleList(deleted){  // deleted can be 'active' or 'deleted', depending on whether we want to see the current (active) or the basket (deleted) list.
		
		var module = $("#modulevalue").val();
		var milliseconds = new Date().getTime();
		var url = "index.php/lists/list_books/" + deleted + "/" + module + "/" + milliseconds + "/";
		url = '<?php echo base_url(); ?>' + url;
		
		$.ajax
		({
			type: "POST", url: url, data: '', cache: false, success: function(html)
			{
				if(html == 'LOG_OUT'){refreshURL('<?php echo($this->config->item('base_url')); ?>lists/login');}
				else{
					$("#editbook").fadeOut("slow");
					$("#booklist").html(' ');
					//$("#booklist").fadeOut("slow");
					$("#booklist").html(html);
					$("#booklist").fadeIn("slow");
				}
			}
		});
		//hasDeletedItems();
	}
	
//	$(window).on('resize', function() { 
//		refreshMainStatsChart();
//	});	
	
	$('body').on('click','.check_catalogue', function(){
		//var myvalue = $(this).html().replace(/^\s+|\s+$/g, '');  //gets the string value of the button
		var values = $(this).attr('value').split(':');
		var val = values[0];
		var type = values[1];
		var mid = $("#modulevalue").val();
		// this is the standard 'edit book' form that appears on the left
		var url = '<?php echo($this->config->item('base_url')); ?>index.php/lists/edit_book/';
		//var postdata = "action=update&bid="+val+"&mid="+mid;
		var postdata = "action=update&bid="+val+"&mid="+mid;
		
		// this is what appears on the right - either search results or a web page in an iframe.
		var url_wit_search_results = '<?php echo($this->config->item('base_url')); ?>lists/library/' + $(this).attr('value'); 
		var search_postdata = "source=witcat&bid=bookID_"+val+"&type="+type;
		url_edit_webpage_postdata = Base64.encode(search_postdata);
		var url_webpage_edit = '<?php echo($this->config->item('base_url')); ?>lists/proxy/'+url_edit_webpage_postdata+"/3/"; 
		
		/* 	where attr.('value') takes this form: '4551/update/';
			depending on which function the value is processed differently.  In the first ajax call
			the numeric book id is used to retrieve specific book data
			to populate the edit form.  In the second call the number is used to retrieve the 
			book title, which is then used to search the local copy of the library database.
		*/
		if('<?php echo($this->session->userdata('authorised')); ?>' == 'TRUE'){
			$("#booklist").hide();
			$(".search_results_container").html(pleaseWaitHTML);
			$(".book_edit_form_container").html(' ');
			$("#editbook").show();  // show the entire pane, including the 'please wait'.
			$("#backtolistview").show();  // show the button of the same title
			$.ajax({
				type: "POST", url: url, data: postdata, cache: false, success: function(html){
				if(html == 'LOG_OUT'){refreshURL('<?php echo($this->config->item('base_url')); ?>lists/login');}
				else{$(".book_edit_form_container").html(html);}
					// the edit book form
					//prompt(html);
				}
			});
			if(type == 3){
					
						var iframe = '<div class="panel panel-primary fieldset"><div class="panel-heading">These are the details on record for this book: </div><div class="panel-body" style="background-color:#d9edf7; ">';
						var iframe = iframe + '<button type="button" class="btn btn-primary"><a href="#" onclick="window.open($(\'#book_url\').val());" target="_blank" style="color: white;" >';
						var iframe = iframe + '<span><i class="fa fa-copy"></i> Open Content in New Tab</span></a></button>';
						var iframe = iframe + '<br/><br/><iframe src="' + url_webpage_edit + '" width="100%" height="700px"></iframe>';
						
						//var iframe = iframe + '<div class="panel-footer"></div>';
						var iframe = iframe + '</div>';
						
						
						
						$(".search_results_container").html(iframe);
						
						
			}else{
				// iframe
				$.ajax({
					type: "POST", url: url_wit_search_results, data: search_postdata, cache: false, success: function(html){
						if(html == 'LOG_OUT'){refreshURL('<?php echo($this->config->item('base_url')); ?>lists/login');}
						else{$(".search_results_container").html(html);}
						//alert('html2: ');
						// the search results
					}
				});
			}
		}
		//alert('fadein');
	});
	// call this before this funciton on the button
	//onclick=\'$(\"#booklist\").hide(); $(\"#editbook\").show(); $(\"#backtolistview\").show();\'
	
	$('body').on('click','a.delete_restore', function(){
		var state = 'active';
		var numItems = $('input[name="bookstomove[]"]').length;
		var url = '/<?php echo($this->config->item('path')); ?>lists/edit_book/';
		var deldata = $(this).attr('value');
		//var displayedItemsCount = $('#displayedItemCount').data('data-itemcount');
		var displayedItemsCount = $('#displayedItemCount').text();  // can't seem to get the data- atrribute value from a dynamic element.
		var nonDisplayedItemsCount = $('#nonDisplayedItemCount').text();
		var totalItemsCount = $('#displayedItemCount').data('data-itemcount');	// container for storing total items decremented if a deletion happens.
		nonDisplayedItemsCount = nonDisplayedItemsCount-1; 
		//displayedItemsCount = +displayedItemsCount+1;
		displayedItemsCount = +displayedItemsCount+1;
		var p = /purge/ig;
		if(!String(deldata).match(p)){	
			//$('#displayedItemCount').text(' drink! ' + displayedItemsCount.toString());
			$('#displayedItemCount').text(displayedItemsCount.toString());
			$('#nonDisplayedItemCount').text(nonDisplayedItemsCount.toString());
		}else{
			state = 'deleted';
			$('#displayedItemCount').data('data-itemcount', totalItemsCount - 1);
			//$('#displayedItemCount').data('data-itemcount', +totalItemsCount - 1);
			$('#nonDisplayedItemCount').text(nonDisplayedItemsCount.toString());
			//$('#nonDisplayedItemCount').attr('title', +totalItemsCount + 1);
		}
		var colour = '#FF0000'; 
		var m = /undelete/ig;
		if(String(deldata).match(m)){var colour = '#00FF00'; state = 'deleted';}
		$(this).parent().parent().parent().fadeOut("slow");
		$.ajax({
			type: "POST", url: url, data: deldata, cache: false, success: function(html){
				if(html == 'LOG_OUT'){refreshURL('<?php echo($this->config->item('base_url')); ?>lists/login');}
			}
		});
		//if(displayedItemsCount == +totalItemsCount){// display nodata or refresh
		//	showModuleList(state);
		//}
		if(nonDisplayedItemsCount == 0){// display nodata or refresh
			showModuleList(state);
		}
		
	});
 

	$('body').on('click', 'a#addnewbook', function(){
		addnewbook('new');
	});
	function addnewbook(el){
		$("#editbook").show();
		var url = '/<?php echo($this->config->item('path')); ?>lists/edit_book/';
		el == 'bookmarklet'?action='new':action=el;
		// this is the element where the current module is dynamically stored => $("#modulevalue").val()
		var postdata = 'action='+el+'&mid='+$("#modulevalue").val();
		
		
		var iframecontent = '<?php echo("/" . $this->config->item('path') . "lists/proxy/" . base64_encode($referer)."/"); ?>';
		if(el == 'bookmarklet'){
		<?php if(isset($_POST['biblio'])){
			foreach($_POST['biblio'] as $key => $value){
				echo "postdata += '&biblio[" . $key . "]=' + encodeURI('" . trim(str_replace(array("\n", "\r", "'"), ' ', $value)) . "');\n";	
				//echo "alert('".$value."');\n";
			}
			if(isset($_POST['biblio']['url'])){
				echo "var iframecontent = '/".$this->config->item('path')."lists/proxy/" . base64_encode($_POST['biblio']['url']) ."';";
			}
		}?>
		}
		
		$(".book_edit_form_container").html(' ');
		$(".search_results_container").html(' ');
		
		$("#booklist").hide();
		$("#editbook").fadeIn("slow");
		$("#viewdeleted").hide();
		$("#backtolistview").fadeIn("slow");
		
		// this is the one that first comes up when the new item is to be added.
		//var iframecontent = '< ? php echo($referer); ?>';
		var form2 = '<iframe src="' + iframecontent + '" width="100%" height="700px"></iframe>';
		var formtop = '<div class="panel panel-primary fieldset"><div class="panel-heading">These are the details on record for this book: </div><div class="panel-body" style="background-color:#d9edf7; ">';
		var form1 = '<div class="alert alert-warning"><i class="fa fa-info-circle"></i> Search the WIT Library Catalogue.  Enter keywords below.</div>';
		var form1 = form1 + '<div class="input-group"><span class="input-group-btn">';
		var form1 = form1 + '<button id="searchcat" class="btn btn-primary" type="button">Go!</button>';
		var form1 = form1 + '</span><input type="text" id="searchcat_input" size="60" class="form-control" value="" /></div></div>';
		//var formbottom = '<div class="panel-footer"></div>';
		var formbottom = '</div>';
		if(el == 'bookmarklet'){
			var form = formtop + form2 + formbottom;
		}else{
			var form = formtop + form1 + formbottom;
		}
		
		$.ajax({
			type: "POST", url: url, data: postdata, cache: false, success: function(html){
				if(html == 'LOG_OUT'){refreshURL('<?php echo($this->config->item('base_url')); ?>lists/login');}
				else{
					$(".book_edit_form_container").html(html);
					$(".search_results_container").html(form);
				}
			}
		});
		
	}

	$('body').on('click', 'input#toggleselectall', function()
	{
		var checkedStatus = this.checked;
		$('input.bookmove').each(function () {
			$(this).prop('checked', checkedStatus);
		});
		
	});
	$('body').on('click', 'button.editessential', function()
	{
		if($(this).find('i').hasClass('fa-star')){ //if is essential
			var status = 'supp';
			$(this).find('i').addClass('fa-star-o');
			$(this).find('i').removeClass('fa-star');
			$(this).find('i').attr('title', 'mark as essential');
		}else{
			var status = 'ess';
			$(this).find('i').addClass('fa-star');
			$(this).find('i').removeClass('fa-star-o');
			$(this).find('i').attr('title', 'mark as supplimentary');
		}
		var params = 'status='+status+'&bid='+$(this).attr('value')+"&mid="+$("#modulevalue").val();
		//alert(params);
		$.ajax({
			type: "POST", url: '/<?php echo($this->config->item('path')); ?>lists/change_status/', data: params,  cache: false, success: function(html)
			{
				//alert('<?php echo base_url(); ?>index.php/lists/change_status/' + html);
				if(html == 'LOG_OUT'){refreshURL('<?php echo($this->config->item('base_url')); ?>lists/login');}
			}
		});
	});
	
	$('body').on('click', "#moveSelectedItems", function(){
		var numSelectedItems = $('input[name="bookstomove[]"]:checked').length;
		//alert(numSelectedItems);
			var str1 = 's';
			numSelectedItems == 1?str1 = '':str1 = 's';
			if(numSelectedItems == 0){
				$("#innerRLMessage").html('<span style="color: red;">You have not selcted any list items to move:</span>');
				$("#innerRLFormDropdown").hide();
			}else{
				$("#innerRLMessage").html('copy selected item'+str1+' to: <br/><br/>');
				$("#innerRLFormDropdown").show();
			}
	});
	
	
	
	
	$("#duplicate_list_to").click(function()
	{
		var mid = $("#staffmodules").val();
		var midtxt = $("#staffmodules option:selected").text();
		var replacewithmid = $("#modulevalue").val();
		var replacewithmidtxt = $("#mods option:selected").text();
		var dataString = "replacewithmid=" + replacewithmid + "&mid=" + mid;
		var confirmstring = "You are about to overrwrite the module above:\n\t" + midtxt + "\n\n... with <?php echo($this->config->item('instancename')); ?> from '" + replacewithmidtxt + "' (below)\n\nDo you really want to do this?\n\n== NOTE: CHANGES YOU MAKE CANNOT BE UNDONE ==";
		
		
		
		if(mid == 0 || replacewithmidtxt == ''){
			alert("You're doing it wrong.\n\nSomething is not selected.\nYou want to choose a module from the dropdowns below to copy the books to the one above.  Make sure you have them chosen.");
		}else if(mid == replacewithmid){
			alert("you're trying to replace the module with itself");
		}else{
			if(confirm(confirmstring)){
				$.ajax({
					type: "POST", url: "/<?php echo($this->config->item('path')); ?>lists/substitute_module_list/", data: dataString, cache: false, success: function(html)
					{
						
						if(html == 'LOG_OUT'){refreshURL('<?php echo($this->config->item('base_url')); ?>lists/login');}
						//confirm(html);
						//$("#link_unlink").html(html);
					}
				});
			}
		}
		
	
	$("#change_module_title").click(function()
	{
		var dataString = "mid=" + $("#modulevalue").val() + "&newtitle=" + $("#module_title").html();
		$.ajax
			({
				type: "POST", url: "/<?php echo($this->config->item('path')); ?>lists/change_module_title/", data: dataString, cache: false, success: function(html)
				{
					//alert(html);
					if(html == 'LOG_OUT'){refreshURL('<?php echo($this->config->item('base_url')); ?>lists/login');}
					else{$("#link_unlink").html(html);}
				}
		});
	});
	/*
	function set_pdf_link(){
		var pdflink = '<a href="https://library.wit.ie/includes/writepdf.php?email=' + $("#staffemail").val() + '" target="_blank"> PDF </a>';	
		$("#pdflink").html(pdflink);
		if($("#staffemail").val() == 0){
			$("#pdflink").html('');
		}
	}
*/
	$("#search_source").change(function(){		
		if($("#search_source").val() == 'isbndb'){	
			var t = $("#book_isbn").attr("value");
		}else if($("#search_source").val() == 'copac'){
			var t = $("#book_title").attr("value");		
		}else{
			var t = $("#book_title").attr("value") + ' ' + $("#book_author").attr("value");
		}
		$("#searchcopac_input").attr("value", t);
	});
	
	/*
	button codes
	 .displ-ctrl-title1
	 .displ-ctrl-title2
	 .displ-ctrl-url
	 .displ-ctrl-authed
	 .displ-ctrl-pfrmrs
	 .displ-ctrl-year
	 .displ-ctrl-edn
	 .displ-ctrl-vol
	 .displ-ctrl-iss
	 .displ-ctrl-pp
	 .displ-ctrl-pub
	 .displ-ctrl-pubplace
	 .displ-ctrl-stdnum
	 .displ-ctrl-libid
	 .displ-ctrl-notes
	 .displ-ctrl-ess

*/	
		
	is_linked();
	set_module_title();
	
	});
	
	// jquery modal form...
	$( "#modal_form" ).dialog({
		autoOpen: false,
		height: 600,
		width: 700, 
		modal: true
	});
	$('body').on("click", "#opac_search_dialogue", function() {
			// open up dialogue and populate empty fields with value from title.
			//alert('modal');
			$( "#modal_form" ).dialog( "open" );
			var t = $("#book_title").attr("value") + ' ' + $("#book_author").attr("value");
			var copac = $("#book_title").attr("value");
			$("#searchcat_input").attr("value", t);
			if($("#search_source").val() == 'isbndb'){	
				t = $("#book_isbn").attr("value");
			}
			if($("#search_source").val() == 'copac'){	
				t = $("#book_title").attr("value");
			}
			
			$("#searchcopac_input").attr("value", t);
		
	});

});


//$("#copytoicon").on("click", function(){
//});
</script>

<!--// end page specific javascript -->
<?php 
$this->load->view('includes/htmltop', array('title' => 'Reports: Main')); ?>

<!-- <h1>Don't touch the lists today<br/> - making some adjustments; <br/>(22nd June 2011).</h1> -->

<?php

if(base_url() != $this->config->item('base_url')){
#if($_SERVER['HTTP_REFERER'] == "https://library.wit.ie/Services/reading-lists"){
		//print("<div style=\"border: dashed 1px black; background-color: #b0b0b0\"><pre>");		print_r($this->session->userdata);  		print("</pre></div>");
}

?>


  
    
    
<div id="list"> <!-- #list encapsulates the first block, which contains the lists.  -->
  <div class="row">
    <div class="col-lg-12">
    <br/>
      <div class="panel panel-primary">
      <div class="panel-heading"><button type="button" data-toggle="modal" data-target="#addNewList" class="addNewList btn btn-primary btn-xs" style="float:right;"><i class="fa fa-user"></i> Add New List</button><h3>Your Lists</h3></div>
      <div class="panel-body" style="background-color:#ffffff">
        <?php
	$inbox_list = '';
	if($this->session->userdata('user')){	  ?>
        <?php 
	  if(isset($selected_module_dropdown)){  // if there is a staff email selected, this gets generated.
		//$c = 0;
		$r = 222;
		//print_r($selected_module_dropdown);
		$inbox_list = "";  
		$spacer = "";
		foreach($selected_module_dropdown as $key => $result){
			if($r != 0 && $r != $result['type']){$spacer = " margin-top: 10px;";}else{$spacer = " margin-top: 0px;";}
			if($result['type'] == 2){ // this is an inbox
				$listOLStyle = "background-color: #f5f5f5;" . $spacer;
				$listIStyle = "title=\"This is your default inbox for new list items\" style=\"cursor:help; color: black; margin-left: 4px;\"";
				$listTypeIcon = "fa-inbox";
			}elseif($result['type'] == 3){ // this is an module descriptor list
				$listOLStyle = "background-color: transparent; border: solid 1px white;" . $spacer;
				$listIStyle = "title=\"This comes from the module catalogue\" style=\"cursor:help; color: black; margin-left: 4px;\"";
				$listTypeIcon = "fa-bookmark";
			}elseif($result['type'] == 4){ // this is an personal list
				$listOLStyle = "background-color: transparent; border: solid 1px white;" . $spacer;
				$listIStyle = "title=\"This is a personal list which you have created\" style=\"cursor:help; color: black; margin-left: 4px;\"";
				$listTypeIcon = "fa-user";
			}else{ // type == 1 (from Moodle)
				$listOLStyle = "background-color: transparent; border: solid 1px white;" . $spacer;
				$listIStyle = "title=\"This is a list created through moodle by your teacher\" style=\"cursor:help; color: black; margin-left: 4px;\"";
				$listTypeIcon = "fa-bookmark-o";
			}
			$ancestry_breadcrumb_end = "</li></ol>\n";
			$r = $result['type'];
			$ancestry_breadcrumb_icon = "<i class=\"fa $listTypeIcon\" $listIStyle></i> ";
				
			// $type can be 1 - own inbox; 2 - moodle lists; 3 - module catalogue; 
			$ancestry_breadcrumb = "<ol class=\"breadcrumb inset-text\" style=\"margin-bottom: 1px; padding: 1px; $listOLStyle\"><li>$ancestry_breadcrumb_icon&nbsp;</li>";
			if($result['type'] == 1){
				if($key != ''){
					$anc = $result['ancestry'];
					$i = 0;
					for($i == 0; $i < count($anc); $i++){// as $an){
						$ancestry_breadcrumb .= "<li><span class=\"removeFromMobileView\">".$anc[$i]['name']."</span></li>"; //$anc[$i]['id']
					}
				}
			}
			$ancestry_breadcrumb .= "<li><span class=\"mods\" style=\"cursor: pointer;\" id=\"modulenameListItem_".$result['mid']."\" ><strong style=\"color: #0088cc\">" . stripslashes($result['modulename'])."&nbsp;</strong></span>". $ancestry_breadcrumb_end;
			if($result['type'] == 2){
				$inbox_list = $ancestry_breadcrumb;
			}else{
				print("$ancestry_breadcrumb\n");
			}
			//$c=0;
		}
		// print($inbox_list);
	  }
?>
      
      
      <?php echo($inbox_list); ?>
      </div>
      </div> 
      <form  id="loginform" method='post' style="margin: 0px;">
        <input type="hidden" name="modulevalue" id="modulevalue" value="<?php echo($sel_mod); ?>"/>
        <?php } ?>
      </form>
      <?php 
		if($this->session->userdata('authorised') == 'TRUE' && $this->session->userdata('admin') == 'TRUE'){
			echo('<div style="">');
		}else{
			# echo('<div style="border:solid 1px red; height: 2px; visibility: hidden">');
			echo('<div style="">');
			# we normally hide the dropdown from non-admin or casual visitors, to avoid
			# them interfering with other courses.
		}
		echo('</div>');
	?>
      <!-- <button id="addnewbook" class="buttonspan"  type="button"style="display: none;">Add new Book</button> --> 
      
    </div>
    <!-- /.col-lg-12 --> 
  </div>
  <!-- /.row --> 
</div>
<div id="booklist" style="">

    <div id="fieldset_readinglist" style="border:none;">
    <!--
        <div class="panel-body" style="border:none; background-color:#d9edf7; ">
          <form id="booklistform" action="/readinglists/lists/movebook/" method="post">
            < !-- /.table-responsive -- >
            
            <div class="col-md-8">
              <div class="panel panel-primary" style="border:none;">
                <div class="panel-body" style="border:none;">
                  <div id="myfirstchart" style="height: 250px; max-width:100%"> </div>
                </div>
              </div>
            </div>
          </form>
        </div>
        -->
    </div>
  
</div> <!-- / div#booklist -->
<div id="editbook" style="display: none;"> <!-- <a name="editbook"> </a> -->
  <div class="row">
      
      <!-- <table id="dynamiccontentcontainer"><tr><td  style="vertical-align:top"> -->
      <div class="book_edit_form_container col-md-6"><!-- edit book form --></div>
      <!-- </td><td  style="vertical-align:top"> -->
      <div id="showcat" class="search_results_container col-md-6"><!-- catalogue results --></div>
      <!-- </td></tr></table> --> 
      
  </div>
  <!-- /.row --> 
  

  
</div>  
<!-- Modal -->
<div class="modal fade" id="addNewList" tabindex="-1" role="dialog" aria-labelledby="addNewListLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="addNewListLabel">Add New List</h4>
      </div>
      <div class="modal-body">
      
            <div class="form-group">
                <input class="form-control" placeholder="Name your list!" id="newListName" name="newListName" type="text" autofocus>
            </div>
                <input class="form-control" id="inviteeMid" name="inviteeMid" value="<?php echo('module_id'); ?>" type="hidden">
          
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" id="newListModalButton" class="btn btn-primary">Create</button>
      </div>
    </div>
    <!-- /.modal-content --> 
  </div>
  <!-- /.modal-dialog --> 

</div>
<!-- /.modal -->
<br/>
<?php $this->load->view('includes/bottom'); ?>
