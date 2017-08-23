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
                $error = 'dns';
            }
        } else {
            $error = 'format';
        }
        return $error;
    }
}
if ( ! function_exists('genHash')) {
	function genHash($userName, $passWord)
	{
		// md5
		$hash = crypt($passWord, '$1$'.$userName);
		return $hash;
	}
}