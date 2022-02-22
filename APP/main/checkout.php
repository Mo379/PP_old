<?php
session_start();
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root/includes/bootstrap.php";
//
$pointcontr = new MVC\pointcontr;
$questioncontr = new MVC\questioncontr;
$userview = new MVC\userview;
$utility = new MVC\utility;
$page = 'Checkout';
?>
<!doctype html>

<html>
	<?php 

	echo $twig->render('subject.head.t.html', ['page_name' => $page]);
	?>
	<body>
		<?php 
		//output
		//setup
		if (isset($_SESSION["u_id"])){
			$u_id = $_SESSION["u_id"];
			$user_info = $userview->get_profile($_SESSION['user_unique_id']);
			//
			if(isset($_SESSION['membership_option'])){
				$membership_option = $_SESSION['membership_option'];
			}else{
				$membership_option = 1;
			}
			
			if(isset($_SESSION['membership_discount'])){
				$membership_discount = $_SESSION['membership_discount'];
			}else{
				$membership_discount = null;
			}
			if(isset($_SESSION['membership_total'])){
				$membership_total = $_SESSION['membership_total'];
			}else{
				$membership_total = null;
			}
			
		}else{
			$u_id = null ;
			$user_info = null;
		}
		//output
		echo $twig->render('global.head.t.html', ['user' => $u_id]);
		?>
		<div class="site">
			 <!--The main heading for all the site-->
			<div class="main_content">
				<div class = "content_container">
					<div id = "content_index">
						<?php
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
						//setup
						if(isset($u_id) and $u_id != null){
							echo $twig->render('index.body.utility.t.html' , ['util'=>'Checkoutform','user_info_member' => $user_info,'payment_approval' => $payment_approval,'membership_option'=>$membership_option,'membership_discount'=>$membership_discount,'membership_total'=>$membership_total
								,'mem_month' => $userview->month, 'mem_sixmonths' =>$userview->six_months,'mem_ninemonths' => $userview->nine_months]);
						}else{
							echo $twig->render('index.body.utility.t.html', ['util'=>'membership_req','user_info_member' => $user_info,'mem_month' => $userview->month, 'mem_sixmonths' =>$userview->six_months,'mem_ninemonths' => $userview->nine_months]);
						}
						
						//
						?>
					</div>
				</div>
			</div>	
		</div>
		<?php
		//generates random question data 
		//$questioncontr-> q_data_gen($utility);
		//output
		echo $twig->render('global.footer.t.html', ['user' => $u_id]);
		?>
	</body>
</html>
