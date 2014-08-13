var numberOfPrice = 0;
var varTax = 0;
$(function(){
	$( "#selectImage" ).click(function() {
		//alert("file clicked");
//		$('#file').click();
//		var finder = new CKFinder();
//		// Launch CKFinder
//		var s = finder.popup();		
	});

    $('#file').change(function(){
       	var fileName = $('#file').val().split('\\');
        var fileNameSafe = filenameSafe(fileName[fileName.length-1]);
        $('#fileName').val(fileNameSafe);
        $('#addProductForm').attr('action','/admin/product/upload');
        $("#addProductForm").ajaxForm({
              success: function(data){
            	  var arrTemp = data.split("/");
            	  $('#fileName').val(arrTemp[arrTemp.length-1]);
                  $("#imgUpload").attr("src",data);
        		}
            }).submit();
    });

    $('#btnAddCategory').click(function() {
        var row = $("#tableCategory").find('.productRow').html();
        var html = '<div class="productRow">';
        html += row;
        html += "</div>";
        if(row!= null && row != "undefined") {
        	$("#tableCategory").append(html);
        }
        
        //Clear value
        $child = $(".catChild").last();
        $child .empty();
        $child .attr("disabled","disabled");
        
        $parent = $(".catParent").last();
        $parent.val(" ");
    });

    $('#btnAddPrice').click(function() {
    	var index = $(".clsPriceIncludingTax").last().attr("id").split("_");
    	index = index[1];
    	numberOfPrice = +index + +1;

        var row = $("#tablePrice").find('.productRow').html();
        
        var html = '<div class="productRow">';
        html += row;
        html += "</div>";

        if(row!= null && row != "undefined") {
        	
        	//add 20140722 locpht start
        	var namePriceCond = "priceCond_" + numberOfPrice;
        	html = html.split("priceCond_0").join(namePriceCond);
        	
        	var nameQuanRes = "quanRes_" + numberOfPrice;
        	html = html.split("quanRes_0").join(nameQuanRes);
            //add 20140722 locpht end
        	$("#tablePrice").append(html);
        }
        
        var today =  getCurrentDateAsString();
        
        //Clear value
        $(".clsPriceApplyStartDate").last().val(today);      
        $(".clsPrice").last().val('');
        $(".clsPrice").last().attr("id","price_" + numberOfPrice);
        $(".clsPrice").last().blur(function(){
        	calPrice(numberOfPrice);
        });
        $(".clsPriceIncludingTax").last().val('');
        $(".clsPriceIncludingTax").last().attr("id","priceIncludingTax_" + numberOfPrice);
        
        $(".clsTax").last().val('');
        $(".clsTax").last().attr("id","tax_" + numberOfPrice);
        /*
        $(".clsPriceConditionClass").last().val('');
        $(".clsPriceConditionClass").last().attr("id","priceCond_" + numberOfPrice);
        
        $(".clsQuantityRestriction").last().val('');
        $(".clsQuantityRestriction").last().attr("id","quanRes_" + numberOfPrice);
        */
        $("#tablePrice").find('.ui-datepicker-trigger').remove();
        $('.clsPriceApplyStartDate').removeClass("hasDatepicker");
        
        setDatePicker('clsPriceApplyStartDate');
        
        setTxtNumber();
        
    });

    $('#btnAddPoint').click(function() {
        var row = $("#tablePoint").find('.productRow').html();
        var html = '<div class="productRow">';
        html += row;
        html += "</div>";
        if(row!= null && row != "undefined") {
        	$("#tablePoint").append(html);
        }
        
        var today =  getCurrentDateAsString();
        
        //Clear value
        $(".clsPointApplyStartDate").last().val(today);      
        $(".clsMagnificationPoint").last().val('1');
        
        $("#tablePoint").find('.ui-datepicker-trigger').remove();
        $('.clsPointApplyStartDate').removeClass("hasDatepicker").removeAttr('id');
        
        setDatePicker('clsPointApplyStartDate');
    });

    $('#btnSave').click(function() {
    	// DEL 20140429 Hieunm start
    	/*var chk = confirm("Do you want insert/update this product ?");
    	if(!chk){
    		return;
    	}*/
    	// DEL 20140429 Hieunm end
    	$('#addProductForm').attr('action','/admin/product/save');
        $("#addProductForm").ajaxForm({
              success: function(data){
                  if(data == "INSERT" || data == "UPDATE") {
                	  // MOD 20140429 Hieunm start
                	  //alert(data + " product success !");
                	  var errMsg = "";
                	  if(data == "INSERT"){
                		  //errMsg = "商品情報が正常に挿入されます。";
                		  errMsg = "商品情報が正常に追加登録されました。";
                	  } else {
                		  //errMsg = "商品情報が正常に更新されます。";
                		  errMsg = "商品情報が正常に更新されました。";
                	  }
                	  alert(errMsg);
                	  // MOD 20140429 Hieunm end
                      
                      window.location.href = $("#url_base").val() +'/admin/product';
                  } else {
                	  $("#displayError").html(data);
                  }
        		}
            }).submit();
    });

    $('#shippingCheck').change(function(){
    	shippingCheck();
    });

    $('#stockCheck').change(function(){
    	stockChange();
    });

    $('input[name=shipping]').change(function(){
    	if(this.value != "2") {
            $("input[name=shippingValue]").attr('disabled',true);
        } else {
        	$("input[name=shippingValue]").attr('disabled',false);
        }
    });

    $('input[name=stock]').change(function(){
    	if(this.value != "2") {
            $("input[name=stockValue]").attr('disabled',true);
        } else {
        	$("input[name=stockValue]").attr('disabled',false);
        }
    });
    
    setDatePicker('clsPriceApplyStartDate');
    setDatePicker('clsPointApplyStartDate');
    
    $(document).ready(function() {
    	setTxtNumber();
    	$(".clsPrice").last().blur(function(){
        	calPrice(numberOfPrice);
        });
    	getTax();
    	
    });
    
    //Rename tag html
    $('select[name=categoryParent]').attr('name','categoryParent[]');
    $('select[name=categoryChild]').attr('name','categoryChild[]');
    $('input[name=priceApplyStartDate]').attr('name','priceApplyStartDate[]');
    $('input[name=price]').attr('name','price[]');
    $('input[name=priceIncludingTax]').attr('name','priceIncludingTax[]');
    $('input[name=tax]').attr('name','tax[]');
    $('input[name=pointApplyStartDate]').attr('name','pointApplyStartDate[]');
    $('input[name=magnificationPoint]').attr('name','magnificationPoint[]');
    
    function shippingCheck(){
    	if($('#shippingCheck').is(":checked")) {
    		$("input[name=shipping]").attr('disabled',false);
            if($('input[name=shipping]:checked').val() == "2") {
            	$("input[name=shippingValue]").attr('disabled',false);
            }else{
            	$("input[name=shippingValue]").attr('disabled',true);
            }
        } else {
            $("input[name=shipping]").attr('disabled',true);
            $("input[name=shippingValue]").attr('disabled',true);
        }	
    }
    
    function stockChange(){
    	if($('#stockCheck').is(":checked")) {
    		$("input[name=stock]").attr('disabled',false);
    		if($('input[name=stock]:checked').val() == "2") {
    			$("input[name=stockValue]").attr('disabled',false);
    		}else {
    			$("input[name=stockValue]").attr('disabled',true);
    		}
        } else {
            $("input[name=stock]").attr('disabled',true);
            $("input[name=stockValue]").attr('disabled',true);
        }	
    }
    
    shippingCheck();
    stockChange();
    
    //Disable combo 商品カテゴリー
    $('.catChild').filter(function() {        
        var val = $(this).val();
        if(val == null || val == "undefined"){
        	$(this).attr("disabled",true);
        }
      }
    );
    
    //Event filter
	$("#productSelectSort").change(function() {
//		var val = $(this).val();
//		$url = $("#url_base").val() +'/admin/product/changesort';
//		$.ajax({
//			url: $url,
//			type: "post",
//			data: { "val": val},
//			success: function( strData ){
//				location.reload();
//			}
//		});
		var currentUrl = $(location).attr('href');
		
		$('#mode').val("changefilter");
		$('#formViewListProduct').append('<input type="hidden" name="operateElementType" value="2"/>');
		$('#formViewListProduct').append('<input type="hidden" name="operateElementName" value="並び替え"/>');
		$('#formViewListProduct').attr('action',currentUrl);
		$('#formViewListProduct').submit();
//		$('#formViewListProduct').attr('action','/admin/product/test1');
//		 $("#formViewListProduct").ajaxForm({
//             success: function(data){
//                 alert("test");
//       		}
//           }).submit();
	});
    	
});
function setDatePicker(classCssName){
	var count = 0;
    $('.'+classCssName).filter(function() {        
        $(this).attr('id',classCssName + count++);
      }
    );
    $("."+classCssName).datepicker({
        dateFormat:'yy/mm/dd',
        showOn: 'both',
        buttonImage: '/images/calendar.gif',
        buttonImageOnly: true
    });
}
function filenameSafe(fileName){
  // Lower case
  var temp =  fileName.toLowerCase();
  // Replace spaces with a '_'
  temp = temp.replace(/ /g,'_');

  return temp;
}

