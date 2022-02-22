<?php
session_start();
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root/includes/bootstrap.php";
//
$pointcontr = new MVC\pointcontr;
$questioncontr = new MVC\questioncontr;
$userview = new MVC\userview;
$page = 'About';
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
		echo $twig->render('global.head.t.html', ['user' => $u_id,'page'=>$page,'nav_map' => $map]);
		?>
		<div class="site">
			<div class="main_content">
				<div class = "content_container">
					<div id='subject_wallpaper' style="background-image: url('/css/img/Home.jpg');background-position: center top;">
							<h1 class = 'subjectmain' style = 'font-size: 28px;'>
								About us
							</h1>
					</div><br><Br>
					<section>
					<h1>Our mission</h1><br>
					<div>
						<p>
						PracticePractice's mission is to provide high quality educational content for students at all levels. This includes a diverse question bank that divides the content into samll bitesize chunks, allowing you (the student) to practice effectively; and detailed notes with helpful external links to free and excellent learning resources, providing you with an all in one revision resource.
						<br><br><br>
							
					<h1>And there is more</h1><br>
						We will continiously work to provide you with new and improved features to aid with your studying habbits. Some of those include:
						<br><br>
						1) A tool allowing you to make your own practive papers from our questions bank.<br><br>
						2) A progress tracking system. <br><br>
						3) A question practice spaced repetition system. <br><br>
						and more with the goal to provide personalised learning systems with the aid of Artificial Inteligence (AI).
						<br><br><br>
					<h1>This is our road map</h1><br>
						We will start by completing the content (notes and question banks) for the sciences and Mathematics for A-level. The majority of the content will remain free; however some of the mentioned features and some content, will be behind a paywall at a suitable price for students. This will help us accelerate our development to the next stages. 
						<br><Br>
						After completion of the A-level Sciences and Mathematics content, we will move on to university/level 6 content while expanding our A-levels library and improving the software; bringing new features and mobile apps. 
						<br><br>
						Our ultimate goal is to become a qualified online STEM university, providing high quality STEM qualifications for the scientists and engineers of the future.
						<br><br><br>
						</p>	
					</div>
					
					</section>
					<br><br><h1>Contact us</h1><br><br>
					<section id='contact_form'>
						<p>
						If you have any quries or suggestions; improvement to our content, please use the following form to get in contact with us. You'll get an automated email that confirms our reception of your message. We will try to get back to you as soon as possible. <br><Br>
						Thanks.<br><br>
						Admin.<br><Br>
						</p>
						<h1> Contact form </h1>
						<div id='usr_nav_element' >
							<input type="text" name="name" placeholder="Full name *" id="con_name" autocomplete="off">
							<br>
							<input type="text" name="email" placeholder="E-mail *" id="con_mail" autocomplete="off">
							<br>
							<input type="text" name="subject" placeholder="Subject *" id="con_subject" autocomplete="off">
							<br>
							<textarea style='height:60px;margin-left:5px;' type="text" id="con_message" name="message" placeholder="Your message (no line breaks or special characters)*" autocomplete="off"></textarea>
							<br>
							<button type="button" onclick="controller.C_contact_form('con_name','con_mail','con_subject','con_message')"> Send </button>
						</div>
					</section>
				</div>	
			</div>
			
			
		
		
		
		</div>
		<?php
		//output
		echo $twig->render('global.footer.t.html', ['user' => $u_id]);
		?>
	</body>
</html>