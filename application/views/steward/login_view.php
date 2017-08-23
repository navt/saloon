<!DOCTYPE html>
<html>
<head>
	<title>Авторизация для персонала</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link type="text/css" rel="stylesheet"
		href="<?php echo base_url('assets/app-css/style.css'); ?>" />
</head>
<body>
<div class="login_box">
	<div class="down"></div>
	<?php if (isset($_SESSION['err_msg']) && $_SESSION['err_msg'] !=''):?>
		<div class="red">
			<?php echo $_SESSION['err_msg']; deleteMsg(); ?>
		</div>
	<?php endif;?>
	<?php
	if (isset($_SESSION['userName']) && isset($_SESSION['passWord'])) {
	 	$userName = $_SESSION['userName'];
	 	$passWord = $_SESSION['passWord'];
	} else {
		$userName = '';
		$passWord = '';
	}
	?>
	<form method="GET" action="<?php echo base_url('steward/index');?>">
		<label for="username">Пользователь</label><br>
		<input class="field" type="text" name="userName"  value="<?php echo $userName; ?>"  placeholder="Ваш e-mail" >
		<br>
		<label for="password">Пароль</label><br>
		<input class="field" type="password" name="passWord"  value="<?php echo $passWord; ?>" placeholder="Пароль" >
		<br>
		<button class="button dtp-button" name="button" value="Войти">Войти</button>
	</form>

</div>

</body>
</html>