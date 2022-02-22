<?php
session_start();
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root/includes/bootstrap.php";
//
$pointcontr = new MVC\pointcontr;
$pointview = new MVC\pointview;
$usercontr = new MVC\usercontr;
$userview = new MVC\userview;
$questioncontr = new MVC\questioncontr;
$questionview = new MVC\questionview;
$filehandle = new MVC\filehandle;
$utility = new MVC\utility;
//
$page = 'Physics';
$subject = 'Physics';
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
		echo $twig->render('global.head.t.html', ['page' => $page,'user' => $u_id,'nav_map' => $map]);
		?>
		<div class="site">
			<div class="main_content">
				<div class = "content_container" id = "browsing_forms">
					<?php
					//setup
					if(isset($_SESSION['user_unique_id'])){
						$usr_unique = $_SESSION['user_unique_id'];
						$usr_information = $userview -> get_profile($usr_unique);
						$usr_status = $userview -> user_status;
						$member = $_SESSION["user_membership"];
						//
						if (isset($_SESSION['u_admin'])){
							$admin = 1;
							$active_tasks_assoc = $pointview -> get_active_tasks($subject,$usercontr);
							$active_tasks_questions_assoc = $questionview -> get_active_tasks($subject,$usercontr);
						}else{
							$admin = null;
						}
						//
						if ( isset($_SESSION['u_editor'])){
							$editor = 1;
							$active_tasks_assoc = $pointview -> get_active_tasks($subject,$usercontr);
							$active_tasks_questions_assoc = $questionview -> get_active_tasks($subject,$usercontr);
						}else{
							$editor = null;
						}
						//
						if(!$editor and !$admin){
							$active_tasks_assoc = null;
							$active_tasks_questions_assoc = null;
						}
						
					}else{
						$usr_unique = null;
						$usr_status = 3;
						$usr_information = null;
						$active_tasks_assoc = null;
						$active_tasks_questions_assoc = null;
						$admin = null;
						$editor = null;
						$member = 0;
					}
					//
					if(isset($_GET['map_moduel'])){
						$moduel = $_GET['map_moduel'];
					}else{
						$moduel= null;
					}
					//
					if(isset($_GET['map_chapter'])){
						$chapter = $_GET['map_chapter'];
					}else{
						$chapter= null;
					}
					//
					$q_moduels = $questionview -> get_question_moduels($subject,$utility,$usercontr);
					$q_papers = $questionview -> get_question_papers($subject,$utility,$usercontr);
					
					
					
					
					
					
					
					
					if(!isset($_SESSION['u_admin'])){
						echo '
						<div id="subject_wallpaper" style="background-image: url(/css/img/Physics.jpg);">
						<h1 class = "subjectmain" style = "font-size: 28px;">
							A level Physics
						</h1>
						</div> <br><Br><br><Br><Br>';
						echo "
						<h1 style = 'width:100%;text-align:center;font-size: 48px;'> Coming soon </h1>";
					}else{
						//output
						echo $twig->render('subject.body.tasks_list.t.html', ['subject'=> $subject,'admin' => $admin,'editor' => $editor ,'user' => $usr_information,'active_tasks' => $active_tasks_assoc,'active_tasks_questions' => $active_tasks_questions_assoc]);



						echo $twig->render('subject.body.spec.t.html', ['subject' => $subject,'map_moduel' => $moduel,'map_chapter' => $chapter,'user_unique_id' => $usr_unique,'admin' =>$admin]);

						echo $twig->render('subject.body.question.t.html', ['user'=> $usr_status,'subject' => $subject, 'moduels' => $q_moduels,'papers' => $q_papers,'member' => $member]);
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




