<?php
namespace MVC;

//gives the model instructions and information so the model can do what we want
//it insert or updates to the database
class questioncontr extends question{
	//Contr: insert all the question information into the database
	public function inject_question ($dir_a,$filehandle,$utility){
		//for individual questions
		//looping funciton
		//loop through the directory
		//extract all txt files
		$all_pdfs = $filehandle -> loop($dir_a,'txt');
		//process tags
		$filehandle -> tag_processing($all_pdfs);
		//filter out the questions from the papers
		$questions = $filehandle->filter_questions($all_pdfs);
		//filter out the type question and type answers from the above filter
		$question = $filehandle -> filter_type_question($questions);
		$tags = $filehandle ->  extract_unique_ids($question);
		//returns array(q_unique->pt_unique, ....)
		$q_points = $filehandle -> find_q_point_unique($question);
		//sort the question and answer arrays so they match and return asoociative array of both with the tag as key 
		$grouped = $filehandle -> group_files($tags,$question,$question);
		//take the question link and extract all info from it chapter,topic,origin and etc, sorted in arrays in arrays
		$q_info = $filehandle -> info_extractor($grouped);
		//take the info generated and group it with the previous group
		$grouped2 = $filehandle -> group_files2($grouped,$q_info);
		//get the directory of files 
		$directories = $filehandle-> extract_files_dir($question);
		//take the directories and group with the latest group
		$grouped3 = $filehandle -> group_files2($grouped2,$directories);
		//getting the question head_information form the files **********potential memory issue*********
		$head_info = $filehandle->read_questions_heads($question);
		//adding the head information to the the big array 
		$grouped4 = $filehandle -> group_files2($grouped3,$head_info);
		$grouped4 = $filehandle -> group_files2($grouped4,$q_points);
		//trim the dir name to remove root
		$question_dir = substr($dir_a,strpos($dir_a,'/specifications/'),strlen($dir_a));
		//extracting the related paper's tag 
		$Dbtags = $this->db_tags_return($question_dir);
		$stray_tags = $utility->find_stray($tags,$Dbtags);
		$delete_strays = $this->delete_rows($stray_tags);
		//turn info into object, make the info associative consistent with the DB 
		$object_info = $filehandle -> make_assoc($grouped4,array(
			'q_unique_id',
			'q_link',
			'q_link',
			'general_info',
			'q_directory',
			'q_head_info',
			'q_point'
		));
		//make question object out of each question packet
		foreach ($object_info as $question){
			$result = $this->treat_db($question);
		}
		return 1;
	}
	//Contr: insert all the question information into the database
	public function inject_question_editor_private($editor_unique,$dir,$filehandle,$utility){
		//for individual questions
		//looping funciton
		//loop through the directory
		//extract all txt files
		$all_pdfs = $filehandle -> loop($dir,'txt');
		//process tags
		$filehandle -> tag_processing($all_pdfs);
		//filter out the questions from the papers
		$questions = $filehandle->filter_questions($all_pdfs);
		//filter out the type question and type answers from the above filter
		$question = $filehandle -> filter_type_question($questions);
		$tags = $filehandle ->  extract_unique_ids($question);
		//returns array(q_unique->pt_unique, ....)
		$q_points = $filehandle -> find_q_point_unique($question);
		//sort the question and answer arrays so they match and return asoociative array of both with the tag as key 
		$grouped = $filehandle -> group_files($tags,$question,$question);
		//take the question link and extract all info from it chapter,topic,origin and etc, sorted in arrays in arrays
		$q_info = $filehandle -> info_extractor($grouped);
		//take the info generated and group it with the previous group
		$grouped2 = $filehandle -> group_files2($grouped,$q_info);
		//get the directory of files 
		$directories = $filehandle-> extract_files_dir($question);
		//take the directories and group with the latest group
		$grouped3 = $filehandle -> group_files2($grouped2,$directories);
		//getting the question head_information form the files **********potential memory issue*********
		$head_info = $filehandle->read_questions_heads($question);
		//adding the head information to the the big array 
		$grouped4 = $filehandle -> group_files2($grouped3,$head_info);
		$grouped4 = $filehandle -> group_files2($grouped4,$q_points);
		//trim the dir name to remove root
		$question_dir = substr($dir,strpos($dir,"users/$editor_unique/specifications/"),strlen($dir));
		//extracting the related paper's tag 
		$Dbtags = $this->db_tags_return($question_dir);
		$stray_tags = $utility->find_stray($tags,$Dbtags);
		$delete_strays = $this->delete_rows($stray_tags);
		//turn info into object, make the info associative consistent with the DB 
		$object_info = $filehandle -> make_assoc($grouped4,array(
			'q_unique_id',
			'q_link',
			'q_link',
			'general_info',
			'q_directory',
			'q_head_info',
			'q_point'
		));
		//make question object out of each question packet
		foreach ($object_info as $question){
			$this->treat_db($question);
		}
		return 1;
	}
	//Contr: 
	public function intraconnect_point($q_unique_id,$pt_unique_id,$filehandle){
		//open point cache file and save the question
		$this->manual_construct_q_unique_id($q_unique_id);
		//connecting the point to the questions 
		return $filehandle->intraconnection("$this->root/$this->directory",	'pt',	$pt_unique_id);
		//
	}
	//Contr:
	public function add_new_question($filter,$subject,$moduel,$chapter,$topic,$pt_unique_id,$pointcontr,$utility,$filehandle){
		//
		$q_unique_id = $utility->tag_generator();
		//
		if($filter == 'point'){
			 $q_link = $pointcontr->get_point_dir($subject,$moduel,$chapter,$topic,$pt_unique_id);
		}elseif($filter == 'topic'){
			$q_link = $pointcontr->get_topic_dir($subject,$moduel,$chapter,$topic);
		}else{
			if($filter == 'chapter'){
				$q_link = $pointcontr->get_chapter_dir($subject,$moduel,$chapter);
			}else{
				return 0;
			}
		}
		//
		$q_dir = "$q_link/questions/$q_unique_id";
		$q_link = "/$q_link/questions/$q_unique_id/type_question.tag_$q_unique_id.txt";
		//exec make directory then make file 	
		if(!is_dir("$this->root/$q_dir")){
			//
			mkdir("$this->root/$q_dir",0777,true);
			//
			$file = fopen("$this->root/$q_link",'a+');
			fwrite($file, "
<my_q_head q_origin='new' q_type='unknown' q_difficulty='1'> </my_q_head>


<my_question part='head'> 
The information about the question.
</my_question>

<my_question part='a' part_mark='3'> 
The question: solve $$ y=mx+c $$
</my_question>

<my_question part='b' part_mark='2'> 
The question: solve $$ z= y^2 + x $$
</my_question>

<my_answer part ='a'> 
The answer $$ y=8x + 3 $$
</my_answer>

<my_answer part ='b'> 
The answer $$ z= 4 + 8 = 12 $$
</my_answer>
");
			fclose($file);	
		}elseif(is_dir("$this->root/$q_dir")){
			//
			$file = fopen("$this->root/$q_link",'a+');
			fwrite($file, "
			
<my_q_head q_origin='new' q_type='unknown' q_difficulty='1'> </my_q_head>


<my_question part='head'> 
The information about the question.
</my_question>

<my_question part='a' part_mark='3'> 
The question: solve $$ y=mx+c $$
</my_question>

<my_question part='b' part_mark='2'> 
The question: solve $$ z= y^2 + x $$
</my_question>

<my_answer part ='a'> 
The answer $$ y=8x + 3 $$
</my_answer>

<my_answer part ='b'> 
The answer $$ z= 4 + 8 = 12 $$
</my_answer>
");
			fclose($file);	
		}
		//
		if(is_file("$this->root/$q_link")){
			//making the query
			$query = "insert into $this->db_table (q_subject,q_moduel,q_chapter,q_topic,q_point,q_link,q_unique_id,q_origin,q_type,q_difficulty,q_directory) values ('$subject','$moduel','$chapter','$topic','$pt_unique_id','$q_link','$q_unique_id','new','unknown','1','/$q_dir')";
			//
			if($this->run_query($query)){
				return 1;
			}else{
				return 0;
			}
			//
		}else{
			return 0;
		}
		
		
		
	}
	//Contr:
	public function add_new_question_editor($filter,$subject,$moduel,$chapter,$topic,$pt_unique_id,$pointcontr,$utility,$filehandle){
		//
		$q_unique_id = $utility->tag_generator();
		$this->db_table= ' questions_editors ';
		//
		if($filter == 'point'){
			 $q_link = $pointcontr->get_point_dir($subject,$moduel,$chapter,$topic,$pt_unique_id);
		}elseif($filter == 'topic'){
			$q_link = $pointcontr->get_topic_dir($subject,$moduel,$chapter,$topic);
		}else{
			if($filter == 'chapter'){
				$q_link = $pointcontr->get_chapter_dir($subject,$moduel,$chapter);
			}else{
				return 0;
			}
		}
		//
		$editor = $_SESSION['user_unique_id'];
		//
		$q_dir = "users/$editor/$q_link/questions/$q_unique_id";
		$q_link = "/users/$editor/$q_link/questions/$q_unique_id/type_question.tag_$q_unique_id.txt";
		//exec make directory then make file 	
		if(!is_dir("$this->root/$q_dir")){
			//
			mkdir("$this->root/$q_dir",0777,true);
			//
			$file = fopen("$this->root/$q_link",'a+');
			fwrite($file, "
<my_q_head q_origin='new' q_type='unknown' q_difficulty='1'> </my_q_head>


<my_question part='head'> 
The information about the question.
</my_question>

<my_question part='a' part_mark='3'> 
The question: solve $$ y=mx+c $$
</my_question>

<my_question part='b' part_mark='2'> 
The question: solve $$ z= y^2 + x $$
</my_question>

<my_answer part ='a'> 
The answer $$ y=8x + 3 $$
</my_answer>

<my_answer part ='b'> 
The answer $$ z= 4 + 8 = 12 $$
</my_answer>
");
			fclose($file);	
		}elseif(is_dir("$this->root/$q_dir")){
			//
			$file = fopen("$this->root/$q_link",'a+');
			fwrite($file, "
			
<my_q_head q_origin='new' q_type='unknown' q_difficulty='1'> </my_q_head>


<my_question part='head'> 
The information about the question.
</my_question>

<my_question part='a' part_mark='3'> 
The question: solve $$ y=mx+c $$
</my_question>

<my_question part='b' part_mark='2'> 
The question: solve $$ z= y^2 + x $$
</my_question>

<my_answer part ='a'> 
The answer $$ y=8x + 3 $$
</my_answer>

<my_answer part ='b'> 
The answer $$ z= 4 + 8 = 12 $$
</my_answer>
");
			fclose($file);	
		}
		//
		if(is_file("$this->root/$q_link")){
			//making the query
			$query = "insert into $this->db_table (q_subject,q_moduel,q_chapter,q_topic,q_point,q_link,q_unique_id,q_origin,q_type,q_difficulty,q_directory) values ('$subject','$moduel','$chapter','$topic','$pt_unique_id','$q_link','$q_unique_id','new','unknown','1','/$q_dir')";
			//
			if($this->run_query($query)){
				return 1;
			}else{
				return 0;
			}
			//
		}else{
			return 0;
		}
		
		
		
	}
	//Contr:
	public function update_question_info($q_unique_id,$new_input,$filehandle,$securityhandle){
		//
		$this->manual_construct_q_unique_id($q_unique_id);
		$link = $this->root.$this->q_link;
		$content = $new_input ;
		//
		if(isset($this->q_id)){
			//
			if(is_file($link)){
				//
				unlink($link);
				//
				$make_file = $filehandle-> make_file($link);
				//
				if($make_file == 1){
					//
					$writeto_file = $filehandle->  writeto_file($link,$content);
					if($writeto_file ==1){
						//if successful in writing to file, update the database's information using a loop
						//return 1;
						$head_info = $this->get_question_head_info($new_input);
						//security gate
						foreach($head_info as $name => $value){
							//
							$status = $securityhandle->check_site_variable($name,$value);
							//
							if($status == 0){
								return 0;
							}
						}
						//
						$q_level = $head_info['q_level'];
						$q_origin = $head_info['q_origin'];
						$q_total_marks = $head_info['q_total_marks'];
						$q_type = $head_info['q_type'];
						$q_difficulty = $head_info['q_difficulty'];
						$is_exam = $filehandle -> derive_is_exam($q_origin);
						if($is_exam == 1){
							$exam_num = substr($q_origin, -2, strlen($q_origin));
							$exam_num = sprintf("%02d", $exam_num);
							$q_origin = substr($q_origin, 0, -3);
						}else{$exam_num = NULL;}
						//
						$query = "update $this->db_table set q_level = '$q_level' , q_origin = '$q_origin',q_is_exam = '$is_exam',q_exam_num = '$exam_num', q_type='$q_type', q_difficulty='$q_difficulty', q_total_marks='$q_total_marks' where q_unique_id = '$q_unique_id' limit 1";
						//
						if($this->run_query($query)){
							return 1;
						}else{
							return 0;
						}
					}else{
						//
						return 0;
					}
				}else{
					return 0;
				}
			}else{
				$make_file = $filehandle-> make_file($link);
				//
				if($make_file == 1){
					//
					$writeto_file = $filehandle->  writeto_file($link,$content);
					if($writeto_file ==1){
						//
						return 1;
					}else{
						//
						return 0;
					}
				}else{
					return 0;
				}
			}
		}else{return 0;}
	}
	//Contr:
	public function update_question_info_editor($q_unique_id,$new_input,$filehandle,$securityhandle){
		//
		$this->db_table=' questions_editors ';
		$this->manual_construct_q_unique_id($q_unique_id);
		$link = $this->root.$this->q_link;
		$content = $new_input ;
		//
		if(isset($this->q_id)){
			//
			if(is_file($link)){
				//
				unlink($link);
				//
				$make_file = $filehandle-> make_file($link);
				//
				if($make_file == 1){
					//
					$writeto_file = $filehandle->  writeto_file($link,$content);
					if($writeto_file ==1){
						//if successful in writing to file, update the database's information using a loop
						//return 1;
						$head_info = $this->get_question_head_info($new_input);
						//security gate
						foreach($head_info as $name => $value){
							//
							$status = $securityhandle->check_site_variable($name,$value);
							//
							if($status == 0){
								return 0;
							}
						}
						//
						$q_level = $head_info['q_level'];
						$q_origin = $head_info['q_origin'];
						$q_type = $head_info['q_type'];
						$q_difficulty = $head_info['q_difficulty'];
						//
						$query = "update $this->db_table set q_level = '$q_level', q_origin = '$q_origin', q_type='$q_type', q_difficulty='$q_difficulty' where q_unique_id = '$q_unique_id' limit 1";
						//
						if($this->run_query($query)){
							return 1;
						}else{
							return 0;
						}
					}else{
						//
						return 0;
					}
				}else{
					return 0;
				}
			}else{
				$make_file = $filehandle-> make_file($link);
				//
				if($make_file == 1){
					//
					$writeto_file = $filehandle->  writeto_file($link,$content);
					if($writeto_file ==1){
						//
						return 1;
					}else{
						//
						return 0;
					}
				}else{
					return 0;
				}
			}
		}else{return 0;}
	}
	//Contr:
	public function delete_question($q_unique_id,$filehandle){
		//
		$this->manual_construct_q_unique_id($q_unique_id);
		//
		if(isset($this->q_id)){
			//
			$exploded = explode("/", $this->q_link);
			array_pop($exploded);
			$dir = implode("/", $exploded);
			//
			if(!empty($dir)){
				$link = $this->root.$dir;
			}else{
				return 0;
			}
			//
			$delete = $filehandle -> rrmdir($link);
			//
			if($delete == 1){
				//
				if($this->delete_row($q_unique_id)){
					return 1;
				}else{
					return 0;
				}
			}else{
				return 0;
			}
		}else{return 0;}
	}
	//Contr:
	public function delete_question_editor($q_unique_id,$filehandle){
		//
		$this->db_table = ' questions_editors ';
		$this->manual_construct_q_unique_id($q_unique_id);
		//
		if(isset($this->q_id)){
			//
			$exploded = explode("/", $this->q_link);
			array_pop($exploded);
			$dir = implode("/", $exploded);
			//
			if(!empty($dir)){
				$link = $this->root.$dir;
			}else{
				return 0;
			}
			//
			$delete = $filehandle -> rrmdir($link);
			//
			if($delete == 1){
				//
				if($this->delete_row($q_unique_id)){
					return 1;
				}else{
					return 0;
				}
			}else{
				return 0;
			}
		}else{return 0;}
	}
	//Contr:
	public function image_question_upload($unique,$files,$utility){
		//
		$this->manual_construct_q_unique_id($unique);
		//define variables
		$fileName = $files["name"]; // The file name
		$fileTmpLoc = $files["tmp_name"]; // File in the PHP tmp folder
		$fileType = $files["type"]; // The type of file it is
		$fileSize = $files["size"]; // File size in bytes
		$fileErrorMsg = $files["error"]; // 0 for false... and 1 for true
		//
		$dir = $this->q_directory;
		$create_dir = "$this->root/$dir/files";
		$destination = "$this->root/$dir/files/$fileName";
		
		//inserting the file into the directory of the point
		if(is_dir($create_dir)){
			if(move_uploaded_file($fileTmpLoc, "$destination")){
				return 1; 
			}else{
				return 0;
			}
		}elseif(!is_dir($create_dir)){
			mkdir("$create_dir", 0777);
			if(is_dir($create_dir)){
				if(move_uploaded_file($fileTmpLoc, "$destination")){
					if (file_exists($destination)){
						return 1 ;
					}else{
						return 0; 
					}
				}else {
					return 0; 
				}
			}else{
				return  0;
			}
		}
	}	
	//Contr:
	public function image_question_upload_editor($unique,$files,$utility){
		//
		$this->db_table = ' questions_editors ';
		$this->manual_construct_q_unique_id($unique);
		//define variables
		$fileName = $files["name"]; // The file name
		$fileTmpLoc = $files["tmp_name"]; // File in the PHP tmp folder
		$fileType = $files["type"]; // The type of file it is
		$fileSize = $files["size"]; // File size in bytes
		$fileErrorMsg = $files["error"]; // 0 for false... and 1 for true
		//
		$dir = $this->q_directory;
		$create_dir = "$this->root/$dir/files";
		$destination = "$this->root/$dir/files/$fileName";
		
		//inserting the file into the directory of the point
		if(is_dir($create_dir)){
			if(move_uploaded_file($fileTmpLoc, "$destination")){
				return 1; 
			}else{
				return 0;
			}
		}elseif(!is_dir($create_dir)){
			mkdir("$create_dir", 0777);
			if(is_dir($create_dir)){
				if(move_uploaded_file($fileTmpLoc, "$destination")){
					if (file_exists($destination)){
						return 1 ;
					}else{
						return 0; 
					}
				}else {
					return 0; 
				}
			}else{
				return  0;
			}
		}
	}
	//Contr:
	public function assign_editor_question($filter,$subject,$moduel,$chapter,$topic,$pt_unique_id,$task_payment_amount,$pointcontr){
		//check that no spec point editing task is listed
		$check_q = "select * from active_tasks where pt_subject = '$subject' and pt_moduel = '$moduel' and pt_chapter='$chapter' limit 1";
		$run = $this->run_query($check_q);
		//
		$check_count = mysqli_num_rows($run);
		if($check_count > 0){
			return 0;
		}
		//
		if($filter == 'chapter'){
			//
			$query = "select * from active_tasks_questions where q_subject='$subject' and q_moduel = '$moduel' and q_chapter='$chapter' and q_topic='' and q_point = ''";
			$query_2 = "insert into active_tasks_questions (q_subject,q_moduel,q_chapter,task_payment_amount) values ('$subject','$moduel','$chapter','$task_payment_amount')";
		}elseif($filter =='topic'){
			//
			$query = "select * from active_tasks_questions where q_subject='$subject' and q_moduel = '$moduel' and q_chapter='$chapter' and q_topic='$topic' and q_point = ''";
			$query_2 = "insert into active_tasks_questions (q_subject,q_moduel,q_chapter,q_topic,task_payment_amount) values ('$subject','$moduel','$chapter','$topic','$task_payment_amount')";
		}else{
			if($filter == 'point'){
				//
				$query = "select * from active_tasks_questions where q_subject='$subject' and q_moduel = '$moduel' and q_chapter='$chapter' and q_topic='$topic' and q_point = '$pt_unique_id'";
				$query_2 = "insert into active_tasks_questions (q_subject,q_moduel,q_chapter,q_topic,q_point,task_payment_amount) values ('$subject','$moduel','$chapter','$topic','$pt_unique_id','$task_payment_amount')";
			}else{
				return 0;
			}
		}
		//checking if the chapter is present
		
		$run = $this->run_query($query);
		//
		$count = mysqli_num_rows($run);
		//different quries are needed for different cases
		if($count == 0){
			//
			if($this->run_query($query_2)){
				return 1;
			}else{
				return 0;
			}
		}elseif($count == 1){
			return 0;
		}else{
			return 0;
		}
	}
	//Contr
	public function unlist_editor_task($subject,$moduel,$chapter,$topic,$q_point){
		//
		$query = "select * from active_tasks_questions where q_subject='$subject' and q_moduel = '$moduel' and q_chapter='$chapter' and q_topic='$topic' and q_point ='$q_point'";
		$run = $this->run_query($query);
		//
		$count = mysqli_num_rows($run);
		//
		if($count == 1){
			//
			$query_2 = "delete from active_tasks_questions where q_subject='$subject' and q_moduel = '$moduel' and q_chapter='$chapter' and q_topic='$topic' and q_point ='$q_point' limit 1";
			//
			if($this->run_query($query_2)){
				return 1;
			}else{
				return 0;
			}
		}elseif($count == 0){
			return 0;
		}else{
			return 0;
		}
	}
	//Contr
	public function get_questions_dir($subject,$moduel,$chapter,$topic,$q_point){
		//
		$query = "select q_directory from $this->db_table where q_subject= '$subject' and q_moduel = '$moduel' and q_chapter = '$chapter' and q_topic='$topic' and q_point='$q_point' limit 1";
		$run = $this -> run_query($query);
		//
		$count = mysqli_num_rows($run);
		//
		if($count == 1 ){
			//
			$assoc = mysqli_fetch_assoc($run);
			$dir_long = $assoc['q_directory'];
			$dir_exp = explode('/',$dir_long);
			array_pop($dir_exp);
			$dir = implode($dir_exp,'/');
			//
			return $dir;
		}else{
			return 0;
		}
	}
	//CONTR
	public function update_topicField_selection_question($subject,$moduel,$utility,$usercontr){
		//setting up for user prefrences
		$user_level = $usercontr -> user_level;
		//
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
		$query = "select q_chapter  from $customtable where 1 and q_subject = '$subject'
				and q_moduel='$moduel' group by q_chapter asc ";
		$run = $this->run_query($query);
		//
		$result = mysqli_num_rows($run);
		//
		$load = "<option value='all'> All </option>";
		if ($result >0){
			while ($fetch_all = mysqli_fetch_assoc($run)){
				$chapter = $fetch_all['q_chapter'];
				$disp = $utility -> make_display_header($chapter);
				$load.= "<option value='$chapter'> $disp </option>";
			}
			return $load;
		}else{
			return "<option> None </option>";
		}
		
	}
	//Contr
	public function mark_question($user_unique_id,$question_unique_id,$mark,$total){
		//
		$time = time();
		$question = $this-> manual_construct_q_unique_id($question_unique_id);
		//
		$level = $this->q_level;
		$subject = $this->q_subject;
		$moduel = $this->q_moduel;
		$chapter = $this->q_chapter;
		$topic = $this->q_topic;
		$q_origin = strtolower($this->q_origin);
		$difficulty = $this->q_difficulty;
		$total = $total;
		//
		$check = "select attempt_num from question_track where user_unique = '$user_unique_id' and q_unique_id = '$question_unique_id' order by id desc limit 1";
		$run = $this->run_query($check);
		$count = mysqli_num_rows($run);
		if($count>0){
			while($result = mysqli_fetch_assoc($run)){
				$attempts = $result['attempt_num'] + 1;
			}
		}else{$attempts = 1;}
		//
		$query = "insert into question_track (`user_unique`,`q_level`,`q_subject`,`q_moduel`,`q_origin`,`q_unique_id`,`mark`,`time`,`q_chapter`,`q_difficulty`,`q_total_marks`,`attempt_num`,`q_topic`) values ('$user_unique_id','$level','$subject','$moduel','$q_origin','$question_unique_id','$mark','$time','$chapter','$difficulty','$total','$attempts','$topic')";
		//
		
		//
		if($this->run_query($query)){
			return 1;
		}else{
			return 0;
		}
		
	}
	//Contr
	public function locate_unique_question($q_unique_id,$pointcontr){
		//
		$this->manual_construct_q_unique_id($q_unique_id);
		$pt_unique = $pointcontr->get_point_unique_pointer($this->q_subject,$this->q_moduel,$this->q_chapter,$this->q_topic,$this->q_point);
		$loc = $this->q_loc;
		//
		$link = "https://www.practicepractice.net/P/questions/$this->q_chapter/$loc/$pt_unique#find_$q_unique_id";
		//
		if(isset($pt_unique) and !empty($pt_unique)){
			return $link;
		}else{
			return 0;
		}
	}
	//
	public function get_performance_data(){
		//
		$user_unique = $_SESSION['user_unique_id'];
		//get the date 24 hours ago including the hour
		$month_ago = time() - 2678400;
		//
		if(isset($_SESSION['u_id'])){
			//get data points
			$query = "SELECT time,q_difficulty,mark,q_total_marks,attempt_num FROM question_track where user_unique ='$user_unique' and time >  $month_ago order by q_unique_id,attempt_num asc";
			//
			$run = $this->run_query($query);
			//
			$A = array();
			$count = mysqli_num_rows($run);
			if($count > 0){
				while ($result = mysqli_fetch_assoc($run)){
					//
					$time = $result['time'];
					$diffculty = $result['q_difficulty'];
					$mark = $result['mark'];
					$total_mark = $result['q_total_marks'];
					$attempt_num = $result['attempt_num'];
					//
					if($attempt_num == 1){
						$save_time = $time;
					}
					
					$days_since_first = round(($time - $save_time)/(60*60*24));
					$days_elapsed = floor((time())/(60*60*24)) - floor(($time)/(60*60*24));
					//this may be due to the detailing of the questions.
					if($total_mark > 0){
						$weight = ($diffculty/5 + $mark/$total_mark)/2;
					}else{
						$weight = 0;
					}
					
					//
					$rest_function = exp(	-1*(pow($attempt_num -1,exp(1)))	*	(1/($days_since_first+1))*1.4	);
					//echo (1/$days_elapsed)*1.4.'<br>';
					//
					$q_weight = $weight * $rest_function;
					$A[$days_elapsed][]= $q_weight;
				}
			}else{
				//get data points
				return array('xs' => array(),'ys' => array());
			}
			//
			if(!empty($A)){
				//
				$averages = array(); 
				foreach($A as $day => $values){
					//
					$count = count($values);
					//
					if($count >= 1){
						$averages[$day] = round(array_sum($values)/$count,2) ;
					}else{
						$averages[$day] = 0;
					}
					
				}
				krsort($averages,1);
				$xs = array_keys($averages);
				$ys  = array_values($averages);
				//
				return array('xs' => $xs,'ys' => $ys);
			}
			//
			
		}
		
	}
	//
	public function q_data_gen($utility){
		for($i = 0; $i <= 200; $i++){
			$user_unique = $_SESSION['user_unique_id'];
			$rand = rand(0,1);
			if($rand == 0){
				$q_level ='A';
			}else{
				$q_level = 'AS';
			}
			$q_origin = 'ghost';
			$q_subject = 'ghost';
			$q_moduel  = 'ghost';
			$q_chapter  = 'ghost';
			$q_diffculty = rand(3,5);
			$q_unique = $utility ->tag_generator();
			$mark = rand(4,6);
			$q_total_mark = rand(6,8);
			$attempt_num = rand(1,1);
			$time = rand(0,7);
			$time = time() - $time*86400;

			//
			$query = "insert into question_track (`user_unique`,`q_level`,`q_subject`,`q_moduel`,`q_origin`,`q_unique_id`,`mark`,`time`,`q_chapter`,`q_difficulty`,`q_total_marks`,`attempt_num`) values ('$user_unique','$q_level','$q_subject','$q_moduel','$q_origin','$q_unique','$mark','$time','$q_chapter','$q_diffculty','$q_total_mark','$attempt_num')";
			//
			$run = $this->run_query($query);
		}
	}
	//
	public function get_general_progress($user_unique,$user_level,$user_status,$subject,$moduel,$chapter,$topic,$utility){
		//
		if(!empty($moduel)){
			$moduel_clause = " and q_moduel ='$moduel' ";
		}else{
			$moduel_clause = ' ';
		}
		//
		if(!empty($chapter)){
			$chapter_clause = " and q_chapter ='$chapter' ";
		}else{
			$chapter_clause = ' ';
		}
		//
		if(!empty($topic)){
			if($topic == '00_general'){
				//$topic_clause = " and q_topic = '' ";
				$topic_clause = " ";
			}else{
				$topic_clause = " ";
			}
			
		}else{
			$topic_clause = ' ';
		}
		//
		$filter_resolution = $moduel_clause.$chapter_clause.$topic_clause;
		//
		if($user_level == 'A'){
			$user_level = '';
		}
		$origin = "and q_origin = 'pp_chq'";
		//setting up user filter query
		if($user_level ==''){
			//
			$customtable1 = $this->db_table;
			//
			$customtable2 ="(select * from question_track where q_subject ='$subject' $origin $filter_resolution and user_unique = '$user_unique' order by q_unique_id,attempt_num desc) as A ";
		}elseif($user_level == 'AS'){
			//
			$customtable1 ="(select * from $this->db_table where `q_level` ='AS' and q_subject ='$subject'  $filter_resolution) as A";
			//
			$customtable2 ="(select * from question_track where `q_level` ='AS' and q_subject ='$subject' $origin in ('pp','WWW')  $filter_resolution and user_unique = '$user_unique' order by q_unique_id,attempt_num desc) as A  ";
		}else{
			//
			$customtable1 = $this->db_table;
			//
			$customtable2 ="(select * from question_track where q_subject ='$subject' $origin $filter_resolution and user_unique = '$user_unique' order by q_unique_id,attempt_num desc) as A ";
		}
		//get scored marks from chapter
		$query = "select mark from $customtable2 where q_subject ='$subject' $filter_resolution $origin and user_unique = '$user_unique' group by q_unique_id";
		//
		$run =$this->run_query($query);
		//echo $query;
		$count = mysqli_num_rows($run);
		//
		if($count > 0){
			$scored = 0;
			while($result = mysqli_fetch_assoc($run)){
				$scored = $scored + $result['mark'];
			}
			//get scored marks from chapter
			$query = "select sum(q_total_marks) as total from $customtable1 where q_is_exam = 0 and q_subject ='$subject' $origin $filter_resolution";
			//
			$run =$this->run_query($query);
			$count = mysqli_num_rows($run);
			//
			if($count > 0){
				while($result = mysqli_fetch_assoc($run)){
					$total = $result['total'];
				}
				if($total > 0){
					$ratio = $scored/$total;
					//
					$rgb = $utility -> numberToColorHsl($ratio, 0.40, 1);
					$pct = $ratio * 100;
					return array('pct' => $pct , 'rgb' =>$rgb);
				}else{
					return array('pct' => 0 , 'rgb' =>'');
				}
				
				
			}else{
				return array('pct' => 0 , 'rgb' =>'');
			}
		}else{
			return array('pct' => 0 , 'rgb' =>'');
		}
		
		
	}
	//
	public function update_question_maker_chapters($subject,$moduels,$utility,$usercontr){
		//
		$moduels_list = implode("','",$moduels);
		//
		$len = count($moduels);
		if($len > 0){
			//
			$query = "select q_moduel,q_chapter from $this->db_table where q_subject ='$subject' and q_moduel in ('$moduels_list') group by q_chapter order by q_moduel asc,q_chapter asc";
			$run = $this->run_query($query);
			$count =mysqli_num_rows($run);
			//
			if($count > 0 ){
				//
				$return = array();
				//
				while($result = mysqli_fetch_assoc($run)){
					//
					$moduel = $result['q_moduel'];
					$value = $result['q_chapter'];
					$disp = $utility -> make_display_header($value);
					//
					$return[] = array('value' => $value, 'disp' => $disp,'rel_moduel' => $moduel);
				}
				return $return;
			}else{
				return 0;
			}
		}else{
			return 0;
		}
	}
	//COntr:
	//this takes the discription written by us and extracts the informatio in the made up html tags
	private function get_question_head_info($question_desc){
		// my tags: hidden, video_section,vid,vid_title,vid_link
		//the aim of the code is to remove the madeup html markup and extract the real information contained within
		if(empty($question_desc)){
			$question_desc = ' ';
		}
		$dom = new \DomDocument();
		libxml_use_internal_errors(true);
		$dom->loadHTML($question_desc);
		libxml_use_internal_errors(true);
		
		
		
		//*******to get head
		$output_head = array();//the storage array for each video
		//look for each video element and extract the title and link from the attributes
		foreach ($dom->getElementsByTagName('my_q_head') as $item) {
			//
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
			//
			$output_head = array (
			   'q_level' => $item->getAttribute('q_level'),
			   'q_origin' => $item->getAttribute('q_origin'),
			   'q_type' => $item->getAttribute('q_type'),
			   'q_difficulty' => $item->getAttribute('q_difficulty'),
			   'q_total_marks' => $total_marks
			);
		};
		//return the full details in the hidden description
		return $output_head;
	}
	//Contr
	public function get_paper_papermaker($q_level,$q_subject,$q_moduel,$q_chapter,$q_type,$q_difficulty,$q_is_exam,$utility,$usercontr){
		//
		if(isset($usercontr->user)){
			//
			$user_unique = $usercontr->user;
			//
			$q_level = $utility->array_in_like_origin_query($q_level);
			$q_subject = $utility->array_in_query($q_subject);
			$q_moduel = $utility->array_in_query($q_moduel);
			$q_chapter = $utility->array_in_query($q_chapter);
			$q_type = $utility->array_in_query($q_type);
			$q_difficulty = $utility->array_in_query($q_difficulty);
			$q_is_exam = $utility->array_in_query($q_is_exam);
			//MAKING  clauses
			
			if($q_level !== 0){
				$q_level =" and q_origin like ('%-%-$q_level-%-%')";
			}else{
				$q_level ='';
			}
			////MAKING  clauses
			if($q_subject !== 0){
				$q_subject =" and q_subject in ('$q_subject') ";
			}else{
				$q_subject ='';
			}
			////MAKING  clauses
			if($q_moduel !== 0){
				$q_moduel =" and q_moduel in ('$q_moduel') ";
			}else{
				$q_moduel ='';
			}
			////MAKING  clauses
			if($q_chapter !== 0){
				$q_chapter =" and q_chapter in ('$q_chapter') ";
			}else{
				$q_chapter ='';
			}
			////MAKING  clauses
			if($q_type !== 0){
				$q_type =" and q_type in ('$q_type') ";
			}else{
				$q_type ='';
			}
			////MAKING  clauses
			if($q_difficulty !== 0){
				$q_difficulty =" and q_difficulty in ('$q_difficulty') ";
			}else{
				$q_difficulty ='';
			}
			////MAKING  clauses
			if($q_is_exam !== 0){
				$q_is_exam ='';
			}else{
				$q_is_exam ="and q_is_exam = 0";
			}
			//
			$full_where_clause = $q_level.$q_subject.$q_moduel.$q_chapter.$q_type.$q_difficulty.$q_is_exam;
			//selects all of the attempted questions from twoweeks ago and sooner
			$twoweeks_ago = time() - 60*60*24*14;
			$q_exclusion_query = "select q_unique_id from question_track where user_unique = '$user_unique' $q_level $q_subject $q_moduel $q_chapter $q_difficulty and time > $twoweeks_ago";
			//
			$run = $this->run_query($q_exclusion_query);
			$count = mysqli_num_rows($run);
			//
			if($count > 0){
				//
				$array = array();
				while($result = mysqli_fetch_assoc($run)){
					//
					$array[] = $result['q_unique_id'];
				}
				//
				$q_exclude = $utility->array_in_query($array);
				//MAKING  exclusion clause, to remove and recently attempted questions from the list of possibilities
				if($q_exclude !== 0){
					$q_exclude =" and q_unique_id NOT IN ('$q_exclude') ";
				}else{
					$q_exclude ='';
				}
			}else{$q_exclude = '';}
			//get all of the questions that could be considered
			$random_query = "select * from $this->db_table where 1 $full_where_clause $q_exclude and q_origin NOT IN ('pp','WWW','pp_chq') order by rand() limit 10";
			$ordered_query = "select q_subject,q_unique_id,q_total_marks from ($random_query) as A order by q_level desc,q_subject asc,q_moduel asc,q_chapter asc,q_topic asc,q_difficulty asc,q_total_marks asc";
			//
			$run = $this->run_query($ordered_query);
			$count = mysqli_num_rows($run);
			if($count > 0){
				//
				$paper = array('user_unique_id' => $user_unique,'marks'=> 0,'time' => time());
				//
				while($paper_q = mysqli_fetch_assoc($run)){
					//
					$paper['subject'] = $paper_q['q_subject'];
					$paper['qs_unique'][] = $paper_q['q_unique_id'];
					$paper['marks'] += $paper_q['q_total_marks'];
				}
				//create paper db_entry
				$collapsed_paper = $utility ->collapse_paper_array($paper);
				$paper_unique = $collapsed_paper['paper_unique'];
				$query = $collapsed_paper['query'];
				$insert_paper_q = "
				insert into user_papers 
				(`user_unique_id`,`paper_unique_id`,`subject`,`marks`,`time`,`q_0`,`q_1`,`q_2`,`q_3`,`q_4`,`q_5`,`q_6`,`q_7`,`q_8`,`q_9`)
				values
				($query)
				";
				if($this->run_query($insert_paper_q)){
					//
					return "P/paper_maker/$user_unique/$paper_unique";
				}else{
					return 0;
				}
				
			}else{return 0;}
			
			
		}else{return 0;}
		
		
	}
	//Contr:
	public function relocate_question_files($q_unique_id,$pt_unique_id,$filehandle,$pointcontr,$utility){
		//
		$this->manual_construct_q_unique_id($q_unique_id);
		if(isset($this->q_id) and !empty($this->q_id)){
			$src = "/var/www/html/Practice-Practice/$this->q_directory";
			//
			$pointobj = $pointcontr -> make_obj_unique_id($pt_unique_id);
			if(isset($pointobj['pt_id']) and !empty($pointobj['pt_id'])){
				$pt_top = $pointobj['pt_topic'];
				$pt_dir = $pointobj['pt_directory'];
				$dest = substr($pt_dir,0,strpos($pt_dir,"/D_$pt_top")); 
				$dest = "/var/www/html/Practice-Practice/$dest/questions/$this->q_unique_id";
				//
				if(!is_dir($src)){
					mkdir($src,0755, true);
				}if(!is_dir($dest)){
					mkdir($dest,0755, true);
				}
				if($filehandle ->recurse_copy($src,$dest)){
					if($filehandle ->rrmdir($src)){
						$query = "delete from AI_target_predictions where q_id= '$this->q_id'";
						if($this->run_query($query)){
							if($this->inject_question ($dest,$filehandle,$utility)){
								return 1;
							}else{return 0;}
						}else{
							return 0;
						}
					}
				}else{
					return 0;
				}
			}else{
				return 0;
			}
			
		}else{
			return 0;
		}
		
	}
	//Contr:
	public function relocate_question_files2($q_unique_id,$pt_unique_id,$filehandle,$pointcontr,$utility){
		//
		$this->manual_construct_q_unique_id($q_unique_id);
		if(isset($this->q_id) and !empty($this->q_id)){
			$src = "/var/www/html/Practice-Practice/$this->q_directory";
			//
			$pointobj = $pointcontr -> make_obj_unique_id($pt_unique_id);
			if(isset($pointobj['pt_id']) and !empty($pointobj['pt_id'])){
				$pt_top = $pointobj['pt_topic'];
				$pt_dir = $pointobj['pt_directory'];
				$dest = substr($pt_dir,0,strpos($pt_dir,"/D_$pt_top")); 
				$dest = "/var/www/html/Practice-Practice/$dest/questions/$this->q_unique_id";
				//
				if(!is_dir($src)){
					mkdir($src,0755, true);
				}if(!is_dir($dest)){
					mkdir($dest,0755, true);
				}
				if($filehandle ->recurse_copy($src,$dest)){
					if($filehandle ->rrmdir($src)){
						if($this->inject_question ($dest,$filehandle,$utility)){
							return 1;
						}else{return 0;}		
					}else{
						return 0;
					}
				}else{
					return 0;
				}
			}else{
				return 0;
			}
			
		}else{
			return 0;
		}
		
	}
	//Contr
	public function get_pointFiles_uniqueandname($q_unique_id,$filename){
		//
		$this->manual_construct_q_unique_id($q_unique_id);
		//
		$dir = $this->q_directory;
		return "$this->root/$dir/files/$d";
	}
	//
	public function ibo_db_sorter($pointcontr,$utility,$filehandle){
		//
		$query = "select * from ibo_qb2 where 1";
		$run = $this->run_query($query);
		$count = mysqli_num_rows($run);
		$images_dir = "$this->root/papers_pre/global_images";
		if($count > 0){
			while ($result = mysqli_fetch_assoc($run)){
				$subject = $result['subject'];
				$html = $result['html'];
				//
				$dom = new \DomDocument();
				libxml_use_internal_errors(true);
				$dom->loadHTML($html);
				$tag_type = 'my_img';
				$output_images = array();
				libxml_use_internal_errors(true);
				foreach ($dom->getElementsByTagName($tag_type) as $item) {
					//******edit 

					$img =$item->getAttribute('name');
					if(!is_file($images_dir.'/'.$img)){
						echo 'e <br>';
					}
					$output_images[] = $img;
				}
				if($subject == 'Mathematics HL' or $subject == 'Mathematics SL'){
					$pt_unique = '3ebb24f2e3';
				}elseif($subject == 'Physics'){
					$pt_unique = 'd653c10d8f';
				}else{
					if($subject == 'Biology'){
						$pt_unique = '5f3d85210e';
					}elseif($subject == 'Chemistry'){
						$pt_unique = '8d5f92c239';
					}else{
						$pt_unique = '3ebb24f2e3';
					}
				}
				//compile the questions including the loc.txt file
				$q_tag = $utility -> tag_generator();
				$question_dir ="$this->root/papers_pre/qs/$q_tag";
				try{
					mkdir("$question_dir/files",0755,true);
				}catch(Exception $e){
					echo "$e<br>";
				}
				//copying files
				if(count($output_images) > 0){
					foreach ($output_images as $key=>$name){
						$src = $images_dir.'/'.$name;
						$dest = $question_dir.'/files/'.$name;
						if (!copy($src, $dest)) {
							echo "failed to copy $file...\n";
						}
					}
				}
				//insert content
				$html = $html;
				$html = preg_replace('/[^\x00-\x7F]+/', '', $html);
				$myfile = fopen("$question_dir/type_question.tag_$q_tag.txt", "w") or die("Unable to open file!");
				fwrite($myfile, $html);
				fclose($myfile);
				//insert location file 
				$loc_file = fopen("$question_dir/loc.txt", "w") or die("Unable to open file!");
				fwrite($loc_file, $pt_unique);
				fclose($loc_file);
			}
			echo 'done.';
		}
	}
	//
	public function distribute_questions($pointcontr,$utility,$filehandle){
		//
		$dir_main    = '/var/www/html/Practice-Practice/papers_pre/qs';
		$files1 = scandir($dir_main);
		foreach($files1 as $dir){
			if($dir !== '.' and $dir !== '..'){
				$loc = file_get_contents("$dir_main/$dir/loc.txt");
				$pointobj = $pointcontr -> make_obj_unique_id($loc);
				$pt_top = $pointobj['pt_topic'];
				$pt_dir = $pointobj['pt_directory'];
				$dest = substr($pt_dir,0,strpos($pt_dir,"/D_$pt_top")); 
				$dest = "/var/www/html/Practice-Practice/$dest/questions/$dir";
				$src = "$dir_main/$dir";
				//copy, and inject
				if($filehandle ->recurse_copy($src,$dest)){
					if($filehandle ->rrmdir($src)){
							$i = 1;
					}else{
						echo 'e <br>';
					}
				}else{
					echo 'e <br>';
				}
			}
		}
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}
