<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Scheme extends CI_Controller {

	public $css_files = [
		'assets/app-css/jquery-ui.min.css',
		'assets/app-css/jquery-ui-timepicker-addon.css',
		'assets/app-css/style.css' ];
	public $js_files = [
		'assets/app-js/jquery-1.12.4.min.js',
		'assets/app-js/jquery-ui.min.js',
		'assets/app-js/jquery-ui-timepicker-addon.js' ];

	public function __construct()
	{
		parent::__construct();
		$this->config->load('app');
		$this->load->library('session');
		$this->load->database();
		$this->load->model('scheme_model');
		$this->load->helper('url');
		$this->load->helper('app');
	}
	public function index()
	{
		$this->addBaseUrl();
		$data['css_files'] = $this->css_files;
		$data['js_files'] = $this->js_files;

		if (!isset($_SESSION['order_date'])) {
			// и клиент видит схему с чёрными столами и должен ввести время
			$this->load->view('scheme/upper', $data);
			$this->load->view('scheme/middle_i');
			$this->load->view('scheme/ground');
		}
		if (isset($_SESSION['order_date'])) {
			toAddress('/scheme/display/');
		}
	}

	public function display()
	{
		// Выборка из БД записей на указанную дату. Если записей нет, то
		// все столы - свободны. Если есть, помечаем забронированные.
		define ('D_S', DIRECTORY_SEPARATOR);
		$this->addBaseUrl();
		$data['css_files'] = $this->css_files;
		$data['js_files'] = $this->js_files;

		$baseDir = dirname(dirname(dirname(__FILE__))).D_S;
		$xml = simplexml_load_file($baseDir.'assets'.D_S.'app-images'.D_S.$this->config->item('scheme'));

		$queryRes = $this->scheme_model->bookedOnDate();

		if ($queryRes === false) {
			foreach ($xml->a as $link) {
				$link->rect['class'] = 'green';
			}
		} else {
			$work = array();
			foreach ($queryRes as $item) {
				$work[] = $item['num_table'];
			}
			foreach ($xml->a as $link) {
				if (in_array($link->rect['id'], $work)) {
					$link->rect['class'] = 'ruddy';
				} else $link->rect['class'] = 'green';
			}

		}

		$xml->asXML($baseDir.'assets'.D_S.'app-images'.D_S.'scheme-temp.svg');

		$this->load->view('scheme/upper', $data);
		$this->load->view('scheme/middle_d');
		$this->load->view('scheme/ground');
	}

	public function order()
	{
		$table = $this->input->get('q');
		// таблицу со столиками загружаем в config в виде массива
		$this->scheme_model->loadTables();
		// если столика нет в списке - досвидос
		$work = array();
		foreach ($this->config->item('tables') as $key => $value) {
			$work[] = $key;
		}
		if (!in_array($table, $work)) {
			$_SESSION['err_msg'] = "Нет столика/объекта {$table}. ".__METHOD__;
			toAddress('/scheme/display/');
		}

		$queryRes = $this->scheme_model->bookedOnDate();
		// если какие-то столики заняты
		if ($queryRes != false)  {
			// провееряем есть ли столик среди заказанных, есть - досвидос
			$work = array();
			foreach ($queryRes as $item) {
				$work[] = $item['num_table'];
			}
			if (in_array($table, $work)) {
				$_SESSION['err_msg'] = "Cтолик/объект {$table} уже занят. ".__METHOD__;
				toAddress('/scheme/display/');
			}
		}
		// устанавливаем #столика и количество обращений к заключительной форме ввода
		$_SESSION['table'] = $table;
		$_SESSION['fail'] = 0;
		// показываем форму заказа
		$this->load->view('scheme/order_form');
	}

	public function reorder()
	{
		$qErrors = 3;               // Максимально возможное количество неправильно
		                            // заполненых форм
		if ($_SESSION['fail'] > $qErrors) {
			$_SESSION = null;
			exit("Вы ошиблись в заполнении формы {$qErrors} раза. Если вы не робот, начните заново.");
		}
		$this->load->view('scheme/order_form');
	}

	// проверка попадает ли время в разрешённый для заказа диапазон
	public function assayDate()
	{
		$get =[];
		$get = $this->input->get(null, true);

		// если попадаем сюда из метода display()
		if (isset($_SESSION['order_date']) && isset($_SESSION['datepicker'])) {
			$_SESSION['order_date'] = null;
			$_SESSION['datepicker'] = null;
		}

		if ($get['q'] == 'time' && mb_strlen($get['datepicker']) == 16) {
			$h = mb_substr($get['datepicker'], 11, 5);
			if ($h >= $this->config->item('start_time') && $h <= $this->config->item('finish_time')) {
				$_SESSION['order_date'] = toDateTime($get['datepicker']);
				$_SESSION['datepicker'] = $get['datepicker'];
				toAddress('/scheme/display/');
			} else {
				$_SESSION['err_msg'] = 'Заказ столиков предусмотрен с '.
				$this->config->item('start_time').' до '.$this->config->item('finish_time').
				'. Пожалуйста, пересмотрите время Вашего визита. '.__METHOD__;
				toAddress('/scheme/');
			}
		} else {
			$_SESSION['err_msg'] = 'Пожалуйста, выберите время Вашего визита. '.__METHOD__;
			toAddress('/scheme/');
		}

	}

	public function closeOrder()
	{
		$get =[];
		$get = $this->input->get(null, true);
		// в поле телефон попадут только цифры, - и +
		$phone =filter_var($get['client_phone'], FILTER_SANITIZE_NUMBER_INT);
		if ($phone === false || mb_strlen($phone) < 6 || mb_strlen($phone) > 16) {
			$_SESSION['err_msg'] = 'Что-то не так с номером телефона. '.__METHOD__;
			$_SESSION['fail'] = $_SESSION['fail'] + 1;
			toAddress('/scheme/reorder');
		}
		// в поле имя попадут только имена на кириллице, допустим пробел и -
		$filter ='~^[а-яА-ЯёЁ\s-]+$~u';
		$flag = filter_var($get['client_name'], FILTER_VALIDATE_REGEXP, ['options'=>['regexp'=>$filter]]);
		if ($flag === false) {
			$_SESSION['err_msg'] = 'Имя должно быть на кириллице. '.__METHOD__;
			$_SESSION['fail'] = $_SESSION['fail'] + 1;
			toAddress('/scheme/reorder');
		} else $name = $get['client_name'];

		$replay = $this->scheme_model->addBooked($phone, $name);
		if ($replay) {
			toAddress('/scheme/display/');
		}
	}
	public function addBaseUrl()
	{
		// css и js для view
		for ($i=0; $i < count($this->css_files); $i++) {
			$this->css_files[$i] = base_url($this->css_files[$i]);
		}
		for ($i=0; $i < count($this->js_files); $i++) {
			$this->js_files[$i] = base_url($this->js_files[$i]);
		}
	}
	public function ex()
	{
		$this->scheme_model->loadTables();
		$tbls = array();
		$tbls = $this->config->item('tables');
		var_dump($tbls);
	}
}