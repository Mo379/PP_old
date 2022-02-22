<?php
namespace MVC;

//it fetched the information coming from the database through the model
//and displays it how we want
class pointview extends point{	
	//****************php stuff
	//checks if the reqired info is present to give access to the specpage
	private function validate_specpage_entry() {
		//ssetting up query
		$com1 =$this->pt_moduel.$this->pt_chapter;
		//kill if no search info is given
		if(empty($com1) and empty($this->pointer) and empty($this->pt_unique_id) and empty($this->user_list)){
			die('missing information');
		}else{
			if(!empty($com1)){
				if(empty($this->pt_subject) or empty($this->pt_moduel) or empty($this->pt_chapter)){
					die('missing information');
				}
			}elseif(empty($this->pt_subject) or empty($this->pointer) and empty($this->pt_unique_id) and  empty($this->user_list) ){
				die('missing information');
			}
		}
	}
	//there are specific ways of entering the spec page, this should result in a different output.
	private function filterQuery_specpage_entry(){
		//	
		if(!empty($this->pt_unique_id)){
			$this->subject_q = '';
			$this->unique_q = $this->t_query_filter('pt_unique_id',$this->pt_unique_id);
			$this->moduel_q = '';
			$this->chapter_q = '';
		}elseif(!empty($this->user_list)){
			
		}else{
			$this->subject_q = $this->t_query_filter('pt_subject',$this->pt_subject);
			$this->unique_q = $this->t_query_filter('pt_unique_id',$this->pt_unique_id);
			$this->moduel_q = $this->t_query_filter('pt_moduel',$this->pt_moduel);
			$this->chapter_q = $this->t_query_filter('pt_chapter',$this->pt_chapter);
		}
	}
	//
	private function list_files_edit($directory,$pt_unique){
		if(!empty($directory)){
			
			$root = $_SERVER['DOCUMENT_ROOT'];
			$directory_f = "$root/$directory/files";
			if(is_dir($directory_f)){
				$files = scandir($directory_f);
				$return = array();
				foreach ($files as $file_n){
					$file = "$directory/files/$file_n";
					$file_real = "$root/$directory/files/$file_n";
					if(is_file($file_real)){
						$return[] = array(
							'name' => $file_n,
						);
					}
				}
				if (!empty ($return)){
					return $return;
				}else{
					return 0;
				}
			}else{
				return 0;
			}
			
		}
	}
	//
	private function gen_link_from_unique_id($pt_unique_id){
		//
		$query = "select pt_subject,pt_moduel,pt_chapter from $this->db_table where pt_unique_id = '$pt_unique_id' limit 1";
		$run = $this->run_query($query);
		//
		$result = mysqli_fetch_assoc($run);
		//
		$subject = $result['pt_subject'];
		$moduel = $result['pt_moduel'];
		$chapter = $result['pt_chapter'];
		//
		$link = "https://practicepractice.net/P/Notes/$subject/$moduel/$chapter";
		return $link;
	}
	//
	private function get_publications(){
		//get published content
		$query_i = "select moduel from publications where 1 and status = 1";
		$run = $this->run_query($query_i);
		//
		$pubs = array();
		while($result = mysqli_fetch_assoc($run)){
			//
			$pub = $result['moduel'];
			$pubs[] = "'$pub'";
		}
		//
		$q = implode(',',$pubs);
		return $q;
	}
	
	
	
