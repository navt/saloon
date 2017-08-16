<!DOCTYPE html>
<html>
<head>
	<title>Заказ столика</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<?php
foreach($css_files as $file): ?>
	<link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
<?php endforeach; ?>
<?php foreach($js_files as $file): ?>
	<script src="<?php echo $file; ?>"></script>
<?php endforeach; ?>
	<style type="text/css">
		.button {
			display: inline-block;
			vertical-align: baseline;
			outline: none;
			cursor: pointer;
			text-align: center;
			text-decoration: none;
			font: 16px/100% Arial, Helvetica, sans-serif;
			margin: .1em;
			padding: .35em .5em .4em .5em;
			border-radius: .3em;
		}
		.green {
			color: #e8f0de;
			background: #64991e;
		}
		.msg {
			color: #faddde;
			background: #ea0000;
			font-size: 1.1em;
			padding: 1em .5em 1em 1.5em;
			margin-bottom: 1em;
		}
		.dtp-button {
			color: #fff;
			background: #7d7070;
			font-size: 1.3em;
		}
		.scheme {
			width: 100%;
			max-width: 996px;
			margin: 0 auto; /* центрируем основной контейнер */
		}
		img {
			width: 100%; /* ширина картинки */
			height: auto; /* высота картинки */
		}
		#datepicker {
			padding: .35em .5em .4em .5em;
			margin: 0 0 .8em;
			border: solid 1px #dadada;
			border-radius: .3em;
			width: 8em;
			font-size: 1.3em;
		}
	</style>

</head>
<body>