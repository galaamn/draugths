<?php

/*
 * Draughts
 * 
 * Version: 1.0.0
 * 
 * Copyright 2015 Galaa (www.galaa.mn). All Rights Reserved.
 * 
 * License - http://www.gnu.org/copyleft/gpl.html
 * 
 */

require('draughts/php/draughts.php');
new Draughts;

$draughts_1 = Draughts::draughts('WP:16,27,28,32,33,34,35,37,38,40,45,BP:7,12,14,18,19,21,23,24,25,26,29', '34-30,25-34,37-31,26-37,32-41,21-43,35-30,24-44,33-22,23-32,22-17,12-21,16-29,14-19,41-37,07-12,37-32,12-18,45-40,19-23,40-34');
$draughts_2 = Draughts::draughts('WP:15,37,WK:36,BP:11,21,33,39,BK:50', '21-27,36-06,33-38,06-44,50-46');

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Draugths</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<meta name="description" content="">
	<meta name="author" content="Galaa">
	<link rel="icon" href="draughts/favicon/favicon.ico" type="image/x-icon">
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="draughts/font-icon/css/draughts.css">
	<link rel="stylesheet" href="draughts/css/draugths.css">
	<!--[if lt IE 9]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<h1 class="text-center">Draugths <small>Project for showing position and replaying games</small></h1>
				<p class="text-center"><a href="http://github.com/galaamn/draugths" target="_blank">github.com/galaamn/draugths</a></p>
			</div>
		</div>
		<br>
		<div class="row">
			<div class="col-md-6">
				<div class="center-block">
					<?php echo $draughts_1->board; ?>
				</div>
			</div>
			<div class="col-md-6">
				<div class="center-block">
					<p><strong>Rule or Game Type</strong></p>
					<ul>
						<li>International draughts</li>
					</ul>
					<p><strong>Features</strong></p>
					<ul>
						<li>Built with PHP, jQuery and CSS</li>
						<li>No image used</li>
						<li>JSON for data transfer</li>
						<li>Font Icons</li>
						<li>Multiple-instance</li>
						<li>Clean Code</li>
						<li>Lightweight</li>
						<li>Easy to Use</li>
						<li>Input Validation</li>
						<li>Game Rule Checking: Position &amp; Movement</li>
						<li>Error Handling</li>
						<li>Movement Speed</li>
					</ul>
				</div>
			</div>
		</div>
		<br>
		<div class="row">
			<div class="col-md-6">
				<div class="center-block">
					<?php echo $draughts_2->board; ?>
				</div>
			</div>
			<div class="col-md-6">
				<div class="center-block">
					<p><strong>Notation</strong> <small>Piece Position</small></p>
					<ul>
						<li>Example: WP:15,37,WK:36,BP:11,21,33,39,BK:50</li>
						<li>WP: The following numbers are squares with a white man (piece)</li>
						<li>WK: The following numbers are squares with a white king</li>
						<li>BP: The following numbers are squares with a black man (piece)</li>
						<li>BK: The following numbers are squares with a black king</li>
						<li>Square number range : 1 to 50</li>
					</ul>
					<p><strong>Notation</strong> <small>Movement</small></p>
					<ul>
						<li>Example: 21-27,36-6,33-28,6-44,50-46</li>
						<li>Square number range : 1 to 50</li>
					</ul>
					<p><strong>Browser Support</strong> <small>Minimum Requirements</small></p>
					<ul>
						<li>IE 9.0</li>
						<li>Chrome 5.0</li>
						<li>Firefox 4.0</li>
						<li>Safari 5.0</li>
						<li>Opera 10.5</li>
					</ul>
				</div>
			</div>
		</div>
		<br>
		<div class="row">
			<div class="col-md-12">
				<h2 class="text-center">Installation</h2>
				<p>Include PHP file, create static object. Also include JS &amp; CSS files.</p>
				<pre>&lt;?php

   require('draughts/php/draughts.php');
   new Draughts;

?&gt;</pre>
				<pre>
&lt;link href="draughts/font-icon/css/draughts.css" rel="stylesheet"&gt;
&lt;link href="draughts/css/draugths.css" rel="stylesheet"&gt;</pre>
				<pre>
&lt;script src="jquery/jquery.min.js"&gt;&lt;/script&gt;
&lt;script src="draughts/js/draugths.jquery.js"&gt;&lt;/script&gt;
&lt;script&gt;
   $(document).ready(function(){
      draughts(1000);
   });
&lt;/script&gt;</pre>
				<h2 class="text-center">Usage</h2>
				<p>Position: for example <code>WP:15,37,WK:36,BP:11,21,33,39,BK:50</code>. WP: The following numbers are squares with a white man (piece), WK: The following numbers are squares with a white king, BP: The following numbers are squares with a black man (piece), BK: The following numbers are squares with a black king, Square number range from 1 to 50.</p>
				<p>Movement: for example <code>21-27,36-6,33-28,6-44,50-46</code>.</p>
				<p>Call <code>Draughts::draughts()</code> function then print HTML result.</p>
				<pre>&lt;?php

   $draughts = Draughts::draughts('WP:15,37,WK:36,BP:11,21,33,39,BK:50', '21-27,36-06,33-38,06-44,50-46');
   echo $draughts->board;

?&gt;</pre>
				<p>Animation speed, such as <code>draughts(500);</code>.</p>
				<pre>
&lt;script&gt;
   $(document).ready(function(){
      draughts(500);
   });
&lt;/script&gt;</pre>
				<h2 class="text-center">Error Handling</h2>
				<p>Check <code>error</code> property.</p>
				<pre>&lt;?php

   $draughts = Draughts::draughts('WP:15,37,WK:36,BP:11,21,33,39,BK:50', '21-27,36-06,33-38,06-44,50-46');
   if(!empty($draughts->error))
      echo '&lt;strong&gt;', $draughts->error, '&lt;/strong&gt;';

?&gt;</pre>
			</div>
		</div>
		<br>
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<footer class="text-center">&copy; 2015<?php if(($year = date('Y')) !== '2015') : echo '-',$year; endif; ?> <a href="http://galaa.mn" target="_blank">Galaa</a></footer>
			</div>
		</div>
		<br>
	</div>
	<script src='jquery/jquery.min.js'></script>
	<script src='bootstrap/js/bootstrap.min.js'></script>
	<script src="draughts/js/draugths.jquery.js"></script>
	<script>
		$(document).ready(function(){
			draughts(1000);
		});
	</script>
</body>
</html>
