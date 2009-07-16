
$(document).ready(function(){
	
	//Tools bar buttons
	$("#buttonNewObject").click(newPcgObject);
	$("#buttonDeleteObject").click(deleteObject);
	$("#buttonNewRelation").click(function(){
		$('#dialog').dialog('open');
	});
	
	
	
	
	
	//Global envents
	//$(".canvas").selectable({ filter: '.pcgObject' });
	$("#dialog").dialog({
		bgiframe: false,
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


function reloadEventTriggers(){
	
	//Objects actions
	$(".pcgObjectHeader").dblclick(renameObject);
	$(".pcgObject").resizable();
	$(".pcgObject").draggable({ handle: '.pcgObjectHeader' });
	$(".addProperty").click(addProperty);
	$(".renameProp").click(renameProperty);
	$(".propertiesBlock").sortable({revert : true, items: 'tr:not(td a .addProperty)'});
	$(".propertiesBlock").disableSelection();

}