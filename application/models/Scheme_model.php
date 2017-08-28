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

	public function loadTables()
	{
		// имя таблицы
		$t = $this->config->item('t_prefix').'tables';
		$q = "SELECT `tbl_number`,`tbl_seats` FROM `{$t}` ";
		$flag = true;

		$query = $this->db->query($q);
		if ($query->num_rows() > 0){
			$queryRes = array();
			$queryRes = $query->result_array();
		} else $flag = false;

		if ($queryRes !== false) {
			$tbls = array();
			foreach ($queryRes as $item) {
				$tbls[$item['tbl_number']] = $item['tbl_seats'];
			}
			$this->config->set_item('tables', $tbls);
		}
		return $flag;
	}

	// INSERT
	public function addBooked($phone, $name)
	{
		// имя таблицы
		$t = $this->config->item('t_prefix').'orders';

		$q = "INSERT INTO `{$t}`
	        (order_date,num_table,client_name,client_phone)
	        VALUES ('{$_SESSION['order_date']}','{$_SESSION['table']}','{$name}','{$phone}')";
        $reply = $this->db->query($q);
        return $reply;
	}
}