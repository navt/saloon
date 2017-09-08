<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Scheme_model extends CI_Model
{
	// SELECT
	// Выборка записей из таблицы saloon_orders на заданную дату в интервале времени
	public function bookedOnDate()
	{
		// имя таблицы
		$t = $this->config->item('t_prefix').'orders';
		// учтём продолжительность брони относительно точки заказа
		// начало интервала:
		$date = new DateTime($_SESSION['order_date']);
		$duration = DateInterval::createFromDateString($this->config->item('booking_duration'));
		$date->sub($duration);
		$start = $date->format('Y-m-d H:i:s');
		// окончание интервала:
		$date = new DateTime($_SESSION['order_date']);
		$date->add($duration);
		$last = $date->format('Y-m-d H:i:s');

		$q = "SELECT `num_table`
			FROM `{$t}`
			WHERE `order_date` >= '{$start}' AND `order_date` <= '{$last}'";

		$query = $this->db->query($q);
		if ($query->num_rows() > 0){
			$queryRes = array();
			$queryRes = $query->result_array();
		} else $queryRes = false;
		return $queryRes;
	}

	public function getTable($table='')
	{
		if ($table === '') {
			return false;
		}
		// имя таблицы
		$t = $this->config->item('t_prefix').'tables';
		$q = "SELECT `tbl_number`,`tbl_seats` FROM `{$t}` WHERE `tbl_number` = '{$table}' ";
		$flag = true;

		$query = $this->db->query($q);
		if ($query->num_rows() > 0){
			$queryRes = array();
			$queryRes = $query->result_array();
		} else $flag = false;

		if ($flag) {
			$tbl = array();
			$tbl[0] = $queryRes[0]['tbl_number'];
			$tbl[1] = $queryRes[0]['tbl_seats'];
			return $tbl;
		}
		return $flag;
	}

	// INSERT
	public function addBooked($fields=array())
	{
		// имя таблицы
		$t = $this->config->item('t_prefix').'orders';
		extract($fields, EXTR_OVERWRITE);
		$q = "INSERT INTO `{$t}`
		        (order_date,num_table,qty_seats,
		        client_name,client_phone,client_email,
		        order_note)
	        VALUES (
		        '{$_SESSION['order_date']}','{$_SESSION['table']}','{$qty_seats}',
		        '{$client_name}','{$client_phone}','{$client_email}',
		        '{$client_msg}')";
        $reply = $this->db->query($q);
        return $reply;
	}

	// VALIDATION
	public function validFields($get=array())
	{
		// отсекаем слишком длинные пожелания
		if (mb_strlen($get['client_msg']) > $this->config->item('message_length')) {
			// классифицируем как спам
			show_error("Ваши пожелания содержат более {$this->config->item('message_length')} символов и классифицированы как спам. ".__METHOD__, 422, 'Ошибка со стороны клиента');
		}

		// в поле имя попадут только имена на кириллице, допустим пробел и -
		if ($get['client_name'] === '') {
			$_SESSION['err_msg'] = 'Поле ФИО должно быть заполнено. '.__METHOD__;
			$_SESSION['fail'] = $_SESSION['fail'] + 1;
			toAddress('/scheme/reorder');
		}
		$filter ='~^[а-яА-ЯёЁ\s-]+$~u';
		$flag = filter_var($get['client_name'], FILTER_VALIDATE_REGEXP, ['options'=>['regexp'=>$filter]]);
		if ($flag === false) {
			$_SESSION['err_msg'] = 'Имя должно быть на кириллице. '.__METHOD__;
			$_SESSION['fail'] = $_SESSION['fail'] + 1;
			toAddress('/scheme/reorder');
		}

		// в поле телефон попадут только цифры, - и +
		if ($get['client_phone'] === '') {
			$_SESSION['err_msg'] = 'Поле Телефон должно быть заполнено. '.__METHOD__;
			$_SESSION['fail'] = $_SESSION['fail'] + 1;
			toAddress('/scheme/reorder');
		}
		$phone =filter_var($get['client_phone'], FILTER_SANITIZE_NUMBER_INT);
		if ($phone === false || mb_strlen($phone) < 6 || mb_strlen($phone) > 16) {
			$_SESSION['err_msg'] = 'Что-то не так с номером телефона. '.__METHOD__;
			$_SESSION['fail'] = $_SESSION['fail'] + 1;
			toAddress('/scheme/reorder');
		} else $get['client_phone'] = $phone;

		// в поле Количество мест должна быть целая цифра
		if (isset($get['qty_seats'])) {
			$qty = filter_var($get['qty_seats'], FILTER_SANITIZE_NUMBER_INT);
			if ($qty === false || $qty > $_SESSION['seats']) {
				$_SESSION['err_msg'] = 'Поле Количество мест не прошло валидацию. '.__METHOD__;
				$_SESSION['fail'] = $_SESSION['fail'] + 1;
				toAddress('/scheme/reorder');
			} else $get['qty_seats'] =$qty;
		} else {
			$_SESSION['err_msg'] = 'Поле Количество мест обязательное. '.__METHOD__;
			$_SESSION['fail'] = $_SESSION['fail'] + 1;
			toAddress('/scheme/reorder');
		}


		// поле e-mail необязательное для заполнения
		if ($get['client_email'] !== '') {
			// проверяем e-mail, если что-то не то, пишем пустую стоку
			$error = emailValidate($get['client_email'], true);
			if ($error != false) {
				$get['client_email'] = '';
			}
		}

		// textarea примечание необязательное для заполнения
		if ($get['client_msg'] !== '') {
			$get['client_msg'] = htmlspecialchars($get['client_msg'], ENT_QUOTES);
		}
		return $get;
	}

	// E-MAIL
	public function notice($fields=array())
	{
		extract($fields, EXTR_OVERWRITE);
		$message = "
		<html>
		<head>
			<title>Бронирование столика</title>
		</head>
		<body>
			Номер стола: {$_SESSION['table']}<br>
			Дата / Время: {$_SESSION['order_date']}<br>
			ФИО: {$client_name}<br>
			Кол-во гостей: {$qty_seats}<br>
			Номер телефона: {$client_phone}<br>
			E-mail: {$client_email}<br>
			Пожелания клиента: {$client_msg}<br>
		</body>
		</html>
		";
		$from = $this->config->item('site_email');
		$subject   = 'Бронирование столика '.date('Y-m-d H:i');
		$to = $this->config->item('manager_email');
		return mail_utf8($to, $from, $subject, $message);
	}
}