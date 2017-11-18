jQuery(document).ready(function($) {
	var editors = {};
  $('.clndr-editor').each(function(index) {
    $(this).attr('id', 'code-' + index);
		var thisWidth = $(this).width();
		var thisHeight = $(this).height();
    var language = $(this).attr("data-language");
    editors[index] = CodeMirror.fromTextArea(document.getElementById('code-' + index), {
      mode: language,
      lineNumbers: true,
      theme: "default",
			lineWrapping: true,
			matchTags: {bothTags: true},
			matchBrackets: true,
			indentWithTabs: true,
			indentUnit: 4
    });
		//editors[index].setSize(thisWidth,thisHeight);

		//inherit the height from the textarea
		editors[index].setSize(null,thisHeight);
  });
	$(".CodeMirror-wrap").css("width","");
});
