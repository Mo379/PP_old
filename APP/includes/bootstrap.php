<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$root = $_SERVER['DOCUMENT_ROOT'];

require_once "$root/vendor/autoload.php";
//
use MVC\usercontr;
use MVC\userview;
//
use MVC\pointcontr;
use MVC\pointview;
//
use MVC\questioncontr;
use MVC\questionview;
//
use MVC\flashcardcontr;
use MVC\flashcardview;
//
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
//
use MVC\utility;
use MVC\filehandle;
use MVC\securityhandle;
//
use MVC\paypal;
//php to html converter
use Spipu\Html2Pdf\Html2Pdf;
// Specify our Twig templates location
$loader = new \Twig\Loader\FilesystemLoader($root.'/templates');
 // Instantiate our Twig
$twig = new \Twig\Environment($loader, [
    'debug' => true,
	'cache' => "$root/cache_twig",
    // ...
]);
$twig->addExtension(new \Twig\Extension\DebugExtension());
$twig->getExtension(\Twig\Extension\CoreExtension::class)->setDateFormat('d/m/Y', '%d days');
//site maintanance mode
$utility = new MVC\utility;
$utility -> cond_check($_SESSION);
if($utility->maitanance){
	echo "We're under construction and will be back soon";
	die;
}
?>
