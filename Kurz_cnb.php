<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
* Třída pro zjištění aktuálního kurzu měn ČNB v PHP frameworku CodeIgniter
*
* @author Lukenzi <lukenzi@gmail.com>
* @package Codeigniter
* @subpackage Kurz_cnb
*
*/



class Kurz_cnb{



	// URL adresa ČNB
	private $cnb_url  = 'http://www.cnb.cz/cs/financni_trhy/devizovy_trh/kurzy_devizoveho_trhu/denni_kurz.txt?date=';

	// Kompletní URL s aktuálními daty
	private $data_url = '';

	// stažená data
	private $data     = '';



	// -----------------------------------------------------------------


	/** Inicializace třídy - nastavení URL, získání dat
	 *
	 * @return void
	 */
	function __construct(){
		// URL pro data dnešního dne
		$this->data_url = $this->cnb_url.date('d.m.Y');
		// naplníme data
		$this->data = $this->_GetData();
	}



	/** Zjištění kurzu
	 *
	 * @param string Kod kurzu
	 * @return string Kurz
	 */
	function Get($code){
		if(empty($this->data)){
			return '???';
		}else{
			$result = '???';
			$row = explode("\n", $this->data);
			unset($row[0]);
			unset($row[1]);
			foreach($row as $val){
				if(!empty($val)){
					$kurz = explode('|', $val);
					if($kurz[3] == $code){
						$result = $kurz[4];
					}
				}
			}
			return trim($result);
		}
	}



	/** Vrátí všechna stáhnutá data z ČNB
	 *
	 * @return string Všechna stažená data
	 */
	public function GetAllData(){
		if(empty($this->data)){
			return false;
		}else{
			return $this->data;
		}
	}



	/** Načtení souboru s daty
	 *
	 * @return string|false Data ČNB
	 */
	private function _GetData(){
		if(function_exists('curl_init')) {
			$ch = curl_init($this->data_url);
			@curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			@curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			@curl_setopt($ch, CURLOPT_AUTOREFERER, true);
			@curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			@curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:27.0) Gecko/20100101 Firefox/27.0');
			@curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			$data = @curl_exec($ch);
			curl_close ($ch);
			if($data === false){
				$data = @file_get_contents($this->data_url);
			}
			return $data;
		}else{
			return @file_get_contents($this->data_url);
		}
	}



}
/* End of file Kurz_cnb.php */
/* Location: ./application/libraries/Kurz_cnb.php */
