var totalShipping = 1;
function openNewUserShipping() {
	totalShipping++;
	var url = "/admin/user/newusershipping/id/"+totalShipping;
	openColorBox(url);
}

function increaseTotalShipping() {
	totalShipping++;
}

function openEditUserShipping(userid, idshipping) {
	var url = "/admin/user/editusershipping/id/" + idshipping;
	openColorBoxForEdit(url, idshipping);
}

function closeUserShipping() {
	$.colorbox.close();
}

function saveNewTempUserShipping(id) {
	if (!validInputShipping()) {
		return;
	}
	$("#add_shipping_form").ajaxForm({
        success: function(data){
        	$.colorbox.close();
        	$('.row_shipping_data').last().after(data);
        	setTimeout(function(){
        		$('.row_shipping_data').last().fadeIn(200);
        	},200);
        }
  }).submit();
	
}

function saveEditTempUserShipping(id) {
	if (!validInputShipping()) {
		return;
	}
	
	updateDataToGrid(id, function(){
		closeUserShipping();
	});
	
}

function deleteShipping(id) {
	if (confirm("削除してもよろしいですか？")) {
		closeUserShipping();
		$("#shipping_no_"+id).fadeOut(500, function(){
			$("#shipping_no_"+id).remove(); 
		});
	}
}

function validInputShipping() {
	
	$('#error_post_no_id').empty();
	$('#error_address1_id').empty();
	$('#error_des_name_id').empty();
	
	var postNo = $('#shipping_post_no').val();
	postNo = $.trim(postNo);
	
	var address1 = $('#shipping_address1').val();
	address1 = $.trim(address1);
	
	var desName = $('#shipping_des_name').val();
	desName = $.trim(desName);
	
	var error = false;
	if (desName == "") {
		$('#error_des_name_id').html('住所検索を入力してください。');
		error = true;
	}
	
	if (postNo == "") {
		$('#error_post_no_id').html('郵便番号を入力してください。');
		error = true;
	}
	
	if (address1 == "") {
		$('#error_address1_id').html('住所1を入力してください。');
		error = true;
	}
	
	
	if (error) {
		return false;
	}
	
	return true;
}

function submitUserForm() {
	window.onbeforeunload = null;
	$('#errorArea').empty();
	$('#user_form').attr('action','/admin/user/checkinput');
    $("#user_form").ajaxForm({
          success: function(data){
        	/*if (data == "true") {
        		$('#user_form').attr('action','/admin/user/edit');
        		$("#user_form").submit();
        	}*/
        	  $("#idTotalShipping").val(totalShipping);
        	  if (data == "true") {
          		$('#user_form').attr('action','/admin/user/edit');
          		$("#user_form").submit();
          	} else {
          		$('#errorArea').empty();
          		$('#errorArea').append(data);
          	}
          }
    }).submit();
	
}

function loadDataToForm(id){
	var desName = $("#hidden_des_name_" + id).val();
	var postNo = $("#hidden_post_no_" + id).val();
	var address1 = $("#hidden_address1_" + id).val();
	var address2 = $("#hidden_address2_" + id).val();
	var tel_no = $("#hidden_tel_no_" + id).val();
	var fax_no = $("#hidden_fax_no_" + id).val();
	var shipping_method = $("#hidden_shipping_method_" + id).val();
	
	$('#shipping_des_name').val(desName);
	$('#shipping_post_no').val(postNo);
	$('#shipping_address1').val(address1);
	$('#shipping_address2').val(address2);
	$('#shipping_tel_no').val(tel_no);
	$('#shipping_fax_no').val(fax_no);
	$('#shipping_method').val(shipping_method);
	
}

function updateDataToGrid(id, callBack) {
	var desName = $('#shipping_des_name').val();
	var postNo = $('#shipping_post_no').val();
	var address1 = $('#shipping_address1').val();
	var address2 = $('#shipping_address2').val();
	var tel_no = $('#shipping_tel_no').val();
	var fax_no = $('#shipping_fax_no').val();
	var shipping_method = $('#shipping_method').val();
	
	$("#hidden_des_name_" + id).val(desName);
	$("#hidden_shiping_no_" + id).val(id);
	$("#hidden_post_no_" + id).val(postNo);
	$("#hidden_address1_" + id).val(address1);
	$("#hidden_address2_" + id).val(address2);
	$("#hidden_tel_no_" + id).val(tel_no);
	$("#hidden_fax_no_" + id).val(fax_no);
	$("#hidden_shipping_method_" + id).val(shipping_method);
	
	$("#des_name_disp_" + id).html(desName);
	$("#post_no_disp_" + id).html(postNo);
	$("#address1_disp_" + id).html(address1);
	$("#tel_no_disp_" + id).html(tel_no);
	$("#fax_no_disp_" + id).html(fax_no);
	$.ajax({
		type: "POST",
		url: "/admin/user/getshippingname" ,
		data : {id : shipping_method}
		})
		.done(function( msg ) {
			$("#shipping_method_disp_" + id).html(msg);
		});
	
	callBack();
}

function openColorBox(url) {
	$.colorbox({
		href         : url,
		opacity      : 0.5,
		width        : 800,
		height       : 390,
		overlayClose : false,
		transition   : 'fade',
		speed        : 300,
		closeButton  : false,
		xhrError     : "このコンテンツは、ロードに失敗しました。",
		onComplete   : function (){
			setTimeout(function(){
				
				$(".justnumber").keydown(function (e) {
			        // Allow: backspace, delete, tab, escape, enter and .
			        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
			             // Allow: Ctrl+A
			            (e.keyCode == 65 && e.ctrlKey === true) || 
			             // Allow: home, end, left, right
			            (e.keyCode >= 35 && e.keyCode <= 39)) {
			                 // let it happen, don't do anything
			                 return;
			        }
			        // Ensure that it is a number and stop the keypress
			        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
			            e.preventDefault();
			        }
			    });
				
				//loadDataToForm(id);
			},100);
		}
	});
}

function openColorBoxForEdit(url, id) {
	$.colorbox({
		href         : url,
		opacity      : 0.5,
		width        : 800,
		height       : 390,
		overlayClose : false,
		transition   : 'fade',
		speed        : 300,
		closeButton  : false,
		xhrError     : "このコンテンツは、ロードに失敗しました。",
		onComplete   : function (){
			setTimeout(function(){
				
				$(".justnumber").keydown(function (e) {
			        // Allow: backspace, delete, tab, escape, enter and .
			        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
			             // Allow: Ctrl+A
			            (e.keyCode == 65 && e.ctrlKey === true) || 
			             // Allow: home, end, left, right
			            (e.keyCode >= 35 && e.keyCode <= 39)) {
			                 // let it happen, don't do anything
			                 return;
			        }
			        // Ensure that it is a number and stop the keypress
			        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
			            e.preventDefault();
			        }
			    });
				
				loadDataToForm(id);
			},100);
			
		}
	});
}
