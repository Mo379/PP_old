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
$page = 'PastPapers';
?>
<!doctype html>
<html>
	<?php
	//This page is to never be indexed
	$blocker = 0;
	$meta_info = $questionview->make_pastpaper_meta($_GET);
	//output
	echo $twig->render('subject.head.t.html', ['page_name' => $page,'blockcrawlers' => $blocker,'meta_desc' => $meta_info]);
	?>
	<body>
		<?php
		//output
		echo $twig->render('googletags.t.html');
		//setuup
		if (isset($_SESSION["u_id"])){
			$u_id = $_SESSION["u_id"];
		}else{
			$u_id = null ;
		}
		//output
		
		echo $twig->render('global.head.t.html', ['user' => $u_id]);
		?>
		
		
		
		
		
		
		<div class="site">
			<div class="main_content">
				<div class = "content_container">
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
					$raw_info = $questionview -> Get_pastpapers($_GET,$usercontr,$filehandle,$utility);
					$u_status = $usercontr->user_status;
					
					//
					echo $twig->render('customquestions.display.t.html', ['raw_info'=>$raw_info,'user' => $u_status]);
					
					
					
					?>
				</div>
			</div>
		</div>
		
		
		
		
		
		
		<?php
		//output
		echo $twig->render('global.footer.t.html', ['user' => $u_id]);
		?>
	</body>
	

</html>