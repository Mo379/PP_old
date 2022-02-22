<?php
namespace MVC;

use mysqli;
use PayPal\Api\Payer;
use PayPal\Api\Details;
use PayPal\Api\Amount;
use PayPal\Api\Transaction;
use PayPal\Api\Currency;
use PayPal\Api\Payment;
use PayPal\Api\Payout;
use PayPal\Api\PayoutItem;
use PayPal\Api\PayoutSenderBatchHeader;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Exception\PayPalConnectionException;

class paypal extends dbh {
	//Model
	public function __construct(){
		parent::__construct();
		$this->PayPal_api_connection();
	}
	//Model:
	public function run_query($query){
		return mysqli_query($this->conn,$query);
	}
	
	
	
	
	
	//****has to be editred so that the price is correct for the membership
	//have to also make the periodic payment plan
	public function payment($total,$credits){
		//setup before loading the api call
		$payer = new Payer();
		$details = new Details();
		$amount = new Amount();
		$transaction = new Transaction();
		$payment = new Payment();
		$redirecturls = new RedirectUrls();
		//payment method, you can also pay using credit card if you like
		//$payer->setPaymentmethod('paypal');
		$payer->setPaymentmethod('paypal');
		//details about the full price
		$details -> setShipping('0.00')
			->setTax('0.00')
			-> setSubtotal($total);
		//amount or the final price
		$amount -> setCurrency('GBP') 
			->setTotal($total) 
			-> setDetails($details);
		//transaction information
		$transaction->setAmount($amount)
			->setDescription('Membership fee');
		//payment intent
		$payment->setIntent('sale')
			->setPayer($payer)
			->setTransactions([$transaction]);
		//Redirect urls 
		$redirecturls-> setReturnUrl('https://practicepractice.net/P/checkout?payment_approval=1')
			->setCancelUrl('https://practicepractice.net/P/checkout');
		//setting redirect urls
		$payment-> setRedirectUrls($redirecturls);
		//throwing the api call 
		try{
			//throwing api call
			$payment->create($this->paypal_api);
		}catch(PayPalConnectionException $e){
			//
			return 0;
		}
		//generate and store hash 
		$hash = md5($payment->getId());
		$_SESSION['PP_hash'] = $hash;
		$_SESSION['bought_credits'] = $credits;
		//
		$payment_id = $payment->getId();
		$u_id = $_SESSION['u_id'];
		//prepair and execute transaction
		$query = "insert into transactions_paypal (user_id,transfer_arrow,payment_id,hash,status) values ('$u_id','in','$payment_id','$hash',0)";
		$this->run_query($query);
		//getting the paypal redirec url to get payment token
		foreach($payment->getLinks() as $link){
			//
			if($link->getRel() == 'approval_url'){
				$redirectUrl = $link->gethref();
			}
		}
		//redirecting the user
		return $redirectUrl;
	}
	
	
	
	//***has to be edited so that we can include a variable amount,the amount has to be fetched from the database somehow
	public function payout($payee_email,$task_price){
		//setting the payout objects
		$payout = new Payout();
		//sender batch header 
		$senderBatchHeader = new PayoutSenderBatchHeader();
		//
		$batch_id = uniqid();
		//
		$senderBatchHeader->setSenderBatchId($batch_id)
    	->setEmailSubject("Practice Practice : New payment !");
		//setting up the item information
		$senderItem = new PayoutItem();
		//
		$amount = '{"value":"'.$task_price.'","currency":"USD"}';
		$senderItem->setRecipientType('Email')
		->setNote('Thanks for your service!')
		->setSenderItemId("1")
		->setReceiver($payee_email)
		->setAmount(new Currency($amount));
		//compiling the api call into a single object
		$payout->setSenderBatchHeader($senderBatchHeader)
			->addItem($senderItem);
		//
		try {
			//
			$payment = $payout->create(array('sync_mode' => 'false'),$this->paypal_api);
			//generate and store hash 
			$hash = md5($batch_id);
			$_SESSION['PP_payout_hash'] = $hash;
			//
			$payment_id = $batch_id;
			$u_id = $_SESSION['u_id'];
			//time
			$time = time();
			//prepair and execute transaction
			$query = "insert into transactions_paypal (user_id,transfer_arrow,payment_id,hash,status,completion_time) values ('$u_id','out','$payment_id','$hash',1,$time)";
			if($this->run_query($query)){
				//
			}else{
				return 0;
			}
		} catch (PayPalConnectionException $e) {
			//ResultPrinter::printError("Created Single Synchronous Payout", "Payout", null, $request, $ex);
			return 0;
		}
		//
		return 1;
	}	
	
