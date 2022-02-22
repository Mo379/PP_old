<?php
namespace MVC;

//gives the model instructions and information so the model can do what we want
//it insert or updates to the database
class question extends dbh{
	public $db_table = ' questions ';
	public $info_src = 'specifications';
	//Model: constructing question variables
	protected function manual_construct($question){
		$this-> input_info = array();;
		//run for all
		if(!empty($question)){
			foreach($question as $key =>$value){
				if($key !== 'q_unique_id' and $key !== 'general_info' and $key !=='q_head_info' and $key !=='q_point' and $key !== 'q_directory' and $key !== 'q_link'){
					$question[$key] = ucfirst(strtolower($value));
				}
				if($key == 'general_info'){
					foreach($value as $n =>$info){
						$question[$key][$n] = ucfirst(strtolower($info));
					}
				}
			}
		}
		
		if(isset($question['q_id'])){
			$this -> q_id = $question['q_id'];
			$this->input_info['q_id'] = $this -> q_id;
		}else{$this->q_id = '';}
		
		if(isset($question['q_level'])){
			$this -> q_level = $question['q_level'];
			$this->input_info['q_level'] = $this -> q_level;
		}else{$this->q_level = '';}
		
		if(isset($question['q_origin'])){
			$this -> q_origin = $question['q_origin'];
			$this->input_info['q_origin'] = $this -> q_origin;
		}else{$this->q_origin = '';}
		
		
		if(isset($question['q_is_exam'])){
			$this -> q_is_exam = $question['q_is_exam'];
			$this->input_info['q_is_exam'] = $this -> q_is_exam;
		}else{$this->q_is_exam = '';}
		

		if(isset($question['q_subject'])){
			$this -> q_subject = $question['q_subject'];
			$this->input_info['q_subject'] = $this -> q_subject;
		}else{$this->q_subject = '';}	
		
		if(isset($question['q_moduel'])){
			$this -> q_moduel = $question['q_moduel'];
			$this->input_info['q_moduel'] = $this -> q_moduel;
		}else{$this->q_moduel = '';}
		
		if(isset($question['q_chapter'])){
			$this -> q_chapter = $question['q_chapter'];
			$this->input_info['q_chapter'] = $this -> q_chapter;
		}else{$this->q_chapter = '';}
		
		if(isset($question['q_topic'])){
			$this -> q_topic = $question['q_topic'];
			$this->input_info['q_topic'] = $this -> q_topic;
		}else{$this->q_topic = '';}
		
		if(isset($question['q_point'])){
			$this -> q_point = $question['q_point'];
			$this->input_info['q_point'] = $this -> q_point;
		}else{$this->q_point = '';}
		
		if(isset($question['q_type'])){
			$this -> q_type = $question['q_type'];
			$this->input_info['q_type'] = $this -> q_type;
		}else{$this->q_type = '';}
		
		if(isset($this->q_subject) and isset($this->q_moduel)){
			//
			if(isset($this->q_point) and !empty($this->q_point)){
				$this -> q_loc = "point";
			}elseif(isset($this->q_topic) and  !empty($this->q_topic)){
				$this -> q_loc = "topic";
			}else{
				$this -> q_loc = "chapter";
			}
			$this->input_info['q_loc'] = $this -> q_loc;
		}else{$this->q_loc = '';}
		
		if(isset($question['q_difficulty'])){
			$this -> q_difficulty = $question['q_difficulty'];
			$this->input_info['q_difficulty'] = $this -> q_difficulty;
		}else{$this->q_difficulty = '';}
		
		if(isset($question['q_total_marks'])){
			$this -> q_total_marks = $question['q_total_marks'];
			$this->input_info['q_total_marks'] = $this -> q_total_marks;
		}else{$this->q_total_marks = '';}
		
		if(isset($question['q_directory'])){
			$this -> q_directory = $question['q_directory'];
			$this->input_info['q_directory'] = $this -> q_directory;
		}else{$this->q_directory = '';}
			
		
		if(isset($question['q_link'])){
			$this ->  q_link= $question['q_link'];
			$this->input_info['q_link'] = $this -> q_link;
		}else{$this->q_link = '';}
		
		
		if(isset($question['q_unique_id'])){
			$this -> q_unique_id = $question['q_unique_id'];
			$this->input_info['q_unique_id'] = $this -> q_unique_id;
		}else{$this->q_unique_id = '';}
		
		if(isset($question['general_info'])){
			$this -> general_info = $question['general_info'];
			foreach($this->general_info as $key => $value){
				
				if($key == 'A'){
					$this ->q_subject = $value;
				}
				
				if($key == 'B'){
					$this ->q_moduel = $value;
				}
				
				if($key == 'C'){
					$this ->q_chapter = $value;
				}
				
				if($key == 'D'){
					$this ->q_topic = $value;
				}
				
			}
			$this->input_info['general_info'] = $this -> general_info;
		}
		
		if(isset($question['q_head_info'])){
			$this -> q_head_info = $question['q_head_info'];
			foreach($this->q_head_info as $key => $value){
				
				if($key == 'q_difficulty'){
					$this ->q_difficulty = $value;
				}
				
				if($key == 'q_type'){
					$this ->q_type = $value;
				}
				
				if($key == 'q_origin'){
					$this ->q_origin = $value;
				}
				
				if($key == 'q_level'){
					$this ->q_level = $value;
				}
				
				if($key == 'q_is_exam'){
					$this ->q_is_exam = $value;
				}
				
				if($key == 'q_exam_num'){
					$this ->q_exam_num = $value;
				}
				if($key == 'q_total_marks'){
					$this ->q_total_marks = $value;
				}
				
				
			}
			$this->input_info['q_head_info'] = $this -> q_head_info;
		}
	}
	//Model: 
	protected function manual_construct_q_unique_id($q_unique_id){
		//
		$q_query = "select * from $this->db_table where q_unique_id = '$q_unique_id' limit 1 ";
		$run = mysqli_query($this->conn,$q_query);
		$result = mysqli_fetch_assoc($run);
		$this->manual_construct($result);
	}
	//Model: run_query
	protected function run_query($query){
		return mysqli_query($this->conn,$query);
	}
	//Model: takes arrays of tags to delete from the database
	protected function delete_row($tag){
		$query =  "delete from $this->db_table where q_unique_id = '$tag'";
		return mysqli_query($this->conn,$query);		
	}
	//Model: auto database treatment
	protected function treat_db($question){
		$this->manual_construct($question);
		//check if the unique id is in the database to begin with
		$query = "select q_unique_id from $this->db_table where q_unique_id = '$this->q_unique_id' ";
		$run = mysqli_query($this->conn,$query);
		$return = mysqli_fetch_row($run);
			
		if($return == true){
			$query_1 = $this->make_query('','update');
			mysqli_query($this->conn,$query_1);
		}else{
			$query_1 = $this->make_query('','insert');
			mysqli_query($this->conn,$query_1);
		}
		return 1; 
	}
	//Model: make insert query
	private function make_query($selects,$type){
		if($type == 'select'){
			$query = "
			select $selects from $this->db_table where 1 and 
			q_id ='$this->q_id' and
			q_level = '$this->q_level' and
			q_origin = '$this->q_origin' and
			q_is_exam = '$this->q_is_exam' and
			q_exam_num = '$this->q_exam_num' and
			q_subject = '$this->q_subject' and
			q_moduel = '$this->q_moduel' and
			q_chapter = '$this->q_chapter' and
			q_topic = '$this->q_topic' and
			q_point = '$this->q_point' and
			q_type = '$this->q_type' and
			q_loc = '$this->q_loc' and
			q_difficulty = '$this->q_difficulty' and
			q_total_marks = '$this->q_total_marks' and
			q_directory = '$this->q_directory' and
			q_link = '$this->q_link' and
			q_unique_id = '$this->q_unique_id'
			";
			return $query;
		}
		
		if($type == 'update'){
			$query = "
			update $this->db_table set
			q_level = '$this->q_level',
			q_origin = '$this->q_origin',
			q_is_exam = '$this->q_is_exam',
			q_exam_num = '$this->q_exam_num',
			q_subject = '$this->q_subject',
			q_moduel = '$this->q_moduel',
			q_chapter = '$this->q_chapter',
			q_topic = '$this->q_topic',
			q_loc = '$this->q_loc',
			q_point = '$this->q_point',
			q_type = '$this->q_type',
			q_difficulty = '$this->q_difficulty',
			q_total_marks = '$this->q_total_marks',
			q_directory = '$this->q_directory',
			q_link = '$this->q_link',
			q_unique_id = '$this->q_unique_id'
			
			
			where 1 
			
			and
			q_unique_id = '$this->q_unique_id'
			";
			return $query;
		}
		
		if($type == 'insert'){
			$query = "
			insert into $this->db_table 
			(
			q_level,
			q_origin,
			q_is_exam,
			q_exam_num,
			q_subject,
			q_moduel, 
			q_chapter,
			q_topic,
			q_point,
			q_type,
			q_loc,
			q_difficulty,
			q_total_marks,
			q_directory,
			q_link,
			q_unique_id) values
			
			(
			'$this->q_level', 
			'$this->q_origin', 
			'$this->q_is_exam', 
			'$this->q_exam_num', 
			'$this->q_subject',
			'$this->q_moduel' ,
			'$this->q_chapter',
			'$this->q_topic',
			'$this->q_point', 
			'$this->q_type', 
			'$this->q_loc', 
			'$this->q_difficulty',
			'$this->q_total_marks',
			'$this->q_directory',
			'$this->q_link',
			'$this->q_unique_id')
			";
			return $query;
		}
	}
	//Model: return database tags of points in a specific dir
	protected function db_tags_return($dir){
		$query = "select q_unique_id from $this->db_table where q_directory like '%$dir%' ";
		$run = mysqli_query($this->conn,$query);
		$return = array();
		while ($row = mysqli_fetch_assoc($run)) {
			$return[]=$row['q_unique_id'];
		}
		return $return;
	}
	//Model: takes arrays of tags to delete from the database
	protected function delete_rows($tags){
		if(!empty($tags)){
			$string = implode(",",$tags);
			$query =  "delete from $this->db_table where q_unique_id in ($string)";
			$run = mysqli_query($this->conn,$query);
		}
		return 1;
	}
}
