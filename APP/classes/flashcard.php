<?php
namespace MVC;

//gives the model instructions and information so the model can do what we want
//it insert or updates to the database
class flashcard extends dbh{
	public $db_table = ' flashcards ';
	//Model: constructing flashcard variables
	protected function manual_construct($flashcards){
		//id,user_unique_id,subject,moduel,chapter,topic,question,answer,bucket,last_seen
		$this->input_info = array();
		//
		if(isset($flashcards['id'])){
			//
			$this -> id = $flashcards['id'];
			$this->input_info['id'] = $this -> id;
		}else{$this->id = '';}
		
		//
		if(isset($flashcards['fc_uuid'])){
			//
			$this -> fc_uuid = $flashcards['fc_uuid'];
			$this->input_info['fc_uuid'] = $this -> fc_uuid;
		}else{$this->fc_uuid = '';}
		//
		if(isset($flashcards['fc_puid'])){
			//
			$this -> fc_puid = $flashcards['fc_puid'];
			$this->input_info['fc_puid'] = $this -> fc_puid;
		}else{$this->fc_puid = '';}
		//
		if(isset($flashcards['fc_subject'])){
			//
			$this -> fc_subject = $flashcards['fc_subject'];
			$this->input_info['fc_subject'] = $this -> fc_subject;
		}else{$this->fc_subject = '';}
		//
		if(isset($flashcards['fc_moduel'])){
			//
			$this -> fc_moduel = $flashcards['fc_moduel'];
			$this->input_info['fc_moduel'] = $this -> fc_moduel;
		}else{$this->fc_moduel = '';}
		//
		if(isset($flashcards['fc_chapter'])){
			//
			$this -> fc_chapter = $flashcards['fc_chapter'];
			$this->input_info['fc_chapter'] = $this -> fc_chapter;
		}else{$this->fc_chapter = '';}
		//
		if(isset($flashcards['fc_topic'])){
			//
			$this -> fc_topic = $flashcards['fc_topic'];
			$this->input_info['fc_topic'] = $this -> fc_topic;
		}else{$this->fc_topic = '';}
		//
		if(isset($flashcards['fc_question'])){
			//
			$this -> fc_question = $flashcards['fc_question'];
			$this->input_info['fc_question'] = $this -> fc_question;
		}else{$this->fc_question = '';}
		//
		if(isset($flashcards['fc_answer'])){
			//
			$this -> fc_answer = $flashcards['fc_answer'];
			$this->input_info['fc_answer'] = $this -> fc_answer;
		}else{$this->fc_answer = '';}
		//
		if(isset($flashcards['fc_bucket'])){
			//
			$this -> fc_bucket = $flashcards['fc_bucket'];
			$this->input_info['fc_bucket'] = $this -> fc_bucket;
		}else{$this->fc_bucket = '';}
		//
		if(isset($flashcards['fc_last_seen'])){
			//
			$this -> fc_last_seen = $flashcards['fc_last_seen'];
			$this->input_info['fc_last_seen'] = $this -> fc_last_seen;
		}else{$this->fc_last_seen = '';}
	}
	//
	protected function manual_construct_id($id){
		//
		$q_query = "select * from $this->db_table where id = '$id' limit 1 ";
		$run = mysqli_query($this->conn,$q_query);
		//
		while($result = mysqli_fetch_assoc($run)){
			$this->manual_construct($result);
		}
		
	}
	//
	public function run_query($query){
		$return = mysqli_query($this->conn,$query);
		return $return;
	}
}