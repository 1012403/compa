$(function(){
	$('#btnSaveTemp').click(function() {
		var chk = confirm("見積り内容を一時保存します。よろしいですか？"); //Do you want update this quote ?
    	if(!chk){
    		return;
    	}
        $("#mode").val("SAVE_TEMP");
        $("#formQuoteDetail").ajaxForm({
            success: function(data){
                if(data == "OK") {
                    alert("正常に更新しました。"); // Update success !
                    window.location.href = $("#url_base").val() +'/admin/quote';
                } else {
              	  $("#displayError").html(data);
                }
      		}
          }).submit();
	});
	$('#btnSaveQuote').click(function() {
		//var chk = confirm("更新したいですか？");
    	//if(!chk){
    	//	return;
    	//}
    	
        $("#mode").val("SAVE");
        $("#formQuoteDetail").ajaxForm({
            success: function(data){
                if(data == "OK") {
                    alert("正常に更新しました。"); // Update success !
                    window.location.href = $("#url_base").val() +'/admin/quote';
                } else {
              	  $("#displayError").html(data);
                }
      		}
          }).submit();
	});
	$("#txtValidDate").datepicker({
        dateFormat:'yy/mm/dd',
        showOn: 'both',
        buttonImage: '/images/calendar.gif',
        buttonImageOnly: true
    });
});