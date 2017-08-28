<?php
// префикс таблиц в БД
$config['t_prefix'] = 'saloon_';

// время, с которого можно делать заказ
$config['start_time'] = '11:30';
// время, с которого нельзя делать заказ
$config['finish_time'] = '21:00';
// продолжительность брони
$config['booking_duration'] = '2 hours';
// список заказываемых столиков
/*
$config['tables'] = array(
	'101', '102', '103', '104', '105', '106', '107',
	'201', '202', '203', '204', '205', '206', '207','208', '209', '210', '211',
	'301', '302', '303', '304', '305', '306',
	'401', '402', '403', '404', '405', '406', '407','408', '409',
	'500'
	);
*/
// исходный файл схемы
$config['scheme'] = 'scheme-base.svg';