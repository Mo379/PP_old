<?php
namespace MVC;

class securityhandle {
	//preg match for intigers
	protected function prgmatch_text($input){
		if (!preg_match("/^[a-zA-Z-_ ? 0-9 $]+$/i",$input)){
			return 0;
		}else{
			return 1;
		}		
	
	}//preg match for intigers
	//
	protected function prgmatch_int($input){
		if (!preg_match("/^[0-9]*$/",$input)){
			return 0;
		}else{
			return 1;
		}		
	
	}
	//preg match for intigers
	protected function prgmatch_task_payment_amount($input){
		if (!preg_match('/^\d+(\.\d{2})?$/',$input)){
			return 0;
		}else{
			if($input > '20'){
				return 0;
			}else{
				return 1;
			}
			
		}		
	}
	//preg match for alpabet and white space.
	protected function prgmatch_name($input){
		if (!preg_match("/^[a-zA-Z-_ ]+$/i",$input)){
			return 0;
		}else{
			return 1;
		}
		
	}//preg match for alpabet and white space.
	//
	protected function prgmatch_pointer($input){
		if (!preg_match("/^[a-zA-Z-_ 0-9 .]+$/i",$input)){
			return 0;
		}else{
			return 1;
		}
		
	}

	//pregmatch for spec point 1.1.1 , 1.2.3 etc
	protected function prgmatch_spec_point($input){
		if (!preg_match("/^[(0-999 )(. )(0-999 )(. )(0-999 ) ]*$/",$input)){
			return 0;
		}else{
			return 1;
		}
		
	}
	//pregmatch for unique ID numers and letters 
	protected function prgmatch_unique($input){
		if (!preg_match("/^[(a-zA-z0-9999999999.)]{10,10}$/",$input)){
			return 0;
		}else{
			return 1;
		}
		
	}
	//pregmatch for unique ID numers and letters 
	protected function prgmatch_pwd($input){
		if (!preg_match("/^[(a-zA-z0-99999.)]{0,99}$/",$input)){
			return 0;
		}else{
			return 1;
		}
	}
	//pregmatch for emails 
	protected function prgmatch_email($input){
		if (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i",$input)){
			return 0;
		}else{
			return 1;
		}
	}
	////pregmatch for png file base 64
	protected function prgmatch_png($input){
		if (!substr( $input, 0, 22 ) === 'data:image/png;base64,'){
			return 0;
		}else{
			return 1;
		}
	}	
	////pregmatch for by name 
	protected function prgmatch_png_file($input){
		if(isset($input['type'])){
			if ($input['type'] == 'image/png'){
				return 1;
			}else{
				return 0;
			}
		}else{
			return 0;
		}
		
	}
	//
	//****************
	public function check_site_variable($name,$value){
		//***** any unprocessed variable has $status = 1 ; this way u can look for them
		//
		if($name == 'editor_unique_id'){
			$status = $this->prgmatch_unique($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//
		
		//
		if($name == 'admin_review'){
			$status = $this->prgmatch_unique($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//
		
		//
		if($name == 'user_unique_id'){
			$status = $this->prgmatch_unique($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//
		
		//
		if($name == 'p_unique_id'){
			$status = $this->prgmatch_unique($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//
		
		//
		if($name == 'email'){
			$status = $this->prgmatch_email($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'email1'){
			$status = $this->prgmatch_email($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'email2'){
			$status = $this->prgmatch_email($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'pwd'){
			$status = $this->prgmatch_pwd($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'pwd1'){
			$status = $this->prgmatch_pwd($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'pwd2'){
			$status = $this->prgmatch_pwd($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'file1'){
			$status = $this->prgmatch_png_file($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'username'){
			$status = $this->prgmatch_pointer($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'firstname'){
			$status = $this->prgmatch_name($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'lastname'){
			$status = $this->prgmatch_name($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'pt_subject'){
			$status = $this->prgmatch_pointer($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//
		
		//
		if($name == 'q_subject'){
			$status = $this->prgmatch_pointer($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//
		
		//
		if($name == 'q_moduel'){
			$status = $this->prgmatch_pointer($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//
		
		//
		if($name == 'q_origin'){
			$status = $this->prgmatch_pointer($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//
		
		//
		if($name == 'q_type'){
			$status = $this->prgmatch_name($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//
		
		//
		if($name == 'q_difficulty'){
			$status = $this->prgmatch_pointer($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//
		
		//
		if($name == 'pt_moduel'){
			$status = $this->prgmatch_pointer($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//
		
		//
		if($name == 'pt_chapter'){
			$status = $this->prgmatch_pointer($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//
		
		//
		if($name == 'q_chapter'){
			$status = $this->prgmatch_pointer($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//
		
		//
		if($name == 'pt_information'){
			$status = 1 ; //$this->prgmatch_pointer($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'pt_topic'){
			$status = $this->prgmatch_pointer($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'q_topic'){
			$status = $this->prgmatch_pointer($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'q_point'){
			$status = $this->prgmatch_pointer($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'primary_subject'){
			$status = $this->prgmatch_pointer($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'secondary_subject'){
			$status = $this->prgmatch_pointer($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'subject'){
			$status = $this->prgmatch_pointer($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'moduel'){
			$status = $this->prgmatch_pointer($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'chapter'){
			$status = $this->prgmatch_pointer($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'topic'){
			$status = $this->prgmatch_pointer($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'user_unique'){
			$status = $this->prgmatch_unique($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'pourl'){
			$status = $this->prgmatch_unique($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'pt_unique_id'){
			$status = $this->prgmatch_unique($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'q_unique_id'){
			$status = $this->prgmatch_unique($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'rm_point_unique_id'){
			$status = $this->prgmatch_unique($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'pt_id'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'pt_unique'){
			$status = $this->prgmatch_unique($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'unique'){
			$status = $this->prgmatch_unique($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'point_unique'){
			$status = $this->prgmatch_unique($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'question_unique'){
			$status = $this->prgmatch_unique($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'unique_id'){
			$status = $this->prgmatch_unique($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'user_list'){
			$status = $this->prgmatch_unique($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'point_unique_id'){
			$status = $this->prgmatch_unique($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'user_logoff'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//
		
		//
		if($name == 'question_page_redirect'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'q_filter_type'){
			$status = $this->prgmatch_name($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'filter'){
			$status = $this->prgmatch_name($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'update_pt_raw_information'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'update_question_info'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'update_question_info_editor'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'user_recover_password'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'vkey'){
			$status = $this->prgmatch_pointer($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'verification_key'){
			$status = $this->prgmatch_pointer($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'apply_new_pwd'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'reset_userPassword'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'editor_contract_application_approval'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'editor_contract_application'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'editor_cancelself_contract'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'editor_suspension'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'admin_updates'){
			$status = $this->prgmatch_png($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'editor_updates'){
			$status = $this->prgmatch_png($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'signature_confirmation'){
			$status = $this->prgmatch_png($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'verify_newuser'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'user_newsignup'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'specpoint_Dbprocessing'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'question_DBprocessing'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'paper_DBprocessing'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'hireuser_toeditor'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'assigneditor'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'alert_editors_for_work'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'assign_editor_question'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'editor_claimtask'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'editor_claimtask_question'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'editor_droptask'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'editor_droptask_question'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'unlist_editor_task'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'unlist_editor_task_question'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'letEditor_work'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'letEditor_work_question'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'editor_submit_work'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'submit_work_question'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'admin_review_redirect'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'admin_review_redirect_question'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'admin_accept_work'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//
		
		//
		if($name == 'admin_accept_work_question'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//
		
		//
		if($name == 'admin_reject_work'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'reject_work_question'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'setting_q_show'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'delete_file_unique'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'delete_file_unique_qs'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'delete_marking'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'delete_history_file'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//
		//
		if($name == 'delete_mylist_items'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'delete_cache_html'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'delete_moduel_specpage'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'delete_topic_specpage'){
			$status = $this->prgmatch_unique($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'delete_chapter_specpage'){
			$status = $this->prgmatch_unique($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'delete_question'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//
		
		//
		if($name == 'delete_cache'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'delete_question_editor'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'remove_point'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'add_newSpec_moduel'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'add_newSpec_chapter'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'add_newSpec_topic'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		
		//
		if($name == 'add_new_point'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		
		//
		if($name == 'add_new_question'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'add_new_question_editor'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		
		//
		if($name == 'user_list_specfilter'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'update_moduel_name'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'update_chapter_name'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'update_topic_name'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'update_topicField_selection'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'update_topicField_selection_question'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//
		
		//
		if($name == 'rm_point_unique_id'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'user_login'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'point_id'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'question_id'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'new_input'){
			$status = 1 ; //$this->prgmatch_pointer($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'filename'){
			$status = $this->prgmatch_pointer($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'image_specform_upload'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'image_question_upload'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'image_question_upload_editor'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'uid'){
			$status = $this->prgmatch_pointer($value);
			if($status == 0){
				$status = $this->prgmatch_email($value);
				if($status == 0){
					return 0;
				}else{return 1;}
				
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'current'){
			$status = $this->prgmatch_pointer($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'new'){
			$status = $this->prgmatch_pwd($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'new2'){
			$status = $this->prgmatch_pwd($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'attempted'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//
		
		//
		if($name == 'initiate_membership_payment'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//
		
		//
		if($name == 'membership_option'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//

		//
		if($name == 'finalise_membership_payment'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//
		
		//
		if($name == 'cancel_membership_payment'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//	
		
		//
		if($name == 'apply_discount_code'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//	
		
		//
		if($name == 'discount_code'){
			$status = $this->prgmatch_pointer($value);
			if($status == 0 and !empty($value)){
				return 0;
			}else{
				return 1;
			}
		}
		//	
		
		//
		if($name == 'paypal_payer_id'){
			$status = 1; //$this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//
		
		//
		if($name == 'paypal_token'){
			$status = 1; // $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//
		
		//
		if($name == 'initiate_editor_payout'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//
		
		//
		if($name == 'initiate_editor_payout_bnkak'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//
		//
		if($name == 'initiate_editor_payout_question'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		////
		if($name == 'initiate_editor_payout_question_bnkak'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//	
		
		//
		if($name == 'editor_paypalemail_input'){
			$status = $this->prgmatch_email($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//
		
		//
		if($name == 'editor_bnkak_input'){
			$status = $this->prgmatch_int($value);
			if(!empty($value)){
				if($status == 0){
				return 0;
				}else{
					return 1;
				}
			}else{
				return 0;
			}
			
		}
		//
			
		//
		if($name == 'editor_paypalverification_input'){
			$status = $this->prgmatch_pwd($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//	
		//
		if($name == 'affiliate_payout'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//
			
		//
		if($name == 'task_payment_amount'){
			$status = $this->prgmatch_task_payment_amount($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//
		
		//
		if($name == 'make_mindmap'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//
		
		//
		if($name == 'publish_moduel'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//
		//
		if($name == 'unpublish_moduel'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//
		if($name == 'track_impressions'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//
		
		//
		if($name == 'get_impressions_data'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//	
		
		//
		if($name == 'get_performance_data'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//	
		
		//
		if($name == 'contact_form'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//
		
		//
		if($name == 'name'){
			$status = $this->prgmatch_pointer($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//
		
		//
		if($name == 'con_subject'){
			$status = $this->prgmatch_pointer($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}	
		
		//
		if($name == 'message'){
			$status = 1; // $this->prgmatch_pointer($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//
		if($name == 'mark_question'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//
		if($name == 'mark'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//
		
		//
		if($name == 'page'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//
		
		
		//
		if($name == 'toggle_visibility'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//
			
		//
		if($name == 'toggle_level'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//
		
		//
		if($name == 'locate_unique_question'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//	
		
		
		//
		if($name == 'total_mark'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//	
		
		//
		if($name == 'point_level'){
			$status = $this->prgmatch_name($value);
			if($status == 0 and !empty($value)){
				return 0;
			}else{
				return 1;
			}
		}
		//	
		
		//
		if($name == 'level'){
			$status = $this->prgmatch_name($value);
			if($status == 0 and !empty($value)){
				return 0;
			}else{
				return 1;
			}
		}
		//	
		
		//
		if($name == 'pt_board'){
			$status = $this->prgmatch_name($value);
			if($status == 0 and !empty($value)){
				return 0;
			}else{
				return 1;
			}
		}
		//	
		
		//
		if($name == 'q_level'){
			$status = $this->prgmatch_name($value);
			if($status == 0 and !empty($value)){
				return 0;
			}else{
				return 1;
			}
		}
		//	
		
		
		//
		if($name == 'make_progress_bar'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//	
		
		//
		if($name == 'q_total_marks'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//
			
		//
		if($name == 'paper_maker_get_chapters'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//
			
		//
		if($name == 'get_paper_papermaker'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//
		if($name == 'paper_unique_id'){
			$status = $this->prgmatch_unique($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//
		
		//
		if($name == 'organise_paper_input'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//
		
		//
		if($name == 'paper_detailed_name'){
			$status = $this->prgmatch_pointer($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//
		
		//
		if($name == 'relocate_question_files'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//
		//
		if($name == 'relocate_question_files2'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//
		
		//
		if($name == 'q_level_array'){
			$arr =json_decode(urldecode($value));
			//
			if(!empty($arr)){
				foreach($arr as $v){
					$status = $this->prgmatch_pointer($v);
					if($status == 0){
						return 0;
					}else{
						return 1;
					}
				}
			}else{return 1;}
			
			
		}
		//	
		
		//
		if($name == 'q_subject_array'){
			$arr =json_decode(urldecode($value));
			//
			if(!empty($arr)){
				foreach($arr as $v){
					$status = $this->prgmatch_name($v);
					if($status == 0){
						return 0;
					}else{
						return 1;
					}
				}
			}else{return 1;}
			
			
		}
		//	
		
		//
		if($name == 'q_moduel_array'){
			$arr =json_decode(urldecode($value));
			//
			if(!empty($arr)){
				foreach($arr as $v){
					$status = $this->prgmatch_pointer($v);
					if($status == 0){
						return 0;
					}else{
						return 1;
					}
				}
			}else{return 1;}
			
			
		}
		//	
		
		//
		if($name == 'q_chapter_array'){
			$arr =json_decode(urldecode($value));
			//
			if(!empty($arr)){
				foreach($arr as $v){
					$status = $this->prgmatch_pointer($v);
					if($status == 0){
						return 0;
					}else{
						return 1;
					}
				}
			}else{return 1;}
			
			
		}
		//	
		
		//
		if($name == 'q_type_array'){
			$arr =json_decode(urldecode($value));
			//
			if(!empty($arr)){
				foreach($arr as $v){
					$status = $this->prgmatch_name($v);
					if($status == 0){
						return 0;
					}else{
						return 1;
					}
				}
			}else{return 1;}
			
			
		}
		//	
		
		//
		if($name == 'q_difficulty_array'){
			$arr =json_decode(urldecode($value));
			//
			if(!empty($arr)){
				foreach($arr as $v){
					$status = $this->prgmatch_int($v);
					if($status == 0){
						return 0;
					}else{
						return 1;
					}
				}
			}else{return 1;}
			
			
		}
		//	
		
		//
		if($name == 'q_is_exam_array'){
			$arr =json_decode(urldecode($value));
			//
			if(!empty($arr)){
				foreach($arr as $v){
					$status = $this->prgmatch_int($v);
					if($status == 0){
						return 0;
					}else{
						return 1;
					}
				}
			}else{return 1;}
			
			
		}
		
		//	
		if($name == 'create_flashcard'){
			$status = $this->prgmatch_unique($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//
		
		//	
		if($name == 'flash_question'){
			$status = 1;//$this->prgmatch_text($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//
		
		//	
		if($name == 'flash_answer'){
			$status = 1;//$this->prgmatch_text($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//
		
		//	
		if($name == 'delete_flashcard'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//
		
		//	
		if($name == 'mark_flashcard'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//
		
		//	
		if($name == 'flashcard_id'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//
		//	
		if($name == 'reset_flashcards_progress'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//
		if($name == 'create_review'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//
		if($name == 'reviewer_name'){
			$status = $this->prgmatch_name($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		
		//
		if($name == 'reviewer_rating'){
			$status = $this->prgmatch_int($value);
			if($status == 0){
				return 0;
			}else{
				if($value < 1 or $value >5){
					return 0;
				}else{
					return 1;
				}
			}
		}
		//
		if($name == 'reviewer_review'){
			$status = 1; //$this->prgmatch_text($value);
			if($status == 0){
				return 0;
			}else{
				return 1;
			}
		}
		//
		return 0;
	}
	//
	public function check_unique($input){
		return $this -> prgmatch_unique($input);
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}

?>