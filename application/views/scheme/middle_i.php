<div class="scheme">
	<div class="sch-mrgn"></div>
	<div class="steps">
		Шаг 1 (из 3-х). Выберите и зафиксируйте дату и время.
	</div>
	<?php if (isset($_SESSION['err_msg']) && $_SESSION['err_msg'] !=''):?>
		<div class="red">
			<?php echo $_SESSION['err_msg']; deleteMsg(); ?>
		</div>
	<?php endif; ?>
	<form method="GET" action="<?php echo base_url('scheme/assayDate');?>">
		<!-- Datepicker -->
		<input type="text" id="datepicker" name="datepicker" value="">
		&nbsp;
		<input type="hidden" name="q" value="time">
		<button class="button dtp-button">Зафиксировать время</button>
	</form>
	<div class="sch-mrgn"></div>
	<object type="image/svg+xml" data="<?php echo base_url('assets/app-images/scheme-init.svg?').time();?>" width="100%" height="100%" ></object>
	<div class="sch-mrgn"></div>
</div>