	//********text processors
	//
	private function highlight_keywords($keywords,$discription){
		//
		
	}
	//
	private function process_keywords($words){
		//
		$arr = array();
		if(isset($words) and !empty($words)){
			foreach($words as $key => $value){
				$arr[$key]['word'] = strip_tags ($value['my_keyword']);
				$arr[$key]['ref'] = $value['ref'];
			}
		}
		return $arr;
	}	
	//
	private function process_objectives($objectives){
		//
		$arr = array();
		if(isset($objectives) and !empty($objectives)){
			foreach($objectives as $key => $value){
				$arr[] = strip_tags ($value['my_objectives']);
			}
		}
		return $arr;
	}
	//
	private function my_img_extractor($pt_directory,$discription,$class,$utility,$dom){
		//*******to get the spec point title
		$output_images = array();//the storage array for all the images present in the description
		//finding the my_images sections and extracting their information for processing
		//***edit
		$tag_type = 'my_img';
		$count = 1;
		$updated = $discription;
		foreach ($dom->getElementsByTagName($tag_type) as $item) {
			//******edit 
			$output_images[] = array (
				'info' => $item->getAttribute('info'),
				'name' => $item->getAttribute('name'),
				'style' => $item->getAttribute('style'),
				'local_id' => $count,
			);
			$count += 1;
		};
		//for the images
		foreach($output_images as $arr){
			//*****edit
			$value = $arr['name'];
			$desc = $arr['info'];
			$style = $arr['style'];
			$local_id = $arr['local_id'];
			
			
			//locating the nth custom element  using the local id
			$tag_type_start = "<$tag_type ";
			$tag_type_end = "</$tag_type>";
			$nth_element_start = $utility->loacte_html_element($discription,$tag_type_start,$local_id);
			$nth_element_end = $utility->loacte_html_element($discription,$tag_type_end,$local_id) + strlen($tag_type_end);
			//gets the whole html element
			$old = substr(	$discription,$nth_element_start,strlen(substr($discription,0,$nth_element_end)) - strlen(substr($discription,0,$nth_element_start))	);
			//
			$src = 'https://practicepractice.net'.$pt_directory."/files/$value";
			//********edit
			$new = 
"<br><br><div class='image_container' style='$style'>
	<img class = '$class-img specification_photos' src = '$src' alt='$desc' /><br>
	<p class='specification_photos_desc'>$desc</p>
</div><br><br>
"; 
		 $updated = str_replace($old,$new,$updated);
		}
		return $updated;
	}
	//this takes the discription written by us and extracts the informatio in the made up html tags
	private function extract_discription_hidden($hidden_discription_section){
		// my tags: hidden, video_section,vid,vid_title,vid_link
		//the aim of the code is to remove the madeup html markup and extract the real information contained within
		//Preprocessing
		if(empty($hidden_discription_section)){
			$hidden_discription_section = ' ';
		}
		//
		$hidden_discription_section = $this->html_stray_delimiter($hidden_discription_section);
		//
		$dom = new \DomDocument();
		libxml_use_internal_errors(true);
		$dom->loadHTML($hidden_discription_section);
		libxml_use_internal_errors(true);
		
		
		
		//*******to get videos
		$output_videos = array();//the storage array for each video
		//look for each video element and extract the title and link from the attributes
		foreach ($dom->getElementsByTagName('my_vid') as $item) {
			$video_id = $item->getAttribute('vid_link');
			$video_id = explode('/', $video_id);
			if (isset($video_id[4])){
				$video_id = $video_id[4];
			}else{
				$video_id = 0;
			}
			$output_videos[] = array (
				'title' => $item->getAttribute('vid_title'),
				'link' => $item->getAttribute('vid_link'),
				'vid_id' =>$video_id 
			);
		};
		//
		//*******to get the spec point title
		$output_title = array();//the storage array for the point title
		//loop through the point titles (the is only one though) and store the values
		foreach ($dom->getElementsByTagName('point_title') as $item) {
			$output_title[] = array (
			   'point_title' => $dom->saveHTML($item),
				'point_level' => $item->getAttribute('level'),
				'pt_board' => $item->getAttribute('pt_board'),
			);
		};
		
		//
		//*******to get any keywords 
		$output_objective= array();//the storage array for the point title
		//loop through the point titles (the is only one though) and store the values
		foreach ($dom->getElementsByTagName('my_objectives') as $item) {
			$output_objective[] = array (
			   'my_objectives' => $dom->saveHTML($item)
			);
		};
		//
		//*******to get any keywords 
		$output_keywords= array();//the storage array for the point title
		//loop through the point titles (the is only one though) and store the values
		foreach ($dom->getElementsByTagName('my_keyword') as $item) {
			$output_keywords[] = array (
			   'my_keyword' => $dom->saveHTML($item),
			   'ref' => $item->getAttribute('ref')
			);
		};
		//
		//*******to get any keywords 
		$output_specpoint= array();//the storage array for the point title
		//loop through the point titles (the is only one though) and store the values
		foreach ($dom->getElementsByTagName('my_specpoint') as $item) {
			$output_specpoint[] = array (
			   'my_specpoint' => $dom->saveHTML($item)
			);
		};
		//
		//*******to get any keywords 
		$output_specpointnote= array();//the storage array for the point title
		//loop through the point titles (the is only one though) and store the values
		foreach ($dom->getElementsByTagName('my_note') as $item) {
			$output_specpointnote[] = array (
			   'my_note' => $dom->saveHTML($item)
			);
		};
		//
		
		
		
		
		
		
		//return the full details in the hidden description
		return array(
			'videos' => $output_videos,
			'title' => $output_title,
			'keywords' => $output_keywords,
			'objectives' => $output_objective,
			'raw_specpoint' => $output_specpoint,
			'raw_specpoint_note' => $output_specpointnote,
		);
	}
	//this takes the discription written by us and extracts the informatio in the made up html tags, edits them how we like and returns them in their correct
	//position in the text, allows us to apply effects how we like to the text
	private function extract_discription_normal($normal_discription,$pt_directory,$utility){
		
		// my tags: more_info
		//the aim of the code is to remove the madeup html markup and extract the real information contained within
		$updated_desc = '';
		if(empty($normal_discription)){
			$normal_discription = ' ' ;
		}
		$normal_discription = $this->html_stray_delimiter($normal_discription);
		//
		$dom = new \DomDocument();
		libxml_use_internal_errors(true);
		$dom->loadHTML($normal_discription);
		libxml_use_internal_errors(true);
		//creating the class to connect all the items created by the following code
		$class = $utility->tag_generator();
		//
		$updated_ = $this-> my_img_extractor($pt_directory,$normal_discription,$class,$utility,$dom);
		return array($updated_,$class);
	}
	//
	private function html_stray_delimiter($html){
		//
		$html = str_replace('< ', ' &lt; ', $html);
		$html = str_replace(' >', ' &gt; ', $html);
		//
		return $html;
	}
	
	
	
