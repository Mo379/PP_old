<?php
namespace MVC;
class Filehandle{
	//Filehandle: constructor
	public function __construct(){
		$this->root = $_SERVER['DOCUMENT_ROOT'];
	}
	//Filehandle: loop through every file in the given directory
	public function loop($directory,$file_type){
		//loop through the files and save all in
		$root = $_SERVER['DOCUMENT_ROOT'];
		
		$path = new \RecursiveDirectoryIterator($directory);
		$files = array();
		foreach(new \RecursiveIteratorIterator($path) as $filename){
			$file_extention =pathinfo($filename, PATHINFO_EXTENSION);
			//special case where all the files are looped through
			if(is_file($filename) and $file_type == 'any'){
				if(($file_extention == 'csv' or $file_extention =='pdf') or $file_extention =='txt'){
					if(strpos($filename,'pt_raw_information') == FALSE){
						$filename = str_replace($root,'',$filename);
						$filename = str_replace('\\','/', $filename);
						$files[]= $filename;
						
					}
					
				}
			}elseif(is_file($filename) and $file_extention == $file_type){
				if(strpos($filename,'pt_raw_information') == FALSE){
					$filename = str_replace($root,'',$filename);
					$filename = str_replace('\\','/', $filename);
					$files[]= $filename;
				}
			}
		}
		return $files;
	}
	//Filehandle: get file directory by removing the filename
	public function extract_file_dir($file){
		$filename_arr = explode("/",$file);
		$pos = count($filename_arr)-1;
		$switch = 0;
		$return = array();
		foreach($filename_arr as $x){
			if($pos !== $switch){
				$return[] = $x;
				$switch+=1;	
			}else{
				break;
			}
			
		}
		return implode ("/",$return);
	}
	//Filehandle: inject input into the name of a file
	public function renamer($file,$input){
		$filename_arr = explode("/",$file);
		$name_pos = count($filename_arr)-1;
		$name = $filename_arr[$name_pos];
		$name_arr = explode('.',$name);
		$pointer_pos = count($name_arr)-2;
		$name_arr[$pointer_pos] = $name_arr[$pointer_pos].'.'.$input;
		
		$new_name =  implode ('.', $name_arr);
		$filename_arr[$name_pos] = $new_name;
		
		return implode ('/', $filename_arr);
	}
	//Filehandle: tag generator
	public function tag_generator(){
		$uniqid = uniqid();
		$rand_start = rand(1,5);
		$rand_8_char = substr($uniqid,$rand_start,8);
		$somenumber = rand(10,99);
		$ID = $rand_8_char.$somenumber;	
		return str_shuffle($ID);
	}
	//extract unique_ids
	public function extract_unique_ids($files){
		$return = array();
		foreach($files as $file){
			if(strpos($file , 'tag_') == true){
				$existing_tag = substr($file, strpos($file,'tag_')+4);
				$existing_tag = substr($existing_tag, 0,10);
				$return[] = $existing_tag;
			}/*else{
				
				$return[] = '';
			}*/
		}
		return $return;
	}
	//Filehandle: tag processing for all files
	public function tag_processing($files){
		foreach($files as $file){
			//if no tag is present in the file...check it's directory for one
			if(strpos($file, 'tag_') == false){
				$root = $_SERVER['DOCUMENT_ROOT'];
				//get the directory in which this file is saved
				$file_dir = $this->extract_file_dir($file);
				$path = "$root/$file_dir";
				$dir_files = $this-> loop($path,'any');
				//
				$file = "$root/$file";
				$tags = $this -> extract_unique_ids($dir_files);
				//add tag to the filename by renaming
				if(isset($tags) and !empty($tags)){
					$name =$this->renamer($file,"tag_$tags[0]");
					
					rename($file,$name);
					
					
				}else{
					//meaning there is no tags in the directory 
					
					$new_tag = $this->tag_generator();
					$name =$this->renamer($file,"tag_$new_tag");
					rename($file,$name);
				}
			}
			
		}
	}
	//Filehandle: filter for the 'questions' directory
	public function filter_questions($all_pdfs){
		$questions = array();
		foreach($all_pdfs as $pdf_link){
			//looks for the /questions/ directory in the pdf file name
			if(strpos($pdf_link,'/questions/') == true){
				$questions[] = $pdf_link;	
			}
		}
		return $questions;
	}	
	//Filehandle: filter for the 'questions' directory
	public function filter_nonquestions($all_pdfs){
		$questions = array();
		foreach($all_pdfs as $pdf_link){
			//looks for the /questions/ directory in the pdf file name
			if(strpos($pdf_link,'/questions/') == false){
				$questions[] = $pdf_link;	
			}
		}
		return $questions;
	}
	//Filehandle: filter for the type of file: type_question
	public function filter_type_question($files){
		$return = array();
		foreach($files as $file){
			if(strpos($file, 'type_question') == true){
				$return[] = $file;
			}
		}
		return $return;
	}
	//Filehandle: filter for the type of file: type_answer
	public function filter_type_answer($files){
		$return = array();
		foreach($files as $file){
			if(strpos($file, 'type_answer') == true){
				$return[] = $file;
			}
		}
		return $return;
	}	
	//Filehandle: filter for the type of file: type_point
	public function filter_type_point($files){
		$return = array();
		foreach($files as $file){
			if(strpos($file, 'type_point') == true){
				$return[] = $file;
			}
		}
		return $return;
	}
	//Filehandle: filter for the type of file: type_cache
	public function filter_type_cache($files){
		$return = array();
		foreach($files as $file){
			if(strpos($file, 'type_cache') == true){
				$return[] = $file;
			}
		}
		return $return;
	}
	//Filehandle: filter for the type of file: cache_q and cache_a for the splitting of any paper
	public function filter_cache_q($files){
		$return = array();
		foreach($files as $file){
			if(strpos($file, 'cache_q') == true ){
				$return[] = $file;
			}
		}
		return $return;
	}
	//Filehandle: 
	public function filter_cache_a($files){
		$return = array();
		foreach($files as $file){
			if(strpos($file, 'cache_a') == true){
				$return[] = $file;
			}
		}
		return $return;
	}
	//Filehandle: extract files dir: returns tag => dir
	public function extract_files_dir($files){
		$return = array();
		foreach($files as $file){
			if(strpos($file , 'tag_') == true){
				$existing_tag = substr($file, strpos($file,'tag_')+4);
				$existing_tag = substr($existing_tag, 0,10);
				//removeing root
				$dir = $this -> extract_file_dir($file);
				
				$return[$existing_tag] = $dir;
			}else{
				$tag = $file;
				$return[$tag] = $tag;
			}
		}
		return $return;
	}
	//Filehandle: extract unique ids and keep the link:return array of arrays
	public function extract_unique_ids2($files){
		$return = array();
		foreach($files as $file){
			if(strpos($file , 'tag_') == true){
				$existing_tag = substr($file, strpos($file,'tag_')+4);
				$existing_tag = substr($existing_tag, 0,10);
				
				$temp = array();
				$temp[] = $existing_tag;
				$temp[] = $file;
				$return[] = $temp;
			}/*else{
				$tag = $file;
				$temp = array();
				$temp[] = $tag;
				$temp[] = $tag;
				$return[] = $temp;}*/
			
		}
		return $return;
	}
	//Filehandle: group files by unique_id
	public function group_files($tags,$questions,$answers){
		$return = array();
		$u_q = $this -> extract_unique_ids2($questions);
		
		$u_a = $this -> extract_unique_ids2($answers);
		
		foreach($tags as $x){
			
			foreach($u_q as $y => $question){
				
				if($x == $question[0]){
					
					foreach($u_a as $z => $answer){
						if($x == $answer[0]){
							$temp = array();
							$temp[]= $x;
							$temp[] = $question[1];
							$temp[] = $answer[1];
							$return[] = $temp;
						}
					}	
				}
				
			}
		}
		return $return;
	}	
	//group files by unique_id, only two arrays
	//Filehandle: takes b as an associatice array
	public function group_files2($a,$b){
		$return = array();
		foreach($a as $pkt){
			$count = count($pkt);
			$x = $pkt[0];
			foreach($b as $y => $info){
				if($x == $y){
					$temp = array();
					for($i=0;$i<$count;$i++){
						$temp[] = $pkt[$i];
					}
					$temp[]= $info;
					$return[] = $temp;
				}
			}
		} 
		return $return;
	}
	//Filehandle: extract info in the file name by letter: takes array of arrays, returns the same
	public function info_extractor($array){
		$return = array();
		foreach($array as $pkt){
			$unique = $pkt[0];
			$link = $pkt[1];
			//array for laterto fill another array
			$filler_array = array();
			foreach (range('A','Z') as $char1){
				$char = $char1.'_';
				$string = substr($link, strpos($link ,$char,true), strlen($link));
				if ($string !== $link){
					$value = substr($string, strpos($string ,$char), strpos($string, '/'));
					
				}else{
					$value = '';
					$char1 = '';
				}
				if ($value == $string){
					$char1 ='';
				}
				if(!empty($char1)){
					$value = ucfirst($value);
					$key = $char1;
					$value = substr($value, 2, strlen($value));
					$filler_array["$key"] = "$value";
					$key ='';
				}
			}
			$return[$unique] = $filler_array;
			unset($filler_array);
		}
		return $return;
		
		
	}	
	//Filehandle: turn to unique -> dir assoc
	public function make_unique_assoc($files){
		$return = array();
		foreach($files as $file){
			if(strpos($file , 'tag_') == true){
				$existing_tag = substr($file, strpos($file,'tag_')+4);
				$existing_tag = substr($existing_tag, 0,10);
				
				
				$return[$existing_tag] = $file;
			}else{
				$tag = $file;
				$return[$tag] = $tag;
			}
		}
		return $return;
	}
	//Filehandle: makes an array associative as you please with the keys
	public function make_assoc($array,$ordered_keys){
		$return = array();
		$pos = 0;
		foreach($array as $arr){
			$it = 0;
			$temp = array();
			foreach($ordered_keys as $key){
				$temp[$key] = $arr[$it];
				$it+=1;
			}
			$return[] = $temp;
			$pos = $pos +1;
		}
		return $return;
	}
	//Filaehandle: saves chace into cache folder
	public function make_cache($input,$cache_name,$user_status){
		//echo $input;
		$root = $_SERVER['DOCUMENT_ROOT'];
		//only cahce user content when accessed by users only
		if($user_status > 1 and $user_status != 4){
			if(file_put_contents("$root/cache/$cache_name.txt",$input )){
				return 1;
			}else{
				return 0;
			}
		}else{
			return 0;
		}
		
	}
	//Filehandl: make cache for an array
	public function make_cache_array($input,$cache_name,$user_status){
		//echo $input;
		$root = $_SERVER['DOCUMENT_ROOT'];
		//only cahce user content when accessed by users only
		if($user_status > 1 and $user_status !=4){
			if(file_put_contents("$root/cache/$cache_name.txt",serialize($input) )){
				return 1;
			}else{
				return 0;
			}
		}else{
			return 0;
		}
		
	}
	//Filehandle: 
	public function rewrite_txt_file($file,$info){
		file_put_contents("$this->root/$file" , $info);	
	}
	//Filehandle: 
	public function delete_file($file,$utility){
		//
		if(is_file($file)){
			if(unlink($file)){
				return 1;
			}else{
				return 0;
			}
		}else{
			return 0;
		}
	}
	//Filehandle: 
	public function delete_cache_html($utility){
		$cache = "$this->root/cache";
		$scan = scandir($cache);
		foreach($scan as $file){
			$file = "$cache/$file";
			$this->delete_file($file,$utility);
		}
		if ($files = glob($cache . "/*")) {
			return 0;
		}else{
			return 1;
		}
	}
	//Filehandle: 
	public function intraconnection($directory,$type,$input){
		//
		$directory = $directory;
		$opendirectory = opendir($directory) or die('Unable to open directory !');
		$type = $type;
		$input = $input;
		$filename = 'cache.type_cache.csv';
		//
		$scan = scandir($directory);
		//check if cache file aleady exists,
		if(in_array($filename,$scan,true) == true){
			//open file and see if the name of subject ->(question,spec-point)
			$full = $directory.'/'.$filename;
			$openfile = fopen("$full","a+") or die('Unable to open file !');
			//
			$csv = array_map('str_getcsv',file($directory.'/'.$filename));
			//
			if (in_array(array('Secondary',$type,$input),$csv) == false and in_array(array('Primary',$type,$input),$csv) == false){
				fputcsv($openfile,array('Secondary',$type,$input));
			}
			//
			fclose($this->openfile);
			closedir($this->opendirectory);
			//
			return 1;
		//
		}elseif(in_array($filename,$scan) == false){
			//
			$openfile = fopen($directory.'/'.$filename,"a+") or die('Unable to create file !');
			fputcsv($openfile,array('Primary',$type,$input));
			//
			fclose($this->openfile);
			closedir($this->opendirectory);
			//
			return 1;
		}else{
			return 0;
		}
		
		
	}
	//Filehandle: remove a directory
	public function delete_directory($dirname) {
		  if (is_dir($dirname))
           $dir_handle = opendir($dirname);
     if (!$dir_handle)
          return false;
     while($file = readdir($dir_handle)) {
           if ($file != "." && $file != "..") {
                if (!is_dir($dirname."/".$file))
                     unlink($dirname."/".$file);
                else
                     rmdir($dirname.'/'.$file);
           }
     }
     closedir($dir_handle);
     rmdir($dirname);
     return 1;
		
	}
	//Filehandle:
	public function rrmdir($dir) { 
		if (is_dir($dir) and $dir !==$this->root) { 
			$objects = scandir($dir);
				foreach ($objects as $object) { 
				if ($object != "." && $object != "..") { 
					if (is_dir($dir. DIRECTORY_SEPARATOR .$object) && !is_link($dir."/".$object))
						$this->rrmdir($dir. DIRECTORY_SEPARATOR .$object);
					else
						unlink($dir. DIRECTORY_SEPARATOR .$object); 
				} 
			}
			rmdir($dir); 
		}
		return 1;
	}	
	//Filehandle: all the topic files 
	public function rrmdir_topics($dir) { 
		if (is_dir($dir) and $dir !==$this->root) { 
			$objects = scandir($dir);
				foreach ($objects as $object) { 
				if (($object != "." ) && ($object != "..") && (strpos($object,'D_') !== FALSE)) { 
					if (is_dir($dir. DIRECTORY_SEPARATOR .$object) && !is_link($dir."/".$object))
						$this->rrmdir($dir. DIRECTORY_SEPARATOR .$object);
					else
						unlink($dir. DIRECTORY_SEPARATOR .$object); 
				} 
			}
		}
		return 1;
	}	
	//Filehandle: all the questions files 
	public function rrmdir_questions($dir) { 
		if (is_dir($dir) and $dir !==$this->root) { 
			$objects = scandir($dir);
				foreach ($objects as $object) { 
				if (($object != "." ) && ($object != "..") && (strpos($object,'questions') !== FALSE)) { 
					if (is_dir($dir. DIRECTORY_SEPARATOR .$object) && !is_link($dir."/".$object))
						$this->rrmdir($dir. DIRECTORY_SEPARATOR .$object);
					else
						unlink($dir. DIRECTORY_SEPARATOR .$object); 
				} 
			}
		}
		return 1;
	}
	//Filehandle:
	public function move_toDelete($dir){
		//
		$date = date("Y_m_d");
		$time = time();
		if(is_dir("$this->root/junk/$date")){
			rename("$dir","$this->root/junk/$date/$time/");
		}else{
			mkdir("$this->root/junk/$date",0777);
			if(is_dir("$this->root/junk/$date")){
				rename("$dir","$this->root/junk/$date/$time/");
			}else{
				return 0;
			}
		}
	}
	//Filehandle: 
	public function make_file($link){
		//
		$dirname = dirname($link);
		
		if(!is_file($link)){
			if(!is_dir($dirname)){
				mkdir($dirname, 0755, true);
				if(!is_dir($dirname)){
					return 0;
				}
			}
			$file = fopen("$link","a+");
			if(!is_file($link)){
				return 0;
			}else{
				return 1;
			}
		}else{
			return 1;
		}
	}
	//Filehandle: 
	public function writeto_file($link,$content){
		if(is_file($link)){
			if(file_put_contents($link,$content)){
				return 1;
			}
		}else{
			return 0;
		}
	}
	//Filehandle: copies files and non-empty directories
	public function recurse_copy($src,$dst) { 
		$dir = opendir($src); 
		if(!is_dir($dst)){
			mkdir($dst,0755, true );
			if(!is_dir($dst)){
				return 0;
			}
		}
		
		while(false !== ( $file = readdir($dir)) ) { 
			if (( $file != '.' ) && ( $file != '..' )) { 
				if ( is_dir($src . '/' . $file) ) { 
					$this-> recurse_copy($src . '/' . $file,$dst . '/' . $file); 
				} 
				else { 
					copy($src . '/' . $file,$dst . '/' . $file); 
				} 
			} 
		} 
		closedir($dir); 
		return 1;
	}	
	//Filehandle: copies files and non-empty directories starting with D_ 
	public function recurse_copy_topics($src,$dst) { 
		$dir = opendir($src); 
		if(!is_dir($dst)){
			mkdir($dst,0755, true );
			if(!is_dir($dst)){
				return 0;
			}
		}
		
		while(false !== ( $file = readdir($dir)) ) { 
			if (( $file != '.' ) && ( $file != '..' ) && (strpos($file,'D_') !== FALSE) ) { 
				if ( is_dir($src . '/' . $file) ) { 
					$this-> recurse_copy($src . '/' . $file,$dst . '/' . $file); 
				} 
				else { 
					copy($src . '/' . $file,$dst . '/' . $file); 
				} 
			} 
		} 
		closedir($dir); 
		return 1;
	}	
	//Filehandle: copies files and non-empty directories starting with D_ 
	public function recurse_copy_questions($src,$dst) { 
		$dir = opendir($src); 
		if(!is_dir($dst)){
			mkdir($dst,0755, true );
			if(!is_dir($dst)){
				return 0;
			}
		}
		
		while(false !== ( $file = readdir($dir)) ) { 
			if (( $file != '.' ) && ( $file != '..' ) && (strpos($file,'questions') !== FALSE) ) { 
				if ( is_dir($src . '/' . $file) ) { 
					$this-> recurse_copy($src . '/' . $file,$dst . '/' . $file); 
				} 
				else { 
					copy($src . '/' . $file,$dst . '/' . $file); 
				} 
			} 
		} 
		closedir($dir); 
		return 1;
	}
	//Filehandle: 
	public function read_questions_heads($questions){
		$return = array();
		foreach($questions as $file){
			if(strpos($file , 'tag_') == true){
				$existing_tag = substr($file, strpos($file,'tag_')+4);
				$existing_tag = substr($existing_tag, 0,10);
				//extracting the question head from the file
				$content= file_get_contents($this->root.$file);
				//extracting head info
				$head_info = $this->get_question_head_info($content);
				$return[$existing_tag] = $head_info;
			}else{
				$tag = $file;
				$return[$tag] = $tag;
			}
		}
		return $return;
	}
	//Filehandle: 
	public function read_points_heads($points){
		$return = array();
		foreach($points as $file){
			if(strpos($file , 'tag_') == true){
				$existing_tag = substr($file, strpos($file,'tag_')+4);
				$existing_tag = substr($existing_tag, 0,10);
				//extracting the question head from the file
				$content= file_get_contents($this->root.$file);
				//extracting head info
				$head_info = $this->get_point_head_info($content);
				$return[$existing_tag] = $head_info;
			}else{
				$tag = $file;
				$return[$tag] = $tag;
			}
		}
		return $return;
	}
	//Filehandle; function compy from questioncontr to prevent entanglement
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
			$is_exam = $this->derive_is_exam($item->getAttribute('q_origin'));
			$q_origin = $item->getAttribute('q_origin');
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
			if($is_exam == 1){
				$exam_num = substr($q_origin, -2, strlen($q_origin));
				$exam_num = sprintf("%02d", $exam_num);
				$q_origin = substr($q_origin, 0, -3);
			}else{$exam_num = NULL;}
			//
			
