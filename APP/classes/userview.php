<?php
namespace MVC;


class userview extends user{
	//View: get the user's row
	public function get_profile($user_unique){
		//
		$this->manual_construct_unique_id($user_unique);
		//
		return $this->input_info;
	}
	//View:
	public function get_editors_contract_status(){
		$query = "select user_unique_id,editors,editors_contract_status,approved_editor,user_email,user_vkey,EditorPrimary_subject,EditorSecondary_subject from users where editors = 1";
		$run = $this->run_query($query);
		//
		$return = array();
		while($assoc = mysqli_fetch_assoc($run)){
			$return[] = $assoc;
		}
		return ($return);
	}
	//View:
	public function get_contract_info($editor_unique_id){
		//
		$this->manual_construct_unique_id($editor_unique_id);
		//
		$info = array();
		$user_dir = "$this->root/users/$editor_unique_id/contract";
		$signature_file_link = "/users/$editor_unique_id/contract/EditorSignature.png";
		//
		$info['signature'] = $signature_file_link;
		$info['editor_name'] = $this->user_first.' '.$this->user_last;
		$info['editor_email'] = $this->user_email;
		//
		$files = scandir($user_dir);
		
		foreach($files as $file){
			//
			if(strpos($file,'EditorID') !== false){
				$info['id_link'] = "/users/$editor_unique_id/contract/$file";
			}
			//
			if(strpos($file,'EditorQualification') !== false){
				$info['qualification_link'] = "/users/$editor_unique_id/contract/$file";
			}
			
		}
		return($info);
	}
	//View
	public function compile_contract_information($editor_unique_id,$vkey){
		//
		$this->manual_construct_unique_id($editor_unique_id);
		//
		if($this->user_vkey == $vkey){
			
			//
			$company_sign_time = date('d-m-y',$this->admin_signature_time);
			$editor_sign_time = date('d-m-y',$this->editor_signature_time);
			//
			if($this->contract_termination_time > 0){
				$termination_time = 'Ended '.date('d-m-y',$this->contract_termination_time);
			}else{
				$termination_time = 'Active';
			}
			
			//
			return array(
				'company_sign_time' => $company_sign_time,
				'editor_sign_time' => $editor_sign_time,
				'termination_time' => $termination_time,
				'editor_name' => $this->user_first.' '.$this->user_last,
				'editor_email' => $this->user_email,
			);
		}
	}
	//View: generate the user's current location on the website by evaluating the url information
	public function nav_engine($page_name,$url,$pointcontr,$questioncontr){
		//
		$return = array();
		if($page_name == 'Home'){
			//
			$return['Home'] = '/index'; 
			return $return;
		}elseif($page_name == 'Maths' or $page_name == 'Physics' or $page_name =='Chemistry' or $page_name == 'Biology' or $page_name == 'Forums' or $page_name == 'About'){
			//
			if($page_name == 'Maths'){
				$return['Home'] = '/index'; 
				$return["$page_name"] = '/P/maths'; 
			}if($page_name == 'Physics'){
				$return['Home'] = '/index'; 
				$return["$page_name"] = '/P/physics'; 
			}if($page_name == 'Chemistry'){
				$return['Home'] = '/index'; 
				$return["$page_name"] = '/P/chemistry'; 
			}if($page_name == 'Biology'){
				$return['Home'] = '/index'; 
				$return["$page_name"] = '/P/biology'; 
			}if($page_name == 'Forums'){
				$return['Home'] = '/index'; 
				$return["$page_name"] = '/P/forums'; 
			}if($page_name == 'About'){
				$return['Home'] = '/index'; 
				$return["$page_name"] = '/P/about'; 
			}
			return $return;
		}else{
			if($page_name == 'Spec'){
				if(isset($url['user_list']) or isset($url['pt_unique_id'])){
					return null;
				}
				//
				$subject = $url['pt_subject'];
				$subject_disp = ucfirst($subject);
				//
				$moduel = $url['pt_moduel'];
				$moduel_disp = str_replace('_',' ',$moduel);
				$moduel_disp = substr($moduel_disp, 3);
				$moduel_disp = ucfirst($moduel_disp);
				//
				$chapter = $url['pt_chapter'];
				$chapter_disp = str_replace('_',' ',$chapter);
				$chapter_disp = substr($chapter_disp, 3);
				$chapter_disp = ucfirst($chapter_disp);
				//
				$return['Home'] = '/index'; 
				$subject = strtolower($subject);
				$return["$subject_disp"] = "/P/$subject"; 
				$return["Spec"] = "#"; 
				$return["$moduel_disp"] = "#"; 
				$return["$chapter_disp"] = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; 
				//
				return $return;
			}elseif($page_name == 'Questions'){
				//
				$filter = $url['filter'];
				$q_point = $url['pt_unique_id'];
				$pt_obj = $pointcontr-> make_obj_unique_id($q_point);
				//
				if($filter == 'point'){
					//
					$subject = $pt_obj['pt_subject']; 
					$moduel = $pt_obj['pt_moduel']; 
					$chapter = $pt_obj['pt_chapter']; 
					$topic = $pt_obj['pt_topic']; 
					$unique = $pt_obj['pt_unique_id']; 
					$spec_id = $pt_obj['pt_spec_id']; 
				}elseif($filter == 'topic'){
					//
					$subject = $pt_obj['pt_subject']; 
					$moduel = $pt_obj['pt_moduel']; 
					$chapter = $pt_obj['pt_chapter']; 
					$topic = $pt_obj['pt_topic']; 
				}else{
					if($filter == 'chapter'){
						//
						$subject = $pt_obj['pt_subject']; 
						$moduel = $pt_obj['pt_moduel']; 
						$chapter = $pt_obj['pt_chapter']; 
					}else{
						return null;
					}
				}
				//
				$subject_disp = strtolower($subject);
				$subject_disp = ucfirst($subject_disp);
				//
				$moduel_disp = str_replace('_',' ',$moduel);
				$moduel_disp = substr($moduel_disp, 3);
				$moduel_disp = ucfirst($moduel_disp);
				//
				$chapter_disp = str_replace('_',' ',$chapter);
				$chapter_disp = substr($chapter_disp, 3);
				$chapter_disp = ucfirst($chapter_disp);
				//
				if(isset($topic)){
					$topic_disp = str_replace('_',' ',$topic);
					$topic_disp = substr($topic_disp, 3);
					$topic_disp = ucfirst($topic_disp);
				}
				//
				$return['Home'] = '/index'; 
				$return["$subject"] = "/P/$subject_disp"; 
				$return["Spec"] = "#"; 
				$return["$moduel_disp"] = "#"; 
				$return["$chapter_disp"] = "/P/Notes/$subject_disp/$moduel/$chapter"; 
				if(isset($topic)){
					$return["$topic_disp"] = "/P/Notes/$subject_disp/$moduel/$chapter#to_$topic"; 
				}if(isset($spec_id)){
					$pt_id = $pt_obj['pt_spec_id'];
					$return["$spec_id"] = "/P/Notes/$subject_disp/$moduel/$chapter#point_$pt_id";  
				}
				$return["Questions"] = "#"; 
				//
				return $return;
			}else{
				//
				if($page_name == 'Practice'){
					//
					$subject = $url['q_subject'];
					//
					$subject_disp = strtolower($subject);
					//
					$return['Home'] = '/'; 
					$return["$subject"] = "/P/$subject_disp"; 
					$return["Spec"] = "#";
					//
					return $return;
				}elseif($page_name == 'Paper-Practice'){
					//
					$return['Home'] = '/'; 
					$return["Paper-Practice"] = ""; 
					return $return;
				}
			}
		}
	}
	//
	public function get_affiliate_total_balance(){
		//
		$query = "select affiliate_balance from affiliate_balance where 1";
		$run = $this->run_query($query);
		$balance = 0.00;
		while($result = mysqli_fetch_assoc($run)){
			$balance += $result['affiliate_balance'];
		}
		if($balance > 0.00){
			return $balance;
		}else{
			return 0;
		}
	}
	//
	public function get_reviews(){
		//
		$query = "select * from reviews where 1";
		$run = $this -> run_query($query);
		$count = mysqli_num_rows($run);
		//
		if($count > 0){
			$arr = array();
			while($result = mysqli_fetch_assoc($run)){
				$arr[] = $result;
			}
			return $arr;
		}else{return 0;}
	}
}