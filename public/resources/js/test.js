
$(document).ready(function(){
	
	$('#monsvg').svg();
	
	var svg = $('#monsvg').svg('get'); 
	
	svg.configure({viewBox: '0 0 100 100'}, true);
	svg.title('SVG Demo');
	svg.describe('This demo ...');
	var defs = svg.defs('myDefs');
	svg.symbol(defs, 'mySym', 10, 20, 30, 40);
	svg.marker(defs, 'myMarker', 0, 0, 20, 20);
	
	svg.linearGradient(defs, 'myGrad', 
		    [[0, 'white'], [1, 'red']], 0, 0, 800, 0, 
		    {gradientUnits: 'userSpaceOnUse'});


	
//	var texts = svg.createText();
//	var text = svg.createText();
//	svg.textpath(text, '#MyPath', texts.string('We go '). 
//		    span('up', {dy: -30, fill: 'red'}). 
//		    span(',', {dy: 30}).string(' then we go down, then up again'));
//	texts.path('#MyOtherPath', 'Skipping along');
	
});