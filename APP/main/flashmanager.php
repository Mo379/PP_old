<?php
//start session
session_start();
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root/includes/bootstrap.php";
//
$pointcontr = new MVC\pointcontr();
$questioncontr = new MVC\questioncontr();
$userview = new MVC\userview();
$usercontr = new MVC\usercontr();
$flashcardview = new MVC\flashcardview();
$flashcardcontr = new MVC\flashcardcontr();
$pointview = new MVC\pointview();
$utility = new MVC\utility();
$filehandle = new MVC\filehandle();
$securityhandle = new MVC\securityhandle();

//setup
$u_status = $usercontr->user_status;
//
$page = 'Flashmanager';
?>
<!doctype html>
<html>
	<?php
	//title
	$title = "FLashmanager";
	//if the page passes dont show noindex
	$blocker = 1;
	//
	echo $twig->render('subject.head.t.html', ['page_name' => $page ,'clean_title' => $title,'blockcrawlers' => $blocker]);
	?>
	<body>
		<?php 
		//setupt
		if (isset($_SESSION["u_id"])){
			$u_id = $_SESSION["u_id"];
			$user_info = $userview->get_profile($_SESSION['user_unique_id']);
		}else{
			$u_id = null ;
			$user_info = null;
		}
		//generate content
		$headder = $twig->render('global.head.t.html', ['user' => $u_id]);
		//make cache from above html variable
		//
		echo $headder;
		
		?>
		
		<div class="site">
			<div class = "main_content">
				<div class = "content_container" id ='content_container' >	
					<div id='subject_wallpaper' style="background-image: url('/css/img/Home.jpg');background-position: center top;">
							<h1 class = 'subjectmain' style = 'font-size: 28px;'>
								Flashcard Manager: <?php echo $_GET['subject'] ?>
							</h1>
					</div><br><Br>
					<?php 
					if(isset($_SESSION['user_membership'])){
						if($_SESSION['user_membership'] == 1){
							//
							if(isset($_GET['subject']) and isset($user_info['user_unique_id'])){
								//
								$user = $user_info['user_unique_id'];
								$subject = $_GET['subject'];
								//
								$raw_info = $flashcardview->load_manager($subject,$user,$utility);
								//
								echo $twig->render('flash.manager.t.html', ['raw_info' => $raw_info]);
							}else{
								echo ".";
							}
						}else{
							echo $twig->render('index.body.utility.t.html', ['util'=>'membership_req','user_info_member' => $user_info]);
						}
					}else{
						echo $twig->render('index.body.utility.t.html', ['util'=>'membership_req','user_info_member' => $user_info]);
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