function delRow(parentNode,btn){
	var size = $("#"+parentNode).find('.productRow').size();
    //If is final row then don't remove
    if(size > 1){
      $(btn).closest('.productRow').remove();
    }
}

function changeCategory(combo){
	var id = $(combo).val();
    if(id == ""){
        return;
    }

    var $divRow = $(combo).closest(".productRow");
    var $child = $divRow.find(".catChild");
    var strURL = $("#url_base").val() + "/admin/product/category";
	$.ajax({
		url: strURL,
		type: 'POST',
		cache: false,
		data: 'categoryParent='+id,
		success: function(string){
			var category = $.parseJSON(string);
            $child.empty();
            $child.removeAttr("disabled");
            $child.append("<option value=\"\"></option>");
            $.each(category,function(index, value){
            	$child.append("<option value="+index+">" + value + "</option>");
            });
		},
		error: function (){
			alert('Error ajax call url:' + strURL);
		}
	});
}

//View page
function editProduct(id){
	var strURL = $("#url_base").val() + "/admin/product/edit/id/" + id;
	window.location.href = strURL;
}
function deleteProduct(id){
	var r = confirm("削除してもよろしいですか？");
	if(r){
		var strURL = $("#url_base").val() + "/admin/product/delete";
		$.ajax({
			url: strURL,
			type: 'POST',
			cache: false,
			data: 'id='+id,
			success: function(json){
				var $res = $.parseJSON(json);				
	            if($res["success"]){
	            	//alert("Delete is success.");	// DEL 20140429 Hieunm
	            	alert("正常に削除しました。");		// ADD 20140429 Hieunm
	            	//window.location.href = $("#url_base").val() +'/admin/product';
	            	$('#product').append('<form method="post" action="" id="formViewListProduct">');
	                $('#formViewListProduct').append('<input type="hidden" name="AfterDelete" value="true"/>');
	                $('#formViewListProduct').attr('action', $("#url_base").val() +'/admin/product/');
	                $('#formViewListProduct').submit();
	            }else{
	            	//alert("Delete is fail");		// DEL 20140429 Hieunm
	            	alert("削除が失敗しました。");		// ADD 20140429 Hieunm
	            }
			},
			error: function (){
				alert('Error ajax call url:' + strURL);
			}
		});
	}
}
function insertCategory(){
	var checkedNum = 0;
	$('input:checked').each(function() {
		checkedNum++;
	});
	if($(".catParent").val()!="" && $(".catChild").val()!="" && checkedNum>0){
		$('#mode').val("insertCategory");
		
		var currentUrl = $(location).attr('href');
		$('#formViewListProduct').attr('action',currentUrl);
		$('#formViewListProduct').submit();
	}
}