	//array('email','amount')
	public function payouts($info){
		//setting the payout objects
		$payout = new Payout();
		//sender batch header 
		$senderBatchHeader = new PayoutSenderBatchHeader();
		//
		$batch_id = uniqid();
		//
		$senderBatchHeader->setSenderBatchId($batch_id)
    	->setEmailSubject("Practice Practice : New payment !");
		//
		foreach($info as $key => $value){
			//
			if($value['amount']){
				$task_price = $value['amount'];
				$payee_email = $value['email'];
				//setting up the item information
				$senderItem = new PayoutItem();
				//
				$amount = '{"value":"'.$task_price.'","currency":"USD"}';
				$senderItem->setRecipientType('Email')
				->setNote('Thanks for your service!')
				->setSenderItemId("1")
				->setReceiver($payee_email)
				->setAmount(new Currency($amount));
				//compiling the api call into a single object
				$payout->setSenderBatchHeader($senderBatchHeader)
					->addItem($senderItem);
			}
		}
		//
		try {
			//
			$payment = $payout->create(null,$this->paypal_api);
			//generate and store hash 
			$hash = md5($batch_id);
			$_SESSION['PP_payout_hash'] = $hash;
			//
			$payment_id = $batch_id;
			$u_id = $_SESSION['u_id'];
			//time
			$time = time();
			//prepair and execute transaction
			$query = "insert into transactions_paypal (user_id,transfer_arrow,payment_id,hash,status,completion_time) values ('$u_id','out','$payment_id','$hash',1,$time)";
			if($this->run_query($query)){
				//
			}else{
				return 0;
			}
		} catch (PayPalConnectionException $e) {
			//ResultPrinter::printError("Created Single Synchronous Payout", "Payout", null, $request, $ex);
			return 0;
		}
		//
		return 1;
	}	
	
	
	
	
	
	
	
	
	
	//Model: send the user a small payment with a code that they can use to confirm they recived the payment 
	public function payout_confirm_account($payee_email,$code){
		//setting the payout objects
		$payout = new Payout();
		//$details = new Details();
		//$amount = new Amount();
		//sender batch header 
		$senderBatchHeader = new PayoutSenderBatchHeader();
		//
		$batch_id = uniqid();
		//
		$senderBatchHeader->setSenderBatchId($batch_id)
    	->setEmailSubject("Code - $code");
		//setting up the item information
		$senderItem = new PayoutItem();
		//set the amount 
		//$details -> setShipping('0.00')->setTax('0.00')-> setSubtotal('0.01');
		//amount or the final price
		//$amount -> setCurrency('GBP') ->setTotal('0.01') -> setDetails($details);
		//
		$senderItem->setRecipientType('Email')
		->setNote("Code - $code")
		->setSenderItemId("1")
		->setReceiver($payee_email)
		->setAmount(new Currency('{
					"value":"0.01",
					"currency":"USD"
				}'));
		//compiling the api call into a single object
		$payout->setSenderBatchHeader($senderBatchHeader)
			->addItem($senderItem);
		//
		try {
			//
			$payment = $payout->create(array('sync_mode' => 'false'),$this->paypal_api);
			//generate and store hash 
			$hash = md5($batch_id);
			$_SESSION['PP_payout_hash'] = $hash;
			//
			$payment_id = $batch_id;
			$u_id = $_SESSION['u_id'];
			//time
			$time = time();
			//prepair and execute transaction
			$query = "insert into transactions_paypal (user_id,transfer_arrow,payment_id,hash,status,completion_time) values ('$u_id','out','$payment_id','$hash',1,$time)";
			if($this->run_query($query)){
				//
			}else{
				return 0;
			}
		} catch (PayPalConnectionException $e) {
			//ResultPrinter::printError("Created Single Synchronous Payout", "Payout", null, $request, $ex);
			return 0;
		}
		//
		return 1;
	}
	//Model: get the payment information using the payment id
	public function getpayment($payment_id){
		return Payment::get($payment_id,$this->paypal_api);
	}
	//MODEL: execute the a payment
	public function execute_payment($payment,$payerid){
		//make execution object
		$execution = new PaymentExecution;
		//set up the variables needed
		$execution->setPayerId($payerid);
		//
		try{
			$payment->execute($execution,$this->paypal_api);
			//
		}catch(PayPalConnectionException $e){
			//echo $e->getCode(); 
			//echo $e->getData();
			return 0;
		}
		//
		$this->set_status_complete($_SESSION['PP_hash']);
		unset($_SESSION['PP_hash']);
		return 1;
	}
	//Model: after the payment is complete set the payment status to complete
	private function set_status_complete($hash){
		//
		$time = time();
		//
		$query = "update transactions_paypal set status = '1', completion_time = '$time' where hash = '$hash'";
		$this->run_query($query);
	}
}