	//*********controller moduel chapter and specific point search
	private function first_loop($run,$customtable){
		//
		$return = array();
		//global keywords for keyword density gradient strategy
		$this->glob_keys = array();
		//
		while($topics = mysqli_fetch_assoc($run)){
			//
			$this->manual_construct($topics);
			//
			$topic_disp = str_replace('_',' ',$this->pt_topic);
			$topic_disp = substr($topic_disp, 3);
			$topics['pt_topic_disp'] = ucfirst($topic_disp);
			//
			$topics['pt_topic_num']  = substr($this->pt_spec_id,strpos($this->pt_spec_id,'.')+1,-strpos($this->pt_spec_id,'.')-1);
			//
			$return[$this->pt_topic]['topic_info']= $topics;
			//
			$this->topic_r = $this->t_query_filter('pt_topic', $this->pt_topic);
			$this->chapter_r = $this->t_query_filter('pt_chapter', $this->pt_chapter);
			//
			$x_points = "select pt_id,pt_topic, LEFT(pt_spec_id,INSTR(pt_spec_id,'.')+2) as fil ,SUBSTRING_INDEX(pt_spec_id,'.',2) as fil_2 from $customtable where 1 $this->subject_q $this->unique_q $this->topic_r $this->chapter_r group by fil order by (fil+1)";
			//
			$run_2 = $this->run_query($x_points);
			//
			$points = $this->second_loop($run_2,$customtable);
			//
			$return[$this->pt_topic]['points']= $points;
		}
		//adding keywords to keyword table
		foreach($this->glob_keys as $kw_arr){
			#
			$kw = $kw_arr['word'];
			if(!empty($kw)){
				$kw= trim($kw); 
				$check_query = "select id from keywords where word = '$kw'";
				$run = $this->run_query($check_query);
				$count = mysqli_num_rows($run);
				if($count == 0){
					$query_insert = "insert into keywords (`word`) values ('$kw')";
					$this->run_query($query_insert);
				}
			}
			
		}
		foreach($return as $key => $arr){
			foreach($arr['points'] as $key_2 => $arr_2 ){
				
				foreach($arr_2 as $key_3 => $arr_3){
					if(is_int($key_3)){
						//highlighting keywords
						$str = $arr_3['pt_discription'];
						$updated = $arr_3['pt_discription'];
						$query = "select word from keywords where 1";
						$run =$this->run_query($query);
						$count = mysqli_num_rows($run);
						if($count > 0){
							while($result = mysqli_fetch_assoc($run)){
								$keyword = $result['word'];
								if(!empty($keyword)){
									$updated = preg_replace("/ ($keyword) (?!([^<]+)?>)/i","<strong class='keyword'> $1 </strong>",$updated);
									$updated = $this-> filter_inside_mathjax($updated);
									#$re = '@ (?:\${2}[^\${2}]*\${2})(*SKIP)(*F)|'.$keyword.'@'; 
									#$updated = preg_replace("$re"," <strong class='keyword'> $keyword </strong> ",$updated);
									#$updated = preg_replace("(?!\${1,2})<strong class='keyword'>(?!\${1,2})",' ',$updated);
									#$updated = preg_replace("(?!\${1,2})</strong>(?!\${1,2})",' ',$updated);
								}
								
							}
						}
						/*
						if(isset($this->§)){
							foreach($this->glob_keys as $key_4=>$value){
								$word = $value['word'];
								$word = str_replace(' ', '', $word);
								$ref = $value['ref'];
								//
								if(!empty($word)){
									if(!empty($ref)){
										$link = $this->gen_link_from_unique_id($ref);
										$updated = preg_replace("/ ($word) (?!([^<]+)?>)/i"," <strong class='keyword $ref'><a href='$link' class='internal_link'> $1 </a></strong> ",$updated);
									}else{
										$updated = preg_replace("/ ($word) (?!([^<]+)?>)/i"," <strong class='keyword $ref'> $1 </strong> ",$updated);
									}
								}
							}
						}
						*/
						$return[$key]['points'][$key_2][$key_3]['pt_discription'] = $updated;
					}
				}
			}
		}
		
		//
		return $return;
	}
	//takes the pt_description input that has applied keywords and removes the html tags inside of any $$-$$ or \(-\)
	private function filter_inside_mathjax($str){
		//takes string and output processed string
		
		//1 explode string by $$
		$str_arr = explode('$$',$str);
		//strip html for every 2nd string inside array
		$c = 1;
		foreach($str_arr as $key=>$val){
			if($c % 2 == 0){
				$val = strip_tags($val);
				$str_arr[$key] = $val;
			}
			$c += 1;
		}
		//implode array to str
		$str = implode('$$',$str_arr);
		//1 explode string by \(
		$str_arr = explode('\(',$str);
		//explode str by \)
		foreach($str_arr as $key=>$val){
			if($key > 0){
				$str_arr_2 = explode('\)', $val);
				$val2 = strip_tags($str_arr_2[0]);
				$str_arr_2[0] = $val2;
				$final = implode('\)',$str_arr_2);	
				$str_arr[$key] = $final;
			}
		}


		//implode big array to get str
		$str = implode('\(',$str_arr);
		//return str
		return $str;
	}
	//
	private function second_loop($run_2,$customtable){
		$points_arr = array();
		while ($points = mysqli_fetch_assoc($run_2)){
			//
			$get_chapter = $points['fil'];
			//
			$this->get_chapter_q ="and pt_spec_id like '$get_chapter%'";
			//get all of the points in the topic
			$y_points = "select *,SUBSTRING_INDEX(SUBSTRING_INDEX(pt_spec_id,'.',-2),'.',1) as pt_chapter_number from $customtable where 1 $this->subject_q $this->unique_q $this->chapter_r $this->moduel_q $this->get_chapter_q  order by pt_number";
			//
			$run_3 = $this->run_query($y_points);
			//get all the topics
			$final_arr = $this->third_loop($run_3);
			//echo $this->t_sixth_out();
			$points_arr[] = $final_arr;
		}
		return $points_arr;
	}
	//
	private function third_loop($run_3){
		$return = array();
		//getting all the learning objectives
		while($pointss = mysqli_fetch_assoc($run_3)){
			//
			$discription_all = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/'.$pointss['pt_link']);
			$mid_discription = substr($discription_all,strpos($discription_all,'<hidden>'),strpos($discription_all,"</hidden>")-1);
			$in_hiddendiscription_items = $this->extract_discription_hidden($mid_discription);
			$this->objectives = $in_hiddendiscription_items['objectives'];
			$pointss['objectives'] = $this-> process_objectives($this->objectives);
			//
			foreach($pointss['objectives'] as $key => $value){
				$return['objectives'][] = $value; 
			}
			
			
			
			
			
			//
			$discription_all = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/'.$pointss['pt_link']);
			$mid_discription = substr($discription_all,strpos($discription_all,'<hidden>'),strpos($discription_all,"</hidden>")-1);
			$in_hiddendiscription_items = $this->extract_discription_hidden($mid_discription);
			$this->keywords = $in_hiddendiscription_items['keywords'];
			//getting a tagless list of keywords
			$pointss['pt_keywords'] = $this-> process_keywords($this->keywords);
			//
			foreach($pointss['pt_keywords'] as $key => $value){
				$return['keywords'][] = $value['word'];
				$this->glob_keys[] = $value;
			}
		}
		//
		mysqli_data_seek ($run_3,0);
		//
		while($pointss = mysqli_fetch_assoc($run_3)){
			//
			$discription_all = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/'.$pointss['pt_link']);
			$pt_directory = $pointss['pt_directory'];
			 //
			$mid_discription = substr($discription_all,strpos($discription_all,'<hidden>'),strpos($discription_all,"</hidden>")-1);
			//
			$in_hiddendiscription_items = $this->extract_discription_hidden($mid_discription);
			//
			$this->short_discription = $in_hiddendiscription_items['title'][0]['point_title'];
			$this->point_level = $in_hiddendiscription_items['title'][0]['point_level'];
			$this->pt_board = $in_hiddendiscription_items['title'][0]['pt_board'];
			//$this->point_level = $in_hiddendiscription_items['level']['point_level'];
			$this->videos = $in_hiddendiscription_items['videos'];
			$this->discription_raw = substr($discription_all,strpos($discription_all,'</hidden>')+9, strlen($discription_all));
			$processed_discription = $this->extract_discription_normal($this->discription_raw,$pt_directory,$this->utility);
			//
			$pointss['pt_full_discription'] = $discription_all;
			$pointss['pt_short_discription'] = strip_tags ($this->short_discription);
			$pointss['pt_level'] = $this->point_level;
			$pointss['pt_board'] = $this->pt_board;
			$pointss['pt_discription'] = $processed_discription[0];
			$pointss['pt_videos'] = $this->videos;
			$pointss['pt_files_list'] = $this-> list_files_edit($pointss['pt_directory'],$pointss['pt_unique_id']);
			
			//
			$topic_disp = str_replace('_',' ',$pointss['pt_topic']);
			$topic_disp = substr($topic_disp, 3);
			$pointss['pt_topic_disp'] = ucfirst($topic_disp);
			
			$return[] = $pointss;
			if(isset($_SESSION['u_admin'])){
				foreach($this->videos as $vid){
					$id = $vid['vid_id'];
					if($id !== 0){
						$check = $this->utility->YouTube_Check_ID($id);
						if ($check==1)
						{
							echo "1";
						}
						else
						{
							echo "0";
						}
					}
				}
			}
			

		}
		return $return;
	}
	//public controller
	public function Get_specpage_moduel_chapter_input($variables,$filehandle,$utility,$usercontr){
		$root = $_SERVER['DOCUMENT_ROOT'];
		$this->utility = $utility;
		//setup
		if(isset($variables['editor_unique_id']) or isset($variables['admin_review'])){
			if(isset($variables['editor_unique_id'])){
				$unique = $variables['editor_unique_id'];
			}elseif(isset($variables['admin_review'])){
				$unique = $variables['admin_review'];
			}
			//
			$this->db_table = ' points_editors ';
			$this->info_sr = "users/$unique/specifications";
		}else{
			$this->db_table = ' points ';
		}
		$this->manual_construct($variables);
		$this->validate_specpage_entry();
		$this->filterQuery_specpage_entry();
		//if the pt_unique_id exists run the uniuqe id constructor
		if(isset($variables['pt_unique_id'])){
			$this->manual_construct_unique_id($variables['pt_unique_id']);
			$link = "$this->root/$this->info_src/universal/A_$this->pt_subject/B_$this->pt_moduel/C_$this->pt_chapter/files/pt_raw_information.txt";
		}else{
			//extracting the moduel's directory and other userful information
			$link = "$this->root/$this->info_src/universal/A_$this->pt_subject/B_$this->pt_moduel/C_$this->pt_chapter/files/pt_raw_information.txt";
		}
		//
		if(is_file($link)){
			$raw_point_inforomation = file_get_contents($link);
		}else{
			$raw_point_inforomation = '';
		}
		//
		$extracted = $this->extract_discription_hidden($raw_point_inforomation);
		$raw_point_inforomation_note = $extracted['raw_specpoint_note'];
		$raw_point_inforomation_specpoint = $extracted['raw_specpoint'];
		//Filter for the user's level
		$user_level = $usercontr -> user_level;
		//setting up user filter query
		if($user_level ==''){
			//
			$customtable = $this->db_table;
		}elseif($user_level == 'AS'){
			//
			$customtable ="(select * from $this->db_table where pt_level ='AS') as A";
		}else{
			if($user_level =='A'){
				//
				//$customtable ="(select * from $this->db_table where pt_level in ('AS','A') )as A";
				$customtable =$this->db_table;
			}else{
				//
				$customtable = $this->db_table;
			}
		}
		//get all 
		$query =  "select pt_moduel,pt_chapter,pt_topic,pt_spec_id,pt_unique_id,SUBSTRING_INDEX(pt_spec_id,'.',2) as fil from $customtable where 1 $this->subject_q $this->unique_q $this->moduel_q $this->chapter_q group by fil order by LENGTH(pt_spec_id),pt_spec_id asc";
		//echo $query;
		//
		$run = $this->run_query($query);
		//extracting the moduel's directory and other userful information
		$getdir_query = "select pt_directory,pt_spec_id,pt_unique_id from $customtable where 1 $this->subject_q $this->unique_q $this->moduel_q $this->chapter_q limit 1";
		$run_2 = $this->run_query($getdir_query);
		$assoc =  mysqli_fetch_assoc($run_2);
		
		//naming userful view variables 
		$spec_id = $assoc['pt_spec_id'];
		$chapter_num = substr($spec_id,0,strpos($spec_id,'.'));
		//
		$dir= $assoc['pt_spec_id'];
		$moduel_dir = substr($dir,0,strpos($dir,"/C_$this->pt_chapter"));
		//
		$chapter_unique_id = $assoc['pt_unique_id'];
		
		//
		$chapter_disp = str_replace('_',' ',$this->pt_chapter);
		$chapter_disp = substr($chapter_disp, 3);
		$chapter_disp = ucfirst($chapter_disp);
		//
		$moduel_disp = str_replace('_',' ',$this->pt_moduel);
		$moduel_disp = substr($moduel_disp, 3);
		$moduel_disp = ucfirst($moduel_disp);
		//get all the data structure
		$return['nav_displayinfo'] =  array(
		
		'pt_moduel_disp' => $moduel_disp,
		'pt_chapter_disp' => $chapter_disp,
		
		);
		$return['nav_generalinfo'] =  array(
		'pt_subject' => $this->pt_subject,
		'pt_moduel' => $this->pt_moduel,
		'pt_chapter' => $this->pt_chapter,
		'pt_raw_information' => $raw_point_inforomation,
		'pt_raw_information_note' => $raw_point_inforomation_note,
		'pt_raw_information_specpoint' => $raw_point_inforomation_specpoint,
		'pt_chapter_num' => $chapter_num,
		'pt_unique_id' => $this->pt_unique_id,
		'pt_moduel_directory' => $moduel_dir,
		'pt_chapter_unqiue_id' => $chapter_unique_id,
		'pt_rename_pointer' => $this->utility->tag_generator(),
		
		);
		$return['table_info'] =  $this->first_loop($run,$customtable);
		//
		return $return;
	}
	//*********controller user list
	private function first_loop_userlist($run){
		//echo $wrap;
		$return = array();
		while($row = mysqli_fetch_assoc($run)){
			//
			$pt_subject = $row['pt_subject'];
			$pt_moduel = $row['pt_moduel'];
			$pt_chapter = $row['pt_chapter'];
			$pt_topic = $row['pt_topic'];
			$pt_directory = $row['pt_directory'];
			//
			$discription_all = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/'.$row['pt_link']);
			 //
			$mid_discription = substr($discription_all,strpos($discription_all,'<hidden>'),strpos($discription_all,"</hidden>")-1);
			//
			$in_hiddendiscription_items = $this->extract_discription_hidden($mid_discription);
			//
			$this->short_discription = $in_hiddendiscription_items['title'][0]['point_title'];
			$this->videos = $in_hiddendiscription_items['videos'];
			$this->discription_raw = substr($discription_all,strpos($discription_all,'</hidden>')+9, strlen($discription_all));
			$processed_discription = $this->extract_discription_normal($this->discription_raw,$pt_directory,$this->utility);
			//
			$moduel_disp = str_replace('_',' ',$row['pt_moduel']);
			$moduel_disp = substr($moduel_disp, 3);
			$pt_moduel = ucfirst($moduel_disp);
			//
			$chapter_disp = str_replace('_',' ',$row['pt_chapter']);
			$chapter_disp = substr($chapter_disp, 3);
			$pt_chapter = ucfirst($chapter_disp);
			//
			$topic_disp = str_replace('_',' ',$row['pt_topic']);
			$topic_disp = substr($topic_disp, 3);
			$pt_topic = ucfirst($topic_disp);
			//
			$row['pt_full_discription'] = $discription_all;
			$row['pt_topic_num'] = substr($row['pt_spec_id'],strpos($row['pt_spec_id'],'.')+1,-strpos($row['pt_spec_id'],'.')-1);;
			$row['pt_short_discription'] = strip_tags ($this->short_discription);
			$row['pt_discription'] = $processed_discription[0];
			$row['pt_videos'] = $this->videos;
			$row['pt_files_list'] = $this-> list_files_edit($row['pt_directory'],$row['pt_unique_id']);
			
			//
			$return [$pt_subject][$pt_moduel][$pt_chapter][$pt_topic]['points'][]= $row;
			
			
			//
		}
		return $return;
	}
	//public controller
	public function Get_specpage_userlist($variables,$filehandle,$utility,$usercontr){
		$this-> utility = $utility;
		$this->usercontr = $usercontr;
		$this-> html_cache = '';
		//
		$this->manual_construct($variables);
		//
		$this->validate_specpage_entry();
		//
		$this->filterQuery_specpage_entry();
		//get all 
		$non_complete_query = $this->usercontr->get_mylist_as_query_specpage();
		$query = "select *, SUBSTRING_INDEX(pt_spec_id,'.',2) as fil from $this->db_table where 1 $non_complete_query order by pt_subject asc,pt_moduel asc,pt_chapter asc,pt_topic asc,fil asc";
		//
		$run = $this->run_query($query);
		//get all the topics
		$return = $this->first_loop_userlist($run);
		return $return;
		
	}
	//
	public function get_active_tasks($subject,$usercontr){
		$query = "select * from active_tasks where pt_subject = '$subject'";
		$run = $this->run_query($query);
		//
		$return = array();
		//
		while($result = mysqli_fetch_assoc($run)){
			$editor = $result['user_unique_id']; 
			
			if(!empty($editor)){
				$userobj = $usercontr->make_obj_unique_id($editor);
				$result['bnkak_status'] = $userobj['bnkak_id_verification'];
				if($userobj['bnkak_id_verification']){
					$result['bnkak_number'] = $userobj['that_other_id'];
				}
			}
			$return[] = $result;
		}
		//
		return $return;
	}
	//
	
