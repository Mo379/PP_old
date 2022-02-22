<?php 
namespace MVC;

//paypal setup
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
class dbh {
	public $root,
	$dbservername,
	$dbusername,
	$dbpassword,
	$dbname,$conn,
	$paypal_api;
	//
	public $client_id,$secret;
	//
	public function __construct(){
		$this -> root = $_SERVER['DOCUMENT_ROOT'];
		$this -> dbservername = 'db_mysql:3306';
		$this -> serverhost = '0.0.0.0:8006';
		$this -> dbusername = "root";
		$this -> dbpassword = "Mustafa12211";
		$this -> dbname = "site";
		$this-> conn = new \mysqli($this->dbservername, $this->dbusername,$this ->dbpassword, $this ->dbname );
		$this-> month = 2.99;
		$this-> six_months = 1.33;
		$this -> nine_months = 1.11;
		$this->affiliates_cut = 0.2;
		$this-> affiliate_discount = 20;
		$this-> mail_username  = 'AKIAYX6RN42NNAJR6SAN';
		$this-> mail_password  = 'BCEg3IXhKYUwKDruGwRLTDPdyk';
	}
	//
	public function PayPal_api_connection(){
		//
		$this->client_id = 'Ae19FkmOCzPlID8pEqfHN0qMo8VOTmy0-VIljlE9qmMHsTIZM_3hVoi0XtivKhyAHGbHTGmMBePDKYlB';
		//
		$this->secret = 'EJwaePWhMSEtgrLUXMesoERyTg6mfp2sumQD0c1I3wfNmZi9S4saHoYBuz6fiegOdG-Pnrkkd-6siopm';
		//
		$this->paypal_api = new ApiContext(
			new OAuthTokenCredential(
				$this->client_id,
				$this->secret
			)
		);
		//
		$this->paypal_api->setConfig([
			'mode' => 'live',
			'http.connectionTimeout'=> 180,
			'log.logEnabled' => false,
			'log.FileoName' => '',
			'log.LogLevel' => 'FINE',
			'validation.level' => 'log',]
		);
	}
	
}
