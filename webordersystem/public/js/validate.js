/**
 * check input empty
 *
 * @param id
 * @param errMsg
 * @param errArea
 * @returns {Boolean}
 */
function checkEmpty(id, label, errArea) {
	if ($.trim($("#" + id).val()).length == 0) {
		var messVar = new Array(label);
		$message = getMessage("W001", messVar);
		$("#" + errArea).append($message + "<br/>");
		return false;
	}
	return true;
}

/**
 * Check max length
 *
 * @param id
 * @param errMesg
 * @param errArea
 * @param maxLength
 * @returns {Boolean}
 */
function checkMaxLength(id, label, errArea, maxLength) {
	if ($.trim($("#" + id).val()).length > maxLength) {
		var messVar = new Array(label, maxLength);
		$message = getMessage("W005", messVar);
		$("#" + errArea).append($message + "<br/>");
		return false;
	}
	return true;
}

/**
 * check email format
 *
 * @param id
 * @param errMesg
 * @param errArea
 * @returns {Boolean}
 */
function checkMailFormat(id, label, errArea) {
	if (!isValidEmail($("#" + id).val())){
		var messVar = new Array(label);
		$message = getMessage("W008", messVar);
		$("#" + errArea).append($message + "<br/>");
		return false;
	}
	return true;
}

/**
 *
 * @param email
 * @returns
 */
function isValidEmail(email) {
	var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
	return filter.test($.trim(email));
}

/**
 * Check That Both Passwords Match
 *
 * @param pass
 * @param passConf
 * @param errMsg
 * @param errArea
 * @returns {Boolean}
 */
function checkMatchPass(pass, passConf, label, errArea) {
	if ($("#" + pass).val() != $("#" + passConf).val()) {
		var messVar = new Array(label);
		$message = getMessage("W003", messVar);
		$("#" + errArea).append($message + "<br/>");
		return false;
	}

	return true;
}

function focus(idElement) {
	$('#'+idElement).focus();
}

function getMessage(code, arrVal) {
	var message = mes[code];
	var key = "";
	for (var i=0;i<arrVal.length;i++)
	{
		key = "{" + i +"}";
		message = message.replace(key, arrVal[i]);
	}
	return message;
}

/**
 * alert message user have no right
 */
function alertNotAllow() {
	var messVar = new Array();
	$message = getMessage("E002", messVar);
	alert($message);
}

function checkHalfSizeEnglish(id, label, typeCheck, errArea) {
	if (!$.trim($("#" + id).val()).match(/^[A-Za-z0-9]*$/)) {
		var messVar = new Array(label, typeCheck);
		$message = getMessage("W004", messVar);
		$("#" + errArea).append($message + "<br/>");
		return false;
	}
	return true;
}


/**
 * textfield only input number
 * @param String classNumberInput
 */
function textfieldMustInputNumber(classNumberInput){
	$("." + classNumberInput).css("ime-mode", "disabled");
    $("." + classNumberInput).keydown(function(event) {
        // press enter to submit form
//    	var thisElement = $(this);
        if (event.keyCode == 13) {
//            if (thisElement.closest("form").length > 0) {
//            	thisElement.closest("form").submit();
//            }
        }

        // Allow: backspace, delete, tab and escape
        if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 ||
             // Allow: Ctrl+A
             (event.keyCode == 65 && event.ctrlKey === true) ||
             // Allow: home, end, left, right
             (event.keyCode >= 35 && event.keyCode <= 39)) {
            // let it happen, don't do anything
            return;
        }
        else {
            // Ensure that it is a number and stop the keypress
            if (event.shiftKey|| (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105)) {
                event.preventDefault();
            }
        }
    });
    $("." + classNumberInput).each(function() {
    	var thisElement = $(this);
    	thisElement.blur(function() {
	    	if (!$.isNumeric(thisElement.val())) {
	    		thisElement.val("");
	    	}
		});
    });

}

/**
 * check number format
 *
 * @param id
 * @param errMesg
 * @param errArea
 * @returns {Boolean}
 */
function checkNumberFormat(id, label, errArea) {
	var intRegex = /[0-9 -()+]+$/;
	if (!intRegex.test($("#" + id).val())){
		var messVar = new Array(label);
		$message = getMessage("W012", messVar);
		$("#" + errArea).append($message + "<br/>");
		return false;
	}
	return true;
}

function checkValidDateOnly(dtYear, dtMonth, dtDay) {

	var bol = true;
	try {

		if (!((dtYear != "" && dtMonth != "" && dtDay != "")
				|| (dtYear == "" && dtMonth == "" && dtDay == ""))) {
			return false;
		}


		dtYear 	= parseInt(dtYear);
		dtMonth = parseInt(dtMonth);
		dtDay 	= parseInt(dtDay);

		if (dtMonth < 1 || dtMonth > 12) {
			bol = false;
		} else if (dtDay < 1 || dtDay > 31) {
			bol = false;
		} else if ((dtMonth == 4 || dtMonth == 6 || dtMonth == 9 || dtMonth == 11)
				&& dtDay == 31) {
			bol = false;
		} else if (dtMonth == 2) {
			var isleap = (dtYear % 4 == 0 && (dtYear % 100 != 0 || dtYear % 400 == 0));

			if (dtDay > 29 || (dtDay == 29 && !isleap)) {
				bol = false;
			}
		}
	} catch (e) {
		alert("catch date");
		bol = false;
	}
	return bol;
}



