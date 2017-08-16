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
		.red {
			color: #faddde;
			background: #d81b21;
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
	<object type="image/svg+xml" data="<?php echo base_url('assets/app-images/scheme-base.svg?').time();?>" width="100%" height="10%" ></object>

</div>

<script>
    $.datepicker.regional['ru'] = {
	        closeText: 'Ввод',
	        prevText: '<Пред',
	        nextText: 'След>',
	        currentText: 'Сейчас',
	        monthNames: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь',
	            'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'
	        ],
	        monthNamesShort: ['Янв', 'Фев', 'Мар', 'Апр', 'Май', 'Июн',
	            'Июл', 'Авг', 'Сен', 'Окт', 'Ноя', 'Дек'
	        ],
	        dayNames: ['воскресенье', 'понедельник', 'вторник', 'среда', 'четверг', 'пятница', 'суббота'],
	        dayNamesShort: ['вск', 'пнд', 'втр', 'срд', 'чтв', 'птн', 'сбт'],
	        dayNamesMin: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
	        weekHeader: 'Не',
	        dateFormat: 'dd:mm:yy',
	        firstDay: 1,
	        isRTL: false,
	        showMonthAfterYear: false,
	        yearSuffix: ''
	    };
    $.datepicker.setDefaults($.datepicker.regional['ru']);
    $.timepicker.regional['ru'] = {
        timeOnlyTitle: 'Выберите время',
        timeText: 'Время',
        hourText: 'Часы',
        minuteText: 'Мин.',
        secondText: 'Секунды',
        millisecText: 'Миллисекунды',
        timezoneText: 'Часовой пояс',
        currentText: 'Сейчас',
        closeText: 'Закрыть',
        timeFormat: 'HH:mm',
        amNames: ['AM', 'A'],
        pmNames: ['PM', 'P'],
        isRTL: false
    };
    $.timepicker.setDefaults($.timepicker.regional['ru']);

	$( "#datepicker" ).datetimepicker({
		controlType: 'slider'
	});
</script>
</body>
</html>