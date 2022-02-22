<?php
namespace MVC;

//gives the model instructions and information so the model can do what we want
//it insert or updates to the database
class point extends dbh{
	public $db_table = ' points ';
	public $info_src = 'specifications';
	//Model: constructing point variables
	protected function manual_construct($point){
		$this->input_info = array();
		//run for all
		if(!empty($point)){
			foreach($point as $key =>$value){
				if($key !== 'pt_unique_id' and $key !== 'general_info' and $key !== 'pt_directory' and $key !=='pt_head_info' and $key !== 'pt_link' and $key !== 'pt_description'){
					$point[$key] = ucfirst(strtolower($value));
				}
				if($key == 'general_info'){
					foreach($value as $n =>$info){
						$point[$key][$n] = ucfirst(strtolower($info));
					}
				}
			}
		}
		//
		if(isset($point['pt_id'])){
			//
			$this -> pt_id = $point['pt_id'];
			$this->input_info['pt_id'] = $this -> pt_id;
		}else{$this->pt_id = '';}
		
		//
		if(isset($point['pt_board'])){
			//
			$this -> pt_board = $point['pt_board'];
			$this->input_info['pt_board'] = $this -> pt_board;
		}else{$this->pt_board = '';}
		//
		if(isset($point['pt_level'])){
			//
			$this -> pt_level = $point['pt_level'];
			$this->input_info['pt_level'] = $this -> pt_level;
		}else{$this->pt_level = '';}
		//
		if(isset($point['pt_description'])){
			//
			$this -> pt_description = $point['pt_description'];
			$this->input_info['pt_description'] = $this -> pt_description;
		}else{$this->pt_description = '';}
		//
		if(isset($point['pt_cache'])){
			//
			$this -> pt_cache = $point['pt_cache'];
			$this->input_info['pt_cache'] = $this -> pt_cache;
		}else{$this->pt_cache = '';}
		//
		if(isset($point['pt_subject'])){
			//
			$this -> pt_subject = $point['pt_subject'];
			$this->input_info['pt_subject'] = $this -> pt_subject;
		}else{$this->pt_subject = '';}
		//
		if(isset($point['pt_moduel'])){
			//
			$this -> pt_moduel = $point['pt_moduel'];
			$this->input_info['pt_moduel'] = $this -> pt_moduel;
		}else{$this->pt_moduel = '';}
		//
		if(isset($point['pt_chapter'])){
			//
			$this -> pt_chapter = $point['pt_chapter'];
			$this->input_info['pt_chapter'] = $this -> pt_chapter;
		}else{$this->pt_chapter = '';}
		//
		if(isset($point['pt_topic'])){
			//
			$this -> pt_topic = $point['pt_topic'];
			$this->input_info['pt_topic'] = $this -> pt_topic;
		}else{$this->pt_topic = '';}
		//
		if(isset($point['pt_number'])){
			//
			$this -> pt_number = $point['pt_number'];
			$this->input_info['pt_number'] = $this -> pt_number;
		}else{$this->pt_number = '';}
		//
		if(isset($point['pt_spec_id'])){
			//
			$this -> pt_spec_id = $point['pt_spec_id'];
			$this->input_info['pt_spec_id'] = $this -> pt_spec_id;
		}else{$this->pt_spec_id = '';}
		//
		if(isset($point['pt_directory'])){
			//
			$this -> pt_directory = $point['pt_directory'];
			$this->input_info['pt_directory'] = $this -> pt_directory;
		}else{$this->pt_directory = '';}
		//
		if(isset($point['pt_link'])){
			//
			$this -> pt_link = $point['pt_link'];
			$this->input_info['pt_link'] = $this -> pt_link;
		}else{$this->pt_link = '';}
		//
		if(isset($point['pt_unique_id'])){
			//
			$this -> pt_unique_id = $point['pt_unique_id'];
			$this->input_info['pt_unique_id'] = $this -> pt_unique_id;
		}else{$this->pt_unique_id = '';}
		//
		if(isset($point['general_info'])){
			//
			$this -> general_info = $point['general_info'];
			$this->input_info['general_info'] = $this -> general_info;
			foreach($this->general_info as $key => $value){
				//
				if($key == 'A'){
					$this ->pt_subject = $value;
				}
				//
				if($key == 'B'){
					$this ->pt_moduel = $value;
				}
				//
				if($key == 'C'){
					$this ->pt_chapter = $value;
				}
				//
				if($key == 'D'){
					$this ->pt_topic = $value;
				}
				//
				if($key == 'N'){
					$this ->pt_number = $value;
				}
			}
		}
		//
		if(isset($point['filter_spec'])){
			//
			$this -> filter_spec = $point['filter_spec'];
			$this->input_info['filter_spec'] = $this -> filter_spec;
		}else{$this->filter_spec = '';}
		//
		if(isset($point['user_list'])){
			//
			$this -> user_list = $point['user_list'];
			$this->input_info['user_list'] = $this -> filter_spec;
		}else{$this->filter_spec = '';}
		//
		if(isset($point['pointer'])){
			//
			$this -> pointer = $point['pointer'];
			$this->input_info['pointer'] = $this -> filter_spec;
		}else{$this->filter_spec = '';}
		//
		if(isset($point['pt_head_info'])){
			$this -> pt_head_info = $point['pt_head_info'];
			foreach($this->pt_head_info as $key => $value){
				
				if($key == 'point_level'){
					$this ->pt_level = $value;
				}
				if($key == 'pt_board'){
					$this ->pt_board = $value;
				}
				
				
				
			}
			$this->input_info['pt_head_info'] = $this -> pt_head_info;
		}
	}
	//
	protected function manual_construct_unique_id($pt_unique_id){
		//
		$q_query = "select * from $this->db_table where pt_unique_id = '$pt_unique_id' limit 1 ";
		$run = mysqli_query($this->conn,$q_query);
		$result = mysqli_fetch_assoc($run);
		$this->manual_construct($result);
	}
	//Model: make point query
	private function make_query($selects,$type){
		if($type == 'select'){
			$query = "
			select $selects from $this->db_table where 1 and 
			pt_board = '$this->pt_board' and
			pt_level = '$this->pt_level' and
			pt_subject = '$this->pt_subject' and
			pt_moduel = '$this->pt_moduel' and
			pt_chapter = '$this->pt_chapter' and
			pt_topic = '$this->pt_topic' and
			pt_number = '$this->pt_number' and
			pt_directory = '$this->pt_directory' and
			pt_link = '$this->pt_link' and
			pt_unique_id = '$this->pt_unique_id'
			";
			return $query;
		}
		
		if($type == 'update'){
			$query = "
			update $this->db_table set
			pt_board = '$this->pt_board' ,
			pt_level = '$this->pt_level' ,
			pt_subject = '$this->pt_subject' ,
			pt_moduel = '$this->pt_moduel' ,
			pt_chapter = '$this->pt_chapter' ,
			pt_topic = '$this->pt_topic' ,
			pt_number = '$this->pt_number',
			pt_directory = '$this->pt_directory',
			pt_link = '$this->pt_link' ,
			pt_unique_id = '$this->pt_unique_id'
			
			
			where 1 
			
			and
			pt_unique_id = '$this->pt_unique_id'
			";
			return $query;
		}
		
		if($type == 'insert'){
			$query = "
			insert into $this->db_table 
			(
			pt_board,
			pt_level,
			pt_subject,
			pt_moduel, 
			pt_chapter,
			pt_topic,
			pt_number,
			pt_directory,
			pt_link,
			pt_unique_id) values
			
			(
			'$this->pt_board',
			'$this->pt_level',
			'$this->pt_subject',
			'$this->pt_moduel' ,
			'$this->pt_chapter',
			'$this->pt_topic',
			'$this->pt_number',
			'$this->pt_directory',
			'$this->pt_link',
			'$this->pt_unique_id')
			";
			return $query;
		}
	}
	//Model: takes arrays of tags to delete from the database
	protected function delete_rows($tags){
		if(!empty($tags)){
			$string = implode(",",$tags);
			$query =  "delete from $this->db_table where pt_unique_id in ($string)";
			$run = mysqli_query($this->conn,$query);
		}
		
	}
	//Model: trat db for points
	protected function treat_db($point){
		$this->manual_construct($point);
		//check if the unique id is in the database to begin with
		$query = "select pt_unique_id from $this->db_table where 1 and pt_unique_id = '$this->pt_unique_id' ";
		$run = mysqli_query($this->conn,$query);
		$return = mysqli_fetch_row($run);
		if($return == true){
			$query_1 = $this->make_query('','update');
			mysqli_query($this->conn,$query_1);
		}else{
			$query_1 = $this->make_query('','insert');
			mysqli_query($this->conn,$query_1);
		}
			
		
	}
	//filters the query, incase an input is empty
	protected function t_query_filter($column,$input){
		if (empty($input)){
			return  '';
		}else{
			return "and $column = '$input'";
		}
	}
	//Model: return database tags of points in a specific dir
	protected function db_tags_return($dir){
		$query = "select pt_unique_id from $this->db_table where pt_directory like '%$dir%' ";
		$run = mysqli_query($this->conn,$query);
		$return = array();
		while ($row = mysqli_fetch_assoc($run)) {
			$return[]=$row['pt_unique_id'];
		}
		return $return;
	}
	//updates the spec point ordering in the database
	//run this after the db table is fully constructed
	public function spec_point_ordering(){
		$query = "select pt_subject from $this->db_table where 1 group by pt_subject";
		$run_a = mysqli_query($this->conn,$query);
		
		
		while($result = mysqli_fetch_assoc($run_a)){
			$subject = $result['pt_subject'];
			
			$query = "select pt_moduel from $this->db_table where 1 and pt_subject = '$subject' group by pt_moduel";
			$run_b = mysqli_query($this->conn,$query);
			
			$a_num = 1;
			while($result = mysqli_fetch_assoc($run_b)){
				$moduel = $result['pt_moduel'];
				$query = "select pt_chapter from $this->db_table where 1 and pt_subject = '$subject' and pt_moduel = '$moduel' group by pt_chapter";
				$run_c = mysqli_query($this->conn,$query);
				$b_num = 1;
				while($result = mysqli_fetch_assoc($run_c)){ 
					$chapter = $result['pt_chapter'];
					
					$query = "select pt_topic from $this->db_table where 1 and pt_subject = '$subject' and pt_moduel = '$moduel' and pt_chapter = '$chapter' group by pt_topic";
					$run_d = mysqli_query($this->conn,$query);
					
					$c_num= 1;
					while($result = mysqli_fetch_assoc($run_d)){ 
						$topic = $result['pt_topic'];
						
						$query = "select pt_number,pt_unique_id from $this->db_table where 1 and pt_subject = '$subject' and pt_moduel = '$moduel' and pt_chapter = '$chapter' and pt_topic='$topic' ";
						$run_e = mysqli_query($this->conn,$query);
						while($result = mysqli_fetch_assoc($run_e)){ 
							$last = $result['pt_number'];
							$unique_id = $result['pt_unique_id'];
							
							$spec_id = "$b_num.$c_num.$last";
							$query_4 = "update $this->db_table set pt_spec_id = '$spec_id' 
											where 1 and  pt_subject= '$subject' and pt_unique_id = '$unique_id'";
							mysqli_query($this->conn, $query_4);
						}
						$c_num+=1;
					}
					$b_num+=1;
				}
				$a_num +=1;
			}
			
		}
	}
	//
	public function run_query($query){
		$return = mysqli_query($this->conn,$query);
		return $return;
	}
	//
	public function get_link_by_pt_id(){
		$query = "select pt_link from $this->db_table where pt_id='$this->pt_id' limit 1";
		$run = mysqli_query($this->conn, $query);
		$result = mysqli_fetch_assoc($run);
		return $result['pt_link'];
	}
	//
	public function get_directory_by_pt_id(){
		$query = "select pt_directory from $this->db_table where pt_id = '$this->pt_id' limit 1";
		$run = mysqli_query($this->conn,$query);
		$result =  mysqli_fetch_assoc($run);
		return  $result['pt_directory'];
		
	}
	//
	public function get_pt_number_by_pt_id(){
		$query = "select pt_number from $this->db_table where pt_id = '$this->pt_id' limit 1";
		$run = mysqli_query($this->conn,$query);
		$result =  mysqli_fetch_assoc($run);
		return  $result['pt_number'];
		
	}
	//
	public function get_pt_topic_by_pt_id(){
		$query = "select pt_topic from $this->db_table where pt_id = '$this->pt_id' limit 1";
		$run = mysqli_query($this->conn,$query);
		$result =  mysqli_fetch_assoc($run);
		return  $result['pt_topic'];
		
	}
	//
	public function get_sister_moduels($dir){
		$query = "select pt_moduel from $this->db_table where pt_directory like '%$dir%' group by pt_moduel desc";
		$run = mysqli_query($this->conn,$query);
		return $this->return_assoc($run);
	}
	//
	public function get_sister_chapters($dir){
		$query = "select pt_chapter from $this->db_table where pt_directory like '%$dir%' group by pt_chapter desc";
		$run = mysqli_query($this->conn,$query);
		return $this->return_assoc($run);
	}
	//
	public function get_sister_topics($dir){
		$query = "select pt_topic from $this->db_table where pt_directory like '%$dir%' group by pt_topic desc";
		$run = mysqli_query($this->conn,$query);
		return $this->return_assoc($run);
	}
	//
	public function get_sister_points(){
		$query = "select * from $this->db_table where pt_chapter = '$this->pt_chapter' and pt_moduel = '$this->pt_moduel' and pt_topic = '$this->pt_topic' order by pt_number desc";
		$run = mysqli_query($this->conn,$query);
		return $this->return_assoc($run);
	}
	//
	public function get_sister_moduels_reverse($dir){
		$query = "select pt_moduel from $this->db_table where pt_directory like '%$dir%' group by pt_moduel ASC";
		$run = mysqli_query($this->conn,$query);
		return $this->return_assoc($run);
	}
	//
	public function get_sister_chapters_reverse($dir){
		$query = "select pt_chapter from $this->db_table where pt_directory like '%$dir%' group by pt_chapter ASC";
		$run = mysqli_query($this->conn,$query);
		return $this->return_assoc($run);
	}
	//
	public function get_sister_topics_reverse($dir){
		$query = "select pt_topic from $this->db_table where pt_directory like '%$dir%' group by pt_topic ASC";
		$run = mysqli_query($this->conn,$query);
		return $this->return_assoc($run);
	}
	
	//
	public function get_sister_points_reverse(){
		$query = "select * from $this->db_table where pt_chapter = '$this->pt_chapter' and pt_moduel = '$this->pt_moduel' and pt_topic = '$this->pt_topic' order by pt_number ASC";
		$run = mysqli_query($this->conn,$query);
		return $this->return_assoc($run);
	}
	
	//getting results as an associative array and storing it in an array
	public function return_assoc($run){
		$return_assoc = array(); 
		while ($result = mysqli_fetch_assoc($run)){
			$return_assoc[]=$result;
			
		}
		return $return_assoc;
		
	}
}