function validateTotal(arrCheck, errorArea) {
	$('#' + errorArea).empty();
	var result = true;
	for ( var int = 0; int < arrCheck.length; int++) {
		var curResult = true;
		var cur = arrCheck[int];
		var type =  "";
		var id = "";
		var label = "";
		var compare = "";

		if (cur['compare'] != undefined) {
			compare = cur['compare'];
		}

		if (cur['type'] != undefined) {
			type = cur['type'];
		}

		if (cur['id'] != undefined) {
			id = cur['id'];
		}

		if (cur['label'] != undefined) {
			label = cur['label'];
		}

		if (type == '') {
			//alert("type empty");
			return false;
		}


		var valueToCheck = $('#'+id).val();
		//alert(id + ",type="+type+",required="+cur['required']+",value="+valueToCheck);
		if (type == 'text') {
			if (cur['required'] != undefined) {
				if (cur['required'] == 'true') {
					if (valueToCheck == '' ) {
						var messVar = new Array(label);
						var message = getMessage("W001", messVar);
						$("#" + errorArea).append(message + "<br/>");
						curResult = curResult && false;
					}
				}
			}

			if (cur['min'] != undefined && curResult != false) {
				try {
					var minInt = parseInt(cur['min']);
					if (minInt > valueToCheck.length) {
						var messVar = new Array(label, minInt);
						var message = getMessage("W015", messVar);
						$("#" + errorArea).append(message + "<br/>");
						curResult = curResult && false;
					}
				} catch (e) {
				}
			}

			if (cur['max'] != undefined && curResult != false) {
				try {
					var maxInt = parseInt(cur['max']);
					if (maxInt < valueToCheck.length) {
						var messVar = new Array(label, maxInt);
						var message = getMessage("W005", messVar);
						$("#" + errorArea).append(message + "<br/>");
						curResult = curResult && false;
					}
				} catch (e) {
				}
			}

			if (cur['number'] != undefined && curResult != false) {
				try {
					if (!$.isNumeric(valueToCheck)) {
						var messVar = new Array(label);
						var message = getMessage("W012", messVar);
						$("#" + errorArea).append(message + "<br/>");
						curResult = curResult && false;
					}
				} catch (e) {
				}
			}

			if (cur['email'] != undefined && curResult != false) {
				try {
					if (!isValidEmail(valueToCheck)) {
						var messVar = new Array(label);
						$message = getMessage("W008", messVar);
						$("#" + errorArea).append($message + "<br/>");
						curResult = curResult && false;
					}
				} catch (e) {
				}
			}
		}else if (type == 'date') {
			var idYear 	= cur['year'];
			var idMonth = cur['month'];
			var idDay 	= cur['day'];

			var year  = parseInt($('#'+idYear).val());
			var month = parseInt($('#'+idMonth).val());
			var day   = parseInt($('#'+idDay).val());

			var dateValid = false;
			if ((year != "" && month != "" && day != "") || (year == "" && month == "" && day == "")) {
				dateValid = checkValidDateOnly(year, month, day);
				var greater;
				var less;
				if (dateValid) {
					if (compare == 'greater' ) {
						greater = new Date(year, month, day);
					}

					if (compare == 'less') {
						less = new Date(year, month, day);
					}

					if (greater != undefined && less != undefined) {
						if(greater <= less) {
							var messVar = new Array(label);
							var message = getMessage("W017", messVar);
							$("#" + errorArea).append(message + "<br/>");
							curResult = curResult && false;
						}
					}

				}

				if (!dateValid) {
					var messVar = new Array(label);
					var message = getMessage("W009", messVar);
					$("#" + errorArea).append(message + "<br/>");
					curResult = curResult && false;
				}
			}else {
				var messVar = new Array(label);
				var message = getMessage("W009", messVar);
				$("#" + errorArea).append(message + "<br/>");
				curResult = curResult && false;
			}
		}else if (type == 'password') {
			var idconfirmPass = cur['confirmpassword'];
			if (valueToCheck != $('#'+idconfirmPass).val()) {
				var messVar = new Array();
				message = getMessage("W003", messVar);
				$("#" + errorArea).append(message + "<br/>");
				curResult = curResult && false;
			}
		}else if (type == 'checkAll') {
			var arrCheckIds = cur['arrCheckId'];
			var optionToCheckId = cur['optionToCheck'];
			if ($('#'+optionToCheckId + ' option').length > 0){
				var isCheckAll = false;
				for(var idxCheck = 0; idxCheck < arrCheckIds.length; idxCheck++) {
					var idCheck = arrCheckIds[idxCheck];
					if ($('#'+idCheck).is(':checked')) {
						isCheckAll = true;
						break;
					}
				}
				if (isCheckAll == false) {
					var messVar = new Array();
					message = getMessage("W018", messVar);
					$("#" + errorArea).append(message + "<br/>");
					curResult = isCheckAll && curResult;
				}
			}
		}

		result = result && curResult;
	}
	return result;
}

/**
 * Check min length
 *
 * @param id
 * @param errMesg
 * @param errArea
 * @param minLength
 * @returns {Boolean}
 */
function checkMinLength(id, label, errArea, minLength) {
	if ($.trim($("#" + id).val()).length < minLength) {
		var messVar = new Array(label, minLength);
		$message = getMessage("W015", messVar);
		$("#" + errArea).append($message + "<br/>");
		return false;
	}
	return true;
}

/**
 * check file empty
 *
 * @param id
 * @param label
 * @param errArea
 * @returns {Boolean}
 */
function checkFileEmpty(id, label, errArea) {
	if ($.trim($("#" + id).val()).length == 0) {
		var messVar = new Array(label);
		$message = getMessage("W016", messVar);
		$("#" + errArea).append($message + "<br/>");
		return false;
	}
	return true;
}