<!DOCTYPE html>
<html>
<head>
	<title>Раздел для менеджеров</title>
	<meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link type="text/css" rel="stylesheet"
		href="<?php echo base_url('assets/app-css/style.css'); ?>" />
<?php
foreach($css_files as $file): ?>
	<link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
<?php endforeach; ?>
<?php foreach($js_files as $file): ?>
	<script src="<?php echo $file; ?>"></script>
<?php endforeach; ?>
</head>
<body>
	<div class="wrap">
		<div class="func letter">
			<div class="sch-mrgn"></div>
			| <a href='<?php echo site_url('steward/orders')?>'>Забронированные столики</a> |
			<a href='<?php echo site_url('steward/tables')?>'>Столики</a> |
			<a href='<?php echo site_url('steward/managers')?>'>Управляющие</a> |
			<a href='<?php echo site_url('steward/generate')?>'>Генератор паролей</a> |
			<a href='<?php echo site_url('steward/deleteAuth')?>'>Выход</a> |
		</div>
		<div class="sch-mrgn"></div>
	    <div>
			<?php echo $output; ?>
	    </div>
	</div>
</body>
</html>
