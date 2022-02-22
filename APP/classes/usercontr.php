<?php
namespace MVC;

//
class usercontr extends user{
	//
	//contr:
	public function make_obj_unique_id($user_unique){
		//
		$this->manual_construct_unique_id($user_unique);
		return $this->input_info;
	}
	//Contr:
	public function password_recovery($email,$utility,$php_mailer){
		//
		if(filter_var($email, FILTER_VALIDATE_EMAIL)){
			$query = "select user_vkey from users where user_email = '$email' limit 1";
			//
			$run = $this->run_query($query); 
			$result = mysqli_num_rows($run);
			$assoc = mysqli_fetch_assoc($run);
			$key = $assoc['user_vkey'];
			if ($result > 0) {
				$message = "<a href='https://practicepractice.net/P/hub?vkey=$key&apply_new_pwd_verified=1' > Recover password here </a>";
				$subject = 'PracticePractice: Password recovery';
				//
				$mail = $utility->send_mail($php_mailer,$email,$subject,$message);
				//
				if($mail ==1){
					return array(
						'status' => 1,
						'msg' => 'A recovery email has been sent to your email.'
					);
				}else{
					return array(
						'status' => 0,
						'msg' => 'something went wrong, cannot send a recovery email.'
					);	
				}
			}else{
				return array(
					'status' => 0,
					'msg' => "user doesn't exist."
				);
			}
		}else{
			return array(
				'status' => 0,
				'msg' => 'invalid email.'
			);
		}
	}
	//Contr
	public function update_to_new_pwd($vkey,$pwd1,$pwd2){
		//
		if($pwd1 == $pwd2){
			$user_vkey = $this->mysqli_var_escape($vkey);
			$pwd1 = $this->mysqli_var_escape($pwd1);
			$hashedpwds = password_hash($pwd1, PASSWORD_DEFAULT);
			$query = "update users set user_pwd = '$hashedpwds' where user_vkey='$user_vkey'";
			$run = $this->run_query($query);
			if($run){
				return array(
					'status' => 1,
					'msg' => 'success: your password has been recovered, write this one down :)'
				);
			}else{
				return array(
					'status' => 0,
					'msg' => 'something went wrong,'
				);
			}
		}else{
			return array(
					'status' => 0,
					'msg' => 'Non-matching passwords'
				);
		}
	
	}
	//Contr:
	public function new_user_signup($uid,$first,$last,$email,$re_email,$pwd,$re_pwd,$u_vkey,$utility,$php_mailer){
		//
		$unique_id = $utility-> tag_generator();
		$first = $this->mysqli_var_escape($first);
		$last = $this->mysqli_var_escape($last);
		$email =$this->mysqli_var_escape($email);
		$re_email = $this->mysqli_var_escape($re_email);
		$uid = $this->mysqli_var_escape($uid);
		$pwd = $this->mysqli_var_escape($pwd);
		$re_pwd = $this->mysqli_var_escape($re_pwd);
		$u_vkey = md5(time().$first);
		//
		if (empty($first) or empty($last) or empty($email) or empty($uid) or empty($pwd)){
			return array(
				'status' => 0,
				'msg' => "Empty fields."
			);
		}else{
			//checking if input is valid for first and last name
			if(!preg_match("/^[a-zA-Z]*$/", $first) or !preg_match("/^[a-zA-Z]*$/", $last)){
				return array(
				'status' => 0,
				'msg' => 'Invalid username.'
			);
			}else{
				//check if email is valid
				if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
					return array(
						'status' => 0,
						'msg' => 'Invalid email.'
					);
				}else{//checking to see if email is same as re_email
					if ($email != $re_email){
						return array(
							'status' => 0,
							'msg' => 'Unmatching Emails.'
						);
					}else{//checking to see if pwd = re_pwd
						if($pwd!=$re_pwd){
							return array(
								'status' => 0,
								'msg' => 'Unmatching passwords.'
							);
						}else{
							// getting unique user names
							$sql1 = "SELECT user_id FROM users WHERE user_uid = '$uid'";
							$result1 = $this->run_query($sql1);
							$resultcheck1 = mysqli_num_rows($result1);
							//
							$sql2 = "SELECT user_id FROM users WHERE user_email = '$email'";
							$result2 = $this->run_query($sql2);
							$resultcheck2 = mysqli_num_rows($result2);
							//
							if ($resultcheck1 > 0) {
								return array(
									'status' => 0,
									'msg' => 'Username taken, cannot be duplicated'
								);
							}elseif($resultcheck2 > 0){
								return array(
									'status' => 0,
									'msg' => 'Email is already used.'
								);
							}else{
								//hasing the password
								$hashedpwds = password_hash($pwd, PASSWORD_DEFAULT);
								//insert the new_user into the database
								$two_weeks= time() + (60*60*24*14); 
								$sql3 = "INSERT INTO users (user_first, user_last, 
										user_email, user_uid, user_pwd,user_unique_id,user_vkey,user_member_status,user_member_credits) VALUES('$first',
										'$last', '$email', '$uid', '$hashedpwds','$unique_id','$u_vkey','1',$two_weeks);";
								//send verification email
								if(!is_dir("$this->root/users/$unique_id")){
									mkdir("$this->root/users/$unique_id" , 0777, true) or die('fail');
									if(is_dir("$this->root/users/$unique_id")){
										//input new user
										$run_final = $this->run_query($sql3);
									}else{
										return array(
											'status' => 0,
											'msg' => 'Something is wrong, cannot create a new user.'
										);
									}
								}else{
									return array(
										'status' => 0,
										'msg' => 'please try again.'
									);
								}
								//
								if($run_final==true){
									$message = "<a href='https://practicepractice.net/P/hub?u_vkey=$u_vkey' > Register Here. </a>";
									$subject = 'PracticePractice: Email Verification';
									$mail = $utility->send_mail($php_mailer,$email,$subject,$message);
								}else{
									return array(
									'status' => 0,
									'msg' => 'Cannot create account.'
								);
								}
								if($mail == 1){
									return array(
										'status' => 1,
										'msg' => "Success, a confirmation email has been sent, you have to confirm before you are able sign-in, be sure to check your junk mail."
									);
								}else{
									return array(
										'status' => 0,
										'msg' => "couldn't send the verification email, please contact us."
									);
								}
							}
						}
					}	
				}
			}
		}
	}
	//Contr: 
	public function usert_verification($key){
		//
		$query = "update users set user_verfication = '1' where user_vkey = '$key'";
		//
		$run = $this->run_query($query);
		//
		if(mysqli_affected_rows($this->conn) > 0){
			return 1;
		}else{
			return 0;
		}
	}
	//Contr: login function
	public function user_login($uid,$pwd){
		//
		$uid = mysqli_real_escape_string($this->conn, $uid);
		$pwd = mysqli_real_escape_string($this->conn, $pwd);
		//
		$return = array();
		//error handlers
		//checking if inputs are empty
		if (empty($uid) or empty($pwd)){
			$return = array (
				'status' => 0,
				'msg' => 'empty input.'
			);
			return $return;
			die;
		}else{
			$sql = "SELECT * FROM users WHERE user_uid = '$uid' or user_email = '$uid'";
			$result = $this->run_query($sql);
			$resultcheck = mysqli_num_rows($result);
			if ($resultcheck < 1 ){
				$return = array (
					'status' => 0,
					'msg' => 'Incorrect input.'
				);
				return $return;
				die;
			}else {
				//
				if ($row = mysqli_fetch_assoc($result)){
					//de-hashing password
					$hashedpwdcheck = password_verify($pwd, $row["user_pwd"]);
					if ($hashedpwdcheck == false){
						$return = array (
							'status' => 0,
							'msg' => 'Password error'
						);
						return $return;
						die;
					}elseif($hashedpwdcheck == true) {
						//login the user
						//check if the user is verified or not :)
						$v_state = $row["user_verfication"];
						if($v_state == 1){
							$_SESSION["u_id"] = $row["user_id"];
							//setting the type of user
							if($row["admins"] == 1){
								$_SESSION["u_admin"] = $row["admins"];
							}elseif($row["admins"] == 0 and $row["editors"] == 1){
								$_SESSION["u_editor"] = $row["editors"];
							}
							//
							$_SESSION["u_first"] = $row["user_first"];
							$_SESSION["u_last"] = $row["user_last"];
							$_SESSION["u_email"] = $row["user_email"];
							$_SESSION["u_uid"] = $row["user_uid"];
							$_SESSION["user_unique_id"] = $row["user_unique_id"];
							$_SESSION["user_setting_showAttempted"] = $row["user_setting_showAttempted"];
							$_SESSION["user_setting_level"] = $row["user_setting_level"];
							$_SESSION['user_member_credits'] = $row["user_member_credits"];
							if(time() < $row["user_member_credits"] ){
								$_SESSION["user_membership"] = $row["user_member_status"];
							}else{
								$_SESSION["user_membership"] = 0;
								$this->deactivate_membership();
							}
							
							//
							if(isset($_SESSION["u_id"])){
								$return = array (
									'status' => 1,
									'msg' => 'success'
								);
								return $return;
								die;
							}else{
								$return = array (
									'status' => 0,
									'msg' => 'Something went wrong'
								);
								return $return;
								die;
							}
							
						}else{
							$return = array (
								'status' => 0,
								'msg' => 'This account is not verified.'
							);
							return $return;
							die;
						}
					}
				}
			}
		}
	}
	//Contr: logout function 
	public function user_logoff(){
		//
		session_unset();
		session_destroy();
		return 1;
	}
	//Contr:
	public function reset_userPassword($current,$new,$new2){
		//
		if(!empty($current) and !empty($new) and !empty($new2)){
			//
			if($new == $new2){
				//
				if(isset($_SESSION['u_id'])){
					//
					$u_unique_id = $_SESSION['user_unique_id'];
					$sql = "SELECT * FROM users WHERE user_unique_id = '$u_unique_id' limit 1";
					$run = $this->run_query($sql);
					$val = mysqli_fetch_assoc($run);
					$resultcheck = mysqli_num_rows($run);
					$pwd_current2 = $val['user_pwd'];
					$hased_result = password_verify($current,$pwd_current2);
					//
					if($resultcheck > 0 ){
						//
						if($hased_result == true){
							//
							if($current !== $new){
								//
								$new = password_hash($new, PASSWORD_DEFAULT);
								$query = "update users set user_pwd = '$new' where user_unique_id =  '$u_unique_id'";
								$run2 =  mysqli_query($this->conn, $query);
								return 1 ;//$user->general_alert_eval('alert_popup','Password successfully updated.');
								exit;
							
							}else{
								//
								return 0 ;// $user->general_alert_eval('alert_warning','Old and new passwords cannot be the same');
								exit;
							}
						}else{
							//
							return 0 ;// $user->general_alert_eval('alert_warning','Incorrect current password, please try again.');
							exit;
						}
					}else{
						//
						return 0 ;// $user->general_alert_eval('alert_warning','Data-base error, please contact us by email.');
						exit;
					}
				}else{
					//
					return 0 ;// $user->general_alert_eval('alert_warning','User not logged on.');
					exit;
				}
			}else{
				//
				return 0 ;// $user->general_alert_eval('alert_warning','Passwords do not match, please try again.');
				exit;
			}
		}else{
			//
			return 0 ;// $user->general_alert_eval('alert_warning','All fields should be filled first, please try again.');
			exit;
		}
	}
	//Contr:
	public function setting_delete_mylist_file(){
		sleep(1.4);
		if(is_file($this-> file_point_mark)){
			unlink($this-> file_point_mark);
			return 1; 
		}else{
			return 0;
		}
			
		
	}
	//Contr:
	public function get_mylist_as_query_specpage(){
		//open up the user points file and extract the unique ids of the points
		if(!isset($_SESSION['user_unique_id'])){
			die('login required');
		}

		if(is_file($this-> file_point_mark)){
			$csv = fopen($this-> file_point_mark,"a+") or die('Unable to access file !');
			$load_arr = array();
			$this->string = '';
			//extract the unique ids from the csv file
			while (($data = fgetcsv($csv)) !== FALSE) {
				$load_arr[] = "'$data[0]'";
				$this->string .= "  '$data[0]'  ,";
			}
			$this->string = substr($this->string,0,-2);
			$trig = 1;
			$st = implode(',',$load_arr);
			$input = "and pt_unique_id in ($st)";
			if(empty($load_arr)){
				die('Nothing in list: please add a study point');
			}
			return $input;
		}else{
			die('No items listed.');
		}
	}
	//Contr:
	public function hireuser_toeditor($user_unique,$user_email,$user_primary_subject,$user_secondary_subject,$utility,$php_mailer){
		//
		if(!empty($user_unique) and !empty($user_email) and !empty($user_primary_subject) and !empty($user_secondary_subject) ){
			//
			$check_query = "select * from users where user_unique_id = '$user_unique' and user_email = '$user_email' limit 1";
			$run = $this->run_query($check_query);
			//
			$count = mysqli_num_rows($run);
			$assoc = mysqli_fetch_assoc($run);
			//
			if($count > 0){
				//
				$verification = $assoc['user_verfication'];
				$admin_status = $assoc['admins'];
				$editor_status = $assoc['editors'];
				//
				if($verification == 1 and $admin_status == 0  and ($editor_status == 0 or $editor_status ==1 ) ){
					//in the case that a user account is being hired, upgrade the status
					$update_status = "update users set editors = 1, EditorPrimary_subject = '$user_primary_subject',EditorSecondary_subject = '$user_secondary_subject'
					where user_unique_id='$user_unique' and user_email = '$user_email' limit 1";
					if($run_2 = $this->run_query($update_status)){
						//
						if($editor_status == 0){
							//
							$message = "You can provide the required documents and sign the contract from the profile page. <br><a href='https://practicepractice.net/' > Sign in  </a>";
							$subject = 'PracticePractice: You have been hired ';
							$mail = $utility->send_mail($php_mailer,$user_email,$subject,$message);
							//
							if($mail == 1){
								return 1;
							}else{
								return 0;
							}
						}else{
							//
							$message = "Your hiring status has been updated <br><a href='https://practicepractice.net/' > Sign in  </a>";
							$subject = 'PracticePractice: Update ';
							$mail = $utility->send_mail($php_mailer,$user_email,$subject,$message);
							//
							if($mail == 1){
								return 1;
							}else{
								return 0;
							}
						}
						
					}else{
						return 0;
					}
				}else{
					return 0;
				}
			}else{
				return 0;
			}
		}else{
			return 0;
		}
	}	//Contr:
	//Contr:
	public function editor_suspension($editor_unique_id,$utility,$php_mailer){
		//
		$this->manual_construct_unique_id($editor_unique_id);
		//
		if(!empty($editor_unique_id) and !empty($this->user_email)){
			//
			$check_query = "select * from users where user_unique_id = '$editor_unique_id' and user_email = '$this->user_email' limit 1";
			$run = $this->run_query($check_query);
			//
			$count = mysqli_num_rows($run);
			$assoc = mysqli_fetch_assoc($run);
			//
			if($count > 0){
				//
				$verification = $assoc['user_verfication'];
				$admin_status = $assoc['admins'];
				$editor_status = $assoc['editors'];
				//
				if($verification == 1 and $admin_status == 0  and  $editor_status ==1  ){
					//in the case that a user account is being hired, upgrade the status
					$update_status = "update users set EditorPrimary_subject = 'suspended',EditorSecondary_subject = 'suspended'
					where user_unique_id='$editor_unique_id' and user_email = '$this->user_email' limit 1";
					if($run_2 = $this->run_query($update_status)){
						//
						$message = "Your hiring status has been updated <br><a href='https://practicepractice.net/' > Sign in  </a>";
						$subject = 'PracticePractice: Update ';
						$mail = $utility->send_mail($php_mailer,$this->user_email,$subject,$message);
						//
						if($mail == 1){
							return 1;
						}else{
							return 0;
						}
						
					}else{
						return 0;
					}
				}else{
					return 0;
				}
			}else{
				return 0;
			}
		}else{
			return 0;
		}
	}
	//Contr: 
	public function process_editor_contract($editor_unique_id,$signature_confirmation,$utility,$php_mailer){
		$u_id = $_SESSION['u_id'];
		
		//
		if($signature_confirmation == 1){
			//check if the editor has already signed
			$query = "select editors_contract_status from users where user_id = '$u_id' and user_unique_id = '$editor_unique_id' limit 1";
			$run = $this->run_query($query);
			$assoc = mysqli_fetch_assoc($run);
			//
			if($assoc['editors_contract_status'] == 1){
				return array(
					'status' => 0,
					'msg' => 'Contract already signed.'
				);
			}
			//
			$this->manual_construct_unique_id($editor_unique_id);
			//
			if(!is_dir($this->dir_contract)){
				//
				mkdir($this->dir_contract);
				if(!is_dir($this->dir_contract)){
					return array(
						'status' => 0,
						'msg' => 'Cannot create contract.'
					);
				}
			}
			
			//upload 
			
			//update database to show the editor has signed the contract
			$time = time();
			$update_signature_section_query = "update users set editors_contract_status = 1, editor_signature_time ='$time'  where user_unique_id = '$editor_unique_id' and user_id = '$u_id' limit 1";
			//
			if($this->run_query($update_signature_section_query)){
				$message = "Your account is now pending approval, you will be notified when a decision has been made.";
				$subject = 'PracticePractice: Pending approval ';
				$mail = $utility->send_mail($php_mailer,$this->user_email,$subject,$message);
				if($mail == 1){
					return array(
						'status' => 1,
						'msg' => "Success"
					);
				}else{
					return array(
						'status' => 0,
						'msg' => "Something went wrong"
					);
				}

			}else{
				return array(
					'status' => 0,
					'msg' => 'Something went wrong'
				);
			}
			//
			fclose($myfile);
			
		}else{
			//
			return array(
				'status' => 0,
				'msg' => 'Signature confirmation is required.'
			);
		}
	}
	//Contr:
	public function editor_contract_application_approval($editor_unique_id,$signature_confirmation,$utility,$php_mailer){
		//
		$user_directory = "$this->root/users/$editor_unique_id/contract";
		//
		if(is_dir($user_directory)){
			if($signature_confirmation == 1){
				//check if the editor has already signed
				$query = "select approved_editor from users where user_unique_id = '$editor_unique_id' limit 1";
				$run = $this->run_query($query);
				$assoc = mysqli_fetch_assoc($run);
				//
				if($assoc['approved_editor'] == 1){
					return array(
						'status' => 0,
						'msg' => 'Contract already signed.'
					);
				}
				
				//
				if(is_file("$user_directory/CompanySignature.png")){
					unlink("$user_directory/CompanySignature.png");
				}
				//
				//update database to show the editor has signed the contract
				$time = time();
				$update_approve_editor_query = "update users set approved_editor = 1,admin_signature_time ='$time',contract_termination_time=0  where user_unique_id = '$editor_unique_id' limit 1";
				//
				if($this->run_query($update_approve_editor_query)){
					//
					$this->manual_construct_unique_id($editor_unique_id);
					//
					$message = "Your account is now approved, you can begin writing and earning as you do so.<br><a href='https://practicepractice.net/' > Log in </a>";
					$subject = 'PracticePractice: Approval ';
					$mail = $utility->send_mail($php_mailer,$this->user_email,$subject,$message);
					if($mail == 1){
						//
						$id = "$this->root/users/$editor_unique_id/contract/EditorID.png";
						$qualification = "$this->root/users/$editor_unique_id/contract/EditorQualification.png";
						//
						if(is_file($id)){
							unlink($id);
						}
						if(is_file($qualification)){
							unlink($qualification);
						}
						//
						return array(
							'status' => 1,
							'msg' => "https://practicepractice.net/P/contract?editor_unique_id=$editor_unique_id&editor_vkey=$this->user_vkey&update=1"
						);


					}else{
						return array(
							'status' => 0,
							'msg' => "Something went wrong"
						);
					}
				}else{
					return array(
						'status' => 0,
						'msg' => 'Something went wrong'
					);
				}
				//
				fclose($myfile);
				
			}else{
				return array(
					'status' => 0,
					'msg' => 'signature confirmation is required'
				); 
			}
		}else{
			return array(
				'status' => 0,
				'msg' => 'cannot find editor'
			);
		}
	}
	//Contr
	public function editor_cancelself_contract($editor_unique_id,$utility,$php_mailer){
		//
		$this->manual_construct_unique_id($editor_unique_id);
		//
		if($this->editors ==1 and $this->editors_contract_status == 1 and $this->approved_editor ==1 and $this->editor_signature_time > 0 and $this->admin_signature_time > 0){
			//
			$time = time();
			$query = "update users set contract_termination_time = '$time',editors = 0,editors_contract_status = 0,approved_editor =0 where user_unique_id = '$editor_unique_id' and user_id = '$this->user_id'";
			//
			if($this->run_query($query)){
				//
				$message = "Your contract with us has been terminated";
				$subject = 'PracticePractice: Contract termination ';
				$mail = $utility->send_mail($php_mailer,$this->user_email,$subject,$message);
				//
				if($mail == 1){
					return array(
						'status' => 1,
						'msg' => "https://practicepractice.net/P/contract?editor_unique_id=$this->user_unique_id&editor_vkey=$this->user_vkey&update=1"
					);
				}else{
					return array(
						'status' => 0,
						'msg' => "Something went wrong"
					);
				}
			}
		}else{
			return array(
				'status' => 0,
				'msg' => 'Contract information missing'
			);
		}
		
		
	}
	//Contr
	public function editor_claimtask($editor_unique,$subject,$moduel,$chapter){
		//check if the user is valid
		$query = "select * from users where user_unique_id = '$editor_unique' limit 1";
		$run = $this->run_query($query);
		//
		$count = mysqli_num_rows($run);
		//
		if($count ==1){
			//check if the task is listed and available
			$query_2 = "select * from active_tasks where pt_subject = '$subject' and pt_moduel = '$moduel' and pt_chapter ='$chapter' and user_unique_id is NULL";
			$run_2 = $this->run_query($query_2);
			//
			$count_2 = mysqli_num_rows($run_2);
			//
			if($count_2 == 1){
				//check if the editor has other active tasks
				//$count_3 = $this->editor_total_claimed_tasks($editor_unique);
				//
				//if($count_3 == 0 or $count_3 == 1){
					//
					$query_4 = "update active_tasks set user_unique_id = '$editor_unique' where pt_subject = '$subject' and pt_moduel = '$moduel' and pt_chapter ='$chapter'";
					//
					if($this->run_query($query_4)){
						return 1;
					}else{
						return 0;
					}
				//}else{
				//	return 0;
				//}
					
			}else{
				return 0;
			}
		}else{
			return 0;
		}
	}
	//COntr
	public function editor_claimtask_question($editor_unique,$subject,$moduel,$chapter,$topic,$q_point){
		//
		if($topic == '' and $q_point == ''){
			//
			$query_2 = "select * from active_tasks_questions where q_subject = '$subject' and q_moduel = '$moduel' and q_chapter ='$chapter' and q_topic='' and q_point='' and user_unique_id is NULL";
			//
			$query_4 = "update active_tasks_questions set user_unique_id = '$editor_unique' where q_subject = '$subject' and q_moduel = '$moduel' and q_chapter ='$chapter' and q_topic='' and q_point='' limit 1";
		}elseif($q_point== ''){
			//
			$query_2 = "select * from active_tasks_questions where q_subject = '$subject' and q_moduel = '$moduel' and q_chapter ='$chapter' and q_topic='$topic' and q_point=''  and user_unique_id is NULL";
			//
			$query_4 = "update active_tasks_questions set user_unique_id = '$editor_unique' where q_subject = '$subject' and q_moduel = '$moduel' and q_chapter ='$chapter' and q_topic='$topic' and q_point='' limit 1";		
		}else{
			//
			$query_2 = "select * from active_tasks_questions where q_subject = '$subject' and q_moduel = '$moduel' and q_chapter ='$chapter' and q_topic='$topic' and q_point='$q_point' and user_unique_id is NULL";
			//
			$query_4 = "update active_tasks_questions set user_unique_id = '$editor_unique' where q_subject = '$subject' and q_moduel = '$moduel' and q_chapter ='$chapter' and q_topic='$topic' and q_point='$q_point' limit 1";
		}
		//check if the user is valid
		$query = "select * from users where user_unique_id = '$editor_unique' limit 1";
		$run = $this->run_query($query);
		//
		$count = mysqli_num_rows($run);
		//
		if($count ==1){
			//check if the task is listed and available
			$run_2 = $this->run_query($query_2);
			//
			$count_2 = mysqli_num_rows($run_2);
			//
			if($count_2 == 1){
				//check if the editor has other active tasks
				//$count_3 = $this->editor_total_claimed_tasks($editor_unique);
				//
				//if($count_3 == 0 or $count_3 == 1){
					//
					if($this->run_query($query_4)){
						return 1;
					}else{
						return 0;
					}
				//}else{
					//return 0;
				//}
					
			}else{
				return 0;
			}
		}else{
			return 0;
		}
	}
	//CONTR
	private function editor_total_claimed_tasks($editor_unique){
		$query = "select id from active_tasks_questions where user_unique_id = '$editor_unique'";
		$query_1 = "select id from active_tasks where user_unique_id = '$editor_unique'";
		//
		$run = $this->run_query($query);
		//limit the editor to two active tasks at any time
		$count = mysqli_num_rows($run);
		//
		$run_1 = $this->run_query($query_1);
		//limit the editor to two active tasks at any time
		$count_1 = mysqli_num_rows($run_1);
		
		return $count + $count_1;
	}
	//Contr
	public function editor_droptask($editor_unique,$subject,$moduel,$chapter,$pointcontr,$filehandle,$utility){
		//
		$query = "select * from users where user_unique_id = '$editor_unique' limit 1";
		$run = $this->run_query($query);
		//
		$count = mysqli_num_rows($run);
		//
		if($count ==1){
			//
			$query_2 = "select * from active_tasks where pt_subject = '$subject' and pt_moduel = '$moduel' and pt_chapter ='$chapter'";
			$run_2 = $this->run_query($query_2);
			//
			$count_2 = mysqli_num_rows($run_2);
			//
			if($count_2 == 1){
				//
				$query_3 = "update active_tasks set user_unique_id = null where pt_subject = '$subject' and pt_moduel = '$moduel' and pt_chapter ='$chapter'";
				//
				if($this->run_query($query_3)){
					//remove files the user has written
					//the live website content
					$src = $pointcontr-> get_chapter_dir($subject,$moduel,$chapter);
					//the copy of the content
					$drop = "users/$editor_unique/$src";
					//
					$all_drops = "users/$editor_unique/specifications/universal";
					//
					if(!empty($editor_unique) and !empty($drop) and is_dir($this->root."/$drop")){
						//
						$delete = $filehandle -> rrmdir($this->root."/$drop");
						//
						if($delete == 1){
							$pointcontr->db_table = ' points_editors ';
							$inject = $pointcontr->inject_point_editor_private($editor_unique,$this->root."/$all_drops",$filehandle,$utility);
							//
							if($inject == 1){
								return 1;
							}else{
								return 0;
							}
						}else{
							return 0;
						}
						//
					}elseif(!is_dir($this->root."/$drop")){
						$pointcontr->db_table = ' points_editors ';
						$inject = $pointcontr->inject_point_editor_private($editor_unique,$this->root."/$all_drops",$filehandle,$utility);
						//
						if($inject == 1){
							return 1;
						}else{
							return 0;
						}
					}else{
						return 0;
					}
					//
				}else{
					return 0;
				}
			}else{
				return 0;
			}
		}else{
			return 0;
		}
	}
	//
	public function editor_droptask_question($editor_unique,$subject,$moduel,$chapter,$topic,$q_point,$questioncontr,$filehandle,$utility){
		//
		//
		if($topic == '' and $q_point == ''){
			//
			$query_2 = "select * from active_tasks_questions where q_subject = '$subject' and q_moduel = '$moduel' and q_chapter ='$chapter' and q_topic='' and q_point='' ";
			//
			$query_3 = "update active_tasks_questions set user_unique_id = null where q_subject = '$subject' and q_moduel = '$moduel' and q_chapter ='$chapter' and q_topic='' and q_point=''";
		}elseif($q_point== ''){
			//
			$query_2 = "select * from active_tasks_questions where q_subject = '$subject' and q_moduel = '$moduel' and q_chapter ='$chapter' and q_topic='$topic' and q_point='' ";
			//
			$query_3 = "update active_tasks_questions set user_unique_id = null where q_subject = '$subject' and q_moduel = '$moduel' and q_chapter ='$chapter' and q_topic='$topic' and q_point=''";
		}else{
			//
			$query_2 = "select * from active_tasks_questions where q_subject = '$subject' and q_moduel = '$moduel' and q_chapter ='$chapter' and q_topic='$topic' and q_point='$q_point' ";
			//
			$query_3 = "update active_tasks_questions set user_unique_id = null where q_subject = '$subject' and q_moduel = '$moduel' and q_chapter ='$chapter' and q_topic='$topic' and q_point='$q_point'";
		}
		//
		$query = "select * from users where user_unique_id = '$editor_unique' limit 1";
		$run = $this->run_query($query);
		//
		$count = mysqli_num_rows($run);
		//
		if($count ==1){
			//
			$run_2 = $this->run_query($query_2);
			//
			$count_2 = mysqli_num_rows($run_2);
			//
			if($count_2 == 1){
				//
				if($this->run_query($query_3)){
					//remove files the user has written
					//the live website content
					$src = $questioncontr-> get_questions_dir($subject,$moduel,$chapter,$topic,$q_point);
					//the copy of the content
					$drop = "users/$editor_unique/$src";
					//
					$all_drops = "users/$editor_unique/specifications/universal";
					//
					if(!empty($editor_unique) and !empty($drop) and is_dir($this->root."/$drop")){
						//
						$delete = $filehandle -> rrmdir($this->root."/$drop");
						//
						if($delete == 1){
							$questioncontr->db_table = ' questions_editors ';
							$inject = $questioncontr->inject_question_editor_private($editor_unique,$this->root."/$all_drops",$filehandle,$utility);
							//
							if($inject == 1){
								return 1;
							}else{
								return 0;
							}
						}else{
							return 0;
						}
						//
					}elseif(!is_dir($this->root."/$drop")){
						$questioncontr->db_table = ' questions_editors ';
						$inject = $questioncontr->inject_question_editor_private($editor_unique,$this->root."/$all_drops",$filehandle,$utility);
						//
						if($inject == 1){
							return 1;
						}else{
							return 0;
						}
					}else{
						return 0;
					}
					//
				}else{
					return 0;
				}
			}else{
				return 0;
			}
		}else{
			return 0;
		}
	}
	//Contr
	public function LetEditor_work($editor_unique,$subject,$moduel,$chapter,$pointcontr,$utility,$filehandle){
		//the live website content
		$src = $pointcontr-> get_chapter_dir($subject,$moduel,$chapter);
		//the copy of the content
		$drop = "users/$editor_unique/$src";
		//
		$all_drops = "users/$editor_unique/specifications/universal";
		//checking if the editor already has a copy of the content
		if(!is_dir($this->root."/$drop")){
			//the editor doesnt have a copy so lets make one
			if(is_dir($this->root."/$src") and !empty($editor_unique) and !empty($subject) and !empty($moduel) and !empty($chapter)	){
				//
				$copy = $filehandle -> recurse_copy($this->root."/$src",$this->root."/$drop");
				if($copy == 1){
					//
					$pointcontr->db_table = ' points_editors ';
					$inject = $pointcontr->inject_point_editor_private($editor_unique,$this->root."/$all_drops",$filehandle,$utility);
					//
					if($inject == 1){
						return array(
							'status' => 1,
							'msg' => "https://practicepractice.net/P/universal_spec_table?editor_unique_id=$editor_unique&pt_subject=$subject&pt_moduel=$moduel&pt_chapter=$chapter"
						);
					}else{
						return array(
							'status' => 0,
							'msg' => 'something went wrong'
						); 
					}
					
				}else{  
					return array(
						'status' => 0,
						'msg' => 'something went wrong'
					);
				}
			}else{
				//there is a weird error
				return array(
					'status' => 0,
					'msg' => 'Something went wrong, task is not available'
				);
			}
		}else{
			//
			$pointcontr->db_table = ' points_editors ';
			$inject = $pointcontr->inject_point_editor_private($editor_unique,$this->root."/$all_drops",$filehandle,$utility);
			//
			if($inject == 1){
				return array(
					'status' => 1,
					'msg' => "https://practicepractice.net/P/universal_spec_table?editor_unique_id=$editor_unique&pt_subject=$subject&pt_moduel=$moduel&pt_chapter=$chapter"
				);
			}else{
				return array(
					'status' => 0,
					'msg' => 'something went wrong'
				); 
			}
		}
	}
	//Contr
	public function letEditor_work_question($editor_unique,$subject,$moduel,$chapter,$topic,$q_point,$questioncontr,$pointcontr,$utility,$filehandle){
		//
		if($topic == '' and $q_point == ''){
			$filter = 'chapter';
		}elseif($q_point== ''){
			$filter = 'topic';
		}else{
			$filter = 'point';
		}
		//
		$pointer_point = $pointcontr -> get_point_unique_pointer($subject,$moduel,$chapter,$topic,$q_point);
		//the live website content
		$src = $questioncontr-> get_questions_dir($subject,$moduel,$chapter,$topic,$q_point);
		//the copy of the content
		$drop = "users/$editor_unique/$src";
		//
		$all_drops = "users/$editor_unique/specifications/universal";
		//checking if the editor already has a copy of the content
		if(!is_dir($this->root."/$drop")){
			//the editor doesnt have a copy so lets make one
			if(is_dir($this->root."/$src") and !empty($editor_unique) and !empty($subject) and !empty($moduel) and !empty($chapter)	){
				//
				$copy = $filehandle -> recurse_copy($this->root."/$src",$this->root."/$drop");
				if($copy == 1){
					//
					$questioncontr->db_table = ' questions_editors ';
					$inject = $questioncontr->inject_question_editor_private($editor_unique,$this->root."/$all_drops",$filehandle,$utility);
					//
					if($inject == 1){
						return array(
							'status' => 1,
							'msg' => "https://practicepractice.net/P/questions?editor_unique_id=$editor_unique&filter=$filter&pt_unique_id=$pointer_point"
						);
					}else{
						return array(
							'status' => 0,
							'msg' => 'something went wrong'
						); 
					}
					
				}else{  
					return array(
						'status' => 0,
						'msg' => 'something went wrong'
					);
				}
			}else{
				//there is a weird error
				return array(
					'status' => 0,
					'msg' => 'Something went wrong, task is not available'
				);
			}
		}else{
			//
			$questioncontr->db_table = ' questions_editors ';
			$inject = $questioncontr->inject_question_editor_private($editor_unique,$this->root."/$all_drops",$filehandle,$utility);
			//
			if($inject == 1){
				return array(
					'status' => 1,
					'msg' => "https://practicepractice.net/P/questions?editor_unique_id=$editor_unique&filter=$filter&pt_unique_id=$pointer_point"
				);
			}else{
				return array(
					'status' => 0,
					'msg' => 'something went wrong'
				); 
			}
		}
	}
	//Contr
	public function editor_submit_work($editor_unique,$subject,$moduel,$chapter){
		//
		if($editor_unique == $_SESSION['user_unique_id']){
			$query = "select * from active_tasks where pt_subject = '$subject' and pt_moduel = '$moduel' and pt_chapter = '$chapter' and user_unique_id = '$editor_unique' limit 1";
			$run = $this->run_query($query);
			//
			$count = mysqli_num_rows($run);
			//
			if($count > 0){
				//
				$query_2 = "update active_tasks set editing_status = 1 where pt_subject = '$subject' and pt_moduel = '$moduel' and pt_chapter = '$chapter' and user_unique_id = '$editor_unique' limit 1";
				if($this->run_query($query_2)){
					return 1;
				}else{
					return 0;
				}
			}else{
				return 0;
			}
		}else{
			return 0;
		}
	}	
	//Contr
	public function submit_work_question($subject,$moduel,$chapter,$topic,$q_point){
		//
		$editor_unique = $_SESSION['user_unique_id'];
		//
		$query = "select * from active_tasks_questions where q_subject = '$subject' and q_moduel = '$moduel' and q_chapter = '$chapter' and q_topic = '$topic' and q_point = '$q_point'  and user_unique_id = '$editor_unique' limit 1";
		$run = $this->run_query($query);
		//
		$count = mysqli_num_rows($run);
		//
		if($count > 0){
			//
			$query_2 = "update active_tasks_questions set editing_status = 1 where q_subject = '$subject' and q_moduel = '$moduel' and q_chapter = '$chapter' and q_topic = '$topic' and q_point = '$q_point' and user_unique_id = '$editor_unique' limit 1";
			if($this->run_query($query_2)){
				return 1;
			}else{
				return 0;
			}
		}else{
			return 0;
		}
		
	}
	//Contr: this checks if the editor own the active task, if now he gets thrown out of the ting
	public function is_editor_valid($editor_unique,$subject,$moduel,$chapter){
		//
		$query = "select * from active_tasks where pt_subject = '$subject' and pt_moduel = '$moduel' and pt_chapter = '$chapter' and user_unique_id = '$editor_unique' limit 1";
		$run = $this->run_query($query);
		//
		$count = mysqli_num_rows($run);
		//
		if($count > 0){
			$assoc = mysqli_fetch_assoc($run);
			$status = $assoc['editing_status'];
			if($status == 0 or isset($_SESSION['u_admin'])){
				return 1;
			}else{
				return 0;
			}
		}else{
			return 0;
		}
	}
	//
	//Contr: this checks if the editor own the active task, if now he gets thrown out of the ting
	public function is_editor_valid_question($editor_unique,$filter,$q_point){
		//
		if($filter =='point'){
			//
			$query = "select * from active_tasks_questions where q_point = '$q_point' and user_unique_id='$editor_unique'";
		}elseif($filter=='topic'){
			//
			$pre_q = "select pt_subject,pt_moduel,pt_chapter,pt_topic from points where pt_unique_id = '$q_point' limit 1";
			//
			$run = $this->run_query($pre_q);
			//
			while($result = mysqli_fetch_assoc($run)){
				$subject = $result['pt_subject'];
				$moduel = $result['pt_moduel'];
				$chapter = $result['pt_chapter'];
				$topic = $result['pt_topic'];
			}
			//
			$query = "select * from active_tasks_questions where q_subject='$subject' and q_moduel='$moduel' and q_chapter = '$chapter' and q_topic = '$topic' and q_point = '' and user_unique_id='$editor_unique'";
		}else{
			if($filter == 'chapter'){
				//
				$pre_q = "select pt_subject,pt_moduel,pt_chapter from points where pt_unique_id = '$q_point' limit 1";
				//
				$run = $this->run_query($pre_q);
				//
				while($result = mysqli_fetch_assoc($run)){
					$subject = $result['pt_subject'];
					$moduel = $result['pt_moduel'];
					$chapter = $result['pt_chapter'];
				}
				//
				$query = "select * from active_tasks_questions where q_subject='$subject' and q_moduel='$moduel' and q_chapter = '$chapter' and q_topic = '' and q_point = '' and user_unique_id='$editor_unique'";
			}else{die;}
		}
		
		//
		$run_2 = $this->run_query($query);
		$count = mysqli_num_rows($run_2);
		//
		if($count > 0){
			$assoc = mysqli_fetch_assoc($run_2);
			$status = $assoc['editing_status'];
			if($status == 0 or isset($_SESSION['u_admin'])){
				return 1;
			}else{
				return 0;
			}
		}else{
			return 0;
		}
		
	}
	//
	public function is_editor_valid_question_2($editor_unique,$subject,$moduel,$chapter,$topic,$pt_unique_id){
		//check if the editor really owns the task and figure out how to do that for both types of tasks
		$query = "select * from active_tasks_questions where q_subject='$subject' and q_moduel='$moduel' and q_chapter = '$chapter' and q_topic = '$topic' and q_point = '$pt_unique_id' and user_unique_id='$editor_unique' limit 1";
		//
		$run = $this->run_query($query);
		$count = mysqli_num_rows($run);
		if($count > 0){
			return 1;
		}else{
			return 0;
		}
	}
	//Contr: this checks if the editor own the active task, if now he gets thrown out of the ting
	public function is_review_valid($editor_unique,$subject,$moduel,$chapter){
		//
		$query = "select * from active_tasks where pt_subject = '$subject' and pt_moduel = '$moduel' and pt_chapter = '$chapter' and user_unique_id = '$editor_unique' and editing_status = 1 and approval_status = 0 limit 1";
		$run = $this->run_query($query);
		//
		$count = mysqli_num_rows($run);
		//
		if($count > 0){
			$assoc = mysqli_fetch_assoc($run);
			$status = $assoc['approval_status'];
			if($status == 0 and isset($_SESSION['u_admin'])){
				return 1;
			}else{
				return 0;
			}
		}else{
			return 0;
		}
	}
	//Contr: this checks if the editor own the active task, if now he gets thrown out of the ting
	public function is_review_valid_question($editor_unique,$filter,$q_point){
		//
		if($filter =='point'){
			//
			$query = "select * from active_tasks_questions where q_point = '$q_point' and user_unique_id='$editor_unique'";
		}elseif($filter=='topic'){
			//
			$pre_q = "select pt_subject,pt_moduel,pt_chapter,pt_topic from points where pt_unique_id = '$q_point' limit 1";
			//
			$run = $this->run_query($pre_q);
			//
			while($result = mysqli_fetch_assoc($run)){
				$subject = $result['pt_subject'];
				$moduel = $result['pt_moduel'];
				$chapter = $result['pt_chapter'];
				$topic = $result['pt_topic'];
			}
			//
			$query = "select * from active_tasks_questions where q_subject='$subject' and q_moduel='$moduel' and q_chapter = '$chapter' and q_topic = '$topic' and q_point = '' and user_unique_id='$editor_unique' and editing_status = 1 and approval_status = 0 limit 1";
		}else{
			if($filter=='chapter'){
				//
				$pre_q = "select pt_subject,pt_moduel,pt_chapter from points where pt_unique_id = '$q_point' limit 1";
				//
				$run = $this->run_query($pre_q);
				//
				while($result = mysqli_fetch_assoc($run)){
					$subject = $result['pt_subject'];
					$moduel = $result['pt_moduel'];
					$chapter = $result['pt_chapter'];
				}
				//
				$query = "select * from active_tasks_questions where q_subject='$subject' and q_moduel='$moduel' and q_chapter = '$chapter' and q_topic = '' and q_point = '' and user_unique_id='$editor_unique' and editing_status = 1 and approval_status = 0 limit 1";
			}else{die;}
		}
		//
		$run = $this->run_query($query);
		//
		$count = mysqli_num_rows($run);
		//
		if($count > 0){
			$assoc = mysqli_fetch_assoc($run);
			$status = $assoc['approval_status'];
			if($status == 0 and isset($_SESSION['u_admin'])){
				return 1;
			}else{
				return 0;
			}
		}else{
			return 0;
		}
	}
	//Contr:
	public function admin_review_redirect($editor_unique,$subject,$moduel,$chapter){
		//
		$query = "select * from active_tasks where pt_subject = '$subject' and pt_moduel = '$moduel' and pt_chapter = '$chapter' and user_unique_id = '$editor_unique' limit 1";
		$run = $this->run_query($query);
		//
		$count = mysqli_num_rows($run);
		//
		if($count > 0){
			//
			if(isset($_SESSION['u_admin'])){
				return array(
					'status' => 1,
					'msg' => "https://practicepractice.net/P/universal_spec_table?admin_review=$editor_unique&pt_subject=$subject&pt_moduel=$moduel&pt_chapter=$chapter"
				);
			}else{
				return array(
					'status' => 0,
					'msg' => "Something went wrong."
				);
			}
		}else{
			return array(
				'status' => 0,
				'msg' => "Something went wrong."
			);
		}
		
	}
	//Contr:
	public function admin_review_question_redirect($editor_unique,$subject,$moduel,$chapter,$topic,$q_point,$pointcontr){
		//
		if($topic == '' and $q_point == ''){
			$filter = 'chapter';
		}elseif($q_point== ''){
			$filter = 'topic';
		}else{
			$filter = 'point';
		}
		//
		$pointer_point = $pointcontr -> get_point_unique_pointer($subject,$moduel,$chapter,$topic,$q_point);	
		//
		$query = "select * from active_tasks_questions where q_subject = '$subject' and q_moduel = '$moduel' and q_chapter = '$chapter' and q_topic = '$topic' and q_point = '$q_point' and user_unique_id = '$editor_unique' limit 1";
		$run = $this->run_query($query);
		//
		$count = mysqli_num_rows($run);
		//
		if($count > 0){
			//
			if(isset($_SESSION['u_admin'])){
				return array(
					'status' => 1,
					'msg' => "https://practicepractice.net/P/questions?admin_review=$editor_unique&&filter=$filter&pt_unique_id=$pointer_point"
				);
			}else{
				return array(
					'status' => 0,
					'msg' => "Something went wrong."
				);
			}
		}else{
			return array(
				'status' => 0,
				'msg' => "Something went wrong."
			);
		}
		
	}
	//Contr:
	public function admin_accept_work($editor_unique,$subject,$moduel,$chapter,$pointcontr,$filehandle,$utility,$php_mailer){
		//
		$query = "select * from active_tasks where pt_subject = '$subject' and pt_moduel = '$moduel' and pt_chapter = '$chapter' and user_unique_id = '$editor_unique' limit 1";
		$run = $this->run_query($query);
		//
		$count = mysqli_num_rows($run);
		//
		if($count > 0){
			//
			if(isset($_SESSION['u_admin'])){
				//
				$chapter_dir = $pointcontr-> get_chapter_dir($subject,$moduel,$chapter);
				//
				if(!empty($chapter_dir)){
					//remove the old content
					$removal = $filehandle->rrmdir_topics($this->root.'/'.$chapter_dir);
					$drop = "/users/$editor_unique/$chapter_dir";
					//
					if($removal == 1 and !empty($editor_unique)){
						//copy the editor content
						$copy = $filehandle -> recurse_copy_topics("$this->root/$drop","$this->root/$chapter_dir");
						if($copy == 1){
							//remove the editor content 
							$second_removal = $filehandle->rrmdir($this->root.'/'.$drop);
							//
							if($second_removal == 1){
								//
								$inject = $pointcontr-> inject_point("$this->root/$chapter_dir",$filehandle,$utility);
								if($inject== 1){
									$result = 1;
								}else{
									$result = 0;
								}
							}else{
								$result = 0;
							}
						}else{
							$return = 0;
						}
					}else{
						$result = 0;
					}
				}else{
					$result = 0;
				}
				//
				if($result == 1){
					$query = "update active_tasks set approval_status =1,editing_status =1 where pt_subject = '$subject' and pt_moduel = '$moduel' and pt_chapter = '$chapter' and user_unique_id = '$editor_unique' limit 1";
					//
					if($this->run_query($query)){
						//
						$message = "One of the projects that you have recently submitted has been accepted.";
						$subject = 'PracticePractice: Work accepted ';
						$this->manual_construct_unique_id($editor_unique);
						$mail = $utility->send_mail($php_mailer,$this->user_email,$subject,$message);
						//
						if($mail == 1){
							return 1;
						}else{
							return 0;
						}
					}else{
						return 0;
					}
				}else{
					return 0;
				}
				
				
				
			}else{
				return 0;
			}
		}else{
			return 0;
		}
	}
	//Contr:
	public function admin_accept_work_question($editor_unique,$subject,$moduel,$chapter,$topic,$pt_unique_id,$questioncontr,$filehandle,$utility,$php_mailer){
		//
		$query = "select * from active_tasks_questions where q_subject = '$subject' and q_moduel = '$moduel' and q_chapter = '$chapter' and q_topic='$topic' and q_point = '$pt_unique_id' and user_unique_id = '$editor_unique' limit 1";
		$run = $this->run_query($query);
		//
		$count = mysqli_num_rows($run);
		//
		if($count > 0){
			//
			if(isset($_SESSION['u_admin'])){
				//
				$questions_dir = $questioncontr-> get_questions_dir($subject,$moduel,$chapter,$topic,$pt_unique_id);
				//
				if(!empty($questions_dir)){
					//remove the old content
					$removal = $filehandle->rrmdir($this->root.'/'.$questions_dir);
					$drop = "/users/$editor_unique/$questions_dir";
					//
					if($removal == 1 and !empty($editor_unique)){
						//copy the editor content
						$copy = $filehandle -> recurse_copy("$this->root/$drop","$this->root/$questions_dir");
						if($copy == 1){
							//remove the editor content 
							$second_removal = $filehandle->rrmdir($this->root.'/'.$drop);
							//
							if($second_removal == 1){
								//
								$inject = $questioncontr-> inject_question("$this->root/$questions_dir",$filehandle,$utility);
								if($inject== 1){
									$result = 1;
								}else{
									$result = 0;
								}
							}else{
								$result = 0;
							}
						}else{
							$return = 0;
						}
					}else{
						$result = 0;
					}
				}else{
					$result = 0;
				}
				//
				if($result == 1){
					$query = "update active_tasks_questions set approval_status =1,editing_status =1 where q_subject = '$subject' and q_moduel = '$moduel' and q_chapter = '$chapter' and q_topic='$topic' and q_point = '$pt_unique_id' and user_unique_id = '$editor_unique' limit 1";
					//
					if($this->run_query($query)){
						//
						$message = "One of the projects that you have recently submitted has been accepted.";
						$subject = 'PracticePractice: Work accepted ';
						$this->manual_construct_unique_id($editor_unique);
						$mail = $utility->send_mail($php_mailer,$this->user_email,$subject,$message);
						//
						if($mail == 1){
							return 1;
						}else{
							return 0;
						}
					}else{
						return 0;
					}
				}else{
					return 0;
				}
				
				
				
			}else{
				return 0;
			}
		}else{
			return 0;
		}
	}
	//Contr:
	public function admin_reject_work($editor_unique,$subject,$moduel,$chapter,$utility,$php_mailer){
		//
		$query = "select * from active_tasks where pt_subject = '$subject' and pt_moduel = '$moduel' and pt_chapter = '$chapter' and user_unique_id = '$editor_unique' limit 1";
		$run = $this->run_query($query);
		//
		$count = mysqli_num_rows($run);
		//
		if($count > 0){
			//
			if(isset($_SESSION['u_admin'])){
				$query = "update active_tasks set approval_status =0,editing_status =0 where pt_subject = '$subject' and pt_moduel = '$moduel' and pt_chapter = '$chapter' and user_unique_id = '$editor_unique' limit 1";
				//
				if($this->run_query($query)){
					//
					//
					$message = "One of the projects that you have recently submitted has been rejected, please check the notes.";
					$subject = 'PracticePractice: Work rejected';
					$this->manual_construct_unique_id($editor_unique);
					$mail = $utility->send_mail($php_mailer,$this->user_email,$subject,$message);
					//
					if($mail == 1){
						return 1;
					}else{
						return 0;
					}
				}else{
					return 0;
				}
			}else{
				return 0;
			}
		}else{
			return 0;
		}
	}
	//Contr:
	public function reject_work_question($editor_unique,$subject,$moduel,$chapter,$topic,$pt_unique_id,$utility,$php_mailer){
		//
		$query = "select * from active_tasks_questions where q_subject = '$subject' and q_moduel = '$moduel' and q_chapter = '$chapter' and q_topic = '$topic' and q_point='$pt_unique_id' and user_unique_id = '$editor_unique' limit 1";
		$run = $this->run_query($query);
		//
		$count = mysqli_num_rows($run);
		//
		if($count > 0){
			//
			if(isset($_SESSION['u_admin'])){
				$query = "update active_tasks_questions set approval_status =0,editing_status =0 where q_subject = '$subject' and q_moduel = '$moduel' and q_chapter = '$chapter' and q_topic = '$topic' and q_point='$pt_unique_id' and user_unique_id = '$editor_unique' limit 1";
				//
				if($this->run_query($query)){
					//
					//
					$message = "One of the projects that you have recently submitted has been rejected, please check the notes.";
					$subject = 'PracticePractice: Work rejected';
					$this->manual_construct_unique_id($editor_unique);
					$mail = $utility->send_mail($php_mailer,$this->user_email,$subject,$message);
					//
					if($mail == 1){
						return 1;
					}else{
						return 0;
					}
				}else{
					return 0;
				}
			}else{
				return 0;
			}
		}else{
			return 0;
		}
	}
	//Contr:
	public function is_task_listed($subject,$moduel,$chapter){
		//
		$query = "select * from active_tasks where pt_subject = '$subject' and pt_moduel = '$moduel' and pt_chapter = '$chapter' limit 1";
		$run = $this->run_query($query);
		//
		$count = mysqli_num_rows($run);
		//
		if($count > 0){
			return 1;
		}else{
			$cross_check = $this->cross_task_listed_check($subject,$moduel,$chapter,'active_tasks_questions');
			//
			if($cross_check == 1){
				return 1;
			}else{
				return 0;
			}
		}
	}
	//Contr:
	public function is_task_listed_question($filter,$pt_unqiue,$pointcontr){
		$point = $pointcontr->make_obj_unique_id($pt_unqiue);
		$subject = $point['pt_subject'];
		$moduel = $point['pt_moduel'];
		$chapter = $point['pt_chapter'];
		$topic = $point['pt_topic'];
		$unique = $point['pt_unique_id'];
		//
		if($filter =='point'){
			//
			$query = "select * from active_tasks_questions where q_point = '$unique' limit 1";
		}elseif($filter=='topic'){
			//
			$query = "select * from active_tasks_questions where q_subject = '$subject' and q_moduel = '$moduel' and q_chapter = '$chapter' and q_topic='$topic' and q_point='' limit 1";
		}else{
			if($filter=='chapter'){
				//
				$query = "select * from active_tasks_questions where q_subject = '$subject' and q_moduel = '$moduel' and q_chapter = '$chapter' and q_topic='' and q_point='' limit 1";
			}else{die;}
		}
		
		
		
		$run = $this->run_query($query);
		//
		$count = mysqli_num_rows($run);
		//
		if($count > 0){
			return 1;
		}else{
			$cross_check = $this->cross_task_listed_check($subject,$moduel,$chapter,'active_tasks');
			//
			if($cross_check == 1){
				return 1;
			}else{
				return 0;
			}
		}
	}
	//
	private function cross_task_listed_check($subject,$moduel,$chapter,$table){
		if($table == 'active_tasks'){
			$query = "select * from $table where pt_subject = '$subject' and pt_moduel = '$moduel' and pt_chapter = '$chapter' limit 1 ";
		}elseif($table == 'active_tasks_questions'){
			$query = "select * from $table where q_subject = '$subject' and q_moduel = '$moduel' and q_chapter = '$chapter' limit 1 ";
		}
		$run = $this->run_query($query);
		$count = mysqli_num_rows($run);
		//
		if($count == 1){
			return 1;
		}else{return 0;}
	}
	//
	public function pay_for_membership($paypal,$option){
		//
		if(isset($_SESSION['membership_discount'])){
			$discount = $_SESSION['membership_discount'];
		}else{
			$discount = 0;
		}
		//
		$month =60*60*24*30; 
		//
		if(isset($option)){
			if($option == 1){
				$subtotal = $this->month;
				$total = $subtotal - (($discount/100) * $subtotal);
				$cr = $month;
			}elseif($option ==2){
				$subtotal = $this->six_months*6;
				$total = $subtotal - (($discount/100) * $subtotal);
				$cr = 6 * $month;
			}else{
				if($option == 3){
					$subtotal = $this->nine_months*9;
					$total = $subtotal - (($discount/100) * $subtotal);
					$cr = 9 * $month;
				}else{
					return 0;
				}
			}
			
		}else{
			return 0;
		}
		//create payment road 
		$_SESSION['membership_total'] = round($total , 2);
		$_SESSION['affiliate_cut'] = $this->affiliates_cut*$_SESSION['membership_total'];
		$credits = time() + $cr;
		$payment_path = $paypal->payment($total,$credits);
		//
		if($payment_path !== 0){
			return array(
				'status'=> 1,
				'msg' => "$payment_path"
			);
		}else{
			return array(
				'status'=> 0,
				'msg' => "Something went wrong"
			);
		}
	}
	//
	public function pay_for_membership_approval($payer_id,$token,$hash,$paypal){
		//get the required variabled to pay
		$query = "select payment_id from transactions_paypal where hash = '$hash'";
		//
		$run = $this->run_query($query);
		$count = mysqli_num_rows($run);
		//
		if($count > 0){
			//
			$assoc = mysqli_fetch_assoc($run);
			$payment_id = $assoc['payment_id'];
			//
			$payment = $paypal->getpayment($payment_id);
			//
			$make_member = $this->activate_membership();
			//
			if($make_member == 1){
				//
				$result = $paypal->execute_payment($payment,$payer_id);
			}else{
				return 0;
			}
			//
			if($result == 1){
				//
				if(isset($_SESSION['discount_affiliate']) and isset($_SESSION['affiliate_cut'])){
					//add to the affiliate
					$affiliate = $_SESSION['discount_affiliate'];
					$cut = $_SESSION['affiliate_cut'];
					$this->manual_construct_uid($affiliate);
					$oid = $this->that_other_id;
					$user = $_SESSION['u_uid'];
					//
					$query = "insert into affiliate_history 
					(affiliate, customer, amount)
					VALUES ('$affiliate', '$user', '$cut');";
					if($this->run_query($query)){
						$query = "select id from affiliate_balance where affiliate_name = '$affiliate'";
						$run = $this->run_query($query);
						$count = mysqli_num_rows($run);
						//
						if($count > 0){
							$query_f = "update affiliate_balance set affiliate_balance = affiliate_balance + $cut where affiliate_name = '$affiliate'";
						}else{
							$query_f = "insert into affiliate_balance (affiliate_name,affiliate_wallet,affiliate_balance) values ('$affiliate','$oid','$cut')";
						}
						if($this->run_query($query_f)){
							return 1;
						}else{
							return 1;
						}
						
					}else{
						return 1;
					}
				}else{
					return 1;
				}
				
			}else{
				//
				$deactivate_member = $this->deactivate_membership();
				//
				if($deactivate_member == 1){
					return 0;
				}else{
					return 0;
				}
			}
		}else{
			return 0;
		}
	}
	//CONTR: 
	private function activate_membership(){
		if(isset($_SESSION['u_id'])){
			//
			$u_id = $_SESSION['u_id'];
			if(isset($_SESSION['bought_credits'])){
				$credits = $_SESSION['bought_credits'];
			}else{
				return 0;
			}
			
			$query = "update $this->db_table set user_member_status = 1, user_member_credits = '$credits' where user_id = '$u_id'";
			//
			if($this->run_query($query)){
				$_SESSION["user_membership"] = 1;
				return 1;
			}else{
				return 0;
			}
		}else{
			return 0;
		}
	}
	//CONTR: 
	private function deactivate_membership(){
		if(isset($_SESSION['u_id'])){
			//
			$u_id = $_SESSION['u_id'];
			$query = "update $this->db_table set user_member_status = 0,user_member_credits=0 where user_id = '$u_id'";
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
	public function initiate_editor_payout($editor_unique,$subject,$moduel,$chapter,$paypal,$mailer){
		//check if the editor really owns the task and figure out how to do that for both types of tasks
		$validate_editor = $this->is_editor_valid($editor_unique,$subject,$moduel,$chapter);
		//instantiate the editor's details
		if($validate_editor == 1){
			$this->manual_construct_unique_id($editor_unique);
		}else{
			return array(
				'status' => 0 ,
				'msg' => "Invalid editor"
			);
		}
		//fetch the amount
		$amount_query = "select task_payment_amount	from active_tasks WHERE pt_subject = '$subject' and pt_moduel = '$moduel' and pt_chapter = '$chapter' and user_unique_id = '$editor_unique' limit 1";
		$run = $this->run_query($amount_query);
		$assoc = mysqli_fetch_assoc($run);
		$amount = $assoc['task_payment_amount'];
		//check if the editor's paypal account is verified 
		if($this->other_id_verification == 1){
			//create the payment path using the editor's paypal ID
			$payment_result = $this->initiate_editor_payout_general($this->that_other_id,$amount,$paypal);
			//
			if($payment_result == 1){
				//delete the active task
				$query = "DELETE FROM active_tasks
				WHERE pt_subject = '$subject' and pt_moduel = '$moduel' and pt_chapter = '$chapter' and user_unique_id = '$editor_unique' limit 1";
				if($this->run_query($query)){
					return array(
						'status' => 1 ,
						'msg' => "Successful payment"
					);
				}else{
					return array(
						'status' => 0 ,
						'msg' => "Cannot update database"
					);
				}
			}else{
				return array(
				'status' => 0 ,
				'msg' => "Cannot execute payment: check balance"
			);
			}
		}else{
			return array(
				'status' => 0 ,
				'msg' => "PayPal account is not verified"
			);
		}
		
	}
	//
	public function initiate_editor_payout_bnkak($editor_unique,$subject,$moduel,$chapter,$mailer){
		//check if the editor really owns the task and figure out how to do that for both types of tasks
		$validate_editor = $this->is_editor_valid($editor_unique,$subject,$moduel,$chapter);
		//instantiate the editor's details
		if($validate_editor == 1){
			$this->manual_construct_unique_id($editor_unique);
		}else{
			return array(
				'status' => 0 ,
				'msg' => "Invalid editor"
			);
		}
		//check if the editor's paypal account is verified 
		if($this->bnkak_id_verification == 1){
			//delete the active task
			$query = "DELETE FROM active_tasks
			WHERE pt_subject = '$subject' and pt_moduel = '$moduel' and pt_chapter = '$chapter' and user_unique_id = '$editor_unique' limit 1";
			if($this->run_query($query)){
				return array(
					'status' => 1 ,
					'msg' => "Successful payment"
				);
			}else{
				return array(
					'status' => 0 ,
					'msg' => "Cannot update database"
				);
			}
			
		}else{
			return array(
				'status' => 0 ,
				'msg' => "PayPal account is not verified"
			);
		}
		
	}
	//
	public function initiate_editor_payout_question($editor_unique,$subject,$moduel,$chapter,$topic,$pt_unique_id,$paypal,$mailer){
		//check if the editor is correct
		$validate_editor = $this->is_editor_valid_question_2($editor_unique,$subject,$moduel,$chapter,$topic,$pt_unique_id);
		//instantiate the editor's details
		if($validate_editor == 1){
			$this->manual_construct_unique_id($editor_unique);
		}else{
			return array(
				'status' => 0 ,
				'msg' => "Invalid editor"
			);
		}
		//fetch the amount
		$amount_query = "select task_payment_amount	from active_tasks_questions WHERE q_subject='$subject' and q_moduel='$moduel' and q_chapter = '$chapter' and q_topic = '$topic' and q_point = '$pt_unique_id' and user_unique_id='$editor_unique' limit 1";
		$run = $this->run_query($amount_query);
		$assoc = mysqli_fetch_assoc($run);
		$amount = $assoc['task_payment_amount'];		
		//check if the editor's paypal account is verified 
		if($this->other_id_verification == 1){
			//create the payment path using the editor's paypal ID
			$payment_result = $this->initiate_editor_payout_general($this->that_other_id,$amount,$paypal);
			//
			if($payment_result == 1){
				//delete the active task
				$query = "DELETE FROM active_tasks_questions
				WHERE q_subject='$subject' and q_moduel='$moduel' and q_chapter = '$chapter' and q_topic = '$topic' and q_point = '$pt_unique_id' and user_unique_id='$editor_unique' limit 1";
				if($this->run_query($query)){
					return array(
						'status' => 1 ,
						'msg' => "Sucessful payment"
					);
				}else{
					return array(
						'status' => 0 ,
						'msg' => "Cannot update database but payment is complete"
					);
					
				}
			}else{
				return array(
					'status' => 0 ,
					'msg' => "Cannot execute payment: check balance"
				);
			}
		}else{
			return array(
				'status' => 0 ,
				'msg' => "PayPal account is not verified"
			);
		}
	}
	//
	public function initiate_editor_payout_question_bnkak($editor_unique,$subject,$moduel,$chapter,$topic,$pt_unique_id,$mailer){
		//check if the editor is correct
		$validate_editor = $this->is_editor_valid_question_2($editor_unique,$subject,$moduel,$chapter,$topic,$pt_unique_id);
		//instantiate the editor's details
		if($validate_editor == 1){
			$this->manual_construct_unique_id($editor_unique);
		}else{
			return array(
				'status' => 0 ,
				'msg' => "Invalid editor"
			);
		}		
		//check if the editor's paypal account is verified 
		if($this->bnkak_id_verification == 1){
			//delete the active task
			$query = "DELETE FROM active_tasks_questions
			WHERE q_subject='$subject' and q_moduel='$moduel' and q_chapter = '$chapter' and q_topic = '$topic' and q_point = '$pt_unique_id' and user_unique_id='$editor_unique' limit 1";
			if($this->run_query($query)){
				return array(
					'status' => 1 ,
					'msg' => "Sucessful payment"
				);
			}else{
				return array(
					'status' => 0 ,
					'msg' => "Cannot update database but payment is complete"
				);
			}
			
		}else{
			return array(
				'status' => 0 ,
				'msg' => "PayPal account is not verified"
			);
		}
	}
	//
	public function affiliate_payout($paypal,$mailer){
		//get valid affiliate's uid and paypal id
		$query = "select user_uid, that_other_id from users where affiliates = 1 and other_id_verification = 1";
		$run = $this->run_query($query);
		$count = mysqli_num_rows($run);
		//
		if ($count > 0 ){
			$affiliate_info = array();
			while($result = mysqli_fetch_assoc($run)){
				//
				$uid = $result['user_uid'];
				$o_id = $result['that_other_id'];
				//
				$affiliate_info[$o_id]['o_id']=$o_id;
				$affiliate_info[$o_id]['bal']= 0;
			}
		}else{
			return array(
				'status' => 0 ,
				'msg' => "There are no valid affiliates."
			);
		}
		//the minimum payment amount should be specified
		$query = "select * from affiliate_balance where affiliate_balance > 0.01";
		$run = $this->run_query($query);
		$count = mysqli_num_rows($run);
		//
		if ($count > 0 ){
			while($result = mysqli_fetch_assoc($run)){
				//
				$uid = $result['affiliate_name'];
				$oid = $result['affiliate_wallet'];
				$bal = $result['affiliate_balance'];
				//
				$affiliate_info[$oid]['bal'] +=$bal;
			}
		}else{
			return array(
				'status' => 0 ,
				'msg' => "Something went wrong."
			);
		}
		//executing payment;
		$payout_info = array();
		foreach($affiliate_info as $oid => $info){
			//
			$email = $info['o_id'];
			$amount = $info['bal'];
			//
			$payout_info[] = array('email' => $email,'amount'=> $amount);
		}
		//
		$payout = $this->initiate_editor_payouts_general($payout_info,$paypal);
		if($payout == 1){
			//left to clear out the balance of the affiliate after they get paid
			if($this->clear_affiliate_balance($payout_info)){
				return array(
					'status' => 1 ,
					'msg' => "Sucess"
				);
			}else{
				return array(
				'status' => 0 ,
				'msg' => "Couldn't clear out the affiliate balances !"
			);
			}
			
		}else{
			return array(
				'status' => 0 ,
				'msg' => "Something went wrong."
			);
		}
		
	}
	//
	private function clear_affiliate_balance($info){
		//
		$wallets = array();
		foreach($info as $key => $value){
			//
			$wallets[] = $value['email'];
			
		}
		//
		$list = implode("','",$wallets);
		$query = "update affiliate_balance set affiliate_balance = 0 where affiliate_wallet in ('$list')";
		if($this->run_query($query)){
			return 1;
		}else{return 0;}
	}
	//
	private function initiate_editor_payout_general($payee_email,$amount,$paypal){
		//
		$send_payout = $paypal->payout($payee_email,$amount);
		//
		if($send_payout==1){
			return 1;
		}else{
			return 0;
		}
	}
	//
	private function initiate_editor_payouts_general($info,$paypal){
		//
		$send_payout = $paypal->payouts($info);
		//
		if($send_payout==1){
			return 1;
		}else{
			return 0;
		}
	}
	//
	public function editor_paypalemail_input($u_id,$editor_unique,$editor_paypal_email,$paypal){
		//get the user information and extract the code
		$this->manual_construct_unique_id($editor_unique);
		$code = substr($this->user_vkey,0,4);
		//send the user a verification payment
		$send_payout = $paypal->payout_confirm_account($editor_paypal_email,$code);
		//
		if($send_payout == 1){
			//update the users table
			$query = "update $this->db_table set that_other_id = '$editor_paypal_email',other_id_verification ='0' where user_id = '$u_id' and user_unique_id='$editor_unique'";
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
	public function editor_bnkak_input($u_id,$editor_unique,$editor_bnkak){
		//update the users table
		$query = "update $this->db_table set that_other_id = '$editor_bnkak',other_id_verification ='1',bnkak_id_verification ='1' where user_id = '$u_id' and user_unique_id='$editor_unique'";
		//
		if($this->run_query($query)){
			return 1;
		}else{
			return 0;
		}
	}	
	//
	public function editor_paypalverification_input($u_id,$editor_unique,$editor_paypalverification_code){
		$this->manual_construct_unique_id($editor_unique);
		$code = substr($this->user_vkey,0,4);
		if($code == $editor_paypalverification_code){
			//update the users table
			$query = "update $this->db_table set other_id_verification ='1' where user_id = '$u_id' and user_unique_id='$editor_unique'";
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
	public function alert_editors_for_work($utility,$php_mailer){
		//get all available editors
		$query = "select user_email from users where editors=1 and editors_contract_status=1 and approved_editor=1 and contract_termination_time=0 and other_id_verification =1";
		$run = $this->run_query($query);
		$count = mysqli_num_rows($run);
		//
		if($count > 0){
			//
			while($assoc = mysqli_fetch_assoc($run)){
				//
				$editor_mail = $assoc['user_email'];
				//
				$message = "There are new <a href='https://practicepractice.net/'>tasks available</a> for work, check if some are in your subject. if you're uncertain about the method of content writing/coding on the website please check this <a href='https://practicepractice.net/P/universal_spec_table?pt_subject=maths&pt_moduel=25_eidtor_tutorial&pt_chapter=01_spec'>Tutorial</a>";
				$subject = 'PracticePractice: Work Available';
				$mail = $utility->send_mail($php_mailer,$editor_mail,$subject,$message);
			}
			return 1;
		}else{
			return 0;
		}
	}
	//
	public function toggle_question_visibility($u_unique_id){
		//
		$current = $_SESSION["user_setting_showAttempted"];
		//
		if($current == 1){
			$val = 0;
		}else{
			$val = 1;
		}
		//
		$query = "update users set user_setting_showAttempted = '$val' where user_unique_id = '$u_unique_id'";
		//
		if($this->run_query($query)){
			$_SESSION["user_setting_showAttempted"] = $val;
			return 1;
		}else{
			return 0;
		}
	}
	//
	public function toggle_level($u_unique_id){
		//
		$current = $_SESSION["user_setting_level"];
		//
		if($current == '' or $current == null){
			$val = 'AS';
		}elseif ($current == 'AS'){
			$val = 'A';
		}else{
			if($current =='A'){
				$val = '';
			}else{
				$val = '';
			}
		}
		//
		$query = "update users set user_setting_level = '$val' where user_unique_id = '$u_unique_id'";
		//
		if($this->run_query($query)){
			$_SESSION["user_setting_level"] = $val;
			return 1;
		}else{
			return 0;
		}
	}
	//
	public function get_affiliates(){
		$query = "select user_uid from users where affiliates = 1";
		$run = $this->run_query($query);
		$count = mysqli_num_rows($run);
		//
		if($count > 0 ){
			$return = array();
			while($result = mysqli_fetch_assoc($run)){
				$affiliate = $result['user_uid'];
				$return[] = $affiliate;
			}
			return $return;
		}else{
			return array();
		}
	}
	//
	public function get_affiliate_bal(){
		//
		$u_id = $_SESSION['u_uid'];
		$query = "select affiliate_balance from affiliate_balance where affiliate_name = '$u_id' limit 1";
		$run = $this->run_query($query);
		$count = mysqli_num_rows($run);
		//
		if($count > 0 ){
			while($result = mysqli_fetch_assoc($run)){
				$bal = $result['affiliate_balance'];
			}
			return $bal;
		}else{
			return "0";
		}
	}
	//
	public function get_affiliate_history(){
		//
		$u_id = $_SESSION['u_uid'];
		$query = "select * from affiliate_history where affiliate = '$u_id' order by id desc limit 50";
		//
		$run = $this->run_query($query);
		$count = mysqli_num_rows($run);
		//
		if($count> 0){
			$return = array();
			while($result = mysqli_fetch_assoc($run)){
				$affiliate = $result['affiliate'];
				$customer = $result['customer'];
				$amount = $result['amount'];
				//
				$return[] = "$customer has used your code: <p style='color:green;display:inline-block;'>+£$amount</p>";
			}
			return $return;
		}else{
			return array('-');
		}
	}
	//
	public function create_review($name,$rating,$review){
		//
		$name = $this->conn -> real_escape_string($name);
		$rating = $this->conn -> real_escape_string($rating);
		$review = $this->conn -> real_escape_string($review);
		$t = time();
		$query = "insert into reviews (name,rating,review,time) values ('$name','$rating','$review','$t')";
		//
		if($this->run_query($query)){
			return 1;
		}else{
			return 0;
		}
	}
}

















