/**
 *
 * @returns {Boolean}
 */
function checkLogin() {
	var username = $("#login_username").val();
	var password = $("#login_password").val();
	if (username == "") {
		$("#errorArea").empty();
		$("#errorArea").append("ユーザIDは必ず入力してください。");
		$('#login_username').focus();
		return false;
	}
	if (password == "") {
		$("#errorArea").empty();
		$("#errorArea").append("パスワードは必ず入力してください。");
		$('#login_password').focus();
		return false;
	}
	return true;
}
/**
 *
 * @returns {Boolean}
 */
function checkChangePw() {
	var passwd = $("#passwd").val();
	var passwdConfirm = $("#passwdConfirm").val();
	if (passwd == "") {
		$("#errorArea").empty();
		$("#errorArea").append("新しいパスワードは必ず入力してください。");
		$('#passwd').focus();
		return false;
	}

	if (passwdConfirm == "") {
		$("#errorArea").empty();
		$("#errorArea").append(" 新しいパスワード（確認用）は必ず入力してください。");
		$('#passwdConfirm').focus();
		return false;
	}

	if (passwdConfirm != passwd) {
		$("#errorArea").empty();
		$("#errorArea").append("パスワードとパスワード（確認）が一致しません。");
		$('#passwd').focus();
		return false;
	}
	return true;

}
/**
 *
 * @returns {Boolean}
 */
function checkSendlink() {
	var userSendlink = $("#login_username").val();
	var emailSendlink = $("#email").val();
	if (userSendlink == "") {
		$("#errorArea").empty();
		$("#errorArea").append("ユーザIDは必ず入力してください。");
		$('#login_username').focus();
		return false;
	}

	if (emailSendlink == "") {
		$("#errorArea").empty();
		$("#errorArea").append("メールアドレスは必ず入力してください。");
		$('#email').focus();
		return false;
	}
	return true;

}

function check() {
	var emailSendlink = $("#email").val();
	if (emailSendlink != "") {
		//$("#errorArea").empty();
		//$("#errorArea").append("ユーザIDは必ず入力してください。");
		$('#email').focus();
		return false;
	}
	return true;

}





