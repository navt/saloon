<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('deleteMsg')) {

	function deleteMsg()
	{
		if (isset($_SESSION['err_msg']) && $_SESSION['err_msg'] !='') {
			$_SESSION['err_msg'] = null;
		}
	}

}
if ( ! function_exists('toDateTime')) {
	//  Функция преобразует время из "русского"" формата в формат DATETIME
    function toDateTime($dateTime)
    {
        $l = mb_strlen($dateTime);
        if ($l==16){
	    	$d = mb_substr($dateTime, 0, 2);
	    	$m = mb_substr($dateTime, 3, 2);
	        $y = mb_substr($dateTime, 6, 4);
	        $H = mb_substr($dateTime, 11, 2);  // часы
	        $i = mb_substr($dateTime, 14, 2);  // минуты
	        $when_time = $y . '-' . $m . '-' . $d .' ' . $H . ':' . $i . ':00';
	    } else 	$when_time = date('Y-m-d H:i:s');
        return $when_time;
    }
}
