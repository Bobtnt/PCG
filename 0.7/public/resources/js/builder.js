newPcgObject = function(){
	var html = '<div class="pcgObject">'
			+  '<div class="pcgObjectHeader">'
			+  'New object'
			+  '</div>'
			+  '<div class="pcgObjectBody">'
			+  'Body'
			+  '</div>'
			+  '</div>';
	var jhtml = $(html);
	jhtml.draggable({ handle: '.pcgObjectHeader'});
	$(".canvas").append($(jhtml));
}

newRelationObject = function(type){

	$(".svgcontainer").drawLine(500, 500, 600, 600);
	
}