	//generate site map
	public function make_sitemap(){
		$q = $this->get_publications();
		//
		if(!empty($q)){
			$query = "select pt_subject,pt_moduel,pt_chapter from points where 1 and pt_moduel in ($q)  group by pt_moduel,pt_chapter order by pt_subject,pt_moduel,pt_chapter ASC ";
		}else{
			die('no publications');
		}
		$run = $this->run_query($query);
		//
		$return = array();
		//
		while($result = mysqli_fetch_assoc($run)){
			//
			$subject = $result['pt_subject'];
			$moduel = $result['pt_moduel'];
			$chapter = $result['pt_chapter'];
			//
			$return[] = array(
			'pt_subject' => $subject,
			'pt_moduel' => $moduel,
			'pt_chapter' => $chapter
			);
		}
		return $return;
	}
	//generate site map
	public function make_sitemap_questions_chapter(){
		$q = $this->get_publications();
		//
		$query = "select pt_subject,pt_moduel,pt_chapter,pt_unique_id from points where 1 and pt_moduel in ($q)  group by pt_moduel,pt_chapter order by pt_subject,pt_moduel,pt_chapter ASC ";
		$run = $this->run_query($query);
		//
		$return = array();
		//
		while($result = mysqli_fetch_assoc($run)){
			//
			$subject = $result['pt_subject'];
			$moduel = $result['pt_moduel'];
			$chapter = $result['pt_chapter'];
			$pt_unique_id = $result['pt_unique_id'];
			//filtering out the empty question pages
			$link = "$this->root/specifications/universal/A_$subject/B_$moduel/C_$chapter/questions";
			//this if statement makes sure that question pages with questions made are the only ones that are indexed
			//had to remove it so that the no index thing is read and so the 900 pages are cut down
			//if(is_dir($link)){
				$return[] = array(
				'pt_subject' => $subject,
				'pt_moduel' => $moduel,
				'pt_chapter' => $chapter,
				'pt_unique_id' => $pt_unique_id
				);
			//}
			
		}
		
		return $return;
	}
	//generate site map
	public function make_sitemap_questions_topic(){
		$q = $this->get_publications();
		//
		$query = "select pt_subject,pt_moduel,pt_chapter,pt_topic,pt_unique_id from points where 1 and pt_moduel in ($q) group by pt_chapter,pt_topic order by pt_subject,pt_moduel,pt_chapter,pt_topic ASC ";
		$run = $this->run_query($query);
		//
		$return = array();
		//
		while($result = mysqli_fetch_assoc($run)){
			//
			$subject = $result['pt_subject'];
			$moduel = $result['pt_moduel'];
			$chapter = $result['pt_chapter'];
			$topic = $result['pt_topic'];
			$pt_unique_id = $result['pt_unique_id'];
			//filtering out the empty question pages
			$link = "$this->root/specifications/universal/A_$subject/B_$moduel/C_$chapter/D_$topic/questions";
			
			
			//if(is_dir($link)){
				$return[] = array(
				'pt_subject' => $subject,
				'pt_moduel' => $moduel,
				'pt_chapter' => $chapter,
				'pt_topic' => $topic,
				'pt_unique_id' => $pt_unique_id
				);
			//}
		}
		return $return;
	}
	//fetch cache from cache folder
	public function fetch_cache($cache_name){
		//
		$content = file_get_contents($cache_name);
		return $content;
	}
	//
	public function make_page_title($input){
		//
		if(isset($input['pt_subject']) and isset($input['pt_moduel']) and isset($input['pt_chapter'])){
			$pt_subject = $input['pt_subject'];
			$moduel = $input['pt_moduel'];
			$chapter = $input['pt_chapter'];
			//
			$mod_disp = str_replace('_',' ',$moduel);
			$mod_disp = substr($mod_disp, 3);
			$pt_moduel = ucfirst($mod_disp);
			//
			$chapter_disp = str_replace('_',' ',$chapter);
			$chapter_disp = substr($chapter_disp, 3);
			$pt_chapter = ucfirst($chapter_disp);
			//
			return "$pt_subject - $pt_moduel - $pt_chapter";
		}else{
			return null;
		}
		
	}
	//
	public function make_page_meta_spec($input){
		//
		if(isset($input['pt_subject']) and isset($input['pt_moduel']) and isset($input['pt_chapter'])){
			$pt_subject = $input['pt_subject'];
			$moduel = $input['pt_moduel'];
			$chapter = $input['pt_chapter'];
			//
			$mod_disp = str_replace('_',' ',$moduel);
			$mod_disp = substr($mod_disp, 3);
			$pt_moduel = ucfirst($mod_disp);
			//
			$chapter_disp = str_replace('_',' ',$chapter);
			$chapter_disp = substr($chapter_disp, 3);
			$pt_chapter = ucfirst($chapter_disp);
			//
			return "A level $pt_subject, discussing $pt_moduel, $pt_chapter. Includes notes, examples, past paper questions for Edexcel,AQA and OCR and other questions and answers. ";
		}else{
			return null;
		}
		
	}
	//
	public function get_next_and_previous_links($input,$usercontr){
		//
		$subject = $input['pt_subject'];
		$moduel = $input['pt_moduel'];
		$chapter = $input['pt_chapter'];
		$level = $usercontr -> user_level;
		//
		if($level == 'AS'){
			$level_clause =" and pt_level = '$level' ";
		}else{
			$level_clause = ' ';
		}
		//
		$query = "SELECT pt_moduel,pt_chapter FROM $this->db_table WHERE 1 and pt_subject = '$subject' $level_clause group by pt_moduel,pt_chapter order by pt_moduel asc,pt_chapter asc";
		$run = $this->run_query($query);
		//
		$vault = array();
		while($result = mysqli_fetch_assoc($run)){
			$vault[] = $result;
		}
		//
		$num = count($vault);
		$ping = array_search(array('pt_moduel' => $moduel, 'pt_chapter' => $chapter), $vault);
		//
		$next = $ping + 1;
		$prev = $ping -1;
		//
		if($prev >= 0){
			//
			$info = $vault[$prev];
			//
			$moduel =$info['pt_moduel'];
			$chapter =$info['pt_chapter'];
			//
			$link_prev = "https://www.practicepractice.net/P/Notes/$subject/$moduel/$chapter";
		}else{
			$link_prev = '#';
		}
		//
		if($num - 1 >= $next){
			//
			$info = $vault[$next];
			//
			$moduel =$info['pt_moduel'];
			$chapter =$info['pt_chapter'];
			//
			$link_next = "https://www.practicepractice.net/P/Notes/$subject/$moduel/$chapter";
		}else{
			$link_next = '#';
		}
		//
		return array('next' => $link_next,'prev' => $link_prev);
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}
