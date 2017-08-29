<!DOCTYPE html>
<html>
<head>
	<title>Заказ столика</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link type="text/css" rel="stylesheet"
		href="<?php echo base_url('assets/app-css/style.css'); ?>" />
</head>
<body>
<div class="scheme">
	<?php if (isset($_SESSION['err_msg']) && $_SESSION['err_msg'] !=''):?>
		<div class="red">
			<?php echo $_SESSION['err_msg']; deleteMsg(); ?>
		</div>
	<?php endif;?>
	<div class="title">
		<?php echo "Бронирование столика {$_SESSION['table']} на {$_SESSION['datepicker']}."; ?>
	</div>
	<form method="GET" action="<?php echo base_url('scheme/closeOrder');?>">
		<input class="field w-input" type="text" name="client_name" value="" placeholder="Ваше имя" >
		&nbsp;
		<input class="field w-select" type="text" name="client_phone" value="" placeholder="Телефон" >
		&nbsp;
		<select class="field w-select" name="qty_seats">
			<option value="0" selected disabled>Количество мест</option>
			<?php for ($i=1; $i <= $_SESSION['seats'] ; $i++): ?>
				<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
			<?php endfor; ?>
		</select>
		&nbsp;
		<button class="button dtp-button">Отправить</button>
	</form>

</div>

</body>
</html>