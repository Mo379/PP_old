<?php
session_start();
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root/includes/bootstrap.php";
//
$pointcontr = new MVC\pointcontr;
$questioncontr = new MVC\questioncontr;
$userview = new MVC\userview;
$filehandle = new MVC\filehandle;
$utility = new MVC\utility;
$page = 'Papers_pre';
//$questioncontr->distribute_questions($pointcontr,$utility,$filehandle);
?>

<!doctype html>

<html>
	<?php
	//output
	echo $twig->render('subject.head.t.html', ['page_name' => $page]);
	?>
	<body>
		<?php 
		//setup
		if (isset($_SESSION["u_id"]) and isset($_SESSION['u_admin'])){
			$u_id = $_SESSION["u_id"];
		}else{
			die;
		}
		//navigation engine 
		//output
		echo $twig->render('global.head.t.html', ['user' => $u_id,'page'=>$page]);
		
		?>
		<div class="site">
			<div class="main_content">
				<div class = "content_container">
					<?php
					if(isset($_GET['paper'])){
						$paper = $_GET['paper'];
						$rawinfo = 'x';
					}else{
						$rawinfo = null;
					}
					
					
					?>
					<input placeholder="paper name" id='paper_name'/><br>
					<button onclick=controller.C_organise_paper()>Organise</button>
				</div>	
			</div>
			
			
		
		
		
		</div>
		<?php
		//output
		echo $twig->render('global.footer.t.html', ['user' => $u_id]);
		?>
	</body>
</html>