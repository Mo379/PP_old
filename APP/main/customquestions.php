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
$page = 'Practice';
?>
<!doctype html>
<html>
	<?php
	//This page is to never be indexed
	$blocker = 1;
	//output
	echo $twig->render('subject.head.t.html', ['page_name' => $page,'blockcrawlers' => $blocker]);
	?>
	<body>
		<?php
		//output
		echo $twig->render('googletags.t.html');
		//setuup
		if (isset($_SESSION["u_id"])){
			$u_id = $_SESSION["u_id"];
			$member = $_SESSION['user_membership'];
			$user_info = $userview->get_profile($_SESSION['user_unique_id']);
		}else{
			$u_id = null ;
			$member = null;
			$user_info = null;
		}
		//navigation engine 
		$map = $userview -> nav_engine($page,$_GET,$pointcontr,$questioncontr);
		//output
		echo $twig->render('global.head.t.html', ['user' => $u_id,'nav_map' => $map]);
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
					
					
					
					
					if($member == 1){
						//
						$raw_info = $questionview -> Get_customquestions($_GET,$usercontr,$filehandle,$utility);
						$u_status = $usercontr->user_status;
						//
						echo $twig->render('customquestions.display.t.html', ['raw_info'=>$raw_info,'user' => $u_status]);
					}else{
						echo $twig->render('index.body.utility.t.html', ['util'=>'membership_req','user_info_member' => $user_info, 'mem_month' => $userview->month, 'mem_sixmonths' =>$userview->six_months,'mem_ninemonths' => $userview->nine_months]);
					}
					
					
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