<?php
namespace MVC;

//gives the model instructions and information so the model can do what we want
//it insert or updates to the database
class flashcardview extends flashcard{
	//
	public function load_practice($subject,$user){
		//
		$query = "select * from $this->db_table where fc_subject = '$subject' and fc_uuid = '$user' order by fc_moduel asc,fc_last_seen asc,fc_bucket asc limit 100";
		$run = $this->run_query($query);
		$info = array();
		//
		$day = 60*60*24;
		$three = 3*60*60*24;
		$seven = 7*60*60*24;
		//
		while($result = mysqli_fetch_assoc($run)){
			//
			$seen = $result['fc_last_seen'];
			//
			if($result['fc_bucket'] == 0){
				//
				if(time() - $seen > $day){
					$info[] = $result;
				}
			}elseif($result['fc_bucket'] == 1){
				//
				if(time() - $seen > $three){
					$info[] = $result;
				}
			}else{
				if($result['fc_bucket'] == 2){
					//
					if(time() - $seen > $seven){
						$info[] = $result;
					}
				}
			}
			
		}
		//
		return $info;
	}
	//
	public function load_manager($subject,$user,$utility){
		//
		$query = "select * from $this->db_table where fc_subject = '$subject' and fc_uuid = '$user' order by fc_moduel asc,fc_chapter asc,fc_topic asc,id asc";
		$run = $this->run_query($query);
		$info = array();
		//
		while($result = mysqli_fetch_assoc($run)){
			//
			$subject = $result['fc_subject'];
			$moduel = $utility -> make_display_header($result['fc_moduel']);
			$chapter = $utility -> make_display_header($result['fc_chapter']);
			$topic = $utility -> make_display_header($result['fc_topic']);
			//
			$info[$subject][$moduel][$chapter][$topic][] = $result;
		}
		//
		return $info;
		
	}
}