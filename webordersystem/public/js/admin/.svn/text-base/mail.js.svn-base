/**
 * checkMailEmpty
 * @returns {Boolean}
 */
function checkMailEmpty() {
	var title = $("#title").val();
	var classitemp = $("#class_itemp").val();
	if (title == "") {
		$("#error").empty();
		$("#error").append("タイトル は必ず入力してください。");
		$('#title').focus();
		return false;
	}
	if (classitemp == "0") {
		$("#error").empty();
		$("#error").append("分類 必ず入力してください。");
		return false;
	}
	return true;
}