<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Scheme extends CI_Controller {

	public $css_files = [
		'assets/app-css/jquery-ui.min.css',
		'assets/app-css/jquery-ui-timepicker-addon.css' ];
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
		$this->load->helper('scheme');
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
			redirect('/scheme/display/');
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
		$xml = simplexml_load_file($baseDir.'assets'.D_S.'app-images'.D_S.'scheme-base.svg');

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
		// если столика нет в списке - досвидос
		if (!in_array($table, $this->config->item('tables'))) {
			$_SESSION['err_msg'] = "Нет столика/объекта {$table}.";
			redirect('/scheme/display/');
		}

		$queryRes = $this->scheme_model->bookedOnDate();
		if ($queryRes === false) {       // если нет занятых столиков
			// показываем форму заказа
		} else {                         // если какие-то столики заняты
			// провееряем есть ли столик среди заказанных, есть - досвидос,
			// нет - показываем форму заказа
		}
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
				redirect('/scheme/display/');
			} else {
				$_SESSION['err_msg'] = 'Заказ столиков предусмотрен с '.
				$this->config->item('start_time').' до '.$this->config->item('finish_time').
				'. Пожалуйста, пересмотрите время Вашего визита.';
				redirect('/scheme/');
			}
		} else {
			$_SESSION['err_msg'] = 'Пожалуйста, выберите время Вашего визита.';
			redirect('/scheme/');
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
}