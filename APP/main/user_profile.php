<?php
session_start();
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root/includes/bootstrap.php";
//
//barier and setup
if (!isset ($_SESSION['user_unique_id'])){
	header("location: /index");
}
//
$pointcontr = new MVC\pointcontr;
$questioncontr = new MVC\questioncontr;
$userview = new MVC\userview;
//
$page = 'Profile';
?>
<!doctype html>
<html>
	<?php
	//output
	echo $twig->render('subject.head.t.html', ['page_name' => $page]);
	?>
	<body>
		<?php 
		//output
		echo $twig->render('googletags.t.html');
		//setup
		if (isset($_SESSION["u_id"])){
			$u_id = $_SESSION["u_id"];
		}else{
			$u_id = null ;
		}
		//navigation engine 
		$map = $userview -> nav_engine($page,$_GET,$pointcontr,$questioncontr);
		//output
		echo $twig->render('global.head.t.html', ['user' => $u_id,'nav_map' => $map]);
		?>
		<div class="site">
			<div class="main_content">
				<div class = "content_container">
					<!-- start -->
					<?php
					//setup
					if (isset ($_SESSION['user_unique_id'])){
						$user_unique = $_SESSION['user_unique_id'];
					}else{
						$user_unique = null;
					}
					//
					if(isset($_GET['payment_approval']) and isset($_GET['paymentId']) and isset($_GET['token']) and isset($_GET['PayerID']) and isset($_SESSION['PP_hash'])){
						$payment_approval = $_GET;
					}else{
						$payment_approval = null;
					}
					//
					if(!empty($user_unique)){
						$user_info = $userview->get_profile($user_unique);
						$user_info['user_type'] = $userview->user_status;
					}
					//output
					echo $twig->render('user.profile.t.html', ['user_unique_id' => $user_unique , 'user_info' => $user_info,'payment_approval' => $payment_approval]);
					?>
					<!--end-->
				</div>
			</div>
		</div>
		<?php
		//output
		echo $twig->render('global.footer.t.html', ['user' => $u_id]);
		?>
	</body>
</html>












