<?php
namespace MVC;
//left to figure out how to load the required information into the data base
//left to figur eout how to prefect navigation
//gives the model instructions and information so the model can do what we want
//it insert or updates to the database
class questionview extends question{
	
	//Contr:
	public function question_page_redirect($filter_type,$point_unique,$pointcontr){
		//
		$pointobj = $pointcontr ->make_obj_unique_id($point_unique);
		//
		$topic = $pointobj['pt_chapter'];
		if($filter_type == 'point' or $filter_type == 'topic' or $filter_type == 'chapter'){
			// 
			return array(
				'status' => 1,
				'msg' => "https://practicepractice.net/P/questions/$topic/$filter_type/$point_unique"
			);
		}else{
			//
			return array(
				'status' => 0,
				'msg' => 'Invalid filter'
			);
		}
	}
	//Contr
	private function list_files_edit($directory){
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
	//this takes the discription written by us and extracts the informatio in the made up html tags
	private function extract_discription_info($discription){
		// my tags: hidden, video_section,vid,vid_title,vid_link
		//the aim of the code is to remove the madeup html markup and extract the real information contained within
		if(empty($discription)){
			$discription = ' ';
			
		}else{
			$discription = $this->html_stray_delimiter($discription);
		}
		
		$dom = new \DomDocument();
		libxml_use_internal_errors(true);
		$dom->loadHTML($discription);
		libxml_use_internal_errors(true);
		
		
		
		
		//*******to get question
		$output_question = array();//the storage array for each video
		//look for each video element and extract the title and link from the attributes
		foreach ($dom->getElementsByTagName('my_question') as $item) {
			//
			if($item->getAttribute('part') == 'head'){
				//
				$output_question['head'] = array (
					'part' => $item->getAttribute('part'),
					'my_question' => $dom->saveHTML($item)
					);
			}else{
				//
				$output_question[] = array (
				'part' => $item->getAttribute('part'),
				'part_mark' => $item->getAttribute('part_mark'),
				'my_question' => $dom->saveHTML($item)
				);
			}
			
		};
		//
		
		
		//*******to get head
		$output_head = array();//the storage array for each video
		//look for each video element and extract the title and link from the attributes
		foreach ($dom->getElementsByTagName('my_q_head') as $item) {
			$total_marks = 0;
			foreach($dom->getElementsByTagName('my_question') as $item2){
				$part_mark = $item2->getAttribute('part_mark');
				if(!is_int($part_mark)){
					$part_mark = intval($part_mark);
					if(!is_int($part_mark)){
					$part_mark = 0;
					}
				}
				$total_marks = $total_marks +$part_mark ;
			}
			$output_head[] = array (
			   'q_level' => $item->getAttribute('q_level'),
			   'q_origin' => $item->getAttribute('q_origin'),
			   'q_type' => $item->getAttribute('q_type'),
			   'q_difficulty' => $item->getAttribute('q_difficulty'),
			   'q_total_marks' => $total_marks,
			);
		};
		//
		
	
		//*******to get answer
		$output_answer = array();//the storage array for each video
		//look for each video element and extract the title and link from the attributes
		foreach ($dom->getElementsByTagName('my_answer') as $item) {
			$output_answer[] = array (
				'part' => $item->getAttribute('part'),
				'my_answer' => $dom->saveHTML($item)
			);
		};
		//

		//return the full details in the hidden description
		return array(
			'my_q_head' => $output_head,
			'my_question' => $output_question,
			'my_answer' => $output_answer
		);
	}
	//this takes the discription written by us and extracts the informatio in the made up html tags, edits them how we like and returns them in their correct
	//position in the text, allows us to apply effects how we like to the text
	private function extract_discription_normal($normal_discription,$pt_directory,$utility){
		// my tags: more_info 
		//the aim of the code is to remove the madeup html markup and extract the real information contained within
		$updated_desc = '';
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
				'class' => $item->getAttribute('class'),
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
			$class_2 = $arr['class'];
			
			
			//locating the nth custom element  using the local id
			$tag_type_start = "<$tag_type";
			$tag_type_end = "</$tag_type>";
			$nth_element_start = $utility->loacte_html_element($discription,$tag_type_start,$local_id);
			$nth_element_end = $utility->loacte_html_element($discription,$tag_type_end,$local_id) + strlen($tag_type_end);
			//
			//
			$old = substr(	$discription,$nth_element_start,strlen(substr($discription,0,$nth_element_end)) - strlen(substr($discription,0,$nth_element_start))	);
			//
			$src = 'https://practicepractice.net'.$pt_directory."/files/$value";
			//********edit
			
		if(!empty($desc)){
			$new = 
"
<div class = '$class_2 image_container images_question ' style='$style'>
	<img class = '$class-img specification_photos ' src = '$src' /><br>
	<p class = 'specification_photos_desc'>$desc</p>
</div>
"; 
		}else{
			$new = 
"
<div class = '$class_2 image_container images_question ' style='$style'>
	<img class = '$class-img specification_photos' src = '$src' /><br>
</div>
"; 
		}
		 $updated = str_replace($old,$new,$updated);
		}
		return $updated;
	}
	//
	private function get_marked_qs($subject,$moduel){
		//
		if(isset($_SESSION['user_unique_id'])){
			if(!empty($moduel)){
				$moduel = " and q_moduel ='$moduel' ";
			}else{
				$moduel = ' ';
			}
			//
			$user_unique_id = $_SESSION['user_unique_id'];
			$query = "select q_unique_id,mark,time from (select * from question_track where user_unique='$user_unique_id' and q_subject = '$subject' $moduel order by id DESC) as A group by q_unique_id order by time desc";
			$run = $this->run_query($query);
			//
			$results = array();
			//
			while($result = mysqli_fetch_assoc($run)){
				//
				$q_unique = $result['q_unique_id'];
				unset($result['q_unique_id']);
				$results[$q_unique] = $result;
				//
			}
			//
			if(!empty($results)){
				return $results;
			}else{
				return 0;
			}
		}
		
	}	
	//
	private function get_marked_qs_papers($subject,$q_origin){
		//
		if(isset($_SESSION['user_unique_id'])){
			//
			$user_unique_id = $_SESSION['user_unique_id'];
			$query = "select q_unique_id,mark,time from (select * from question_track where user_unique='$user_unique_id' and q_subject = '$subject' and q_origin = '$q_origin' order by id asc) as A group by q_unique_id order by time desc";
			$run = $this->run_query($query);
			//
			$results = array();
			//
			if($run){
				while($result = mysqli_fetch_assoc($run)){
					//
					$q_unique = $result['q_unique_id'];
					unset($result['q_unique_id']);
					$results[$q_unique] = $result;
					//
				}
				//
				if(!empty($results)){
					return $results;
				}else{
					return 0;
				}
			}else{return 0;}
			
		}
		
	}
	//function to make the pagination old function pre mvc 
	public function make_pagintation($query,$n_rows,$limit,$current){
		//get pages
		$available_pages = ceil($n_rows/$limit);
		//correction of location 
		$return = '';
		$return .= "<ul id = 'question_nav'>";
		//first page access
		$query['page'] = 1;
		$query_result = implode('/', $query);
		$return .= "<li> <a href='https://www.practicepractice.net/P/customquestions/$query_result'> First </a> - </li>";
		//middle ground access
		for ($i =$current-1;$i <= $current+1;$i++){
			if ($i < 1){
				continue;
			}elseif($i > $available_pages){
				continue;
			}
			$query['page'] = $i;
			$query_result = implode('/', $query);
			if ($i == $current){
				$return .= "<li> <a style = 'color:black;' href='https://www.practicepractice.net/P/customquestions/$query_result'> $i  </a> -  </li>";
			}else{
				$return .= "<li> <a href='https://www.practicepractice.net/P/customquestions/$query_result'> $i </a> - </li>";
			}
		}
		//last page access
		$query['page'] = $available_pages ;
		$query_result = implode('/', $query);
		$return .= "<li> <a href='https://www.practicepractice.net/P/customquestions/$query_result'> Last </a> </li>";
		$return .= "</ul>";
		
		return $return;
	}
	//
	private function html_stray_delimiter($html){
		//
		$html = str_replace('< ', ' &lt; ', $html);
		$html = str_replace(' >', ' &gt; ', $html);
		//
		return $html;
	}
	//generate site map
	public function make_sitemap_past_papers(){
		//
		$query = "select q_subject,q_origin from $this->db_table where q_is_exam = 1 group by q_origin";
		$run = $this->run_query($query);
		//
		$return = array();
		//
		while($result = mysqli_fetch_assoc($run)){
			//
			$q_subject = $result['q_subject'];
			$q_origin = $result['q_origin'];
			$return[] = array(
			'q_subject' => $q_subject,
			'q_origin' => $q_origin
			);
		}
		return $return;
	}
	//
	public function make_pastpaper_meta($info){
		//
		$subject = $info['q_subject'];
		$origin = $info['q_origin'];
		//
		$arr = explode('-',$origin);
		$level = $arr[0];
		if($level == 'A'){
			$level = 'A Level';
		}else{
			$level = 'AS Level';
		}
		$board = $arr[1];
		$moduel = $arr[2];
		$month = $arr[3];
		$year = $arr[4];
		//
		$meta = "$board $level $subject $moduel $month/20$year  Past Paper with answers and supporting content";
		return $meta;
	}
	
	
	
