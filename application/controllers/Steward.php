<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Steward extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->config->load('app');
		$this->load->database();
		$this->load->helper('url');
		$this->load->helper('steward');
		$this->load->library('grocery_CRUD');
	}
	public function orders()
	{
		$this->grocery_crud = new grocery_CRUD();
		$this->grocery_crud->set_subject('&nbsp;&nbsp;&nbsp;Забронированные столики');
		$this->grocery_crud->set_language("russian");

		$this->grocery_crud->display_as('order_date','Дата и время');
		$this->grocery_crud->display_as('num_table','Номер столика');
		$this->grocery_crud->display_as('client_name','Имя клиента');
		$this->grocery_crud->display_as('client_phone','Телефон');
		$this->grocery_crud->display_as('order_note','Примечание');

		$this->grocery_crud->set_table($this->fullTabName('orders'));
		$output = $this->grocery_crud->render();
		$this->load->view('steward/crud_view.php',$output);


	}
	public function managers()
	{
		$this->grocery_crud = new grocery_CRUD();
		$this->grocery_crud->set_subject('&nbsp;&nbsp;&nbsp;Управляющие');
		$this->grocery_crud->set_language("russian");

		$this->grocery_crud->display_as('stw_email','E-mail');
		$this->grocery_crud->display_as('stw_password','Пароль');

		$this->grocery_crud->set_table($this->fullTabName('steward'));
		$output = $this->grocery_crud->render();
		$this->load->view('steward/crud_view.php',$output);
	}
	public function password()
	{
		# code...
	}
	public function fullTabName($name='')
	{
		if ($name != ''){
			return $this->config->item('t_prefix').$name;
		} else return null;
	}
}
