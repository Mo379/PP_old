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
$page = 'Questions';
?>
<!doctype html>
<html>
	<?php
	//
	$filter = $_GET['filter'];
	$pt_unique_id = $_GET['pt_unique_id'];
	//if the page passes dont show noindex
	$blocker = $pointcontr -> questions_publication_check($filter,$pt_unique_id);
	//output
	$title = $questionview -> make_title($_GET,$pointcontr,$utility);
	$meta_info = $questionview->make_page_meta_spec($_GET,$pointcontr,$utility);
	echo $twig->render('subject.head.t.html', ['page_name' => $page,'blockcrawlers' => $blocker , 'clean_title' => $title,'meta_desc' => $meta_info]);
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
					
					
					
					
					
					//setup
					$point_obj = $pointcontr->  make_obj_unique_id($_GET['pt_unique_id']);
					if(isset($point_obj['pt_id']) and !empty($point_obj['pt_id'])){
						if($_GET['filter'] == 'point' or $_GET['filter'] == 'topic' or $_GET['filter'] == 'chapter'){
							//this verifies the input, everything we need to make the question view
							$filter = $_GET['filter'];
							//
							$raw_info = $questionview -> Get_questions($filter,$point_obj,$_GET,$usercontr,$filehandle,$utility);
						}else{
							die('error');
						}
					}else{
						die('error');
					}
					//
					$u_status = $usercontr->user_status;
					//
					if (isset($_SESSION['u_admin']) and isset($_GET['filter']) and isset($_GET['pt_unique_id']) ){
						$listed_task = $usercontr->is_task_listed_question($_GET['filter'],$_GET['pt_unique_id'],$pointcontr);
						//
						if($listed_task == 1){
							//
							$u_status = 3;
						}
					}
					
					
					
					//if the editor wants to edit
					if(isset($_GET['editor_unique_id']) and isset($_SESSION['user_unique_id'])){
						//check if the session editor matches the get id
						if($_GET['editor_unique_id'] == $_SESSION['user_unique_id']){
							//safe cehck the the url variables
							$security_check = $securityhandle -> check_unique($_GET['editor_unique_id']);
							//check if the editor is the claimant of this task
							$result = $usercontr -> is_editor_valid_question($_GET['editor_unique_id'],$_GET['filter'],$_GET['pt_unique_id']);
							//if the editor is valid for this task let him edit the secondary version of the questions
							if($result == 1 and $security_check == 1 ){
								//render the questions display for this special case
								echo $twig->render('questions.display.t.html', ['raw_info'=>$raw_info,'user' => $u_status,'valid_editor' => 1,'editor_unique' => $_GET['editor_unique_id']]);
							}else{
								die;
							}
						}else{
							die;
						}
					}elseif(isset($_GET['admin_review'])){
						//check the admin's unique is safe
						$security_check = $securityhandle -> check_unique($_GET['admin_review']);
						//
						$result = $usercontr -> is_review_valid_question($_GET['admin_review'],$_GET['filter'],$_GET['pt_unique_id']);
						//
						if($result == 1 and $security_check == 1){
							//
							echo $twig->render('questions.display.t.html', ['raw_info'=>$raw_info,'user' => 3,'admin_review' => 1,'editor_unique' => $_GET['admin_review']]);
							
						}else{
							die;
						}
					}else{
						if($_GET['pt_unique_id'] == "3ebb24f2e3"){
							$paper_locator_tool = 1;
						}else{
							$paper_locator_tool = null;
						}
						if($u_status == 4){
							$wp = 1;
						}else{
							$wp = null;
						}
						echo $twig->render('questions.display.t.html', ['raw_info'=>$raw_info,'user' => $u_status,'paper_locator' => $paper_locator_tool,'writer_permission'=>$wp]);
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