CodeIgniter-Kurzy-ČNB
=====================

Zjištění aktuálních kurzů měn z České Národní Banky v PHP frameworku CodeIgniter.

Požadavky
---------

* PHP 5.3.0 nebo novější
* Framevork CodeIgniter

Použití
-------

Třídu je třeba před použitím v controlleru prvně načíst:

```php
$this->load->library('kurz_cnb');
```

poté můžete zjistit libovolný kurz pouze zadáním kódu měny:

```php
// zjištění kurzu Dolaru v Korunách
$this->kurz_cnb->Get('USD');

// zjištění kurzu Eura v Korunách
$this->kurz_cnb->Get('EUR');

// zjištění kurzu Chorvatské Kuny v Korunách
$this->kurz_cnb->Get('HRK');
```

Můžete si také nechat zobrazit kompletní data všech měn a dále s nimi pracovat:

```php
$this->kurz_cnb->GetAllData();
```

Doporučení
----------

Protože API České Národní Banky může blokovat časté dotazy z jedné IP adresy,
doporučuji vytvořit si stránku (controller) s názvem například "API", která bude zobrazovat pouze požadovaný výsledek (např. aktuální kurz Eura).
Poté v tomto controlleru nastavte ukládání do cache na například 1 minutu pomocí funkce `$this->output->cache(1);`.
Místo načítání kurzu přímo z ČNB pomocí této knihovny budete pak načítat kurz z této nově vytvořené stránky.
Kurz se ve výsledku nebude zjišťovat pokaždé, ale pouze podle nastavené cache stránky, tedy v tomto příkladu jednu minutu.


Ukázka vlastního API pro zjištění kurzu Eura a Dolaru
-----------------------------------------------------

```php
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/*!
 * ČNB API
 */
class Api extends CI_Controller {


	public function __construct(){
		parent::__construct();
        log_message('debug', "API controller Initialized");

		// inicializace třídy Kurz_cnb
        $this->load->library('kurz_cnb');
	}



	// při zadání URL "/api" přesměrujeme na "/api/usd"
	public function index(){
		redirect('api/usd');
	}



	// na stránce "api/usd" zobrazíme kurz Dolaru
	public function usd(){
		// nastavení cache
		$this->output->cache(1);

		echo $this->kurz_cnb->Get('USD');
	}



	// na stránce "api/eur" zobrazíme kurz Eura
	public function eur(){
		// nastavení cache
		$this->output->cache(1);

		echo $this->kurz_cnb->Get('EUR');
	}



}
/* End of file Api.php */
/* Location: ./application/controllers/Api.php */
```
