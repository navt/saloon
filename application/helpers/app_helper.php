<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Немногочисленные хелперы приложения сведены в один файл, чтобы избежать
 * дублирования
 */

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
	//  для работы с БД
    function toDateTime($dateTime)
    {
        $l = mb_strlen($dateTime);
        if ($l==16){

	    	$noError = true;
	    	$d = mb_substr($dateTime, 0, 2);
	    	if (!is_numeric($d)) $noError = false;

	    	$m = mb_substr($dateTime, 3, 2);
	    	if (!is_numeric($m)) $noError = false;

	        $y = mb_substr($dateTime, 6, 4);
	        if (!is_numeric($y)) $noError = false;

	        $H = mb_substr($dateTime, 11, 2);  // часы
	        if (!is_numeric($H)) $noError = false;

	        $i = mb_substr($dateTime, 14, 2);  // минуты
	        if (!is_numeric($i)) $noError = false;

	        if ($noError) {
	        	$when_time = $y . '-' . $m . '-' . $d .' ' . $H . ':' . $i . ':00';
	        } else 	$when_time = date('Y-m-d H:i:s');

	    } else 	$when_time = date('Y-m-d H:i:s');
        return $when_time;
    }
}

if ( ! function_exists('emailValidate')) {
	/**
     * Email validate
     *
     * @category   validate
     * @version    0.1
     * @license    GNU General Public License (GPL), http://www.gnu.org/copyleft/gpl.html
     * @param string $email проверяемый email
     * @param boolean $dns проверять ли DNS записи
     * @return boolean Результат проверки почтового ящика
     * @author Anton Shevchuk
     */
    function emailValidate($email, $dns = true)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            list($user, $domain) = explode("@", $email, 2);
            if (!$dns || ($dns && checkdnsrr($domain, "MX") && checkdnsrr($domain, "A"))) {
                $error = false;
            } else {
                $error = 'dns resource record';
            }
        } else {
            $error = 'format';
        }
        return $error;
    }
}

if ( ! function_exists('genHash')) {
	/**
	 * genHash генерация хеша из строки и соли по алгоритму md5
	 * @param  [string] $userName соль для md5
	 * @param  [string] $passWord строка, для которой нужен хеш
	 * @return [string]           хеш d5
	 */
	function genHash($userName, $passWord)
	{
		// md5
		$hash = crypt($passWord, '$1$'.$userName);
		return $hash;
	}
}

if ( ! function_exists('toAddress'))
{
	/**
	 * штатный Header Redirect, изменённый под мои нужды
	 *
	 * Header redirect in two flavors
	 * For very fine grained control over headers, you could use the Output
	 * Library's set_header() function.
	 *
	 * @param	string	$uri	URL
	 * @param	string	$method	Redirect method
	 *			'auto', 'location' or 'refresh'
	 * @param	int	$code	HTTP Response status code
	 * @return	void
	 */
	function toAddress($uri = '', $method = 'auto', $code = NULL)
	{
		if ( ! preg_match('#^(\w+:)?//#i', $uri)) {
			// site_url заменён на base_url, чтобы убрать из адресной стоки index.php
			$uri = base_url($uri);
		}
		// IIS environment likely? Use 'refresh' for better compatibility
		if ($method === 'auto' && isset($_SERVER['SERVER_SOFTWARE']) && strpos($_SERVER['SERVER_SOFTWARE'], 'Microsoft-IIS') !== FALSE)	{
			$method = 'refresh';
		}
		elseif ($method !== 'refresh' && (empty($code) OR ! is_numeric($code)))	{
			if (isset($_SERVER['SERVER_PROTOCOL'], $_SERVER['REQUEST_METHOD']) && $_SERVER['SERVER_PROTOCOL'] === 'HTTP/1.1') {
				$code = ($_SERVER['REQUEST_METHOD'] !== 'GET')
					? 303	// reference: http://en.wikipedia.org/wiki/Post/Redirect/Get
					: 307;
			}
			else {
				$code = 302;
			}
		}

		switch ($method) {
			case 'refresh':
				header('Refresh:0;url='.$uri);
				break;
			default:
				header('Location: '.$uri, TRUE, $code);
				break;
		}
		exit;
	}
}

if ( ! function_exists('mail_utf8')) {
	function mail_utf8($to, $from, $subject = '(No subject)', $message = '')
	{
	    $subject = "=?UTF-8?B?".base64_encode($subject)."?=";

	    $headers[] = 'MIME-Version: 1.0';
	    $headers[] = 'Content-type: text/html; charset=utf-8';
	    $headers[] = 'X-Mailer: PHP v' . phpversion();
	    $headers[] = "From: {$from}";

	    return mail($to, $subject, $message, implode("\r\n", $headers));
	}
}