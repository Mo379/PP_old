<?php
session_start();
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root/includes/bootstrap.php";
//
$page = 'History';
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
		//output
		echo $twig->render('global.head.t.html', ['user' => $u_id]);
		?>
		<div class="site">
			<div class="main_content">
				<div class = "content_container" >
					<h2> Questions  previously visited </h2>
					<div id='history_listing'>
						<?php
							
						?>
					</div>
				</div>
			</div>
		</div>
		<?php
		//output
		echo $twig->render('global.footer.t.html', ['user' => $u_id]);
		?>
	</body>
	
	



	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	


</html>