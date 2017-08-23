<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Steward_model extends CI_Model
{
	// SELECT
	public function stewardData($email='')
	{
		// имя таблицы
		$t = $this->config->item('t_prefix').'steward';
		$q = "SELECT `stw_email`,`stw_hash`
			FROM `{$t}`
			WHERE `stw_email` = '{$email}' LIMIT 1";
		$query = $this->db->query($q);
		if ($query->num_rows() > 0){
			$queryRes = array();
			$queryRes = $query->result_array();
		} else $queryRes = false;
		return $queryRes;
	}
}