<?php
session_start();
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root/includes/bootstrap.php";

//
$pointcontr = new MVC\pointcontr;
$questioncontr = new MVC\questioncontr;
$userview = new MVC\userview;
$page = 'Forums';
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
		echo $twig->render('global.head.t.html', ['user' => $u_id,'page' => $page,'nav_map' => $map]);
		?>
		<div class="site">
			<div class="main_content">
				<div class = "content_container">
					
				</div>
			</div>
		</div>
		<?php
		//output
		echo $twig->render('global.footer.t.html', ['user' => $u_id]);
		?>
	</body>
</html>







