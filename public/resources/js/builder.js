//Globals
aModifiedElements = new Array();


//Tools bar action
newPcgObject = function(){
	var html = '<div class="pcgObject">'
			+  '<div class="pcgObjectHeader">'
			+  '<span value="New object">New object</span>'
			+  '</div>'
			+  '<div class="pcgObjectBody">'
			+  'Body'
			+  '</div>'
			+  '</div>';
	var jhtml = $(html);
	$(".canvas").append($(jhtml));	
	reloadEventTriggers();
}

newRelationObject = function(type){
}

deleteObject = function(){
}

//Objects actions

renameObject = function(){
	convertIntoInput(this.firstChild);	
}



function convertIntoInput(oDOM){	
	var mValue = $(oDOM).attr('value');
	var html = '<input type="text" value="'+ mValue +'" />';
	var storedDOM = new Object;	
	storedDOM.content = $(oDOM).parent().html();
	storedDOM.parent = $(oDOM).parent();
	
	var oHtml = $(html);
	oHtml.blur(returnInputToInitialState);
	
	storedDOM.input = oHtml;
	$(oDOM).parent().html(storedDOM.input);	
		
	aModifiedElements.push(storedDOM);	
}

returnInputToInitialState = function(){
	for(var a in aModifiedElements){
		var sVal = aModifiedElements[a].input.val();
		var oHtml = $(aModifiedElements[a].content);
		oHtml.attr('value', sVal);
		oHtml.html(sVal);
		var oParent = aModifiedElements[a].parent;
		oParent.html(oHtml);
		delete aModifiedElements[a];
	}
	
}