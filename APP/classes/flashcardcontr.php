<?php
namespace MVC;

//gives the model instructions and information so the model can do what we want
//it insert or updates to the database
class flashcardcontr extends flashcard{
	//Contr: 
	public function create_flashcard($user_unique_id,$pt_unique_id,$question,$answer,$pointcontr){
		//
		$point_obj =$pointcontr -> make_obj_unique_id($pt_unique_id);
		//
		$user = $user_unique_id;
		$subject = $point_obj['pt_subject'];
		$moduel = $point_obj['pt_moduel'];
		$chapter = $point_obj['pt_chapter'];
		$topic = $point_obj['pt_topic'];
		$question = $this->conn -> real_escape_string($question);
		$answer = $this->conn -> real_escape_string($answer);
		$bucket = 0;
		$last_seen = time();
		//
		$query= "insert into $this->db_table 
			(
			fc_puid,
			fc_uuid,
			fc_subject,
			fc_moduel, 
			fc_chapter,
			fc_topic,
			fc_question,
			fc_answer,
			fc_bucket,
			fc_last_seen) values
			(
			'$pt_unique_id',
			'$user',
			'$subject',
			'$moduel' ,
			'$chapter',
			'$topic',
			'$question',
			'$answer',
			'$bucket',
			'$last_seen')
			";
		if($this->run_query($query)){
			return 1;
		}else{
			return 0;
		}
	}
	//
	public function delete_flashcard($fc_id,$uuid){
		//
		$query = "select id from $this->db_table where id = '$fc_id' and fc_uuid = '$uuid'";
		$run = $this->run_query($query);
		$count = mysqli_num_rows($run);
		//
		if($count > 0){
			//
			$query = "delete from $this->db_table where id = '$fc_id' limit 1";
			//
			if($this->run_query($query)){
				return 1;
			}else{
				return 0;
			}
		}else{
			return 0;
		}
	}
	//
	public function mark_flashcard($fc_id,$uuid,$status){
		//
		$this->manual_construct_id($fc_id);
		settype($this->fc_bucket, "integer");
		//
		if($this->fc_last_seen + 60*60*23 > time()){
			return 1;
			die;
		}
		//
		if($status == 1){
			
			if($this->fc_bucket < 2){
				//
				$new_bucket = $this->fc_bucket + 1;
			}else{
				$new_bucket = $this->fc_bucket;
			}
		}elseif($status == 0){
			if($this->fc_bucket > 0){
				$new_bucket = $this->fc_bucket - 1;
			}else{
				$new_bucket = $this->fc_bucket;
			}
		}
		//
		if(isset($new_bucket) ){
			//
			$time = time();
			$query = "update $this->db_table set fc_bucket = '$new_bucket',fc_last_seen='$time' where id ='$this->id' and fc_uuid='$this->fc_uuid'";
			if($this->run_query($query)){
				return 1;
			}else{
				return 0;
			}
		}else{
			return 0;
		}
		
	}
	//
	public function reset_flashcards_progress($fc_uuid){
		//
		$query = "update $this->db_table set fc_last_seen = 0 where fc_uuid = '$fc_uuid'";
		if($this->run_query($query)){
			return 1;
		}else{
			return 0;
		}
	}
}