<?php
namespace MVC;

class user extends dbh {
	//
	public function __construct(){
		parent::__construct();
		$this->db_table = ' users ';
		//
		if(isset($_SESSION['user_unique_id'])){
			//
			$this->fail_safe = 0 ;
			$this->user = $_SESSION['user_unique_id'];
			$this->q_vis = $_SESSION['user_setting_showAttempted'];
			$this->user_level = '';//$_SESSION['user_setting_level'];
			$this-> file_history = "$this->root/users/$this->user/history_cache.csv";
			$this-> file_point_mark = "$this->root/users/$this->user/point_mark_cache.csv";
			//
			if(isset($_SESSION["u_admin"])){
				$this->user_status = 0;
			}elseif(isset($_SESSION["u_editor"])){
				$this->user_status = 1;
				$this-> dir_contract = "$this->root/users/$this->user/contract";
			}else{
				if(isset($_SESSION["user_membership"])){
					$this->user_status = 2.5;
				}elseif(isset($_SESSION["u_id"])){
					$this->user_status = 2;
				}
			}
			//left to figure out how to give write perm
			//left to figure out how to allow write perms to see exam qs
			$_SESSION['write_perm'] = null;
			if(isset($_SESSION['write_perm'])){
				$this->user_status = 4;
			}
		}else{
			$this->fail_safe = 1 ;
			$this->user_status = 3;
			$this->user_level = '';
		}
	}
	//Model:
	public function run_query($query){
		return mysqli_query($this->conn,$query);
	}
	//Model:
	public function mysqli_var_escape($var){
		return mysqli_real_escape_string($this->conn,$var);
	}
	//Model: constructing point variables
	protected function manual_construct($user){
		$this->input_info = array();
		//
		if(isset($user['user_id'])){
			//
			$this -> user_id = $user['user_id'];
			$this->input_info['user_id'] = $this -> user_id;
		}else{$this->user_id = '';}
		//
		if(isset($user['user_first'])){
			//
			$this -> user_first = $user['user_first'];
			$this->input_info['user_first'] = $this -> user_first;
		}else{$this->user_first = '';}
		//
		if(isset($user['user_last'])){
			//
			$this -> user_last = $user['user_last'];
			$this->input_info['user_last'] = $this -> user_last;
		}else{$this->user_last = '';}
		//
		if(isset($user['user_email'])){
			//
			$this -> user_email = $user['user_email'];
			$this->input_info['user_email'] = $this -> user_email;
		}else{$this->user_email = '';}
		//
		if(isset($user['user_uid'])){
			//
			$this -> user_uid = $user['user_uid'];
			$this->input_info['user_uid'] = $this -> user_uid;
		}else{$this->user_uid = '';}
		//
		if(isset($user['user_unique_id'])){
			//
			$this -> user_unique_id = $user['user_unique_id'];
			$this->input_info['user_unique_id'] = $this -> user_unique_id;
		}else{$this->user_unique_id = '';}
		//
		if(isset($user['user_vkey'])){
			//
			$this -> user_vkey = $user['user_vkey'];
			$this->input_info['user_vkey'] = $this -> user_vkey;
		}else{$this->user_vkey = '';}
		//
		if(isset($user['user_verification'])){
			//
			$this -> user_verification = $user['user_verification'];
			$this->input_info['user_verification'] = $this -> user_verification;
		}else{$this->user_verification = '';}
		//
		if(isset($user['user_member_status'])){
			//
			$this -> user_member_status = $user['user_member_status'];
			$this->input_info['user_member_status'] = $this -> user_member_status;
		}else{$this->user_member_status = '';}
		//
		if(isset($user['admins'])){
			//
			$this -> admins = $user['admins'];
			$this->input_info['admins'] = $this -> admins;
		}else{$this->admins = '';}
		
		//
		if(isset($user['editors'])){
			//
			$this -> editors = $user['editors'];
			$this->input_info['editors'] = $this -> editors;
		}else{$this->editors = '';}
		//	
		
		//
		if(isset($user['affiliates'])){
			//
			$this -> affiliates = $user['affiliates'];
			$this->input_info['affiliates'] = $this -> affiliates;
		}else{$this->affiliates = '';}
		//	
		
		//
		if(isset($user['editors_contract_status'])){
			//
			$this -> editors_contract_status = $user['editors_contract_status'];
			$this->input_info['editors_contract_status'] = $this -> editors_contract_status;
		}else{$this->editors_contract_status = '';}
		//
		
		//
		if(isset($user['approved_editor'])){
			//
			$this -> approved_editor = $user['approved_editor'];
			$this->input_info['approved_editor'] = $this -> approved_editor;
		}else{$this->approved_editor = '';}
		//
		
		//
		if(isset($user['editor_signature_time'])){
			//
			$this -> editor_signature_time = $user['editor_signature_time'];
			$this->input_info['editor_signature_time'] = $this -> editor_signature_time;
		}else{$this->editor_signature_time = '';}
		//
		
		//
		if(isset($user['admin_signature_time'])){
			//
			$this -> admin_signature_time = $user['admin_signature_time'];
			$this->input_info['admin_signature_time'] = $this -> admin_signature_time;
		}else{$this->admin_signature_time = '';}
		//
			
		//
		if(isset($user['contract_termination_time'])){
			//
			$this -> contract_termination_time = $user['contract_termination_time'];
			$this->input_info['contract_termination_time'] = $this -> contract_termination_time;
		}else{$this->contract_termination_time = '';}
		//
		
		//
		if(isset($user['EditorPrimary_subject'])){
			//
			$this -> EditorPrimary_subject = $user['EditorPrimary_subject'];
			$this->input_info['EditorPrimary_subject'] = $this -> EditorPrimary_subject;
		}else{$this->EditorPrimary_subject = '';}
		//
		
		//
		if(isset($user['EditorSecondary_subject'])){
			//
			$this -> EditorSecondary_subject = $user['EditorSecondary_subject'];
			$this->input_info['EditorSecondary_subject'] = $this -> EditorSecondary_subject;
		}else{$this->EditorSecondary_subject = '';}
		//
		
		//
		if(isset($user['that_other_id'])){
			//
			$this -> that_other_id = $user['that_other_id'];
			$this->input_info['that_other_id'] = $this -> that_other_id;
		}else{$this->that_other_id = '';}
		//	
		
		//
		if(isset($user['other_id_verification'])){
			//
			$this -> other_id_verification = $user['other_id_verification'];
			$this->input_info['other_id_verification'] = $this -> other_id_verification;
		}else{$this->other_id_verification = '';}
		//
		if(isset($user['bnkak_id_verification'])){
			//
			$this -> bnkak_id_verification = $user['bnkak_id_verification'];
			$this->input_info['bnkak_id_verification'] = $this -> bnkak_id_verification;
		}else{$this->bnkak_id_verification = '';}
		//
		
		//
		if(isset($user['user_setting_showAttempted'])){
			//
			$this -> user_setting_showAttempted = $user['user_setting_showAttempted'];
			$this->input_info['user_setting_showAttempted'] = $this -> user_setting_showAttempted;
		}else{$this->user_setting_showAttempted = '';}
		//
		
		//
		if(isset($user['user_setting_level'])){
			//
			$this -> user_setting_level = '';//$user['user_setting_level'];
			$this->input_info['user_setting_level'] = $this -> user_setting_level;
		}else{$this->user_setting_level = '';}
		//	
		
		
	}
	//Model: 
	protected function manual_construct_unique_id($user_unique_id){
		//
		$q_query = "select * from $this->db_table where user_unique_id = '$user_unique_id' limit 1 ";
		$run = mysqli_query($this->conn,$q_query);
		$result = mysqli_fetch_assoc($run);
		$this->manual_construct($result);
	}
	//
	//Model: 
	protected function manual_construct_uid($u_id){
		//
		$q_query = "select * from $this->db_table where user_uid = '$u_id' limit 1 ";
		$run = mysqli_query($this->conn,$q_query);
		$result = mysqli_fetch_assoc($run);
		$this->manual_construct($result);
	}
	//
	//CONTR: 
	private function deactivate_membership(){
		if(isset($_SESSION['u_id'])){
			//
			$u_id = $_SESSION['u_id'];
			$query = "update $this->db_table set user_member_status = 0,user_member_credits = 0 where user_id = '$u_id'";
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
	
}






















?>