function setTxtNumber() {
	/*$(".txtNumber").keydown(function (e) {
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
    });*/
	$('.txtNumber').number( true, 0 );
}

function calPrice(id) {
	for (i = 0; i < id + 1; i++) { 
		var taxEl = $('#tax_' + i);
		var priceIncTaxEl = $('#priceIncludingTax_' + i);
		var priceEl = $('#price_' + i);
		
		var price = priceEl.val();
		price = parseInt(price, 10);
		
		var tax = price * varTax / 100;
		var priceIncTax = price + tax;
		
		taxEl.val(tax);
		priceIncTaxEl.val(priceIncTax);
	}
}

function getTax() {
	$.ajax({
		  url: "/admin/product/gettax"
		})
		.done(function( data ) {
				varTax = parseInt(data, 10);
		});
}
var featuredProductRunning = 0;
function addFeatureProduct(productId) {
	/*
	if (featuredProductRunning == 1) {
		return;
	}
	featuredProductRunning = 1;
	$.ajax({
		  url: "/admin/product/addfeadture",
		  data: { id: productId}
		})
		.done(function( data ) {
			try {
				data = jQuery.parseJSON( data );
				if (data.success == true) {
					displayRemoveFeatureBtn();
				} else {
					alert("エラー:" + data.error );
				}
			} catch (e) {
				alert("エラー:" + e.message );
			}
			setTimeout(function(){
				featuredProductRunning = 0;
			},50);
			
		});
	*/
	$('#addFeatureId').val('1');
	displayRemoveFeatureBtn();
}

