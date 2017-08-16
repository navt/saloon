<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style type='text/css'>
		body {
			font-family: Arial;
			font-size: 1.1em;
			background: #fff;
		}
		.wrap a {
		    color: #1E35CD;
		    text-decoration: none;
		    font-size: 100%;
		    padding: .3em .3em .3em .3em;
		}
		.wrap a:hover	{
			text-decoration: none;
			color: #fff;
			background: #002cdb;
		}
		.step {
			height:1em;
		}
	</style>
<?php
foreach($css_files as $file): ?>
	<link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
<?php endforeach; ?>
<?php foreach($js_files as $file): ?>
	<script src="<?php echo $file; ?>"></script>
<?php endforeach; ?>
</head>
<body>
	<div class='wrap'>
		| <a href='<?php echo site_url('steward/orders')?>'>Забронированные столики</a> |
		<a href='<?php echo site_url('steward/managers')?>'>Управляющие</a> |
		<a href='<?php echo site_url('steward/password')?>'>Генератор паролей</a> |
	</div>
	<div class="step"></div>
    <div>
		<?php echo $output; ?>
    </div>

</body>
</html>
