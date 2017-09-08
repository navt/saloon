<!DOCTYPE html>
<html>
<head>
	<title>Генерация хеша пароля</title>
	<meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link type="text/css" rel="stylesheet"
		href="<?php echo base_url('assets/app-css/style.css'); ?>" />
</head>
<body>
	<div class='wrap'>
	<div class="sch-mrgn"></div>
	<div class="func letter">
		| <a href='<?php echo site_url('steward/orders')?>'>Забронированные столики</a> |
		<a href='<?php echo site_url('steward/tables')?>'>Столики</a> |
		<a href='<?php echo site_url('steward/managers')?>'>Управляющие</a> |
		<a href='<?php echo site_url('steward/generate')?>'>Генератор паролей</a> |
		<a href='<?php echo site_url('steward/deleteAuth')?>'>Выход</a> |
	</div>
	<div class="step"></div>
	<?php
	if (isset($_SESSION['userName']) && isset($_SESSION['passWord'])) {
	 	$userName = $_SESSION['userName'];
	 	$passWord = $_SESSION['passWord'];
	} else {
		$userName = '';
		$passWord = '';
	}
	?>
    <div>
		<form method="GET" action="<?php echo base_url('steward/generate');?>">
		<input class="be-field" type="text" name="userName"  value="<?php echo $userName; ?>"  placeholder="E-mail пользователя" >
		<br>
		<input class="be-field" type="password" name="passWord"  value="<?php echo $passWord; ?>" placeholder="Желаемый пароль" >
		<br>
		<button class="button be-button" name="button" value="Генерировать">Генерировать</button>
	</form>

    </div>
    <div class="step"></div>
    <?php if (isset($_SESSION['err_msg']) && $_SESSION['err_msg'] !=''):?>
		<div class="red">
			<?php echo $_SESSION['err_msg']; deleteMsg(); ?>
		</div>
	<?php endif;?>
	<?php if (isset($_SESSION['hash']) && $_SESSION['hash'] !=''):?>
		<div class="title">
			<?php echo $_SESSION['hash']; $_SESSION['hash'] = null; ?>
		</div>
	<?php endif;?>
	</div>
</body>
</html>