function removeFeatureProduct(productId) {
	/*if (featuredProductRunning == 1) {
		return;
	}
	featuredProductRunning = 1;
	$.ajax({
		  url: "/admin/product/removefeadture",
		  data: { id: productId}
		})
		.done(function( data ) {
			try {
				data = jQuery.parseJSON( data );
				if (data.success == true) {
					displayAddFeatureBtn();
				} else {
					alert("エラー:" + data.error );
				}
			} catch (e) {
				alert("エラー:" + e.message );
			}
			setTimeout(function(){
				featuredProductRunning = 0;
			},50);
		});*/
	$('#addFeatureId').val('0');
	displayAddFeatureBtn();
}

function displayRemoveFeatureBtn() {
	$('#btnAddFeatureProduct').hide();
	
	if ($('#btnAddFeatureProduct_space').length > 0) {
		$('#btnAddFeatureProduct_space').hide();
	}
	
	$('#btnRemoveFeatureProduct').show();
	
	if ($('#btnRemoveFeatureProduct_space').length > 0) {
		$('#btnRemoveFeatureProduct_space').show();
	}
}

function displayAddFeatureBtn() {
	$('#btnRemoveFeatureProduct').hide();
	if ($('#btnRemoveFeatureProduct_space').length > 0) {
		$('#btnRemoveFeatureProduct_space').hide();
	}
	
	$('#btnAddFeatureProduct').show();
	
	if ($('#btnAddFeatureProduct_space').length > 0) {
		$('#btnAddFeatureProduct_space').show();
	}
}

function addFeaturedProductById(productId) {
	
	if (featuredProductRunning == 1) {
		return;
	}
	featuredProductRunning = 1;
	$.ajax({
		  url: "/admin/product/addfeadture",
		  data: { id: productId}
		})
		.done(function( data ) {
			try {
				data = jQuery.parseJSON( data );
				if (data.success == true) {
					displayRemoveFeatureBtnById(productId);
				} else {
					alert("エラー:" + data.error );
				}
			} catch (e) {
				alert("エラー:" + e.message );
			}
			setTimeout(function(){
				featuredProductRunning = 0;
			},50);
			
		});
}

function removeFeaturedProductById(productId) {
	if (featuredProductRunning == 1) {
		return;
	}
	featuredProductRunning = 1;
	$.ajax({
		  url: "/admin/product/removefeadture",
		  data: { id: productId}
		})
		.done(function( data ) {
			try {
				data = jQuery.parseJSON( data );
				if (data.success == true) {
					displayAddFeatureBtnById(productId);
				} else {
					alert("エラー:" + data.error );
				}
			} catch (e) {
				alert("エラー:" + e.message );
			}
			setTimeout(function(){
				featuredProductRunning = 0;
			},50);
		});
}

function displayRemoveFeatureBtnById(id) {
	$('#addFeaturedProductId_'+id).hide();
	$('#removeFeaturedProductId_'+id).show();
}

function displayAddFeatureBtnById(id) {
	$('#addFeaturedProductId_'+id).show();
	$('#removeFeaturedProductId_'+id).hide();
}

function getCurrentDateAsString() {
	var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth()+1; //January is 0!

    var yyyy = today.getFullYear();
    if(dd < 10){
    	dd = '0' + dd;
    } 
    if(mm < 10){
    	mm = '0' + mm;
    } 
    var today =  yyyy + '/' + mm + '/' + dd;
    
    return today;
}
