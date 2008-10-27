<?php

// $Id$

header('Content-Type: text/html; charset=utf-8');
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta name="MSSmartTagsPreventParsing" content="true" />
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>dashed and dotted lines</title>
	<style type="text/css"><!--
#start_stop_btn {
	width: 8em;
}
--></style>
	<!--[if IE]><script type="text/javascript" src="excanvas/excanvas.js"></script><![endif]-->
	<script type="text/javascript" src="prototype/prototype.js"></script>
	<script type="text/javascript" src="../libs/path.js"></script>
	<script type="text/javascript"><!--
function newTestPaths() {
	return [
		new Path([
			new Bezier([
				new Point(150, 80),
				new Point(80, 120),
				new Point(10, 80)
			]),
			new Bezier([
				new Point(10, 80),
				new Point(10, 10)
			]),
			new Bezier([
				new Point(10, 10),
				new Point(340, 10),
				new Point(60, 200),
				new Point(390, 200)
			]),
			new Bezier([
				new Point(390, 200),
				new Point(390, 140),
				new Point(330, 140),
				new Point(300, 160)
			])
		]),
		new Ellipse(300, 80, 90, 40)
	];
}
var testPaths = [], spacing = 30, firstDistance = spacing, drawFirst = true, ctx, animating = false;
function startStop() {
	if (animating) {
		$('start_stop_btn').value = 'Animate';
		animating = false;
	} else {
		$('start_stop_btn').value = 'Stop';
		animating = true;
		animateFrame();
	}
}
function animateFrame() {
	if (!animating) return;
	drawFrame();
	if (animating) setTimeout('animateFrame()', 50);
}
function drawFrame() {
	ctx.clearRect(0, 0, 400, 400);
	
	ctx.strokeStyle = 'rgb(233,233,233)';
	
	ctx.beginPath();
	testPaths.each(function(paths) {
		paths.each(function(path) {
			path.draw(ctx);
		});
	});
	ctx.stroke();
	
	ctx.strokeStyle = 'rgb(100,100,100)';
	
	ctx.beginPath();
	var before = new Date();
	testPaths[0].each(function(path) {
		path.drawDotted(ctx, spacing, firstDistance);
	});
	var after = new Date();
	ctx.stroke();
	if (!animating) $('output').innerHTML += 'dotted took ' + (after.getTime() - before.getTime())/1000 + ' seconds<br/>';
	
	ctx.beginPath();
	before = new Date();
	testPaths[1].each(function(path) {
		path.drawDashed(ctx, spacing, firstDistance, drawFirst);
	});
	after = new Date();
	ctx.stroke();
	if (!animating) $('output').innerHTML += 'dashed took ' + (after.getTime() - before.getTime())/1000 + ' seconds<br/>';
	
	++firstDistance;
	if (firstDistance > spacing) {
		firstDistance -= spacing;
		drawFirst = !drawFirst;
	}
}
function init() {
	var canvas = $('canvas');
	if (canvas.getContext) {
		ctx = canvas.getContext('2d');
		
		ctx.lineWidth = 4;
		ctx.lineCap = 'round';
		
		testPaths[0] = newTestPaths();
		testPaths[1] = newTestPaths();
		testPaths[1].each(function(path) {
			path.offset(0, 190);
		});
		
		$('start_stop_btn').value = animating ? 'Stop' : 'Animate';
		drawFrame();
	}
}
function dump(obj) {
	var output = '';
	output += '<fieldset><legend>' + typeof(obj) + '<\/legend>';
	for (var elem in obj) {
		output += '<b>' + elem + '<\/b>: ' + ('function' == typeof(obj[elem]) ? '' : obj[elem]) + ('object' == typeof(obj[elem]) ? '' : ' [' + typeof(obj[elem]) + ']') + '<br/>';
	}
	output += '<\/fieldset>';
	$('output').innerHTML += output;
}
// --></script>
</head>
<body onload="init()">

<canvas id="canvas" width="400" height="400"></canvas>

<form>
<input id="start_stop_btn" type="button" value="" onclick="startStop()" />
<input id="start_stop_btn" type="button" value="Step" onclick="drawFrame()" />
</form>

<div id="output"></div>

</body>
</html>
