<?php
session_start();
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root/includes/bootstrap.php";

$userview = new MVC\userview;
$usercontr = new MVC\usercontr;
$pointcontr = new MVC\pointcontr;
$questioncontr = new MVC\questioncontr;
$questionview = new MVC\questionview;
$filehandle = new MVC\filehandle;
$utility = new MVC\utility;
$securityhandle = new MVC\securityhandle;
//
$page = 'Paper-Print';
?>
<!doctype html>
<html>
<?php
	//This page is to never be indexed
	$blocker = 1;
	//output
	echo $twig->render('subject.head.t.html', ['page_name' => $page,'blockcrawlers' => $blocker]);
?>
<?php
	//setuup
	if (isset($_SESSION["u_id"])){
		$u_id = $_SESSION["u_id"];
	}else{
		$u_id = null ;
	}
?>

<?php
	//security check 
	foreach($_POST as $name => $value){
		//
		$status = $securityhandle->check_site_variable($name,$value);
		//
		if($status == 0){
			echo "Invalid input: $name"; die;
		}
	}
	foreach($_GET as $name => $value){
		//
		$status = $securityhandle->check_site_variable($name,$value);
		//
		if($status == 0){
			echo "Invalid input: $name"; die;
		}
	}
	//
	$raw_info = $questionview -> Get_user_papermaker($_GET,$usercontr,$filehandle,$utility);
	//
	if($raw_info != 0){
		echo $twig->render('paper_file.t.html', ['raw_info' => $raw_info,'user_unique'=>$_GET['user_unique_id'],'paper_unique'=>$_GET['paper_unique_id']]);
	}else{
		echo $twig->render('global.head.t.html');
		echo "This paper has been lost.";
	}
	
?>
</html>