	//*********controller moduel chapter and specific point search
	//Contr: Fetches information from the questions table
	public function Get_questions($filter,$point_obj,$variables,$usercontr,$filehandle,$utility){
		//determining the order by clause
		$user_status = $usercontr->user_status;
		if(isset($_SESSION['user_membership'])){
			$member = $_SESSION['user_membership'];
		}else{
			$member = 0;
		}
		//
		if($user_status <= 1 or $user_status ==4){
			$orderby = "order by q_id desc limit 50";
		}else{
			$orderby = "order by q_difficulty asc limit 50";
		}
		//
		//only show exam questions to admin
		//if($filter != 'chapter' and !isset($_SESSION['u_admin'])){
		if(!isset($_SESSION['u_admin']) and !isset($_SESSION['u_editor']) and !isset($_SESSION['write_perm'])){
			$exclude_exam = "and q_is_exam = 0";
			$origin_qry = "and q_origin = 'pp_chq'";
		}else{
			$exclude_exam = "";
			$origin_qry = '';
		}
		//setup
		if(isset($variables['editor_unique_id']) or isset($variables['admin_review'])){
			if(isset($variables['editor_unique_id'])){
				$unique = $variables['editor_unique_id'];
			}elseif(isset($variables['admin_review'])){
				$unique = $variables['admin_review'];
			}
			//
			$this->db_table = ' questions_editors ';
			$this->info_sr = "users/$unique/specifications";
		}else{
			$this->db_table = ' questions ';
		}
		$exclude_exam = "and q_is_exam = 0";

		//
		$q_subject = $point_obj['pt_subject'];
		$q_moduel = $point_obj['pt_moduel'];
		$q_chapter = $point_obj['pt_chapter'];
		$q_topic = $point_obj['pt_topic'];
		$q_point = $point_obj['pt_unique_id'];
		$return = array();
		//get the marked questions relavent to this user,subject and moduel
		$user_marked_qs = $this->get_marked_qs($q_subject,$q_moduel);
		//making the query depending on the filter
		if($filter == 'point'){
			$query = "select q_origin,q_link,q_directory,q_unique_id from $this->db_table where q_subject = '$q_subject' and q_moduel='$q_moduel' and q_chapter='$q_chapter' and q_topic='$q_topic' and q_point='$q_point' $exclude_exam $origin_qry $orderby";
			$return['general_info']['filter'] = $filter;  
			$return['general_info']['q_subject'] = $q_subject;  
			$return['general_info']['q_moduel'] = $q_moduel;  
			$return['general_info']['q_chapter'] = $q_chapter;  
			$return['general_info']['q_topic'] = $q_topic;  
			$return['general_info']['q_point'] = $q_point;
			//
			$return['disp_info']['chapter'] = $utility -> make_display_header($q_chapter);
			$return['disp_info']['topic'] = $utility -> make_display_header($q_topic);
			$return['disp_info']['point'] = $q_point;
		}elseif($filter == 'topic'){
			$query = "select q_origin,q_link,q_directory,q_unique_id from $this->db_table where q_subject = '$q_subject' and q_moduel='$q_moduel' and q_chapter='$q_chapter' and q_topic='$q_topic' and q_point='' $exclude_exam $origin_qry $orderby";
			$return['general_info']['filter'] = $filter;  
			$return['general_info']['q_subject'] = $q_subject;  
			$return['general_info']['q_moduel'] = $q_moduel;  
			$return['general_info']['q_chapter'] = $q_chapter;  
			$return['general_info']['q_topic'] = $q_topic;
			//
			$return['disp_info']['chapter'] = $utility -> make_display_header($q_chapter);
			$return['disp_info']['topic'] = $utility -> make_display_header($q_topic);
		}else{
			if($filter == 'chapter'){
				//$query = "select q_origin,q_link,q_directory,q_unique_id from $this->db_table where q_subject = '$q_subject' and q_moduel='$q_moduel' and q_chapter='$q_chapter' and q_topic='' and q_point='' $exclude_exam $origin_qry $orderby";
				
				$query = "select q_origin,q_link,q_directory,q_unique_id from $this->db_table where q_subject = '$q_subject' and q_moduel='$q_moduel' and q_chapter='$q_chapter' $exclude_exam $origin_qry $orderby";
				$return['general_info']['filter'] = $filter;  
				$return['general_info']['q_subject'] = $q_subject;  
				$return['general_info']['q_moduel'] = $q_moduel;  
				$return['general_info']['q_chapter'] = $q_chapter;  
				//
				$return['disp_info']['chapter'] = $utility -> make_display_header($q_chapter);
			}else{
				return 0;
			}
		}

		//
		$run = $this->run_query($query);
		$count = mysqli_num_rows($run);
		if($count == 0){
			$query = str_replace('and q_is_exam = 0 ', ' ', $query);
			$run = $this->run_query($query);
			$count = mysqli_num_rows($run);
		}

		//counting the number of questions available
		if($count > 0 ){
			//looping though all the questions
			$i = 0;
			while($result = mysqli_fetch_assoc($run)){
				//
				$q_origin = $result['q_origin'];
				$link = $result['q_link'];
				$dir = $result['q_directory'];
				$unique = $result['q_unique_id'];
				//open the file and read the content 
				if(is_file($this->root.'/'.$link)){
					$raw_question_desc = file_get_contents("$this->root/$link");
				}else{
					$raw_question_desc = '';
				}
				//
				$in_hiddendiscription_items = $this->extract_discription_info($raw_question_desc);
				$head = $in_hiddendiscription_items['my_q_head'];
				$question = $in_hiddendiscription_items['my_question'];
				//
				$question_arr = array();
				foreach($question as $key => $part){
					if($key == 'head'){
						$processed = $this->extract_discription_normal($part['my_question'],$dir,$utility)[0];
						$question[$key]['my_question'] = $processed;
					}else{
						$processed = $this->extract_discription_normal($part['my_question'],$dir,$utility)[0];
						$question[$key]['my_question'] = $processed;
					}
					
				}
				//
				$answer = $in_hiddendiscription_items['my_answer'];
				//
				foreach($answer as $key => $part){
					//
					$processed = $this->extract_discription_normal($part['my_answer'],$dir,$utility)[0];
					$answer[$key]['my_answer'] = $processed;
				}
				//
				$files_list = $this->list_files_edit($dir);
				//checking if the question has been marked before
				if($user_marked_qs != 0){
					if(array_key_exists ( $unique, $user_marked_qs)){
						$user_marked_qs[$unique]['time'] = $utility -> history_time_calculator($user_marked_qs[$unique]['time']);
						$marking_info = $user_marked_qs[$unique];
					}else{
						$marking_info = '';
					}
				}else{
					$marking_info = '';
				}
				
				//
				$return['Questions'][$i] = array
					(
					'description'=> $raw_question_desc ,
					'head_info'=> $head,
					'question_desc'=> $question,
					'answer_desc'=> $answer,
					'q_files_list'=> $files_list,
					'q_unique_id'=> $unique,
					'marking_info'=> $marking_info,
					'q_origin'=> $q_origin
					); 
				$i +=1;
			}
			return $return;
		}else{
			return $return;
		}
	}
	//
	public function Get_classification_question($pointcontr,$filehandle,$utility){
		//
		$chapters_arr = $pointcontr->get_chapters_including_point_pointer('Maths');
		//
		$query = "select * from questions where q_moduel= '25_eidtor_tutorial' and q_chapter = '02_question' and q_origin like ('%-%-C1-%-%')";
		if(isset($_SESSION['u_admin'])){
			$query = "select * from questions where q_moduel= '25_eidtor_tutorial' and q_chapter = '02_question' and q_origin like ('%-%-C1-%-%')";
			//$query = "select * from questions where q_moduel= '25_eidtor_tutorial' and q_chapter = '02_question'"; 
			//$query = "select * from questions where q_moduel= '25_eidtor_tutorial' and q_chapter = '02_question' and q_origin like ('%-%-C4-%-%')";
		}elseif($_SESSION['user_unique_id'] == 'fbc79d4f80'){
			$query = "select * from questions where q_moduel= '25_eidtor_tutorial' and q_chapter = '02_question' and q_origin like ('%-%-C4-%-%')";
		}else{
			if($_SESSION['user_unique_id'] == 'd531fb60ee'){
				$query = "select * from questions where q_moduel= '25_eidtor_tutorial' and q_chapter = '02_question' and q_origin like ('%-%-C3-%-%')";
			}
		}
		
		//
		$run = $this->run_query($query);
		$count = mysqli_num_rows($run);
		//counting the number of questions available
		if($count > 0 ){
			//looping though all the questions
			$result = mysqli_fetch_assoc($run);
			//
			$q_origin = $result['q_origin'];
			$q_chapter = $result['q_chapter'];
			$link = $result['q_link'];
			$dir = $result['q_directory'];
			$unique = $result['q_unique_id'];
			$q_loc = $result['q_loc'];
			//open the file and read the content 
			if(is_file($this->root.'/'.$link)){
				$raw_question_desc = file_get_contents("$this->root/$link");
			}else{
				$raw_question_desc = '';
			}
			//
			$in_hiddendiscription_items = $this->extract_discription_info($raw_question_desc);
			$head = $in_hiddendiscription_items['my_q_head'];
			$question = $in_hiddendiscription_items['my_question'];
			//
			$question_arr = array();
			foreach($question as $key => $part){
				if($key == 'head'){
					$processed = $this->extract_discription_normal($part['my_question'],$dir,$utility)[0];
					$question[$key]['my_question'] = $processed;
				}else{
					$processed = $this->extract_discription_normal($part['my_question'],$dir,$utility)[0];
					$question[$key]['my_question'] = $processed;
				}
				
			}
			//
			$answer = $in_hiddendiscription_items['my_answer'];
			//
			foreach($answer as $key => $part){
				//
				$processed = $this->extract_discription_normal($part['my_answer'],$dir,$utility)[0];
				$answer[$key]['my_answer'] = $processed;
			}
			//
			$files_list = $this->list_files_edit($dir);
			//
			$return['Questions'][] = array
				(
				'number_of_qs' => $count,
				'description'=> $raw_question_desc ,
				'head_info'=> $head,
				'question_desc'=> $question,
				'answer_desc'=> $answer,
				'q_files_list'=> $files_list,
				'q_chapter'=> $q_chapter,
				'q_unique_id'=> $unique,
				'q_loc'=> $q_loc,
				'q_origin'=> $q_origin,
				'all_chapters'=> $chapters_arr
				); 
			
			//
			return $return;
		}else{
			return $return;
		}
	}
	//
	public function Get_customquestions($input,$usercontr,$filehandle,$utility){
		//determining the order by clause
		$user_status = $usercontr->user_status;
		if(isset($usercontr->q_vis)){
			$q_vis = $usercontr->q_vis;	
		}
		//
		if($user_status <= 1){
			$orderby = "order by q_id asc";
		}else{
			$orderby = "order by q_difficulty asc";
		}
		//
		$subject = $input['q_subject'];
		$moduel = $input['q_moduel'];
		$chapter = $input['q_chapter'];
		$type = $input['q_type'];
		$difficulty = $input['q_difficulty'];
		$page = $input['page'];
		//get the marked questions relavent to this user,subject and moduel
		$user_marked_qs = $this->get_marked_qs($subject,$moduel);
		//builiding query clauses depending on user input
		if($chapter == 'all'){
			$chapter_query = "";
		}else{
			$chapter_query = "and q_chapter = '$chapter'"; 
		}
		//
		if($type == 'all'){
			$type_query = "";
		}else{
			$type_query = "and q_type = '$type'"; 
		}
		//
		if($difficulty == 'all'){
			$diff_query = "";
		}else{
			$diff_query = "and q_difficulty = '$difficulty'"; 
		}
		//
		if(empty($user_marked_qs)){
			//
			$exclude_query = '';
		}else{
			//
			$keys = array_keys($user_marked_qs);
			$str_list = implode("','",$keys);
			$str_list = "'$str_list'";
			//depending on the user settings the marked questions are either displayed or not.
			if(isset($q_vis) ){
				if($q_vis == 0){
					$exclude_query = "and q_unique_id NOT IN ($str_list)";
				}else{
					//
					$exclude_query = '';
				}
			}else{
				//
				$exclude_query = '';
			}
			
		}
		//
		$pagination_offset = ($page -1)*5;
		//
		$where_clause = "where  q_subject = '$subject' and q_moduel='$moduel' $chapter_query $type_query $diff_query $exclude_query $orderby";
		//
		$query = "select q_origin,q_chapter,q_loc,q_link,q_directory,q_unique_id,( SELECT COUNT(*) FROM $this->db_table $where_clause ) as count from $this->db_table  $where_clause limit 5 offset $pagination_offset ";
		//
		$return['disp_info']['moduel'] = $utility -> make_display_header($moduel);
		//
		$run = $this->run_query($query);
		$count = mysqli_num_rows($run);
		//counting the number of questions available
		$i = 0 + $pagination_offset;
		if($count > 0 ){
			//looping though all the questions
			while($result = mysqli_fetch_assoc($run)){
				//
				$q_origin = $result['q_origin'];
				$q_chapter = $result['q_chapter'];
				$link = $result['q_link'];
				$dir = $result['q_directory'];
				$unique = $result['q_unique_id'];
				$q_loc = $result['q_loc'];
				$counter = $result['count'];
				//open the file and read the content 
				if(is_file($this->root.'/'.$link)){
					$raw_question_desc = file_get_contents("$this->root/$link");
				}else{
					$raw_question_desc = '';
				}
				//
				$in_hiddendiscription_items = $this->extract_discription_info($raw_question_desc);
				$head = $in_hiddendiscription_items['my_q_head'];
				$question = $in_hiddendiscription_items['my_question'];
				//
				$question_arr = array();
				foreach($question as $key => $part){
					if($key == 'head'){
						$processed = $this->extract_discription_normal($part['my_question'],$dir,$utility)[0];
						$question[$key]['my_question'] = $processed;
					}else{
						$processed = $this->extract_discription_normal($part['my_question'],$dir,$utility)[0];
						$question[$key]['my_question'] = $processed;
					}
					
				}
				//
				$answer = $in_hiddendiscription_items['my_answer'];
				//
				foreach($answer as $key => $part){
					//
					$processed = $this->extract_discription_normal($part['my_answer'],$dir,$utility)[0];
					$answer[$key]['my_answer'] = $processed;
				}
				//
				$files_list = $this->list_files_edit($dir);
				//checking if the question has been marked before
				if($user_marked_qs != 0){
					if(array_key_exists ( $unique, $user_marked_qs)){
						$user_marked_qs[$unique]['time'] = $utility -> history_time_calculator($user_marked_qs[$unique]['time']);
						$marking_info = $user_marked_qs[$unique];
					}else{
						$marking_info = '';
					}
				}else{
					$marking_info = '';
				}
				//
				$return['Questions'][$i] = array
					(
					'description'=> $raw_question_desc ,
					'head_info'=> $head,
					'question_desc'=> $question,
					'answer_desc'=> $answer,
					'q_files_list'=> $files_list,
					'q_chapter'=> $q_chapter,
					'q_unique_id'=> $unique,
					'q_loc'=> $q_loc,
					'marking_info'=> $marking_info,
					'q_origin'=> $q_origin
					); 
				$i += 1;
			}
			//
			$pagination = $this->make_pagintation($input,$counter,5,$page);
			$return['pagination'] = $pagination;
			return $return;
		}else{
			return $return;
		}
	}
	//CONTR
	public function Get_pastpapers($input,$usercontr,$filehandle,$utility){
		//determining the order by clause
		$user_status = $usercontr->user_status;
		if(isset($usercontr->q_vis)){
			$q_vis = $usercontr->q_vis;	
		}
		//
		$subject = $input['q_subject'];
		$q_origin = $input['q_origin'];
		//get the marked questions relavent to this user,subject and moduel
		$user_marked_qs = $this->get_marked_qs_papers($subject,$q_origin);
		//builiding query clauses depending on user input
		
		if(empty($q_origin)){
			$origi_query = "";
		}else{
			$origi_query = "and q_origin = '$q_origin'"; 
		}
		//
		if(empty($user_marked_qs)){
			//
			$exclude_query = '';
		}else{
			//
			$keys = array_keys($user_marked_qs);
			$str_list = implode("','",$keys);
			$str_list = "'$str_list'";
			//depending on the user settings the marked questions are either displayed or not.
			if(isset($q_vis) ){
				if($q_vis == 0){
					$exclude_query = " ";
					//$exclude_query = "and q_unique_id NOT IN ($str_list)";
				}else{
					//
					$exclude_query = '';
				}
			}else{
				//
				$exclude_query = '';
			}
			
		}
		//
		$where_clause = "where  q_subject = '$subject' $origi_query $exclude_query order by q_exam_num asc";
		//
		$query = "select q_chapter,q_loc,q_link,q_directory,q_unique_id,( SELECT COUNT(*) FROM $this->db_table $where_clause ) as count from $this->db_table  $where_clause";
		//
		$return['disp_info']['moduel'] = $utility -> make_display_paper($q_origin);
		//
		$run = $this->run_query($query);
		$count = mysqli_num_rows($run);
		//counting the number of questions available
		$i = 0;
		if($count > 0 ){
			//looping though all the questions
			while($result = mysqli_fetch_assoc($run)){
				//
				$q_chapter = $result['q_chapter'];
				$link = $result['q_link'];
				$dir = $result['q_directory'];
				$unique = $result['q_unique_id'];
				$q_loc = $result['q_loc'];
				$counter = $result['count'];
				//open the file and read the content 
				if(is_file($this->root.'/'.$link)){
					$raw_question_desc = file_get_contents("$this->root/$link");
				}else{
					$raw_question_desc = '';
				}
				//
				$in_hiddendiscription_items = $this->extract_discription_info($raw_question_desc);
				$head = $in_hiddendiscription_items['my_q_head'];
				$question = $in_hiddendiscription_items['my_question'];
				//
				$question_arr = array();
				foreach($question as $key => $part){
					if($key == 'head'){
						$processed = $this->extract_discription_normal($part['my_question'],$dir,$utility)[0];
						$question[$key]['my_question'] = $processed;
					}else{
						$processed = $this->extract_discription_normal($part['my_question'],$dir,$utility)[0];
						$question[$key]['my_question'] = $processed;
					}
					
				}
				//
				$answer = $in_hiddendiscription_items['my_answer'];
				//
				foreach($answer as $key => $part){
					//
					$processed = $this->extract_discription_normal($part['my_answer'],$dir,$utility)[0];
					$answer[$key]['my_answer'] = $processed;
				}
				//
				$files_list = $this->list_files_edit($dir);
				//checking if the question has been marked before
				if($user_marked_qs != 0){
					if(array_key_exists ( $unique, $user_marked_qs)){
						$user_marked_qs[$unique]['time'] = $utility -> history_time_calculator($user_marked_qs[$unique]['time']);
						$marking_info = $user_marked_qs[$unique];
					}else{
						$marking_info = '';
					}
				}else{
					$marking_info = '';
				}
				//
				$return['Questions'][$i] = array
					(
					'description'=> $raw_question_desc ,
					'head_info'=> $head,
					'question_desc'=> $question,
					'answer_desc'=> $answer,
					'q_files_list'=> $files_list,
					'q_chapter'=> $q_chapter,
					'q_unique_id'=> $unique,
					'q_loc'=> $q_loc,
					'marking_info'=> $marking_info,
					'q_origin'=> $q_origin
					); 
				$i += 1;
			}
			//
			return $return;
		}else{
			return $return;
		}
	}
	//CONTR
	public function get_active_tasks($subject,$usercontr){
		//
		$query = "select * from active_tasks_questions where q_subject = '$subject' order by q_point asc, q_topic asc,q_chapter asc";
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
	public function make_title($input,$pointcontr,$utility){
		//
		$filter = $input['filter'];
		$point = $input['pt_unique_id'];
		//
		$point_obj = $pointcontr -> make_obj_unique_id($point);
		//
		if($filter == 'chapter'){
			//
			$chapter = $point_obj['pt_chapter'];
			$chapter = $utility -> make_display_header($chapter);
			return "Questions - $chapter";
		}elseif($filter == 'topic'){
			//
			$topic = $point_obj['pt_topic'];
			$topic = $utility -> make_display_header($topic);
			return "Questions - $topic";
		}else{
			return null;
		}
	}
	//
	public function make_page_meta_spec($input,$pointcontr,$utility){
		//
		$filter = $input['filter'];
		$point = $input['pt_unique_id'];
		//
		$point_obj = $pointcontr -> make_obj_unique_id($point);
		//
		$subject = $point_obj['pt_subject'];
		$moduel = $point_obj['pt_moduel'];
		$moduel = $utility -> make_display_header($moduel);
		$chapter = $point_obj['pt_chapter'];
		$chapter = $utility -> make_display_header($chapter);
		//
		$topic = $point_obj['pt_topic'];
		$topic = $utility -> make_display_header($topic);
		//
		if($filter == 'chapter'){
			//
			return "All about A level $subject questions and answers for $moduel $chapter, includes solutions, past paper questions from edexcel,aqa and OCR A level $subject, and other practice questions. ";
		}elseif($filter == 'topic'){
			//
			return "All about A level $subject questions and answers for $moduel $chapter $topic, includes solutions, past paper questions from edexcel,aqa and OCR A level $subject and other practice questions.";
		}else{
			return null;
		}
	}
	//
	public function get_question_moduels($subject,$utility,$usercontr){
		//
		$user_level = $usercontr -> user_level;
		//setting up user filter query
		if($user_level ==''){
			//
			$customtable = $this->db_table;
		}elseif($user_level == 'AS'){
			//
			$customtable ="(select * from $this->db_table where q_level ='AS') as A";
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
		//
		$query = "select q_moduel from $customtable where 1 and q_origin like ('%-%-%-%-%') group by q_moduel order by q_moduel asc";
		$run = $this->run_query($query);
		//
		$count = mysqli_num_rows($run);
		if($count > 0){
			$moduels = array();
			while($result = mysqli_fetch_assoc($run)){
				$moduels[] = $result['q_moduel'];
			}
			if(!empty($moduels)){
				$query = "select moduel from publications where status = 1 and subject = '$subject' order by moduel asc";
				$run = $this-> run_query ($query);
				//
				$result = mysqli_num_rows($run);
				//
				$load = array();
				//
				if ($result >0){
					//
					while ($fetch_all = mysqli_fetch_assoc($run)){
						//
						if(in_array($fetch_all['moduel'],$moduels)){
							$moduel = $fetch_all['moduel'];
							$disp = $utility -> make_display_header($moduel);
							//
							$load[] = array('value' => $moduel, 'display' => $disp);
						}
						
					}
					//
					if(!empty($load)){
						return $load;
					}else{
						return array('value' => '', 'display' => 'None');
					}
					
				//
				}else{
					//
					return array('value' => '', 'display' => 'None');
				}
			}else{
				return array('value' => '', 'display' => 'None');
			}
		}else{
			return array('value' => '', 'display' => 'None');
		}
		
	}	
	//
	public function Get_user_papermaker($input,$usercontr,$filehandle,$utility){
		$user_unique = $input['user_unique_id'];
		$paper_unique = $input['paper_unique_id'];
		//
		$query = "select * from user_papers where user_unique_id = '$user_unique' and paper_unique_id = '$paper_unique' limit 1";
		$run = $this->run_query($query);
		$count = mysqli_num_rows($run);
		if($count > 0){
			//
			$paper_arr = array();
			while($paper = mysqli_fetch_assoc($run)){
				$paper_arr['subject']= $paper['subject'];
				$paper_arr['marks']= $paper['marks'];
				$paper_arr['time']= $paper['time'];
				//
				for($i = 0;$i<11;$i++){
					$q_col = "q_".$i;
					if(!empty($paper[$q_col])){
						$paper_arr['qs'][$i] = $paper[$q_col];
					}
				}
				//
				
			}
		}else{
			return 0;
		}
		
		
		
		
		
		$subject = $paper_arr['subject'];
		$marks = $paper_arr['marks'];
		$disp_time = $paper_arr['time'];
		$disp_time = date("Y-m-d", $disp_time);
		$questions_list  = implode("','",$paper_arr['qs']);
		$moduel = '';
		//get the marked questions relavent to this user,subject and moduel
		$user_marked_qs = $this->get_marked_qs($subject,$moduel);
		//
		$query = "select * from questions where q_subject = '$subject' and q_unique_id in ('$questions_list') order by q_level desc,q_subject,q_moduel asc,q_chapter asc,q_topic asc,q_difficulty asc  limit 10";
		//
		$return['disp_info']['moduel'] = $subject;
		$return['disp_info']['marks'] = $marks;
		$return['disp_info']['date'] = $disp_time;
		//
		$run = $this->run_query($query);
		$count = mysqli_num_rows($run);
		//counting the number of questions available
		$i = 0;
		if($count > 0 ){
			//looping though all the questions
			while($result = mysqli_fetch_assoc($run)){
				//
				$q_origin = $result['q_origin'];
				$q_moduel = $result['q_moduel'];
				$q_moduel_disp = $utility-> make_display_header($q_moduel);
				$q_chapter = $result['q_chapter'];
				$q_chapter_disp = $utility-> make_display_header($q_chapter);
				$link = $result['q_link'];
				$dir = $result['q_directory'];
				$unique = $result['q_unique_id'];
				$total_marks = $result['q_total_marks'];
				$q_loc = $result['q_loc'];
				//open the file and read the content 
				if(is_file($this->root.'/'.$link)){
					$raw_question_desc = file_get_contents("$this->root/$link");
				}else{
					$raw_question_desc = '';
				}
				//
				$in_hiddendiscription_items = $this->extract_discription_info($raw_question_desc);
				$head = $in_hiddendiscription_items['my_q_head'];
				$head['q_moduel_disp'] = $q_moduel_disp;
				$head['q_chapter_disp'] = $q_chapter_disp;
				$question = $in_hiddendiscription_items['my_question'];
				//
				$question_arr = array();
				foreach($question as $key => $part){
					if($key == 'head'){
						$processed = $this->extract_discription_normal($part['my_question'],$dir,$utility)[0];
						$question[$key]['my_question'] = $processed;
					}else{
						$processed = $this->extract_discription_normal($part['my_question'],$dir,$utility)[0];
						$question[$key]['my_question'] = $processed;
					}
					
				}
				//
				//
				$answer = $in_hiddendiscription_items['my_answer'];
				//
				foreach($answer as $key => $part){
					//
					$processed = $this->extract_discription_normal($part['my_answer'],$dir,$utility)[0];
					$answer[$key]['my_answer'] = $processed;
				}
				//
				$files_list = $this->list_files_edit($dir);
				//checking if the question has been marked before
				if($user_marked_qs != 0){
					if(array_key_exists ( $unique, $user_marked_qs)){
						$user_marked_qs[$unique]['time'] = $utility -> history_time_calculator($user_marked_qs[$unique]['time']);
						$marking_info = $user_marked_qs[$unique];
					}else{
						$marking_info = '';
					}
				}else{
					$marking_info = '';
				}
				//
				$return['Questions'][$i] = array
					(
					'description'=> $raw_question_desc ,
					'head_info'=> $head,
					'question_desc'=> $question,
					'answer_desc'=> $answer,
					'q_files_list'=> $files_list,
					'q_moduel'=> $q_moduel,
					'q_chapter'=> $q_chapter,
					'q_unique_id'=> $unique,
					'q_loc'=> $q_loc,
					'marking_info'=> $marking_info,
					'total_marks'=> $total_marks,
					'q_origin'=> $q_origin
					); 
				$i += 1;
			}
			//
			return $return;
		}
	}
	//
	public function get_question_papers($subject,$utility,$usercontr){
		//
		$user_level = $usercontr -> user_level;
		//setting up user filter query
		if($user_level ==''){
			//
			$customtable = $this->db_table;
		}elseif($user_level == 'AS'){
			//
			$customtable ="(select * from $this->db_table where q_level ='AS') as A";
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
		//$query = "select q_moduel from questions where 1 group by q_moduel order by q_moduel asc";
		$query = "select q_origin from $customtable where q_subject = '$subject' and q_is_exam = 1 group by q_origin order by q_origin asc";
		$run = $this-> run_query ($query);
		//
		$result = mysqli_num_rows($run);
		//
		$load = array();
		//
		if ($result >0){
			//
			while ($fetch_all = mysqli_fetch_assoc($run)){
				$q_origin = $fetch_all['q_origin'];
				$disp = $utility -> make_display_paper($q_origin);
				//
				$load[] = array('value' => $q_origin, 'display' => $disp);
			}
			//
			return $load;
		//
		}else{
			//
			return array('value' => '', 'display' => 'None');
		}
	}
	//
	public function get_q_history($utility){
		//
		$user_unique = $_SESSION['user_unique_id'];
		$month_ago = time() - 30*60*60*24;
		$week = 7*60*60*24;
		//
		//$query = "SELECT q_unique_id,time,q_diffculty,mark,q_total_marks FROM question_track where user_unique ='$user_unique' and time >  $month_ago  and attempt_num = 1 order by time desc";
		$query = "select q_unique_id,time,q_difficulty,mark,q_total_marks from (select * from question_track where user_unique ='$user_unique' and time >  $month_ago order by id DESC) as A group by q_unique_id order by time desc";
		//
		$run = $this->run_query($query);
		$count = mysqli_num_rows($run);
		if($count > 0){
			//
			$return = array();
			while($result = mysqli_fetch_assoc($run)){
				//
				$q_unique = $result['q_unique_id'];
				//used sorting into weeks 
				$time = $result['time'];
				$nth_week = round((time()-$time)/$week);
				//used for ordering within the week
				$q_diffculty = $result['q_difficulty'];
				//userd for colouring the history box
				$mark = $result['mark'];
				$q_total_marks = $result['q_total_marks'];
				if($q_total_marks > 0){
					$mark_ratio = round ($mark/$q_total_marks , 2);
				}else{
					$mark_ratio = 0;
				}
				$mark_ratio = round ($mark/$q_total_marks , 2);
				//to color the box depending on value
				$rgb = $utility-> numberToColorHsl($mark_ratio, 0.45, 1);
				$return[$nth_week][] = array('diff' =>$q_diffculty,'mark_ratio' => $mark_ratio,'rgb' =>$rgb,'unique'=>$q_unique);
			}
			return $return;
		}else{
			//
			return null;
		}
	}
	//
	public function get_p_history($utility){
		//
		$user_unique = $_SESSION['user_unique_id'];
		//
		$query = "select * from user_papers where user_unique_id = '$user_unique' order by id desc limit 10";
		//
		$run = $this->run_query($query);
		$count = mysqli_num_rows($run);
		//
		$return = array();
		if($count > 0){
			//
			while($paper = mysqli_fetch_assoc($run)){
				//
				$return_temp = array();
				$return_temp['user_unique'] = $paper['user_unique_id'];
				$return_temp['paper_unique'] = $paper['paper_unique_id'];
				$return_temp['subject'] = $paper['subject'];
				$return_temp['total_marks'] = $paper['marks'];
				$time = $paper['time'];
				$return_temp['attempt_time'] = $utility->history_time_calculator($time);
				for($i = 0;$i<11;$i++){
					$q_col = "q_".$i;
					if(!empty($paper[$q_col])){
						$return_temp['qs'][$i] = $paper[$q_col];
					}
				}
				//
				$question_string = implode("','", $return_temp['qs']);
				//$query = "select sum(mark) as sum from question_track where user_unique = '$user_unique' and q_unique_id in ('$question_string')";
				$query = "select sum(mark) as sum  from(select * from (select id,q_unique_id,mark from question_track where user_unique = '$user_unique' and q_unique_id in ('$question_string') order by id desc) as A  where 1 group by q_unique_id) as B where 1";
				$run_2 = $this->run_query($query);
				$count = mysqli_num_rows($run_2);
				if($count> 0){
					//
					while($result = mysqli_fetch_assoc($run_2)){
						$return_temp['score'] = $result['sum'];
					}
				}else{
					$return_temp['score'] = 0;
				}
				//
				if ($return_temp['total_marks'] > 0){
					$mark_ratio = round($return_temp['score']/$return_temp['total_marks'], 2);
				}else{
					$mark_ratio = 0;
				}
				$return_temp['rgb'] = $utility-> numberToColorHsl($mark_ratio, 0.4, 1);
				$return_temp['pct'] = round($mark_ratio * 100);
				$return[] = $return_temp;
			}
			return $return;
			
		}else{
			//
			return null;
		}
	}
	//
	public function make_pdf_user_paper($paper_unique_id,$user_unique_id,$usercontr){
		
	}
}


















