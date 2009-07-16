<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>PCG Builder</title>
<script language="javascript" src="/resources/lib/jquery-1.3.2.min.js"></script>
<script language="javascript" src="/resources/lib/jquery-ui-1.7.2.custom.min.js"></script>
<!-- <script language="javascript" src="/resources/lib/jquery.svg.js"></script>
<script language="javascript" src="/resources/lib/jquery.drawinglibrary.js"></script> -->
<script language="javascript" src="/resources/js/builder.js"></script>
<script language="javascript" src="/resources/js/builder_events.js"></script>
<link rel="stylesheet" href="/resources/css/builder.css" type="text/css" />
<link rel="stylesheet" href="/resources/css/jquery-ui-1.7.2.custom.css" type="text/css" />
</head>
<body>
<div class="toolsBar">
	<div class="toolsButton">
		<a href="javascript:void(0);" id="buttonNewObject">New object</a>
	</div>
	<div class="toolsButton">
		<a href="javascript:void(0);" id="buttonNewObject">Modify Object</a>
	</div>
	<div class="toolsButton">
		<a href="javascript:void(0);" id="buttonDeleteObject">Delete object</a>
	</div>
	<div class="toolsButton">
		<a href="javascript:void(0);" id="buttonNewRelation">New Relation</a>
	</div>
</div>
<div class="canvas"></div>


<div id="dialog" title="Select Relation type">
	<select id="relationType">
		<option value="1:1">1:1</option>
		<option value="1:1">1:n</option>
		<option value="1:1">n:m</option>
	</select>
</div>
<div class="svgcontainer">
</div>

</body>
</html>