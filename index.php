<?php

/*
 * Draughts - Free Version
 * 
 * Version: 1.0.0
 * 
 * Copyright 2015 Galaa (www.galaa.mn). All Rights Reserved.
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 * 
 */

$position = htmlspecialchars('[{"position":"11","team":"black","crowned":"0"},{"position":"15","team":"white","crowned":"0"},{"position":"16","team":"black","crowned":"0"},{"position":"21","team":"black","crowned":"0"},{"position":"33","team":"black","crowned":"0"},{"position":"36","team":"white","crowned":"1"},{"position":"37","team":"white","crowned":"0"},{"position":"39","team":"black","crowned":"0"},{"position":"50","team":"black","crowned":"1"}]', ENT_QUOTES);

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
	<link rel="stylesheet" href="draughts/css/draugths.min.css">
	<!--[if lt IE 9]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>
<body>
	<div class="container">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<h1 class="text-center">Draugths <small>Project for showing position and replaying games</small></h1>
				<p class="text-center"><span class="glyphicon glyphicon-home" aria-hidden="true"></span> <a href="http://github.com/galaamn/draugths" target="_blank">github.com/galaamn/draugths</a></p>
				<div class="alert alert-info" role="alert">
					<strong>Free version</strong> shows position only!
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="pull-right">
					<div class="draugths" data-pieces="<?php echo $position; ?>"></div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="pull-left">
					<p><strong>Features</strong></p>
					<ul>
						<li>Based on jQuery and CSS</li>
						<li>No image used</li>
						<li>JSON for data transfer</li>
						<li>Multiple-instance</li>
						<li>Clean Code</li>
						<li>Lightweight /4.5kB minified/</li>
						<li>Easy to Use</li>
					</ul>
					<p><strong>Browser Support</strong> <small>Minimum Requirements</small></p>
					<ul>
						<li>IE 9.0</li>
						<li>Chrome 5.0</li>
						<li>Firefox 4.0</li>
						<li>Safari 5.0</li>
						<li>Opera 10.5</li>
					</ul>
					<p><strong>Licence</strong> GPL</p>
				</div>
			</div>
		</div>
		<br>
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<footer class="text-center">&copy; 2015<?php if(($year = date('Y')) !== '2015') : echo '-',$year; endif; ?> <a href="http://galaa.mn" target="_blank">Galaa</a></footer>
			</div>
		</div>
	</div>
	<script src='jquery/jquery.min.js'></script>
	<script src='bootstrap/js/bootstrap.min.js'></script>
	<script src="draughts/js/draugths.jquery.min.js"></script>
</body>
</html>
