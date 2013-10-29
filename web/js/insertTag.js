function insertUrl(textarea) {
	url = prompt('Inscrivez ici votre URL :');
	insertion("[url="+ url + "]", "[/url]", textarea);
}	
function insertion(text1,text2, textarea) {
	var ta = document.getElementById(textarea);
	if (document.selection) {
		var str = document.selection.createRange().text; 
		ta.focus();
		var sel = document.selection.createRange();
		if (text2!="") {
			if (str=="") {
				var instances = countInstances(text1,text2);
				if (instances%2 != 0){ sel.text = sel.text + text2;}
				else{ sel.text = sel.text + text1;}
			}
			else { sel.text = text1 + sel.text + text2; }
		}
		else { sel.text = sel.text + text1; }
	}
	else if (ta.selectionStart || ta.selectionStart == 0) {
		if (ta.selectionEnd > ta.value.length) { ta.selectionEnd = ta.value.length; }
		var firstPos = ta.selectionStart;
		var secondPos = ta.selectionEnd+text1.length;
		var texteScrollTop = ta.scrollTop;
		ta.value=ta.value.slice(0,firstPos)+text1+ta.value.slice(firstPos);
		ta.value=ta.value.slice(0,secondPos)+text2+ta.value.slice(secondPos);
		ta.selectionStart = firstPos+text1.length;
		ta.selectionEnd = secondPos;
		ta.focus();
		ta.scrollTop = texteScrollTop;
	}
	else { // Opera
		var sel = document.formulaire.texte;
		var instances = countInstances(text1,text2);
		if (instances%2 != 0 && text2 != ""){ sel.value = sel.value + text2; }
		else{ sel.value = sel.value + text1; }
	}
}
function insertTag(startTag, endTag, textareaId, tagType) {
	var field  = document.getElementById(textareaId); 
	var scroll = field.scrollTop;
	field.focus();
	if (window.ActiveXObject) {
		var textRange = document.selection.createRange();            
		var currentSelection = textRange.text;
		textRange.text = startTag + currentSelection + endTag;
		textRange.moveStart("character", -endTag.length - currentSelection.length);
		textRange.moveEnd("character", -endTag.length);
		textRange.select();     
	} 
	else {
		var startSelection   = field.value.substring(0, field.selectionStart);
		var currentSelection = field.value.substring(field.selectionStart, field.selectionEnd);
		var endSelection     = field.value.substring(field.selectionEnd);
                
		field.value = startSelection + startTag + currentSelection + endTag + endSelection;
		field.focus();
		field.setSelectionRange(startSelection.length + startTag.length, startSelection.length + startTag.length + currentSelection.length);
	} 
	field.scrollTop = scroll;   
}