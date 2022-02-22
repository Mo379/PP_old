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
$pointview = new MVC\pointview();
$utility = new MVC\utility();
$filehandle = new MVC\filehandle();
$securityhandle = new MVC\securityhandle();

//setup
$u_status = $usercontr->user_status;
//
$page = 'Spec';
?>
<!doctype html>
<html>
	<?php
	//title
	$title = $pointview->make_page_title($_GET);
	//
	$meta_info = $pointview->make_page_meta_spec($_GET);
	//if the page passes dont show noindex
	$blocker = $pointcontr -> chapter_publication_check($_GET);
	//
	echo $twig->render('subject.head.t.html', ['page_name' => $page ,'clean_title' => $title,'meta_desc' => $meta_info,'blockcrawlers' => $blocker]);
	?>
	<body>
		<?php 
		//setupt
		if (isset($_SESSION["u_id"])){
			$u_id = $_SESSION["u_id"];
		}else{
			$u_id = null ;
		}
		//Output linking pages, the next and previous pages
		$get_links = $pointview-> get_next_and_previous_links($_GET,$usercontr);
		//navigation engine 
		$map = $userview -> nav_engine($page,$_GET,$pointcontr,$questioncontr);

		//generate content
		$headder = $twig->render('global.head.t.html', ['user' => $u_id,'nav_map' => $map]);
		//make cache from above html variable
		//
		echo $headder;
		
		?>
		
		<div class="site">
			<div class = "main_content">
				<div class = "content_container" id ='content_container' >	
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
					
					
					
					//
					
					//
					if (isset($_GET['pt_subject'])){
						$entry_subject = $_GET['pt_subject'];
					}else{
						$entry_subject = null ;
					}
					//
					if (isset($_SESSION['u_admin']) and isset($_GET['pt_subject']) and isset($_GET['pt_moduel']) and isset($_GET['pt_chapter'])){
						$listed_task = $usercontr->is_task_listed($_GET['pt_subject'],$_GET['pt_moduel'],$_GET['pt_chapter']);
						//
						if($listed_task == 1){
							//
							$u_status = 3;
						}
					}
					//
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					
					//output
					if(isset($_GET['user_list'])){
						//
						$raw_info = $pointview -> Get_specpage_userlist($_GET,$filehandle,$utility,$usercontr);
						//var_dump($raw_info);
						echo $twig->render('spec.table.userlist.t.html', ['raw_info' => $raw_info,'entry_subject' => $entry_subject,'user' => $u_status]);
					}elseif(isset($_GET['editor_unique_id']) and isset($_SESSION['user_unique_id'])){
						//
						//Output linking pages, the next and previous pages
						if($_GET['editor_unique_id'] == $_SESSION['user_unique_id']){
							$security_check = $securityhandle -> check_unique($_GET['editor_unique_id']);
							//
							$result = $usercontr -> is_editor_valid($_GET['editor_unique_id'],$_GET['pt_subject'],$_GET['pt_moduel'],$_GET['pt_chapter']);
							//
							if($result == 1 and $security_check == 1 ){
								$raw_info = $pointview -> Get_specpage_moduel_chapter_input($_GET,$filehandle,$utility,$usercontr);
								echo $twig->render('spec.table.moduelchapter.t.html', ['raw_info' => $raw_info,'entry_subject' => $entry_subject,'user' => $u_status,'valid_editor' => 1,'editor_unique' => $_GET['editor_unique_id'] ,'next_prev_links' =>$get_links]);
							}else{
								die;
							}
						}else{
							die;
						}
						
						
					}else{
						//
						//Output linking pages, the next and previous pages
						if(isset($_GET['admin_review'])){
							$security_check = $securityhandle -> check_unique($_GET['admin_review']);
							//
							$result = $usercontr -> is_review_valid($_GET['admin_review'],$_GET['pt_subject'],$_GET['pt_moduel'],$_GET['pt_chapter']);
							//
							if($result == 1 and $security_check == 1){
								//
								$raw_info = $pointview -> Get_specpage_moduel_chapter_input($_GET,$filehandle,$utility,$usercontr);
								//var_dump($raw_info);
								if(!empty($raw_info['table_info'])){
									echo $twig->render('spec.table.moduelchapter.t.html',['raw_info' => $raw_info,'entry_subject' => $entry_subject,'user' => 3,'admin_review' => 1,'editor_unique' => $_GET['admin_review'],'next_prev_links' =>$get_links]);
								}else{
									die;
								}
							}else{
								die;
							}
						}else{
							
							//
							if(!empty($_GET)){
								//
								//$subject = $raw_info['nav_generalinfo']['pt_subject'];
								$subject = $_GET['pt_subject'];
								$moduel = $_GET['pt_moduel'];
								$chapter = $_GET['pt_chapter'];
								//
								$user_level = $usercontr -> user_level;
								$user_leveltemp = $usercontr -> user_level;
								//
								if($user_leveltemp == 'A'){
									$user_leveltemp = '';
								}
								//
								$cache_name = "$user_leveltemp-$subject-$moduel-$chapter";								
								//
								if(!is_file("$root/cache/$cache_name.txt") or ($u_status < 2 or $u_status == 4)){
									if($u_status == 4){
										$wp = 1;
									}else{
										$wp = null;
									}
									//Output linking pages, the next and previous pages
									$raw_info = $pointview -> Get_specpage_moduel_chapter_input($_GET,$filehandle,$utility,$usercontr);
									if(!empty($raw_info['table_info'])){
										//generate content
										$html = $twig->render('spec.table.moduelchapter.t.html', ['raw_info' => $raw_info,'entry_subject' => $entry_subject,'user' => $u_status,'writer_permission' => $wp,'next_prev_links' =>$get_links ]);
										$progress = $twig->render('index.body.utility.t.html', ['util' => 'chapter_progress_bar','user' => $u_status,'subject'=> $subject,'moduel'=> $moduel, 'chapter' => $chapter]);
										//make cache from above html variable
										$result = $filehandle -> make_cache($html,$cache_name,$u_status);
										//
										echo $progress;
										echo $html;
									}else{
										echo "No content available, change your settings and try again";
									}
										
								}else{
									//fetch html from cache file
									$subject = $_GET['pt_subject'];
									$moduel = $_GET['pt_moduel'];
									$chapter = $_GET['pt_chapter'];
									//
									$progress = $twig->render('index.body.utility.t.html', ['util' => 'chapter_progress_bar','user' => $u_status,'subject'=> $subject,'moduel'=> $moduel, 'chapter' => $chapter]);
									$content = $pointview -> fetch_cache("$root/cache/$cache_name.txt");
									//
									echo $progress;
									echo $content;
								}
							}else{
								die;
							}
							
						}
						
					}
					?>
					
					<?php 
					
					
					// load ads
					if(($u_status == 2 or $u_status==3) and !isset($_SESSION['u_admin'])){
						echo "
<script>
//
setTimeout(function(){   
(adsbygoogle = window.adsbygoogle || []).onload = function () {
[].forEach.call(document.getElementsByClassName('adsbygoogle'), function () {
adsbygoogle.push({})
})
}
}, 2000);
</script>
						";
					}
					
					//
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








