<?php
//phpinfo();
session_start();
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root/includes/bootstrap.php";
//
$pointcontr = new MVC\pointcontr;
$questioncontr = new MVC\questioncontr;
$userview = new MVC\userview;
$utility = new MVC\utility;
$filehandle = new MVC\filehandle;
$page = 'Home';
//
#if(isset($_SESSION['u_admin'])){
#	$pointcontr->temp_script($filehandle);
#	echo 'admin';
#}
?>
<!doctype html>

<html>
	<?php 
	//output
	$meta = "Our aim is to provide you with the best revision resources for your A-levels and beyond.
	This includes notes; a question bank sorted by chapter, topic, difficulty and type; past papers from all exam boards and more.
	";
	echo $twig->render('subject.head.t.html', ['page_name' => $page,'meta_desc' => $meta]);
	?>
	<body>
		<?php 
		//output
		echo $twig->render('googletags.t.html');
		//setup
		if (isset($_SESSION["u_id"])){
			$u_id = $_SESSION["u_id"];
			$user_info = $userview->get_profile($_SESSION['user_unique_id']);
		}else{
			$u_id = null ;
			$user_info = null;
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
						<?php
						//setup
						echo $twig->render('Home.t.html' , ['user' => $user_info, 'mem_month' => $userview->month, 'mem_sixmonths' =>$userview->six_months,'mem_ninemonths' => $userview->nine_months]);
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
