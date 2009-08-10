
$(document).ready(function(){
	//Global var
	iPcgContainerGlobalCounter = 0;
	aOpcgContainer = new Array;
	aModifiedElements = new Array();
	//daemon
	//$('#controlerDebug').daemonize(pcgObjectControl, 0.5 * 1000);	
	//$('.messageBox').daemonize(reposMessagebox, 0.5 * 1000);
	
	setInterval('pcgObjectControl()', 0.4 * 1000);	
	setInterval('reposMessagebox()', 0.5 * 1000);
	
	
	//Tools bar buttons
	$("#buttonNewObject").click(newPcgObject);
	//$("#buttonDeleteObject").click(deleteObject);
	$("#buttonNewRelation").click(function(){
		$('#dialog').dialog('open');
	});
	$("#buttonDebug").click(function(){
			console.log(object_dump(aOpcgContainer[0]));
			
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
	
	$("#dialogChangeType").dialog({
		bgiframe: true,
		autoOpen: false,
		height: 300,
		modal: true,
		buttons: {
			'Select': function() {				
				var selectedValue = $("#dialogChangeType select").val();
				iPcgId = $("#dialogChangeType #pcgObjectId").val();
				iPropId = $("#dialogChangeType #pcgPropId").val();
				oPcg = getPcgInstance(iPcgId);
				oProp = oPcg.properties[iPropId];
				oProp.changePropType(selectedValue);
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

