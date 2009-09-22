<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>PCG Builder</title>
<script language="javascript" src="/resources/lib/jquery-1.3.2.min.js"></script>
<script language="javascript" src="/resources/lib/jquery-ui-1.7.2.custom.min.js"></script>
<script language="javascript" src="/resources/lib/jquery.ooCookie.js"></script>
<script language="javascript" src="/resources/lib/jquery.json.js"></script>
<script language="javascript" src="/resources/lib/jquery.base64.js"></script>
<script language="javascript" src="/resources/lib/wz_jsgraphics.js"></script>
<script language="javascript" src="/resources/js/builder_events.js"></script>
<script language="javascript" src="/resources/js/builder_object_sp.js"></script>
<script language="javascript" src="/resources/js/builder_object_control.js"></script>
<script language="javascript" src="/resources/js/builder.minimap.js"></script>
<script language="javascript" src="/resources/js/builder.js"></script>
<script language="javascript" src="/resources/js/test.js"></script>
<link rel="stylesheet" href="/resources/css/builder.css" type="text/css" />
<link rel="stylesheet" href="/resources/css/jquery-ui-1.7.2.custom.css" type="text/css" />
</head>
<body>
<div id="myCanvas" class="canvas"></div>

<div id="dialog" title="Select type">
	<input type="hidden" id="dialogType" value="" />
	<input type="hidden" id="pcgObjectId" value="" />
	<input type="hidden" id="pcgPropId" value="" />
	<input type="hidden" id="pcgSenderId" value="" />
	<input type="hidden" id="pcgReceiverId" value="" />
	<input type="hidden" id="propId" value="" />
	<span name="dialogMessage"></span>
	<p>
	<select id="propType">
		<option value="auto">auto</option>
		<option value="varchar">varchar</option>
		<option value="integer">integer</option>
		<option value="date">date</option>
		<option value="time">time</option>
		<option value="datetime">datetime</option>
		<option value="enum">enum</option>
	</select>
	<select id="relationType">
		<option value="1:1">1:1</option>
		<option value="1:n">1:n</option>
		<option value="n:m">n:m</option>
	</select>
	</p>
</div>




<div class="messageBox ui-state-highlight ui-corner-all">
	<span>Messages:</span>
</div>
<div class="svgcontainer" id="svgcontainer">
</div>

<div class="accordion-container">
	<div class="accordion ac-minimap">
	   <h3><a href="#">Minimap</a></h3>
	   <div>
	   </div>
	</div> 
	<div class="accordion ac-tools" style="margin-top: 20px;">
	<h3><a href="#">Tools</a></h3>
	   <div>
	      <p>
	      <a style="display: block" href="javascript:void(0);" id="buttonNewObject">New object</a>
	      </p>
	      <p>
	      <a style="display: block" href="javascript:void(0);" id="buttonSaveWorkbench">Save Workbench</a>
	      <a style="display: block" href="javascript:void(0);" id="buttonLoadWorkbench">Load Workbench</a>
	      <a style="display: block" href="javascript:void(0);" id="buttonGenerate">Generate!</a>
	      </p>
	      <a style="display: block" href="javascript:void(0);" id="buttonDisplayGrid">Display Grid:<span>on</span></a>
	   </div>
	   <h3><a href="#">Objects Tree</a></h3>
	   <div>
	   	  <a style="display: block"href="javascript:void(0);" id="buttonDebug">debug</a>
	      <span style="display: block" id="controlerDebug"></span>
	   </div>
	   <h3><a href="#">Models</a></h3>
	   <div>
	   You can one or more of these models in your workbench
	   <p>
	   	  <a style="display: block"href="javascript:void(0);" id="buttonModel1">Object with category</a>
	   	  <a style="display: block"href="javascript:void(0);" id="buttonModel2">Users and Groups</a>
	   	  <a style="display: block"href="javascript:void(0);" id="buttonModel3">1:n linked objects</a>
	   	  <a style="display: block"href="javascript:void(0);" id="buttonModel4">Multi linked object</a>
	   	</p>
	   </div>
	</div>
</div>
</body>
</html>