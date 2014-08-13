function gotoPage(page) {
	document.location = page;
}

function resizePage() {
	 var heightVal = $('#contentsWrapper').height();
     var heightVal2 = $('#globalNav').height() + 85;
     if (heightVal2 < heightVal) {
     	$('#globalNav').height(heightVal - 22);
     } else {
     	$('#globalNav').height(heightVal2 -22);
     	$('#contentsWrapper').height(heightVal2);
     }
}

function resizePage2() {
	var leftHeight = $('#globalNavWrapper').height();
    var contentHeight = $('#contentsWrapper').height();
    if (leftHeight < contentHeight) {
    	$('#globalNavWrapper').height(contentHeight);
    	$('#globalNav').height(contentHeight - 22);
    } else {
    	$('#contentsWrapper').height(leftHeight);
    }
}

function resizeImage(classImage, maxWidth, maxHeight) {
	//setTimeout(function(){

		$('.' + classImage).each(function() {
		    var ratio = 0;  // Used for aspect ratio
		    var width = $(this).width();    // Current image width
		    var height = $(this).height();  // Current image height

		    // Check if the current width is larger than the max
		    if(width > maxWidth){
		        ratio = maxWidth / width;   // get ratio for scaling image
		        $(this).css("width", maxWidth); // Set new width
		        $(this).css("height", height * ratio);  // Scale height based on
														// ratio
		        height = height * ratio;    // Reset height to match scaled image
		    }

		    var width = $(this).width();    // Current image width
		    var height = $(this).height();  // Current image height

		    // Check if current height is larger than max
		    if(height > maxHeight){
		        ratio = maxHeight / height; // get ratio for scaling image
		        $(this).css("height", maxHeight);   // Set new height
		        $(this).css("width", width * ratio);    // Scale width based on
														// ratio
		        width = width * ratio;    // Reset width to match scaled image
		    }
		});

	//}, 250);

}

function enable(idElement, isEnable) {
	if (!isEnable) {
		$('#'+idElement).attr("disabled", "disabled");
	}else {
		$('#'+idElement).removeAttr("disabled");
	}
}

function enableForm(idForm, isEnable) {
	if (!isEnable) {
		$('#'+idForm+" :input").attr("disabled", "disabled");
	}else {
		$('#'+idForm+" :input").removeAttr("disabled");
	}
}

function fillZero(idElement, expectLength) {
	while ($('#' + idElement).val().length < expectLength) {
		$('#' + idElement).val('0' + $('#' + idElement).val());
	}
}

function fillZeroVal(val, expectLength) {
	val = val +'';
	while (val.length < expectLength) {
		val = '0' + val;
	}
	return val;
}

function getCurrentDate(){
    var currentTime = new Date();
    var month = currentTime.getMonth() + 1;
    var day = currentTime.getDate();
    var year = currentTime.getFullYear();
    var hours = currentTime.getHours();
    var minutes = currentTime.getMinutes();

    month 	= fillZeroVal(month,2);
    day   	= fillZeroVal(day,2);
    hours 	= fillZeroVal(hours,2);
    minutes = fillZeroVal(minutes,2);


    return year + "-" + month + "-" + day + " " + hours + ":" + minutes + ":00";
}

/*function showErrorMessage(mess) {
	$.colorbox({
		html:"<div><img width=\"50\" src=\"" + urlBase + "/images/web/error.png\">&nbsp;&nbsp;<span style=\"font-weight:bold;font-size:25px;\">" + mess + "</span>",
		scrolling : false,
		transition : 'none',
		speed : 100,
		width:400
	});
}*/

function disableBtn(id) {
	$('#' + id).attr("disabled", true);
}

// Add 20140515 Hieunm start
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

function setDatePicker(classCssName){
	var count = 0;
    $('.'+classCssName).filter(function() {        
        $(this).attr('id',classCssName + count++);
      }
    );
    $("."+classCssName).datepicker({
    });
}
// Add 20140515 Hieunm end

function imgError(image) {
    image.onerror = "";
    image.src = urlBase + "/images/products/nopicture.png";
    return true;
}