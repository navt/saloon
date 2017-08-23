<div class="scheme">
	<?php if (isset($_SESSION['err_msg']) && $_SESSION['err_msg'] !=''):?>
		<div class="red">
			<?php echo $_SESSION['err_msg']; deleteMsg(); ?>
		</div>
	<?php endif; ?>
	<form method="GET" action="<?php echo base_url('scheme/assayDate');?>">
		<!-- Datepicker -->
		<input type="text" id="datepicker" name="datepicker" value="">
		<input type="hidden" name="q" value="time">
		<button class="button dtp-button">Зафиксировать время</button>
	</form>
	<object type="image/svg+xml" data="<?php echo base_url('assets/app-images/scheme-init.svg?').time();?>" width="100%" height="10%" ></object>

</div>