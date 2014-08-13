var message_error = new Array();
message_error["1000"] = "{0}";
message_error["1001"] = "{0} is not blank";
message_error["1002"] = "{0} is not number";


function getMessage(messCd, params) {
	var str = message_error[messCd];
	str.replace(/blue/g,"red");
	for (var int = 0; int < params.length; int++) {
		var repl = "{" + int + "}";
		str = str.replace(repl,params[int]);
	}
	return str;
}

function showErrorMessage(messCd, params) {
	var mess =  getMessage(messCd, params);
	alert(mess);
}
