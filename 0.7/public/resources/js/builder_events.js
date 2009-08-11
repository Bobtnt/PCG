
$(document).ready(function(){
	//Global var
	iPcgContainerGlobalCounter = 0;
	aOpcgContainer = new Array;
	aModifiedElements = new Array();
	
	iGrapherCounter = 0;
	aGrapherContainer = new Array();
	aGrapherDivContainer = new Array();
	
	
	
	//daemon
//	setInterval('pcgObjectControl()', 0.4 * 1000);	
	setInterval('reposMessagebox()', 0.5 * 1000);
	
	
	//Tools bar buttons
	$("#buttonNewObject").click(newPcgObject);
	//$("#buttonDeleteObject").click(deleteObject);
	$("#buttonNewRelation").click(function(){
		$('#dialog').dialog('open');
	});
	$("#buttonDebug").click(function(){
		pcgObjectControl();
	});
	//Global envents
	//$(".canvas").selectable({ filter: '.pcgObject' });
	$(".messageBox").css('top', window.innerHeight - 30 );
	
	// new relation dialog
	$("#dialog").dialog({
		bgiframe: false,
		autoOpen: false,
		height: 300,
		modal: true,
		buttons: {
			'Select': function() {
				dialogType = $('#dialog #dialogType').val()
				
				if(dialogType == 'relation'){
					var relationType = $("#dialog #relationType").val();
					iPcgSenderId = $("#dialog #pcgSenderId").val();
					iPcgReceiverId = $("#dialog #pcgReceiverId").val();
					iPropId = $("#dialog #propId").val();
					oPcgSender = getPcgInstance(iPcgSenderId);
					oPcgReceiver = getPcgInstance(iPcgReceiverId);
					oProp = oPcgSender.getProp(iPropId);
					newRelation(oPcgSender, oPcgReceiver, oProp, relationType);				
					$(this).dialog('close');
				}
				else if(dialogType == 'changeType'){
					var selectedValue = $("#dialog #propType").val();
					iPcgId = $("#dialog #pcgObjectId").val();
					iPropId = $("#dialog #pcgPropId").val();
					oPcg = getPcgInstance(iPcgId);
					oProp = oPcg.properties[iPropId];
					oProp.changePropType(selectedValue);
					$(this).dialog('close');
				}
			},
			Cancel: function() {
				$(this).dialog('close');
			}
		},
		close: function() {
		}
	});
	

	
});