			//
			$output_head = array (
			   'q_level' => $item->getAttribute('q_level'),
			   'q_origin' => $q_origin,
			   'q_is_exam' => $is_exam,
			   'q_exam_num' => $exam_num,
			   'q_type' => $item->getAttribute('q_type'),
			   'q_difficulty' => $item->getAttribute('q_difficulty'),
			   'q_total_marks' => $total_marks,
			);
		};
		//return the full details in the hidden description
		return $output_head;
	}
	//Filehandle: this takes the discription written by us and extracts the informatio in the made up html tags
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
	//Filehandle:
	public function find_q_point_unique($question){
		//
		$tags_point = array();
		//loop throught the questions link
		foreach($question as $link){
			//getting the question tage from the link
			if(strpos($link , 'tag_') == true){
				$existing_tag = substr($link, strpos($link,'tag_')+4);
				$question_tag = substr($existing_tag, 0,10);
			}else{
				$question_tag= '';
			}
			//
			if(strpos($link,'/N_')!== false){
				//
				$point_directory = substr($link,0,strpos($link,'/questions'));
				$point_directory = substr($point_directory,strpos($point_directory, '/specifications'),strlen($point_directory));
				//
				$files = scandir($this->root.$point_directory);
				//
				$tags_point[$question_tag] = $this-> extract_unique_ids($files)[0];
			}else{
				$tags_point[$question_tag] = '';
			}
		}
		return $tags_point;
	}
	//Model
	public function derive_is_exam($q_origin){
		//q_origin_format is a string with structure  level-board-paper-month-year-qnumber
		$arr = explode('-',$q_origin);
		if(count($arr) == 6){
			//
			return 1;
		}else{
			if(count($arr) > 1){
				return -1;
			}else{
				return 0;
			}
			
		}
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}
