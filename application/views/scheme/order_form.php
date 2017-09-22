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
<div class="login_box">
	<div class="sch-mrgn"></div>
	<div class="steps">
		Шаг 3.  Подтвердите заявку на бронирование.
	</div>
	<?php if (isset($_SESSION['err_msg']) && $_SESSION['err_msg'] !=''):?>
		<div class="red">
			<?php echo $_SESSION['err_msg']; deleteMsg(); ?>
		</div>
	<?php endif;?>
	<div class="title">
		<?php echo "Бронирование столика {$_SESSION['table']} на {$_SESSION['datepicker']}."; ?>
	</div>
	<?php
	$fs = [];
	if (isset($_SESSION['form'])) {
		$fs = $_SESSION['form'];
	} else {
		$fs = array(
			"client_name" => '',
			"client_phone" => '',
			"qty_seats" => 0,
			"client_email" => '',
			"client_msg" => '' );
	}
	extract($fs, EXTR_OVERWRITE);
	?>
	<form method="GET" action="<?php echo base_url('scheme/closeOrder');?>">
		<input class="field w-input" type="text" name="client_name"
			value="<?php echo $client_name;?>" placeholder="Ваше имя" required >
		<div class="sch-mrgn"></div>
		<input class="field w-select" type="text" name="client_phone"
			value="<?php echo $client_phone;?>" placeholder="Телефон" required >
		<div class="sch-mrgn"></div>
		<select class="field w-select" name="qty_seats" >
			<option value="-1" selected disabled>Количество мест</option>
			<?php for ($i=1; $i <= $_SESSION['seats'] ; $i++): ?>
				<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
			<?php endfor; ?>
		</select>
		<div class="sch-mrgn"></div>
		<input class="field w-input" type="email" name="client_email"
			value="<?php echo $client_email;?>"  placeholder="E-mail" >
		<div class="sch-mrgn"></div>
		<textarea class="field w-input" type="textarea" name="client_msg"
			placeholder="Пожелания" ><?php echo $client_msg;?></textarea>
		<div class="sch-mrgn"></div>
		<button class="button dtp-button">Отправить</button>
	</form>

</div>

</body>
</html>