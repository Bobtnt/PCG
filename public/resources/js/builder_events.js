
$(document).ready(function(){
	
	
	$("#buttonNewObject").click(newPcgObject);
	
	$(".canvas").selectable({ filter: '.pcgObject' });
	
	$("#buttonNewRelation").click(function(){
		$('#dialog').dialog('open');
	});
	
	$("#dialog").dialog({
		bgiframe: true,
		autoOpen: false,
		height: 300,
		modal: true,
		buttons: {
			'Select': function() {
				
				var selectedValue = $("#dialog select").val();
				newRelationObject(selectedValue);
				
				$(this).dialog('close');
				
			},
			Cancel: function() {
				$(this).dialog('close');
			}
		},
		close: function() {
		}
	});

	
});