<div class="scheme">
	<div class="sch-mrgn"></div>
	<div class="steps">
		Шаг 2 (из 3-х). Выберите "зелёный" столик.
	</div>
	<?php if (isset($_SESSION['err_msg']) && $_SESSION['err_msg'] !=''):?>
		<div class="red">
			<?php echo $_SESSION['err_msg']; deleteMsg(); ?>
		</div>
	<?php endif; ?>
	<form method="GET" action="<?php echo base_url('scheme/assayDate');?>">
		<!-- Datepicker -->
		<?php if (isset($_SESSION['datepicker'])) {
			$dp = $_SESSION['datepicker'];
		} else $dp = '';
		?>
		<input type="text" id="datepicker" name="datepicker" value="<?php echo $dp; ?>" >
		&nbsp;
		<input type="hidden" name="q" value="time">
		<button class="button dtp-button">Зафиксировать время</button>
	</form>
	<div class="sch-mrgn"></div>
	<!--
	<object type="image/svg+xml" data="<?php/*  echo base_url('assets/app-images/scheme-temp.svg?').time();*/ ?>" width="100%" height="100%" ></object>
	-->
	<?php echo $svg; ?>
	<div class="sch-mrgn"></div>

</div>