<?php
session_start();
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root/includes/bootstrap.php";
//
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
//
$userview = new MVC\userview;
$utility = new MVC\utility;
$php_mailer = new PHPMailer(true);
//
$page = 'Contract';

if(isset($_GET['editor_unique_id'])  and !empty($_GET['editor_unique_id']) and isset($_GET['editor_vkey']) and !empty($_GET['editor_vkey'])){
	//
	$unique = $_GET['editor_unique_id'];
	$vkey = $_GET['editor_vkey'] ;
	$contract_information = $userview -> compile_contract_information($unique,$vkey);
}else{
	die('missing information');
}
//
$contract = "$root/users/$unique/contract/editor_contract_$unique.pdf";
if(!is_file($contract) or isset($_GET['update'])){
	//
	$html = $twig->render('index.body.utility.editorapplication.t.html', ['util' => 'compile_contract','compile_contract_information' => $contract_information]);
	$html2pdf = new \Spipu\Html2Pdf\Html2Pdf('P', 'A4', 'en','true','UTF-8',array(4, 4, 8, 10),'false');
	$html2pdf->writeHTML($html);
	$html2pdf->output("$contract",'F');
	$html2pdf->output("editor_contract_$unique.pdf");
	//mailer
	$message = "Attached is a copy of your contract with us.";
	$subject = 'Practice Practice: Your copy of the contract';
	$email = $contract_information['editor_email'];
	//
	$mail = $utility->send_mail_attachement($php_mailer,$email,$subject,$message,$contract);
	
	
	
}else{
	echo "<iframe src='https://practicepractice.net/users/$unique/contract/editor_contract_$unique.pdf' style='width: 100%;height: 100%;border: 0;'></iframe>";
}

?>