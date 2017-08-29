<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Steward extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->config->load('app');
		$this->load->database();
		$this->load->library('grocery_CRUD');
		$this->load->library('session');
		$this->load->model('steward_model');
		$this->load->helper('url');
		$this->load->helper('app');
	}

    public function index()
	{

		$get =[];
		$get = $this->input->get(null, true);
		if ($get == array()) {
			// форма авторизации
			$this->viewForm();
		} elseif ($get['button'] == 'Войти') {
			$_SESSION['err_msg'] = '';
			$userName = trim($get['userName']);
			$passWord = trim($get['passWord']);
			$_SESSION['userName'] = $userName;
			$_SESSION['passWord'] = $passWord;

			$error = emailValidate($userName, true);
			if ($error != false) {
				$_SESSION['err_msg'] = "E-mail не прошёл проверки - {$error} ".__METHOD__;

				redirect('/steward/viewForm/');
			}
			$filter ='~^[a-zA-Z0-9_-]+$~u';
			$flag = filter_var($passWord, FILTER_VALIDATE_REGEXP, ['options'=>['regexp'=>$filter]]);
			if ($flag === false) {
				$_SESSION['err_msg'] = 'Пароль должен состоять из латинских букв, цифр, - и _ . '.__METHOD__;

				redirect('/steward/viewForm/');
			}
			// если всё прошло, то запрос к БД
			$queryRes = $this->steward_model->stewardData($userName);
			//echo $queryRes[0]['stw_password'];
			if ($queryRes === false) {
				$_SESSION['err_msg'] = "Не нашлось пары {$userName} / {$passWord} в БД. ".__METHOD__;
				redirect('/steward/viewForm/');
			} else {
				$hash = genHash($userName, $passWord);
				if ($hash === $queryRes[0]['stw_hash']) {
					// логиним этого пользователя
					$_SESSION['auth'] = array('is_login' => true,
						'user_agent' => $this->input->server('HTTP_USER_AGENT'));

					$_SESSION['userName'] = null;
					$_SESSION['passWord'] = null;
					redirect('/steward/orders/');

				} else {
					$_SESSION['err_msg'] = "Не нашлось такой пары {$userName} / {$passWord} в БД. ".__METHOD__;
					redirect('/steward/viewForm/');
				}
			}
		}
    }
    public function viewForm()
    {
    	$this->load->view('steward/login_view');
    }
    private function checkAuth()
    {
		if (isset($_SESSION['auth'])){
	    	if (is_array($_SESSION['auth'])) {
	    		$item = array();
	    		$item = $_SESSION['auth'];
	    		if ($item['is_login'] === true && $item['user_agent'] == $this->input->server('HTTP_USER_AGENT')) {
	    			$noError = true;
	    		} else $noError = false;
	    	} else $noError = false;
	    } else $noError = false;
    	if (!$noError) show_error("Сейчас доступ к этому адресу запрещён. Авторизируйтесь.");
    }

	public function orders()
	{
		$this->checkAuth();
		$this->grocery_crud = new grocery_CRUD();
		$this->grocery_crud->set_subject('&nbsp;&nbsp;&nbsp;Забронированные столики');
		$this->grocery_crud->set_language("russian");

		$this->grocery_crud->display_as('order_date','Дата и время');
		$this->grocery_crud->display_as('num_table','Номер столика');
		$this->grocery_crud->display_as('qty_seats','Мест в заказе');
		$this->grocery_crud->display_as('client_name','Имя клиента');
		$this->grocery_crud->display_as('client_phone','Телефон');
		$this->grocery_crud->display_as('order_note','Примечание');

		$this->grocery_crud->set_table($this->fullTabName('orders'));
		$output = $this->grocery_crud->render();
		$this->load->view('steward/crud_view.php',$output);


	}
	public function managers()
	{
		$this->checkAuth();
		$this->grocery_crud = new grocery_CRUD();
		$this->grocery_crud->set_subject('&nbsp;&nbsp;&nbsp;Управляющие');
		$this->grocery_crud->set_language("russian");

		$this->grocery_crud->display_as('stw_email','E-mail');
		$this->grocery_crud->display_as('stw_hash','Хеш пароля');

		$this->grocery_crud->set_table($this->fullTabName('steward'));
		$output = $this->grocery_crud->render();
		$this->load->view('steward/crud_view.php',$output);
	}
	public function tables()
	{
		$this->checkAuth();
		$this->grocery_crud = new grocery_CRUD();
		$this->grocery_crud->set_subject('&nbsp;&nbsp;&nbsp;Столики/объекты');
		$this->grocery_crud->set_language("russian");

		$this->grocery_crud->display_as('tbl_number','Номер столика');
		$this->grocery_crud->display_as('tbl_seats','Количество мест');

		$this->grocery_crud->set_table($this->fullTabName('tables'));
		$output = $this->grocery_crud->render();
		$this->load->view('steward/crud_view.php',$output);
	}

	public function fullTabName($name='')
	{
		if ($name != ''){
			return $this->config->item('t_prefix').$name;
		} else return null;
	}

	public function generate()
	{
		$get =[];
		$get = $this->input->get(null, true);
		if ($get == array()) {
			// форма запроса хеша
			$this->viewGenForm();
		} elseif ($get['button'] == 'Генерировать') {
			$_SESSION['err_msg'] = '';
			$userName = trim($get['userName']);
			$passWord = trim($get['passWord']);
			$_SESSION['userName'] = $userName;
			$_SESSION['passWord'] = $passWord;
			$error = emailValidate($userName, true);
			if ($error != false) {
				$_SESSION['err_msg'] = "E-mail не прошёл проверки - {$error} ".__METHOD__;
				redirect('/steward/viewGenForm/');
			}
			$qty = mb_strlen($passWord);
			$confQty = 8;
			if ($qty < $confQty) {
				$_SESSION['err_msg'] = "Длина пароля должна быть {$confQty} или более символов ".__METHOD__;
				redirect('/steward/viewGenForm/');
			}
			$filter ='~^[a-zA-Z0-9_-]+$~u';
			$flag = filter_var($passWord, FILTER_VALIDATE_REGEXP, ['options'=>['regexp'=>$filter]]);
			if ($flag === false) {
				$_SESSION['err_msg'] = 'Пароль должен состоять из латинских букв, цифр, - и _ . '.__METHOD__;
				redirect('/steward/viewGenForm/');
			}
			$_SESSION['hash'] = genHash($userName, $passWord);
			redirect('/steward/viewGenForm/');
		}
	}
	public function viewGenForm()
	{
		$this->load->view('steward/hash_view');
	}
	public function deleteAuth()
	{
		$_SESSION['auth']     = null;
		$_SESSION['userName'] = null;
		$_SESSION['passWord'] = null;
		redirect('/steward/index/');
	}
}