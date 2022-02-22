<?php
session_start();
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root/includes/bootstrap.php";
//
$pointcontr = new MVC\pointcontr;
$pointview = new MVC\pointview;
$questioncontr = new MVC\questioncontr;
$questionview = new MVC\questionview;
$userview = new MVC\userview;
$utility = new MVC\utility;
$usercontr = new MVC\usercontr;
$page = 'Hub';
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
			 <!--The main heading for all the site-->
			<div class="main_content">
				<div class = "content_container">
					<div id = "content_index">
						<div id='subject_wallpaper' style="background-image: url('/css/img/Home.jpg');background-position: center top;">
							<h1 class = 'subjectmain' style = 'font-size: 28px;'>
								Hub
							</h1>
						</div><br><br>
						<?php
						//setup
						if ( isset($_SESSION['u_admin'])){
							
							$admin = $_SESSION['u_admin']; 
							$editors_information =$userview->get_editors_contract_status(); 
							$affiliate_balance =$userview->get_affiliate_total_balance(); 
							//
							if(isset($_GET['Editor_application_process']) and isset($_GET['editor_unique_id'])){
								//
								$approval_process = 1;
								$editor_information = $userview->get_contract_info($_GET['editor_unique_id']);
								$editor_information['editor_unique_id'] = $_GET['editor_unique_id'];
							}else{
								//
								$approval_process = null;
								$editor_information = null;
							}
						}else{
							//
							$admin = null;
							$editors_information = null;
							$affiliate_balance = null;
						}
						//
						if ( isset($_SESSION['u_editor'])){
							$editor_info = $userview->get_profile($_SESSION['user_unique_id']);
						}else{
							$editor_info = null;
						}
						//
						if ( isset($_GET['vkey'])){
							$vkey =$_GET['vkey'];
						}else{
							$vkey = null;
						}
						//
						if ( isset($_GET['apply_new_pwd_verified'])){
							$pwd_verified =$_GET['apply_new_pwd_verified'];
						}else{
							$pwd_verified = null;
						}//
						if ( isset($_GET['u_vkey'])){
							$u_vkey =$_GET['u_vkey'];
						}else{
							$u_vkey = null;
						}
						//
						if(isset($_SESSION['u_id'])){
							$u_id = $_SESSION['u_id'];
							$q_history_info = $questionview-> get_q_history($utility);
							$p_history_info = $questionview-> get_p_history($utility);
							$member = $_SESSION["user_membership"];
							$user_info = $userview->get_profile($_SESSION['user_unique_id']);
						}else{
							$u_id = null;
							$q_history_info = null;
							$p_history_info = null;
							$member = 0;
						}
						//affiliates
						if(isset($_SESSION['u_id'])){
							if($user_info['affiliates'] == 1){
								$user_info['aff_bal'] = $usercontr->get_affiliate_bal();
								$user_info['aff_his'] = $usercontr->get_affiliate_history();
								
								//
								echo $twig->render('index.body.utility.t.html', ['util' => 'affiliates_space','user_affiliate' => $user_info ]);
							}
							
						}
						
						
						//Outputs
						if(isset($_GET['Editor_application'])){
							echo $twig->render('index.body.utility.editorapplication.t.html', ['util' => 'editor_application','editor' => $editor_info ]);
						}
						//
						if(isset($_GET['Editor_application_process'])){
							echo $twig->render('index.body.utility.editorapplication.t.html', ['util' => 'editor_application_process','editor_information' => $editor_information ]);
						}
						echo $twig->render('index.body.utility.t.html', ['util' => 'verify_newuser', 'u_vkey' => $u_vkey ]);
						echo $twig->render('index.body.utility.t.html', ['util' => 'user_login','u_id' => $u_id ]);
						echo $twig->render('index.body.utility.t.html', ['util' => 'admin_controls','admin' => $admin,'editors_information' => $editors_information,'total_affiliate_payout'=>$affiliate_balance]);
						echo $twig->render('index.body.utility.t.html', ['util' => 'performance_chart','u_id' => $u_id,'member' => $member]);//$u_id
						echo $twig->render('index.body.utility.t.html', ['util' => 'question_history','u_id' => $u_id,'history_info'=>$q_history_info,'member' => $member]);//$u_id
						
						
						echo $twig->render('index.body.utility.t.html', ['util' => 'paper_history','u_id' => $u_id,'paper_info'=>$p_history_info,'member' => $member]);//$u_id
						echo $twig->render('index.body.utility.t.html', ['util' => 'apply_new_pwd_verified', 'pwd_verified' => $pwd_verified ,'vkey' => $vkey ]);
						
						
						

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
