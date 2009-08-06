
$(document).ready(function(){
	//Global var
	aOpcgContainer = new Array;
	aModifiedElements = new Array();
	//daemon
	$('#controlerDebug').daemonize(pcgObjectControl, 0.5 * 1000);	
	$('.messageBox').daemonize(reposMessagebox, 0.5 * 1000);
	
	setInterval('pcgObjectControl()', 0.4 * 1000);	
	setInterval('reposMessagebox()', 0.5 * 1000);
	
	
	//Tools bar buttons
	$("#buttonNewObject").click(newPcgObject);
	//$("#buttonDeleteObject").click(deleteObject);
	$("#buttonNewRelation").click(function(){
		$('#dialog').dialog('open');
	});
	$("#buttonDebug").click(function(){
			console.log(object_dump($.fn.helper[0]));
			console.log(object_dump($.fn.helper[1]));
	});
	//Global envents
	//$(".canvas").selectable({ filter: '.pcgObject' });
	$(".messageBox").css('top', window.innerHeight - 30 );
	
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

