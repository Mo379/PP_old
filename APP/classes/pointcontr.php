<?php
namespace MVC;

//gives the model instructions and information so the model can do what we want
//it insert or updates to the database
class pointcontr extends point{
	//
	public function temp_script($filehandle){
		//
		$query = "SELECT * FROM `questions` WHERE 1 and q_origin like ('%-OCR-C34-%-%')";
		$run = $this->run_query($query);
		$count = mysqli_num_rows($run);
		if ($count > 0){
			while($result = mysqli_fetch_assoc($run)){
				$dir = $this->root . $result['q_directory'];
				if(strpos($dir, "specifications") !== false and is_dir($dir) and $dir != $this->root){
					$filehandle -> rrmdir($dir);
				}
			}
		}else{
			echo 'no results';
		}
	}
	//contr:
	public function make_obj_unique_id($pt_unique_id){
		//
		$this->manual_construct_unique_id($pt_unique_id);
		return $this->input_info;
	}
	//Contr:
	public function inject_point($dir,$filehandle,$utility){
		//for all the specification points
		$all_txt = $filehandle -> loop($dir,'txt');
		$tag_process = $filehandle -> tag_processing($all_txt,'txt');
		$points = $filehandle -> filter_type_point($all_txt);
		$tags = $filehandle ->  extract_unique_ids($points);
		//
		$grouped = $filehandle -> group_files($tags,$points,$points);
		$directories = $filehandle-> extract_files_dir($points);
		$grouped2 = $filehandle -> group_files2($grouped,$directories);
		$pt_info = $filehandle -> info_extractor($grouped);
		//
		$head_info = $filehandle->read_points_heads($points);
		//
		$grouped3 = $filehandle -> group_files2($grouped2,$head_info);
		$grouped3 = $filehandle -> group_files2($grouped3,$pt_info);
		//get point files in the directory
		$all_files_del = $filehandle -> loop($dir,'txt');
		$points_del = $filehandle -> filter_type_point($all_files_del);
		
		//from the point text files, get the unique ids, those are the points we want
		$tags_present = $filehandle ->extract_unique_ids($points_del);
		//trim the dir name to remove root
		$spec_dir = substr($dir,strpos($dir,'/specifications/'),strlen($dir));
		//open the database and get the tags of points with this directory id prsent 
		$Dbtags = $this->db_tags_return($spec_dir);
		//find which tags in the database arent present in the local files
		$stray_tags = $utility->find_stray($tags_present,$Dbtags);
		//
		$delete_strays = $this->delete_rows($stray_tags);
		//left to figure out how to make the script insert on the first run instead of two
		$object_info2 = $filehandle -> make_assoc($grouped3,array(
			'pt_unique_id',
			'pt_link',
			'pt_link',
			'pt_directory',
			'pt_head_info',
			'general_info',
		));
		
		//this completes the processing for spec points
		foreach ($object_info2 as $point){
			$this->treat_db($point);
		}
		//order the spec points accodringly
		$this->spec_point_ordering();
		return 1;
	}
	//Contr:
	public function inject_point_editor_private($editor_unique,$dir,$filehandle,$utility){
		//for all the specification points
		$all_txt = $filehandle -> loop($dir,'txt');
		$tag_process = $filehandle -> tag_processing($all_txt,'txt');
		$points = $filehandle -> filter_type_point($all_txt);
		$tags = $filehandle ->  extract_unique_ids($points);
		//
		$grouped = $filehandle -> group_files($tags,$points,$points);
		$directories = $filehandle-> extract_files_dir($points);
		$grouped2 = $filehandle -> group_files2($grouped,$directories);
		$pt_info = $filehandle -> info_extractor($grouped);
		$grouped3 = $filehandle -> group_files2($grouped2,$pt_info);
		//get point files in the directory
		$all_files_del = $filehandle -> loop($dir,'txt');
		$points_del = $filehandle -> filter_type_point($all_files_del);
		//from the point text files, get the unique ids, those are the points we want
		$tags_present = $filehandle ->extract_unique_ids($points_del);
		//trim the dir name to remove root
		$spec_dir = substr($dir,strpos($dir,"users/$editor_unique/specifications/"),strlen($dir));
		//open the database and get the tags of points with this directory id prsent 
		$Dbtags = $this->db_tags_return($spec_dir);
		//find which tags in the database arent present in the local files
		$stray_tags = $utility->find_stray($tags_present,$Dbtags);
		//
		$delete_strays = $this->delete_rows($stray_tags);
		//left to figure out how to make the script insert on the first run instead of two
		$object_info2 = $filehandle -> make_assoc($grouped3,array(
			'pt_unique_id',
			'pt_link',
			'pt_link',
			'pt_directory',
			'general_info',
		));
		//this completes the processing for spec points
		foreach ($object_info2 as $point){
			$this->treat_db($point);
		}
		//order the spec points accodringly
		$this->spec_point_ordering();
		return 1;
	}
	//Contr: updates the description of a point 
	public function description_update($info,$filehandle,$securityhandle){
		$this->manual_construct($info);
		if(!empty($this->pt_description)){
			//
			$link = $this->get_link_by_pt_id();
			//
			$file_link = $link;
			//
			$filehandle->rewrite_txt_file($file_link,$this->pt_description);
			//
			$head_info = $this->get_point_head_info($this->pt_description);
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
			$pt_level = $head_info['point_level'];
			$pt_board = $head_info['pt_board'];
			//
			$query = "update $this->db_table set pt_level = '$pt_level', pt_board = '$pt_board' where pt_id = '$this->pt_id' limit 1";
			//
			if($this->run_query($query)){
				return 1;
			}else{
				return 0;
			}
			//
		}else{
			return 0 ;
		}
	}
	//Contr: 
	public function upload_specimage($id,$files,$utility){
		//
		$info = $utility->make_assoc2(array($id),array('pt_id'));
		//
		$this->manual_construct($info);
		//define variables
		$fileName = $files["name"]; // The file name
		$fileTmpLoc = $files["tmp_name"]; // File in the PHP tmp folder
		$fileType = $files["type"]; // The type of file it is
		$fileSize = $files["size"]; // File size in bytes
		$fileErrorMsg = $files["error"]; // 0 for false... and 1 for true
		//
		$dir = $this->get_directory_by_pt_id();
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
	public function intraconnect_question($pt_unique_id,$q_unique_id,$filehandle){
		//open point cache file and save the question
		$this->manual_construct_unique_id($pt_unique_id);
		//connecting the question to the point 
		return $filehandle->intraconnection("$this->root/$this->directory",	'q',$q_unique_id);
		//
		
	}
	//Contr:
	public function add_new_specpoint($pt_unique_id,$filehandle,$utility){
		//
		$this->manual_construct_unique_id($pt_unique_id);
		//
		$sisters_assoc = $this->get_sister_points();
		$point_num = $this->pt_number;
		$point_dir = $this->pt_directory;
		//
		foreach($sisters_assoc as $pt){
			$this->manual_construct($pt);
			$this->point_shimmy($point_num,$utility);
		}
		//get the directory of the topic containing all of these now edited points
		$dir = str_replace("N_$point_num",'',$point_dir);
		//
		return $this->inject_point("$this->root/$dir",$filehandle,$utility);
		//
	}	
	//Contr:
	public function remove_moduel($point_unique,$filehandle,$utility){
		//open point cache file and save the question
		$this->manual_construct_unique_id($point_unique);
		$dir_short = "/$this->info_src/universal/A_$this->pt_subject/";
		//
		$sisters_assoc = $this->get_sister_moduels_reverse($dir_short);
		$pt_number = $this->get_pt_number_by_pt_id();
		$full_link = $this->get_directory_by_pt_id();
		//
		$moduel_link = substr($full_link,0,strpos($full_link,"/C_$this->pt_chapter"));
		
		//check if all the topics are named correctly
		foreach($sisters_assoc as $fetch_all){
			$moduel = $fetch_all['pt_moduel'];
			//
			$number = substr($moduel,0,strpos($moduel,'_'));
			$name = substr($moduel,strpos($moduel,'_')+1,strlen($moduel));
			//check if all the topics are named correctly
			if(!is_numeric($number) or empty($name)){
				return 0;
			}
			//
		}
		
		//remove the topic
		if(!empty($point_unique) and !empty($moduel_link) and !empty($pt_number)){
			//
			$filehandle->move_toDelete("$this->root/$moduel_link");
		}else{
			return 0;
		}
		
		
		//
		$status = $this->negative_moduel_shimmy($sisters_assoc,$dir_short,$this->pt_moduel,$utility);
		//
		$dir = "$this->root/$this->info_src/universal/A_$this->pt_subject";
		//
		if($status == 1){
			$this->inject_point($dir,$filehandle,$utility);
			return $this->inject_point($dir,$filehandle,$utility);
		}else{
			return $status;
		}
		
	}
	//Contr:
	public function remove_chapter($point_unique,$filehandle,$utility){
		//open point cache file and save the question
		$this->manual_construct_unique_id($point_unique);
		$dir_short = "/$this->info_src/universal/A_$this->pt_subject/B_$this->pt_moduel/";
		//
		$sisters_assoc = $this->get_sister_chapters_reverse($dir_short);
		$pt_number = $this->get_pt_number_by_pt_id();
		$full_link = $this->get_directory_by_pt_id();
		//
		$chapter_link = substr($full_link,0,strpos($full_link,"/D_$this->pt_topic"));
		
		
		//check if all the topics are named correctly
		foreach($sisters_assoc as $fetch_all){
			$chapter = $fetch_all['pt_chapter'];
			//
			$number = substr($chapter,0,strpos($chapter,'_'));
			$name = substr($chapter,strpos($chapter,'_')+1,strlen($chapter));
			//check if all the topics are named correctly
			if(!is_numeric($number) or empty($name)){
				return 0;
			}
			//
		}
		
		
		//remove the topic
		if(!empty($point_unique) and !empty($chapter_link) and !empty($pt_number)){
			//
			$filehandle->move_toDelete("$this->root/$chapter_link");
		}else{
			return 0;
		}
		
		
		//
		$status = $this->negative_chapter_shimmy($sisters_assoc,$dir_short,$this->pt_chapter,$utility);
		//
		$dir = "$this->root/$this->info_src/universal/A_$this->pt_subject/B_$this->pt_moduel";
		//
		if($status == 1){
			$this->inject_point($dir,$filehandle,$utility);
			return $this->inject_point($dir,$filehandle,$utility);
		}else{
			return $status;
		}
		
	}
	//Contr:
	public function remove_topic($point_unique,$filehandle,$utility){
		//open point cache file and save the question
		$this->manual_construct_unique_id($point_unique);
		$dir_short = "/$this->info_src/universal/A_$this->pt_subject/B_$this->pt_moduel/C_$this->pt_chapter";
		//
		$sisters_assoc = $this->get_sister_topics_reverse($dir_short);
		$pt_number = $this->get_pt_number_by_pt_id();
		$full_link = $this->get_directory_by_pt_id();
		//
		$topic_link = substr($full_link,0,strpos($full_link,"/N_$pt_number"));
		//check if all the topics are named correctly
		foreach($sisters_assoc as $fetch_all){
			$topic = $fetch_all['pt_topic'];
			//
			$number = substr($topic,0,strpos($topic,'_'));
			$name = substr($topic,strpos($topic,'_')+1,strlen($topic));
			//check if all the topics are named correctly
			if(!is_numeric($number) or empty($name)){
				return 0;
			}
			//
		}
		//remove the topic
		if(!empty($point_unique) and !empty($topic_link) and !empty($pt_number)){
			//
			$filehandle->move_toDelete("$this->root/$topic_link");
			//
			$dir = substr($topic_link,0,strpos($topic_link,'/D_'));
			//s
		}else{
			return 0;
		}
		//
		$status = $this->negative_topic_shimmy($sisters_assoc,$dir_short,$this->pt_topic,$utility);
		//
		$dir = "$this->root/$this->info_src/universal/A_$this->pt_subject/B_$this->pt_moduel";
		
		if($status == 1){
			$this->inject_point($dir,$filehandle,$utility);
			return $this->inject_point($dir,$filehandle,$utility);
		}else{
			return $status;
		}
		
		
	}
	//Contr:
	public function remove_specpoint($pt_unique_id,$filehandle,$utility){
		//
		$this->manual_construct_unique_id($pt_unique_id);
		//
		$sisters_assoc = $this->get_sister_points_reverse();
		$point_num = $this->pt_number;
		$point_dir = $this->pt_directory;
		//remove the point
		if(!empty($point_dir)){
			//
			$filehandle->move_toDelete("$this->root/$point_dir");
		}else{
			return 0;
		}
		//
		foreach($sisters_assoc as $pt){
			$this->manual_construct($pt);
			$this->negative_point_shimmy($point_num,$utility);
		}
		//get the directory of the topic containing all of these now edited points
		$dir = str_replace("N_$point_num",'',$point_dir);
		//
		return $this->inject_point("$this->root/$dir",$filehandle,$utility);
		//
	}
	//Contr: make the pairing options to allow question pairing, used in spec page
	//adds a new point to the neighbourhood by pushing all the points below once 
	//takes a refrence that points to where the point pushing starts
	public function moduel_shimmy($sisters_assoc,$dir_short,$reference_moduel_name,$utility){
		//scan the directory to get sister topics
		$dir = "$this->root/$dir_short";
		//check if all the topics are named correctly
		foreach($sisters_assoc as $fetch_all){
			$moduel = $fetch_all['pt_moduel'];
			//
			$number = substr($moduel,0,strpos($moduel,'_'));
			$name = substr($moduel,strpos($moduel,'_')+1,strlen($moduel));
			//check if all the topics are named correctly
			if(!is_numeric($number) or strlen((string)$number) != 2  or empty($name) ){
				return 0;
			}
			//
		}
		
		
		//the reference topic's number
		$ref_number = substr($reference_moduel_name,0,strpos($reference_moduel_name,'_'));
		//new_topic's num
		$new_moduel_num = $ref_number +1 ;
		//loop through each sister topic
		foreach($sisters_assoc as $fetch_all){
			$moduel = $fetch_all['pt_moduel'];
			//break down the name of the sister topic
			$prefix = 'B';
			$number = substr($moduel,0,strpos($moduel,'_'));
			$name = substr($moduel,strpos($moduel,'_')+1,strlen($moduel));
			//see if the sister topic is below the reference topic
			if($number > $ref_number){
				
				//for a sister topic underneath the reference, move it's numerical value by a single unit
				$num_old = sprintf("%'02d", $number );
				$num_new = sprintf("%'02d", $number + 1);
				//rename the sister topic
				$old_sister_name = "$dir/$prefix".'_'.$num_old.'_'.$name;
				$new_sister_name = "$dir/$prefix".'_'.$num_new.'_'.$name;
				//
				rename($old_sister_name,$new_sister_name);
			}
		}
		
		
		//left to make each of the above to be made at the click of a button
		$new = sprintf("%'02d", $new_moduel_num );
		//
		$new_tag_n = $utility->tag_generator();
		$new_tag = $utility->tag_generator();
		//
		$new_moduel_link = "$dir/B_$new"."_$new_tag_n";
		$new_chapter_link = "$new_moduel_link/C_01"."_new";
		$new_topic_link = "$new_chapter_link/D_01_new";
		$new_topic_point = "$new_topic_link/N_1";
		$point_file = "$new_topic_point/type_point.tag_$new_tag.txt";	
		
		
		//
		if(!is_dir($new_topic_point)){
			mkdir($new_topic_point,0777,true);
		}
		if(!is_dir($new_topic_point)){
			return 0;
			die;
		}
		$file = fopen($point_file,'a+');
		fwrite($file, '
<hidden>
<point_title>some title</point_title>
<my_vid 
vid_title = 
vid_link = 
> 
</my_vid>
</hidden>
xxxxxxxxxxxxx
		');
		
		
		fclose($file);
		if(is_file($point_file)){
			return 1;
		}else{
			return 0;
		}
	}
	//Contr:
	public function chapter_shimmy($sisters_assoc,$dir_short,$reference_chapter_name,$utility){
		//scan the directory to get sister topics
		$dir = "$this->root/$dir_short";
		//check if all the topics are named correctly
		foreach($sisters_assoc as $fetch_all){
			$chapter = $fetch_all['pt_chapter'];
			//
			$number = substr($chapter,0,strpos($chapter,'_'));
			$name = substr($chapter,strpos($chapter,'_')+1,strlen($chapter));
			//check if all the topics are named correctly
			if(!is_numeric($number) or strlen((string)$number) != 2  or empty($name) ){
				return 0;
			}
			//
		}
		
		
		//the reference topic's number
		$ref_number = substr($reference_chapter_name,0,strpos($reference_chapter_name,'_'));
		//new_topic's num
		$new_chapter_num = $ref_number +1 ;
		//loop through each sister topic
		foreach($sisters_assoc as $fetch_all){
			$chapter = $fetch_all['pt_chapter'];
			//break down the name of the sister topic
			$prefix = 'C';
			$number = substr($chapter,0,strpos($chapter,'_'));
			$name = substr($chapter,strpos($chapter,'_')+1,strlen($chapter));
			//see if the sister topic is below the reference topic
			if($number > $ref_number){
				
				//for a sister topic underneath the reference, move it's numerical value by a single unit
				$num_old = sprintf("%'02d", $number );
				$num_new = sprintf("%'02d", $number + 1);
				//rename the sister topic
				$old_sister_name = "$dir/$prefix".'_'.$num_old.'_'.$name;
				$new_sister_name = "$dir/$prefix".'_'.$num_new.'_'.$name;
				//
				rename($old_sister_name,$new_sister_name);
			}
		}
		
		
		//left to make each of the above to be made at the click of a button
		$new = sprintf("%'02d", $new_chapter_num );
		//
		$new_tag_n = $utility->tag_generator();
		$new_tag = $utility->tag_generator();
		//
		$new_chapter_link = "$dir/C_$new"."_$new_tag_n";
		$new_topic_link = "$new_chapter_link/D_01_new";
		$new_topic_point = "$new_topic_link/N_1";
		$point_file = "$new_topic_point/type_point.tag_$new_tag.txt";	
		
		
		//
		if(!is_dir($new_topic_point)){
			mkdir($new_topic_point,0777,true);
		}
		if(!is_dir($new_topic_point)){
			return 0;
			die;
		}
		$file = fopen($point_file,'a+');
		fwrite($file, '
<hidden>
<point_title>some title</point_title>
<my_vid 
vid_title = 
vid_link = 
> 
</my_vid>
</hidden>
xxxxxxxxxxxxx
		');
		
		
		fclose($file);
		if(is_file($point_file)){
			return 1;
		}else{
			return 0;
		}
	}
	//Contr:
	public function topic_shimmy($sisters_assoc,$dir_short,$reference_topic_name,$utility){
		//scan the directory to get sister topics
		$dir = "$this->root/$dir_short";
		//check if all the topics are named correctly
		foreach($sisters_assoc as $fetch_all){
			$topic = $fetch_all['pt_topic'];
			//
			$number = substr($topic,0,strpos($topic,'_'));
			$name = substr($topic,strpos($topic,'_')+1,strlen($topic));
			//check if all the topics are named correctly
			if(!is_numeric($number) or strlen((string)$number) != 2  or empty($name) ){
				return 0;
			}
			//
		}
		
		
		//the reference topic's number
		$ref_number = substr($reference_topic_name,0,strpos($reference_topic_name,'_'));
		//new_topic's num
		$new_topic_num = $ref_number +1 ;
		//loop through each sister topic
		foreach($sisters_assoc as $fetch_all){
			$topic = $fetch_all['pt_topic'];
			//break down the name of the sister topic
			$prefix = 'D';
			$number = substr($topic,0,strpos($topic,'_'));
			$name = substr($topic,strpos($topic,'_')+1,strlen($topic));
			//see if the sister topic is below the reference topic
			if($number > $ref_number){
				
				//for a sister topic underneath the reference, move it's numerical value by a single unit
				$num_old = sprintf("%'02d", $number );
				$num_new = sprintf("%'02d", $number + 1);
				//rename the sister topic
				$old_sister_name = "$dir/$prefix".'_'.$num_old.'_'.$name;
				$new_sister_name = "$dir/$prefix".'_'.$num_new.'_'.$name;
				//
				rename($old_sister_name,$new_sister_name);
			}
		}
		
		
		//left to make each of the above to be made at the click of a button
		$new = sprintf("%'02d", $new_topic_num );
		//
		$new_tag_n = $utility->tag_generator();
		$new_tag = $utility->tag_generator();
		//
		$new_topic_link = "$dir/D_$new"."_$new_tag_n";
		$new_topic_point = "$new_topic_link/N_1";
		$point_file = "$new_topic_point/type_point.tag_$new_tag.txt";	
		
		
		//
		if(!is_dir($new_topic_point)){
			mkdir($new_topic_point,0777,true);
		}
		if(!is_dir($new_topic_point)){
			return 0;
			die;
		}
		$file = fopen($point_file,'a+');
		fwrite($file, '
<hidden>
<point_title>some title</point_title>
<my_vid 
vid_title = 
vid_link = 
> 
</my_vid>
</hidden>
xxxxxxxxxxxxx
		');
		
		
		fclose($file);
		if(is_file($point_file)){
			return 1;
		}else{
			return 0;
		}
		
	}
	//Contr:
	public function point_shimmy($refrence_point,$utility){
		//initiation left to generalise it so it updates the database by running dir loop
		$pt_num_old = $this->pt_number;
		//new_point's num
		$new_pt_num = $refrence_point +1 ;
		//old_point's new num
		$this->pt_number =$pt_num_old + 1;
		//initiation2 prepping the shimmy
		$dir_old = $this->pt_directory;
		$this->pt_link = str_replace("N_$pt_num_old","N_$this->pt_number",$this->pt_link);
		//adding the file on the first run and then skipping this code once it's added
		//the shimmy will continue once this is done
		$new_link = str_replace("N_$pt_num_old","N_$new_pt_num",$dir_old);
		$new_tag = $utility->tag_generator();
		$point_file ="$new_link/type_point.tag_$new_tag.txt";
		if(($pt_num_old > $refrence_point)){
			$this->pt_directory = str_replace("N_$pt_num_old","N_$this->pt_number",$dir_old);
			//exec rename and update for the point
			rename("$this->root/$dir_old","$this->root/$this->pt_directory");
			$query = "delete from $this->db_table where pt_unique_id='$this->pt_unique_id'";
			$this->run_query($query);
		}
		//exec make directory then make file 	
		if(!is_dir("$this->root/$new_link")){
			$f = $point_file;
			$d = "$this->root$new_link";
			mkdir($d,0777,true);
			$file = fopen("$this->root/$f",'a+');
			fwrite($file, '
<hidden>
<point_title>some title</point_title>
<my_vid 
vid_title = 
vid_link = 
> 
</my_vid>
</hidden>
xxxxxxxxxxxxx
			');
			fclose($file);	
		}
		return 1;
		
	}
	//Contr: after a point is removed, this takes all neighboring points and pulls thir order back by a single digit
	//takes a refrence that points to where the point pulling starts
	public function negative_moduel_shimmy($sisters_assoc,$dir_short,$reference_moduel_name,$utility){
		//scan the directory to get sister topics
		$dir = "$this->root/$dir_short";
		
		
		//the reference topic's number
		$ref_number = substr($reference_moduel_name,0,strpos($reference_moduel_name,'_'));
		//loop through each sister topic
		foreach($sisters_assoc as $fetch_all){
			$moduel = $fetch_all['pt_moduel'];
			//break down the name of the sister topic
			$prefix = 'B';
			$number = substr($moduel,0,strpos($moduel,'_'));
			$name = substr($moduel,strpos($moduel,'_')+1,strlen($moduel));
			//see if the sister topic is below the reference topic
			if($number > $ref_number){
				
				//for a sister topic underneath the reference, move it's numerical value by a single unit
				$num_old = sprintf("%'02d", $number );
				$num_new = sprintf("%'02d", $number - 1);
				//rename the sister topic
				$old_sister_name = "$dir/$prefix".'_'.$num_old.'_'.$name;
				$new_sister_name = "$dir/$prefix".'_'.$num_new.'_'.$name;
				//
				rename($old_sister_name,$new_sister_name);
			}
		}
		return 1;
	}
	//Contr:
	public function negative_chapter_shimmy($sisters_assoc,$dir_short,$reference_chapter_name,$utility){
		//scan the directory to get sister topics
		$dir = "$this->root/$dir_short";
		
		
		//the reference topic's number
		$ref_number = substr($reference_chapter_name,0,strpos($reference_chapter_name,'_'));
		//loop through each sister topic
		foreach($sisters_assoc as $fetch_all){
			$chapter = $fetch_all['pt_chapter'];
			//break down the name of the sister topic
			$prefix = 'C';
			$number = substr($chapter,0,strpos($chapter,'_'));
			$name = substr($chapter,strpos($chapter,'_')+1,strlen($chapter));
			//see if the sister topic is below the reference topic
			if($number > $ref_number){
				
				//for a sister topic underneath the reference, move it's numerical value by a single unit
				$num_old = sprintf("%'02d", $number );
				$num_new = sprintf("%'02d", $number - 1);
				//rename the sister topic
				$old_sister_name = "$dir/$prefix".'_'.$num_old.'_'.$name;
				$new_sister_name = "$dir/$prefix".'_'.$num_new.'_'.$name;
				//
				rename($old_sister_name,$new_sister_name);
			}
		}
		return 1;
	}
	//Contr:
	public function negative_topic_shimmy($sisters_assoc,$dir_short,$reference_topic_name,$utility){
		//scan the directory to get sister topics
		$dir = "$this->root/$dir_short";
		
		
		//the reference topic's number
		$ref_number = substr($reference_topic_name,0,strpos($reference_topic_name,'_'));
		//loop through each sister topic
		foreach($sisters_assoc as $fetch_all){
			$topic = $fetch_all['pt_topic'];
			//break down the name of the sister topic
			$prefix = 'D';
			$number = substr($topic,0,strpos($topic,'_'));
			$name = substr($topic,strpos($topic,'_')+1,strlen($topic));
			//see if the sister topic is below the reference topic
			if($number > $ref_number){
				
				//for a sister topic underneath the reference, move it's numerical value by a single unit
				$num_old = sprintf("%'02d", $number );
				$num_new = sprintf("%'02d", $number - 1);
				//rename the sister topic
				$old_sister_name = "$dir/$prefix".'_'.$num_old.'_'.$name;
				$new_sister_name = "$dir/$prefix".'_'.$num_new.'_'.$name;
				//
				rename($old_sister_name,$new_sister_name);
			}
		}
		return 1;
		
	}
	//contr::
	public function negative_point_shimmy($refrence_point){
		//initiation left to generalise it so it updates the database by running dir loop
		$pt_num_old = $this->pt_number; // N_x  where this is x and x ranges from 1 to however many points there are in the dir
		//old_point's new num
		$this->pt_number =$pt_num_old - 1;
		//initiation2 prepping the shimmy
		$dir_old = $this->pt_directory;
		$this->pt_link = str_replace("N_$pt_num_old","N_$this->pt_number",$this->pt_link);
		//adding the file on the first run and then skipping this code once it's added
		//the shimmy will continue once this is done
		if($pt_num_old > $refrence_point){
			$this->pt_directory = str_replace("/N_$pt_num_old","/N_$this->pt_number",$dir_old);
			//exec rename and update for the point
			rename("$this->root/$dir_old","$this->root/$this->pt_directory");
			$query = "delete from $this->db_table where pt_unique_id='$this->pt_unique_id'";
			$this->run_query($query);
		}
		return 1;
		
	}
	//Contr:adds a new moduel to the subject
	public function add_moduel($subject,$moduel,$filehandle,$utility){
		//to add a new moduel point, you have to find the link where the subject is held then add a new folder 
		$dir_short = "/$this->info_src/universal/A_$subject";
		//
		$sisters_assoc = $this->get_sister_moduels($dir_short);
		//
		$status = $this->moduel_shimmy($sisters_assoc,$dir_short,$moduel,$utility);
		//
		$dir = "$this->root/$dir_short";
		if($status == 1){
			$this->inject_point($dir,$filehandle,$utility);
			return $this->inject_point($dir,$filehandle,$utility);
		}else{
			return $status;
		}
		
	}
	//Contr: adds a new moduel to the subject
	public function add_chapter($subject,$moduel,$chapter,$filehandle,$utility){
		//to add a new moduel point, you have to find the link where the subject is held then add a new folder 
		$dir_short = "/$this->info_src/universal/A_$subject/B_$moduel";
		//
		$sisters_assoc = $this->get_sister_chapters($dir_short);
		$status = $this->chapter_shimmy($sisters_assoc,$dir_short,$chapter,$utility);
		//
		$dir = "$this->root/$dir_short";
		if($status == 1){
			$this->inject_point($dir,$filehandle,$utility);
			return $this->inject_point($dir,$filehandle,$utility);
		}else{
			return $status;
		}
		
	}
	//Contr: adds a new moduel to the subject
	public function add_topic($subject,$moduel,$chapter,$topic,$filehandle,$utility){
		//directory contatinng all sister topic
		$dir_short = "/$this->info_src/universal/A_$subject/B_$moduel/C_$chapter";
		//
		$sisters_assoc = $this->get_sister_topics($dir_short);
		$status = $this->topic_shimmy($sisters_assoc,$dir_short,$topic,$utility);
		//
		$dir = "$this->root/$dir_short";
		if($status == 1){
			$this->inject_point($dir,$filehandle,$utility);
			return $this->inject_point($dir,$filehandle,$utility);
		}else{
			return $status;
		}
		
	}
	//Contr:
	public function update_moduel_name($subject,$moduel,$new,$chapter,$filehandle,$utility){
		//
		$dir = "$this->root/$this->info_src/universal/A_$subject";
		$dir_moduel = "$dir/B_$moduel";
		$dir_moduel_new = "$dir/B_$new";
		$dir_moduel_new = str_replace(' ', '', $dir_moduel_new);
		rename($dir_moduel,$dir_moduel_new);
		//
		$injection = $this->inject_point($dir,$filehandle,$utility);
		//
		if($injection == 1){
			return array(
				'status' => 1,
				'msg' => "https://practicepractice.net/P/universal_spec_table?pt_subject=$subject&pt_moduel=$new&pt_chapter=$chapter"
			);
		}else{
			return array(
				'status' => 0,
				'msg' => "Something went wrong"
			);
		}
	}
	//Contr:
	public function update_pt_raw_information($subject,$moduel,$chapter,$pt_raw_information,$filehandle){
		//
		if(!empty($subject) or !empty($moduel) or !empty($chapter) or !empty($pt_raw_information)){
			//
			$link_file = "/$this->info_src/universal/A_$subject/B_$moduel/C_$chapter/files/pt_raw_information.txt";
			//run update
			$link = $this->root.$link_file;
			$content = $pt_raw_information;
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
						//
						return 1;
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
		}else{
			return 0;
		}
	}
	//Contr: 
	public function update_chapter_name($subject,$moduel,$chapter,$new,$filehandle,$utility){
		//
		$dir = "$this->root/$this->info_src/universal/A_$subject";
		$dir_moduel = "$dir/B_$moduel";
		$dir_chapter = "$dir_moduel/C_$chapter";
		$dir_chapter_new = "$dir_moduel/C_$new";
		$dir_chapter_new = str_replace(' ', '', $dir_chapter_new);
		$point = new point('');
		rename($dir_chapter,$dir_chapter_new);
		//
		$injection = $this->inject_point($dir_moduel,$filehandle,$utility);
		//
		if($injection == 1){
			return array(
				'status' => 1,
				'msg' => "https://practicepractice.net/P/universal_spec_table?pt_subject=$subject&pt_moduel=$moduel&pt_chapter=$new"
			);
		}else{
			return array(
				'status' => 0,
				'msg' => "Something went wrong"
			);
		}
	}
	//Contr:
	public function update_topic_name($subject,$moduel,$chapter,$topic,$new,$filehandle,$utility){
		$dir = "$this->root/$this->info_src/universal/A_$subject";
		$dir_moduel = "$dir/B_$moduel";
		$dir_chapter = "$dir_moduel/C_$chapter";
		$dir_topic = "$dir_chapter/D_$topic";
		$dir_topic_new = "$dir_chapter/D_$new";
		$dir_topic_new = str_replace(' ', '', $dir_topic_new);
		if(rename($dir_topic,$dir_topic_new)){
			//
			$this->inject_point($dir_chapter,$filehandle,$utility);
			return $this->inject_point($dir_chapter,$filehandle,$utility);
		}else{
			return 0;
		}
		
		
	}
	//Contr:
	public function update_topicField_selection($subject,$moduel){
		//
		$query = "select pt_chapter,pt_spec_id,LEFT(pt_spec_id,INSTR(pt_spec_id,'.')-1) as fil  from $this->db_table where 1 and pt_subject = '$subject'
				and pt_moduel='$moduel' group by pt_chapter order by pt_chapter ";
		$run = $this->run_query($query);
		$result = mysqli_num_rows($run);
		//
		$load = '';
		//
		if ($result >0){
			//
			while ($fetch_all = mysqli_fetch_assoc($run)){
				//
				$chapter = $fetch_all['pt_chapter'];
				$chapter_disp = $chapter;
				//
				$chapter_number = $fetch_all['pt_spec_id'];
				$chapter_number = substr($chapter_number,	0	, strpos($chapter_number,'.'));
				//
				if(!isset($_SESSION['u_admin'])){
					//
					$chapter_disp = str_replace('_',' ',$chapter);
					$chapter_disp = substr($chapter_disp, 3);
					$chapter_disp = ucfirst($chapter_disp);
				}
				//
				$subject = strtolower($subject);
				$url_link = "https://$_SERVER[HTTP_HOST]/P/universal_spec_table";
				$load.= "<a href='$url_link?pt_subject=$subject&pt_moduel=$moduel&pt_chapter=$chapter'> $chapter_number - $chapter_disp </a><br>";
			}
		}
		return $load;
	}
	//Contr:
	public function filter_spec_user_unique_id($pt_subject,$user_unique_id){
		//
		$pt_subject = strtolower($pt_subject);
		//
		$url_link = "https://$_SERVER[HTTP_HOST]/P/universal_spec_table";
		$full_link = "$url_link?pt_subject=$pt_subject&user_list=$user_unique_id";
		return $full_link;
	}
	//Contr: topic selector for the specification 
	public function topic_selector($subject,$filehandle){
		//
		$query = "select pt_moduel from $this->db_table where 1 and pt_subject = '$subject'
		 group by pt_moduel order by pt_moduel ";
		$run = $this->run_query($query);
		//
		$result = mysqli_num_rows($run);
		$return = array();
		if ($result >0){
			$num = 1;
			while ($fetch_all = mysqli_fetch_assoc($run)){
				$topic = $fetch_all['pt_moduel'];
				$topic_disp = $topic;
				//
				if ($topic =="automated"){}else{
					//providing a clean looking list
					if(!isset($_SESSION['u_admin'])){
						$topic_disp = substr($topic, 3);
						$topic_disp = ucfirst($topic_disp);
						$topic_disp = str_replace('_',' ',$topic_disp);
					}
					//
					$topic_disp = "$num - $topic_disp";
					$num += 1;
					//
					$return[] = array(
						'value' =>$topic,
						'display'=> $topic_disp);
				}
			}
		}
		return $return;
	}
	//
	public function make_mindmap($subject,$moduel,$chapter,$usercontr,$filehandle,$questioncontr,$utility){
		//
		$return = array();
		//
		$user_level = $usercontr -> user_level;
		$user_leveltemp = $usercontr -> user_level;
		$u_status = $usercontr -> user_status;
		//
		if($user_leveltemp == 'A'){
			$user_leveltemp = '';
		}
		//so the number of cache files is reduced 
		$cache_name = "mindmap-$user_leveltemp-$subject-$moduel-$chapter";
		//setting up user filter query
		if($user_leveltemp ==''){
			//
			$customtable = $this->db_table;
		}elseif($user_leveltemp == 'AS'){
			//
			$customtable ="(select * from $this->db_table where pt_level ='AS') as A";
		}else{
			if($user_leveltemp =='A'){
				//
				//$customtable ="(select * from $this->db_table where pt_level in ('AS','A') )as A";
				$customtable =$this->db_table;
			}else{
				//
				$customtable = $this->db_table;
			}
		}
		//
		if(empty($moduel) and empty($chapter)){
			$query = "select pt_moduel from $customtable where 1 and pt_subject = '$subject'
			group by pt_moduel order by pt_moduel ";
			$back_link = '' ;
		}elseif(empty($chapter)){
			$query = "select pt_chapter from $customtable where 1 and pt_subject = '$subject'
			and pt_moduel = '$moduel' group by pt_chapter order by pt_chapter ";
			$back_link = array('a' => 'refresh' ,'subject'=>$subject,'moduel' => '');
			//
			$return[] = array(
				'id' => "back",
				'topic'=> "<-",
				'direction'=> "left",
				'link'=> $back_link);
		}else{
			$query = "select pt_topic from $customtable where 1 and pt_subject = '$subject'
			and pt_moduel = '$moduel' and pt_chapter='$chapter' group by pt_topic order by pt_topic ";
			$back_link = array('a' => 'refresh' ,'subject'=>$subject, 'moduel' => $moduel);
			//
			$return[] = array(
				'id' => "back",
				'topic'=> "<-",
				'direction'=> "left",
				'link'=> $back_link);
		}
		//chcking for cache
		if(!is_file("$this->root/cache/$cache_name.txt") or $u_status < 2 or $u_status == 4){
			$run = $this->run_query($query);
			//
			$result = mysqli_num_rows($run);
			if ($result >0){
				$divide = $result/2;
				//
				$query2 = "select status from publications where subject = '$subject' and status = '1'";
				$run2 = $this->run_query($query2);
				//
				$num_publications = mysqli_num_rows($run2);
				$divide2 = $num_publications/2;
				//
				$num = 1;
				while ($fetch_all = mysqli_fetch_assoc($run)){
					$page = strtolower($subject);
					if(isset($fetch_all['pt_moduel'])){
						$topic = $fetch_all['pt_moduel'];
						//check if the moduel is published
						$publication_check_query = "select status from publications where subject = '$subject' and moduel = '$topic' limit 1";
						$run_2 = $this->run_query($publication_check_query);
						//************************change this value to zero to prevent the user from seeing unpublished content, to fix left right issue see below 
						$published = 0;
						while($fetch = mysqli_fetch_assoc($run_2)){

							//
							$result = $fetch['status'];
							if($result == 1){
								$published = 1;
							}
						}
						if(!isset($published)){
							//************************change this value to zero to prevent the user from seeing unpublished content
							$published = 0;
						}
						$link = array('a' => 'refresh' ,'subject'=>$subject, 'moduel' => $topic);
					}elseif(isset($fetch_all['pt_chapter'])){
						//
						$topic = $fetch_all['pt_chapter'];
						$link = array('a' => 'refresh' ,'subject'=>$subject, 'moduel' => $moduel,'chapter' => $topic);
					}else{
						if(isset($fetch_all['pt_topic'])){
							//
							$topic = $fetch_all['pt_topic'];
							//
							$link = array('a' => 'redirect' ,'subject'=>$subject, 'moduel' => $moduel,'chapter' => $chapter,'topic' => $topic);
						}else{
							//
							return array();
						}
					}
					$topic_disp = $topic;
					//
					if ($topic =="automated"){}else{
						//providing a clean looking list
						if(!isset($_SESSION['u_admin'])){
							$topic_disp = substr($topic, 3);
							$topic_disp = ucfirst($topic_disp);
							$topic_disp = str_replace('_',' ',$topic_disp);
							//
							$topic_disp = "$num - $topic_disp";
						}
						//********* un comment the if statment head to prevent the user from seeingunpublished content
						if($num <= $divide){
						//if($num <= $divide){
							$direction ='left';
						}else{
							$direction = 'right';
						}
						//
						if(isset($published) and (!isset($_SESSION['u_admin']) and !isset($_SESSION['u_editor']) and !isset($_SESSION['write_perm']))){
							//
							if($published == 1){
								//********* un comment the if statment head to prevent the user from seeingunpublished content
								if($num <= $divide2){
								//if($num <= $divide){
									$direction2 ='left';
								}else{
									$direction2 = 'right';
								}

								$return[] = array(
									'id' => "$num",
									'topic'=> "$topic_disp",
									'direction'=> "$direction2",
									'link'=> $link);
								$num += 1;
							}
						}else{
							//
							$return[] = array(
								'id' => "$num",
								'topic'=> "$topic_disp",
								'direction'=> "$direction",
								'link'=> $link);
							$num += 1;
						}

					}
				}

			}
			$result = $filehandle -> make_cache_array($return,$cache_name,$u_status);
			//
			if(isset($usercontr -> user) and $_SESSION["user_membership"] == 1){
				$user_unique = $usercontr -> user;
				$avg = 0;
				$n = 0;
				foreach($return as $key => $leg){
					//finding the current level of resolution
					if(isset($leg['link']['topic'])){
						$chapter_p =$leg['link']['chapter'];
						$topic_p =$leg['link']['topic'];
						//enable this line to allow the topics in the mind map to have percentages !!!
						//$arr = $questioncontr -> get_general_progress($user_unique,$user_level,$u_status,$subject,$moduel,$chapter_p,$topic_p,$utility);
						$chapter_arr = $questioncontr -> get_general_progress($user_unique,$user_level,$u_status,$subject,$moduel,$chapter_p,'00_general',$utility);
						$arr['pct'] = 0;
						$arr['rgb'] = $chapter_arr['rgb'];
						
					}elseif(isset($leg['link']['chapter'])){
						//
						$chapter_p =$leg['link']['chapter'];
						$topic_p ='';
						$arr = $questioncontr -> get_general_progress($user_unique,$user_level,$u_status,$subject,$moduel,$chapter_p,$topic_p,$utility);
					}else{
						if(isset($leg['link']['moduel'])){
							//
							$moduel = $leg['link']['moduel'];
							$chapter_p ='';
							$topic_p ='';
							$arr = $questioncontr -> get_general_progress($user_unique,$user_level,$u_status,$subject,$moduel,$chapter_p,$topic_p,$utility);
						}
					}
					if($leg['id'] == 'back'){
						unset($arr);
					}
					//
					if(isset($arr)){
						$pct = $arr['pct'];
						$rgb = $arr['rgb'];
						//the avg calculation assumes that that each topic has the same number of marks (not the case), so the weighting of the pcts is that wrong
						$avg += $pct;
						$n += 1;
						//
						$return[$key]['link']['pct'] = round($pct);
						$return[$key]['link']['rgb'] = $rgb;
					}

				}
				if(!isset($chapter_arr)){
					if($avg > 0){
						$n = $n;
						$avg_pct = 0 ;//round($avg/$n);
						$count = count($return);
						$avg_rgb = $utility -> numberToColorHsl($avg_pct/100, 0.40, 1);
						$return[$count]['pct'] = $avg_pct;
						$return[$count]['rgb'] = $avg_rgb;
					}else{
						$count = count($return);
						$return[$count]['pct'] = null;
						$return[$count]['rgb'] = null;
					}
				}else{
					$count = count($return);
					$return[$count]['pct'] = round($chapter_arr['pct']);
					$return[$count]['rgb'] = $chapter_arr['rgb'];
				}
			}else{
				$count = count($return);
				$return[$count]['pct'] = null;
				$return[$count]['rgb'] = null;
			}
			//
			return $return;
		}else{
			//fetch ouput from file
			$content = unserialize(file_get_contents("$this->root/cache/$cache_name.txt"));
			
			//
			if(isset($usercontr -> user) and $_SESSION["user_membership"] == 1){
				$user_unique = $usercontr -> user;
				$avg = 0;
				$n = 0;
				foreach($content as $key => $leg){
					//finding the current level of resolution
					if(isset($leg['link']['topic'])){
						$chapter_p =$leg['link']['chapter'];
						$topic_p =$leg['link']['topic'];
						//enable this line to allow the topics in the mind map to have percentages !!!
						//$arr = $questioncontr -> get_general_progress($user_unique,$user_level,$u_status,$subject,$moduel,$chapter_p,$topic_p,$utility);
						$chapter_arr = $questioncontr -> get_general_progress($user_unique,$user_level,$u_status,$subject,$moduel,$chapter_p,'00_general',$utility);
						$arr['pct'] = 0;
						$arr['rgb'] = $chapter_arr['rgb'];
						
					}elseif(isset($leg['link']['chapter'])){
						//
						$chapter_p =$leg['link']['chapter'];
						$topic_p ='';
						$arr = $questioncontr -> get_general_progress($user_unique,$user_level,$u_status,$subject,$moduel,$chapter_p,$topic_p,$utility);
					}else{
						if(isset($leg['link']['moduel'])){
							//
							$moduel = $leg['link']['moduel'];
							$chapter_p ='';
							$topic_p ='';
							$arr = $questioncontr -> get_general_progress($user_unique,$user_level,$u_status,$subject,$moduel,$chapter_p,$topic_p,$utility);
						}
					}
					if($leg['id'] == 'back'){
						unset($arr);
					}
					//
					if(isset($arr)){
						$pct = $arr['pct'];
						$rgb = $arr['rgb'];
						//the avg calculation assumes that that each topic has the same number of marks (not the case), so the weighting of the pcts is that wrong
						$avg += $pct;
						$n += 1;
						//
						$content[$key]['link']['pct'] = round($pct);
						$content[$key]['link']['rgb'] = $rgb;
					}

				}
				if(!isset($chapter_arr)){
					if($avg > 0){
						$n = $n;
						$avg_pct = 0 ;//round($avg/$n);
						$count = count($content);
						$avg_rgb = $utility -> numberToColorHsl($avg_pct/100, 0.40, 1);
						$content[$count]['pct'] = $avg_pct;
						$content[$count]['rgb'] = $avg_rgb;
					}else{
						$count = count($content);
						$content[$count]['pct'] = null;
						$content[$count]['rgb'] = null;
					}
				}else{
					$count = count($content);
					$content[$count]['pct'] = round($chapter_arr['pct']);
					$content[$count]['rgb'] = $chapter_arr['rgb'];
				}
			}else{
				$count = count($content);
				$content[$count]['pct'] = null;
				$content[$count]['rgb'] = null;
			}
			//
			
			
			return $content;
		}
		
	}
	//Contr
	public function get_pointFiles_uniqueandname($pt_unique_id,$filename){
		//
		$this->manual_construct_unique_id($pt_unique_id);
		//
		$dir = $this->pt_directory;
		return "$this->root/$dir/files/$filename";
	}
	//Contr
	public function assigneditor_listing($pt_subject,$pt_moduel,$pt_chapter,$task_payment_amount){
		//check that no spec point editing task is listed
		$check_q = "select * from active_tasks_questions where q_subject = '$pt_subject' and q_moduel = '$pt_moduel' and q_chapter='$pt_chapter' limit 1";
		$run = $this->run_query($check_q);
		//
		$check_count = mysqli_num_rows($run);
		if($check_count > 0){
			return 0;
		}
		//checking if the chapter is present
		$query = "select * from active_tasks where pt_subject='$pt_subject' and pt_moduel = '$pt_moduel' and pt_chapter='$pt_chapter'";
		$run = $this->run_query($query);
		//
		$count = mysqli_num_rows($run);
		//different quries are needed for different cases
		if($count == 0){
			//
			$query_2 = "insert into active_tasks (pt_subject,pt_moduel,pt_chapter,task_payment_amount) values ('$pt_subject','$pt_moduel','$pt_chapter','$task_payment_amount')";
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
	public function unlist_editor_task($subject,$moduel,$chapter){
		//
		$query = "select * from active_tasks where pt_subject='$subject' and pt_moduel = '$moduel' and pt_chapter='$chapter'";
		$run = $this->run_query($query);
		//
		$count = mysqli_num_rows($run);
		//
		if($count == 1){
			//
			$query_2 = "delete from active_tasks where pt_subject='$subject' and pt_moduel = '$moduel' and pt_chapter='$chapter' limit 1";
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
	//Contr:
	public function get_chapter_dir($subject,$moduel,$chapter){
		//
		$query = "select pt_directory from $this->db_table where pt_subject= '$subject' and pt_moduel = '$moduel' and pt_chapter = '$chapter' limit 1";
		$run = $this -> run_query($query);
		//
		$count = mysqli_num_rows($run);
		//
		if($count == 1 ){
			//
			$assoc = mysqli_fetch_assoc($run);
			$dir_long = $assoc['pt_directory'];
			//
			$chapter_dir = substr($dir_long, 1 , strpos($dir_long,'/D_')-1);
			return $chapter_dir;
		}else{
			return 0;
		}

	}
	//Contr:
	public function get_topic_dir($subject,$moduel,$chapter,$topic){
		//
		$query = "select pt_directory from $this->db_table where pt_subject= '$subject' and pt_moduel = '$moduel' and pt_chapter = '$chapter' and pt_topic='$topic' limit 1";
		$run = $this -> run_query($query);
		//
		$count = mysqli_num_rows($run);
		//
		if($count == 1 ){
			//
			$assoc = mysqli_fetch_assoc($run);
			$dir_long = $assoc['pt_directory'];
			//
			$topic_dir = substr($dir_long, 1 , strpos($dir_long,'/N_')-1);
			return $topic_dir;
		}else{
			return 0;
		}

	}
	//Contr:
	public function get_point_dir($subject,$moduel,$chapter,$topic,$point_unique){
		//
		$query = "select pt_directory from $this->db_table where pt_subject= '$subject' and pt_moduel = '$moduel' and pt_chapter = '$chapter' and pt_topic='$topic' and pt_unique_id = '$point_unique' limit 1";
		$run = $this -> run_query($query);
		//
		$count = mysqli_num_rows($run);
		//
		if($count == 1 ){
			//
			$assoc = mysqli_fetch_assoc($run);
			$dir_long = $assoc['pt_directory'];
			//
			return $dir_long;
		}else{
			return 0;
		}

	}
	//CONTR:
	public function get_point_unique_pointer($subject,$moduel,$chapter,$topic,$q_point){
		//
		if($topic == '' and $q_point == ''){
			$query = "select pt_unique_id from $this->db_table where pt_subject='$subject' and pt_moduel = '$moduel' and pt_chapter = '$chapter' limit 1";
		}elseif($q_point== ''){
			$query = "select pt_unique_id from $this->db_table where pt_subject='$subject' and pt_moduel = '$moduel' and pt_chapter = '$chapter' and pt_topic='$topic' limit 1";
		}else{
			$query = "select pt_unique_id from $this->db_table where pt_subject='$subject' and pt_moduel = '$moduel' and pt_chapter = '$chapter' and pt_topic='$topic' and pt_unique_id='$q_point' limit 1";
		}
		//
		$run = $this->run_query($query);
		if(!$run){
			return 0;
		}
		//
		while($assoc = mysqli_fetch_assoc($run)){
			//
			$point = $assoc['pt_unique_id'];
			return $point;
		}
		
	}
	//Contr: 
	public function chapter_publication_check($info){
		if(isset($info['user_list'])){
			return 1;
		}else{
			//check if the moduel publication, to decide if noindex should be displayed
			$subject = $info['pt_subject'];
			$moduel = $info['pt_moduel'];
			//
			$query = "select status from publications where subject = '$subject' and moduel = '$moduel' limit 1";
			$run = $this->run_query($query);
			//
			$result = mysqli_fetch_assoc($run);
			if($result){
				//
				if($result['status'] == 1){
					//dont block
					return 0;
				}else{
					//block
					return 1;
				}
			}else{
				//block
				return 1;
			}
		}
		
	}
	//Contr: publish moduels
	public function publish_moduel($subject, $moduel){
		//
		$query = "select status from publications where subject='$subject' and moduel='$moduel' limit 1";
		$run = $this->run_query($query);
		//check if the row exists
		$count = mysqli_num_rows($run);
		//
		if($count > 0 ){
			//
			$query2 = "update publications set status='1' where subject='$subject' and moduel='$moduel'";
			//
			if($this->run_query($query2)){
				return 1;
			}else{return 0;}
		}else{
			//
			$query2 = "INSERT INTO publications (subject, moduel, status)
			VALUES ('$subject', '$moduel', '1')";
			//
			if($this->run_query($query2)){
				return 1;
			}else{return 0;}
		}
	}
	//Contr: unpublish moduels
	public function unpublish_moduel($subject, $moduel){
		//
		$query = "select status from publications where subject='$subject' and moduel='$moduel' limit 1";
		$run = $this->run_query($query);
		//check if the row exists
		$count = mysqli_num_rows($run);
		//
		if($count > 0 ){
			//
			$query2 = "update publications set status = '0' where subject='$subject' and moduel='$moduel'";
			//
			if($this->run_query($query2)){
				return 1;
			}else{return 0;}
		}else{
			//
			$query2 = "INSERT INTO publications (subject, moduel, status)
			VALUES ('$subject', '$moduel', '0')";
			$this->run_query($query2);
			//
			if($this->run_query($query2)){
				return 1;
			}else{return 0;}
		}
	}
	//Contr: check if the question page is supposed to be published or not, by checking the moduel
	public function questions_publication_check($filter,$pt_unique_id){
		//check filter type
		if($filter == 'point'){
			//block
			return 1;
		}
		//
		$point_obj = $this->make_obj_unique_id($pt_unique_id);
		$pt_subject = $point_obj['pt_subject'];
		$pt_moduel = $point_obj['pt_moduel'];
		//check if the moduel of this point is published
		$query = "select status from publications where subject = '$pt_subject' and moduel = '$pt_moduel' limit 1";
		$run = $this->run_query($query);
		//
		$result = mysqli_fetch_assoc($run);
		if($result){
			//
			if($result['status'] == 1){
				//dont block
				return 0;
			}else{
				//block
				return 1;
			}
		}else{
			//block
			return 1;
		}
	}
	//Contr: this takes the discription written by us and extracts the informatio in the made up html tags
	private function get_point_head_info($point_desc){
		// my tags: hidden, video_section,vid,vid_title,vid_link
		//the aim of the code is to remove the madeup html markup and extract the real information contained within
		if(empty($point_desc)){
			$point_desc = ' ';
		}
		$dom = new \DomDocument();
		libxml_use_internal_errors(true);
		$dom->loadHTML($point_desc);
		libxml_use_internal_errors(true);
		
		
		//*******to get the spec point level
		$output_head = array();//the storage array for the point title
		//loop through the point titles (the is only one though) and store the values
		foreach ($dom->getElementsByTagName('point_title') as $item) {
			$output_head = array (
			   'point_level' => $item->getAttribute('level'),
			   'pt_board' => $item->getAttribute('pt_board'),
			);
		};
		//return the full details in the hidden description
		return $output_head;
	}
	//
	public function get_anypoint_from_modnchap($moduel,$chap){
		//
		$query = "select pt_unique_id from $this->db_table where pt_moduel='$moduel' and pt_chapter = '$chap' group by pt_moduel,pt_chapter limit 1";
		$run = $this->run_query($query);
		while($result = mysqli_fetch_assoc($run)){
			return $result['pt_unique_id'];
		}
	}
	//
	public function get_chapters_including_point_pointer($subject){
		//
		$arr = array();
		$query = "select pt_moduel,pt_chapter,pt_unique_id from $this->db_table where pt_subject = '$subject' group by pt_chapter order by pt_moduel,pt_chapter";
		$run = $this->run_query($query);
		$count = mysqli_num_rows($run);
		//
		if($count > 0){
			while($return = mysqli_fetch_assoc($run)){
				$arr[] = $return;
			}
			return $arr;
		}else{
			return array();
		}
		
	}
}











