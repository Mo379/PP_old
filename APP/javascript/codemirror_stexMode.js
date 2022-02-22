	function qsa(sel) {
    	return Array.apply(null, document.querySelectorAll(sel));
	}
	
	$(document).ready(
	function(){
		var name = ".codemirror-textarea";
		
		
		var elements = qsa(name);
		
		elements.forEach(
			
			
			function (element) {
			var id = element.id;
			var content = element.value;
			var editor = CodeMirror.fromTextArea(element, {
				area_id: id,
				indentWithTabs: true,
				electricChars: false,
				fixedGutter: true,
				cursorScrollMargin:0,
				viewportMargin: Infinity,
				spellcheck:true,
				autocorrect:true,
				autocapitalize: true,
				mode :  "stex",
				height: "auto",
				width: "auto",
				lineWrapping:true,
			});
		})
	});
