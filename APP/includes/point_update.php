<?php

//for the admin 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
//autoload 
$root = $_SERVER['DOCUMENT_ROOT'];
require_once "$root/includes/bootstrap.php";
//
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
//
$securityhandle = new MVC\securityhandle;
$utility = new MVC\utility;
//run a general check aon all variables that pass though
foreach($_POST as $name => $value){
	//
	$status = $securityhandle->check_site_variable($name,$value);
	//
	if($status == 0){
		echo $utility->general_alert_eval('alert_warning',"Invalid input: $name"); die;
	}
}
foreach($_FILES as $name => $value){
	//
	$status = $securityhandle->check_site_variable($name,$value);
	//
	if($status == 0){
		echo $utility->general_alert_eval('alert_warning',"Invalid input: $name"); die;
	}
	
}




//C: tracker
if(isset($_POST['track_impressions']) and !isset($_SESSION['u_admin']) and !isset($_SESSION['u_editor'])){
	//
	$utility = new MVC\utility;
	//
	$utility -> track_impressions();
	//
	die;
	
}
//C: tracker
if(isset($_POST['get_impressions_data']) and isset($_SESSION['u_admin'])){
	//
	$utility = new MVC\utility;
	//
	$xys = $utility -> get_impressions_data();
	//
	$myJSON = $xys;
	echo json_encode($myJSON);
	die;
	
}
//C: tracker
//if(isset($_POST['get_performance_data']) and isset($_SESSION['u_id'])){
if(isset($_POST['get_performance_data'])){
	//
	$questioncontr = new MVC\questioncontr;
	//
	$xys = $questioncontr -> get_performance_data();
	//
	$myJSON = $xys;
	echo json_encode($myJSON);
	die;
	
}
//*******************************************
//MVC:
if(isset($_SESSION['u_admin']) and isset($_POST['specpoint_Dbprocessing'])){
	//
	$pointcontr = new MVC\pointcontr;
	$filehandle = new MVC\filehandle();
	$utility = new MVC\utility();
	//
	$result = $pointcontr->inject_point("$root/specifications/universal/",$filehandle,$utility);
	//
	if($result ==1){
		echo $utility->general_alert_eval('alert_popup_fresh','success');
	}else{
		echo $utility->general_alert_eval('alert_warning','something went wrong');
	}
	die;
}
//MVC:
if(isset($_SESSION['u_admin']) and isset($_POST['update_pt_raw_information'])){
	//
	$pointcontr = new MVC\pointcontr;
	$filehandle = new MVC\filehandle();
	$utility = new MVC\utility();
	//
	$subject = $_POST['pt_subject'];
	$moduel = $_POST['pt_moduel'];
	$chapter = $_POST['pt_chapter'];
	$pt_raw_information = $_POST['pt_information'];
	//
	$result = $pointcontr -> update_pt_raw_information($subject,$moduel,$chapter,$pt_raw_information,$filehandle);
	//
	if($result ==1){
		echo $utility->general_alert_eval('alert_popup_fresh','success');
	}else{
		echo $utility->general_alert_eval('alert_warning','something went wrong');
	}
	die;
}
//MVC:
if(isset($_SESSION['u_admin']) and isset($_POST['assigneditor'])){
	//
	$pointcontr = new MVC\pointcontr;
	$filehandle = new MVC\filehandle;
	$utility = new MVC\utility;
	//
	$pt_subject = $_POST['pt_subject'];
	$pt_moduel = $_POST['pt_moduel'];
	$pt_chapter = $_POST['pt_chapter'];
	$task_payment_amount = $_POST['task_payment_amount'];
	//
	$result = $pointcontr -> assigneditor_listing($pt_subject,$pt_moduel,$pt_chapter,$task_payment_amount);
	//
	if($result ==1){
		echo $utility->general_alert_eval('alert_popup_fresh','success');
	}else{
		echo $utility->general_alert_eval('alert_warning','something went wrong');
	}
	die;
	
}
//MVC:
if(isset($_SESSION['u_admin']) and isset($_POST['hireuser_toeditor'])){
	//
	$usercontr = new MVC\usercontr;
	$filehandle = new MVC\filehandle;
	$utility = new MVC\utility;
	$mailer = new PHPMailer(true);
	//
	$user_unique = $_POST['user_unique_id'];
	$user_email = $_POST['email'];
	$user_primary_subject = $_POST['primary_subject'];
	$user_secondary_subject = $_POST['secondary_subject'];
	//
	$result = $usercontr -> hireuser_toeditor($user_unique,$user_email,$user_primary_subject,$user_secondary_subject,$utility,$mailer);
	//
	if($result ==1){
		echo $utility->general_alert_eval('alert_popup_fresh','success');
	}else{
		echo $utility->general_alert_eval('alert_warning','something went wrong');
	}
	die;
	
}
//MVC:
if(isset($_SESSION['u_admin']) and isset($_POST['question_DBprocessing'])){
	//
	$questioncontr = new MVC\questioncontr;
	$filehandle = new MVC\filehandle();
	$utility = new MVC\utility();
	set_time_limit(0);
	//
	$result = $questioncontr->inject_question("$root/specifications/universal/",$filehandle,$utility);
	//
	if($result ==1){
		echo $utility->general_alert_eval('alert_popup_fresh','success');
	}else{
		echo $utility->general_alert_eval('alert_warning','something went wrong');
	}
	die;
}
//MVC:
if(isset($_SESSION['u_admin']) and isset($_POST['paper_DBprocessing'])){
	//
	$papercontr = new MVC\papercontr;
	$filehandle = new MVC\filehandle();
	$utility = new MVC\utility();
	//
	$result = $papercontr->inject_paper("$root/pdf/papers/",$filehandle,$utility);
	//
	if($result ==1){
		echo $utility->general_alert_eval('alert_popup_fresh','success');
	}else{
		echo $utility->general_alert_eval('alert_warning','something went wrong');
	}
	die;
}
//MVC: 
if((isset($_SESSION['u_admin']) or (isset($_POST['editor_updates']) and isset($_SESSION['u_editor'])) or isset($_SESSION['write_perm'])) and isset($_POST['point_id']) and isset($_POST['new_input'])){
	//initiation
	$utility = new MVC\utility;
	$filehandle = new MVC\filehandle;
	$pointcontr = new MVC\pointcontr;
	$pointview = new MVC\pointview;
	//extraction
	if(isset($_POST['editor_updates'])){
		$editor_unique = $_SESSION['user_unique_id'];
		$pointcontr->db_table = ' points_editors ';
		$pointview->db_table = ' points_editors ';
		$pointcontr->info_src = "users/$editor_unique/specifications";
		$pointview->info_src = "users/$editor_unique/specifications";
	}
	//
	$p_id = $_POST['point_id'];
	$update_discription = $_POST['new_input'];
	
	$input_info = $utility->make_assoc2(array($p_id,$update_discription),array('pt_id','pt_description'));
	
	//execution
	$result = $pointcontr->description_update($input_info,$filehandle,$securityhandle);
	$desc= $pointcontr->pt_description;
	$new = $utility->remove_hidden_tag($desc);
	//
	if($result == 1){
		$alert = $utility->general_alert_eval('alert_popup',"successful update");
		$myJSON = array("description"=>$new,"alert"=>$alert);
		echo json_encode($myJSON);
		die;
	}elseif($result == 0){
		$alert = $utility->general_alert_eval('alert_popup',"Filed to update");
		$myJSON = array("alert"=>$alert);
		echo json_encode($myJSON);
		die;
	}
	
}
//MVC: 
if((isset($_SESSION['u_admin']) or (isset($_POST['editor_updates']) and isset($_SESSION['u_editor'])) or isset($_SESSION['write_perm'])) and isset($_POST['image_specform_upload'])){
	$filehandle = new MVC\filehandle;
	$utility = new MVC\utility;
	$pointcontr = new MVC\pointcontr;
	//check if the user has
	if(isset($_POST['editor_updates'])){
		$editor_unique = $_SESSION['user_unique_id'];
		$pointcontr->db_table = ' points_editors ';
		$pointcontr->info_src = "users/$editor_unique/specifications";
	}
	$id = $_POST["pt_id"]; // The point id, needed to find the home directory for the file.
	$file = $_FILES["file1"]; // The point id, needed to find the home directory for the file.
	//
	if(!empty($file) and !empty($id)){
		//
		$result = $pointcontr->upload_specimage($id,$file,$utility);
		//
		if($result ==1){
			echo $utility->general_alert_eval('alert_popup','Sucess');
		}else{
			echo $utility->general_alert_eval('alert_warning','Failue');
		}
		die;
		//
	}else{
		echo $utility->general_alert_eval('alert_warning','Failue');
		die;
	}
	//getting the directory of the point from the data base
	die;
}
//MVC: 
if((isset($_SESSION['u_admin']) or isset($_SESSION['write_perm'])) and isset($_POST['image_question_upload']) ){
	$filehandle = new MVC\filehandle;
	$utility = new MVC\utility;
	$questioncontr = new MVC\questioncontr;
	//check if the user has
	$q_unique = $_POST["q_unique_id"]; // The point id, needed to find the home directory for the file.
	$files = $_FILES["file1"]; // The point id, needed to find the home directory for the file.
	//
	if(!empty($files) and !empty($q_unique)){
		//
		$result = $questioncontr->image_question_upload($q_unique,$files,$utility);
		//
		if($result ==1){
			echo $utility->general_alert_eval('alert_popup','Sucess');
		}else{
			echo $utility->general_alert_eval('alert_warning','Failue');
		}
		die;
		//
	}else{
		echo $utility->general_alert_eval('alert_warning','Failue');
		die;
	}
	//getting the directory of the point from the data base
	die;
}
//MVC: 
if(isset($_SESSION['u_editor']) and isset($_POST['image_question_upload_editor']) ){
	$filehandle = new MVC\filehandle;
	$utility = new MVC\utility;
	$questioncontr = new MVC\questioncontr;
	//check if the user has
	$q_unique = $_POST["q_unique_id"]; // The point id, needed to find the home directory for the file.
	$files = $_FILES["file1"]; // The point id, needed to find the home directory for the file.
	//
	if(!empty($files) and !empty($q_unique)){
		//
		$result = $questioncontr->image_question_upload_editor($q_unique,$files,$utility);
		//
		if($result ==1){
			echo $utility->general_alert_eval('alert_popup','Sucess');
		}else{
			echo $utility->general_alert_eval('alert_warning','Failue');
		}
		die;
		//
	}else{
		echo $utility->general_alert_eval('alert_warning','Failue');
		die;
	}
	//getting the directory of the point from the data base
	die;
}
//MVC: 
if((isset($_SESSION['u_admin']) or (isset($_POST['editor_updates']) and isset($_SESSION['u_editor'])) or isset($_SESSION['write_perm'])) and isset($_POST['delete_file_unique'])){
	//initiation
	$pointcontr = new MVC\pointcontr;
	$filehandle = new MVC\filehandle;
	$utility = new MVC\utility;
	//
	if(isset($_POST['editor_updates'])){
		$editor_unique = $_SESSION['user_unique_id'];
		$pointcontr->db_table = ' points_editors ';
		$pointcontr->info_src = "users/$editor_unique/specifications";
	}
	$pt_unique_id = $_POST['point_unique'];
	$filename = $_POST['filename'];
	//
	$file = $pointcontr->get_pointFiles_uniqueandname($pt_unique_id,$filename);
	//$file = full linke within server
	$result = $filehandle->delete_file($file,$utility);
	//
	if($result ==1){
		echo $utility->general_alert_eval('alert_popup','Sucess');
	}else{
		echo $utility->general_alert_eval('alert_warning','Failue');
	}
	die;
}
//MVC: 
if((isset($_SESSION['u_admin']) or (isset($_POST['editor_updates']) and isset($_SESSION['u_editor'])) or isset($_SESSION['write_perm'])) and isset($_POST['delete_file_unique_qs'])){
	//initiation
	$questioncontr = new MVC\questioncontr;
	$filehandle = new MVC\filehandle;
	$utility = new MVC\utility;
	//
	if(isset($_POST['editor_updates'])){
		$editor_unique = $_SESSION['user_unique_id'];
		$pointcontr->db_table = ' questions_editors ';
		$pointcontr->info_src = "users/$editor_unique/specifications";
	}
	$q_unique_id = $_POST['question_unique'];
	$filename = $_POST['filename'];
	//
	$file = $questioncontr->get_pointFiles_uniqueandname($q_unique_id,$filename);
	//$file = full linke within server
	$result = $filehandle->delete_file($file,$utility);
	//
	if($result ==1){
		echo $utility->general_alert_eval('alert_popup','Sucess');
	}else{
		echo $utility->general_alert_eval('alert_warning','Failue');
	}
	die;
}
//MVC:
if(isset($_SESSION['u_admin']) and isset($_POST['delete_cache_html'])){
	$filehandle = new MVC\filehandle;
	$utility = new MVC\utility;
	$result = $filehandle -> delete_cache_html($utility);
	//
	if($result ==1){
		echo $utility->general_alert_eval('alert_popup','Sucess');
	}else{
		echo $utility->general_alert_eval('alert_warning','Failue');
	}
	die;
}
//MVC:
if(isset($_SESSION['u_admin']) and isset($_POST['question_id']) and isset($_POST['point_id'])){
	//initiation
	$pointcontr = new MVC\pointcontr;
	$questioncontr = new MVC\questioncontr;
	$filehandle = new MVC\filehandle;
	$utility = new MVC\utility;
	//
	$q_unique = $_POST['question_id'];
	$p_unique = $_POST['point_id'];
	//
	$A = $pointcontr->intraconnect_question($p_unique,$q_unique,$filehandle);
	$B = $questioncontr->intraconnect_point($q_unique,$p_unique,$filehandle);
	//
	if($A == 1 and $B ==1){
		$utility->general_alert_eval('alert_popup','Sucess');
	}else{
		$utility->general_alert_eval('alert_warning','Failue');
	}
	die;
}
//MVC:
if((isset($_SESSION['u_admin']) or (isset($_POST['editor_updates']) and isset($_SESSION['u_editor'])) or isset($_SESSION['write_perm'])) and isset($_POST['add_new_point'])){
	//initiation
	$pointcontr = new MVC\pointcontr;
	$utility = new MVC\utility;
	$filehandle = new MVC\filehandle;
	//
	if(isset($_POST['editor_updates'])){
		$editor_unique = $_SESSION['user_unique_id'];
		$pointcontr->db_table = ' points_editors ';
		$pointcontr->info_src = "users/$editor_unique/specifications";
	}
	$p_unique = $_POST['point_unique_id'];
	//exec 1
	$result = $pointcontr->add_new_specpoint($p_unique,$filehandle,$utility);
	//
	if($result == 1 ){
		echo $utility->general_alert_eval('alert_popup_fresh','success');
	}else{
		echo $utility->general_alert_eval('alert_warning','something went wrong');
	}
	die;
	
}
//MVC:
if(isset($_SESSION['u_admin']) and isset($_POST['delete_moduel_specpage'])){
	//initiation
	$pointcontr = new MVC\pointcontr;
	$utility = new MVC\utility;
	$filehandle = new MVC\filehandle;
	//
	$unique_id = $_POST['unique_id'];
	//exec 1
	$result = $pointcontr->remove_moduel($unique_id,$filehandle,$utility);
	//
	if($result == 1 ){
		echo $utility->general_alert_eval('alert_popup_fresh','success');
	}else{
		echo $utility->general_alert_eval('alert_warning','something went wrong');
	}
	die;
}
//MVC:
if(isset($_SESSION['u_admin']) and isset($_POST['delete_chapter_specpage'])){
	//initiation
	$pointcontr = new MVC\pointcontr;
	$utility = new MVC\utility;
	$filehandle = new MVC\filehandle;
	//
	$point_unique = $_POST['delete_chapter_specpage'];
	//exec 1
	$result = $pointcontr->remove_chapter($point_unique,$filehandle,$utility);
	//
	if($result == 1 ){
		echo $utility->general_alert_eval('alert_popup_fresh','success');
	}else{
		echo $utility->general_alert_eval('alert_warning','something went wrong');
	}
	die;
}
//MVC:
if( (isset($_SESSION['u_admin']) or (isset($_POST['editor_updates']) and isset($_SESSION['u_editor']))) and isset($_POST['delete_topic_specpage'])){
	//initiation
	$pointcontr = new MVC\pointcontr;
	$utility = new MVC\utility;
	$filehandle = new MVC\filehandle;
	//
	$point_unique = $_POST['delete_topic_specpage'];
	//
	if(isset($_POST['editor_updates'])){
		$editor_unique = $_SESSION['user_unique_id'];
		$pointcontr->db_table = ' points_editors ';
		$pointcontr->info_src = "users/$editor_unique/specifications";
	}
	//
	$result = $pointcontr->remove_topic($point_unique,$filehandle,$utility);
	//
	if($result == 1 ){
		echo $utility->general_alert_eval('alert_popup_fresh','success');
	}else{
		echo $utility->general_alert_eval('alert_warning','something went wrong');
	}
	die;
}
//MVC:
if((isset($_SESSION['u_admin']) or (isset($_POST['editor_updates']) and isset($_SESSION['u_editor'])) or isset($_SESSION['write_perm'])) and isset($_POST['remove_point'])){
	//initiation
	$pointcontr = new MVC\pointcontr;
	$utility = new MVC\utility;
	$filehandle = new MVC\filehandle;
	//
	if(isset($_POST['editor_updates'])){
		$editor_unique = $_SESSION['user_unique_id'];
		$pointcontr->db_table = ' points_editors ';
		$pointcontr->info_src = "users/$editor_unique/specifications";
	}
	$p_unique = $_POST['rm_point_unique_id'];
	//
	$result = $pointcontr->remove_specpoint($p_unique,$filehandle,$utility);
	//success
	if($result == 1 ){
		echo $utility->general_alert_eval('alert_popup_fresh','success');
	}else{
		echo $utility->general_alert_eval('alert_warning','something went wrong');
	}
	die;
}
//MVC:
if(isset($_SESSION['u_admin']) and isset($_POST['editor_contract_application_approval'])){
	//
	$usercontr = new MVC\usercontr;
	$filehandle = new MVC\filehandle;
	$utility = new MVC\utility;
	$mailer = new PHPMailer(true);
	//
	$editor_unique_id = $_POST['editor_unique_id'];
	$signature_confirmation = $_POST['signature_confirmation'];
	//
	$result = $usercontr -> editor_contract_application_approval($editor_unique_id,$signature_confirmation,$utility,$mailer);
	//
	$status = $result['status'];
	$msg = $result['msg'];
	//
	if($status == 1 ){
		echo $utility->general_alert_eval('headder_redirect',$msg);
	}else{
		echo $utility->general_alert_eval('alert_warning',$msg);
	}
	die;
}
//MVC:
if(isset($_SESSION['u_admin']) and isset($_POST['editor_suspension']) and isset($_POST['user_unique_id'])){
	//
	$usercontr = new MVC\usercontr;
	$filehandle = new MVC\filehandle;
	$utility = new MVC\utility;
	$mailer = new PHPMailer(true);
	//
	$editor_unique = $_POST['user_unique_id'];
	//
	$result = $usercontr -> editor_suspension($editor_unique,$utility,$mailer);

	//
	if($result == 1 ){
		echo $utility->general_alert_eval('alert_popup_fresh','success');
	}else{
		echo $utility->general_alert_eval('alert_warning','something went wrong');
	}
	die;
}
//MVC:
if(isset($_SESSION['u_admin']) and isset($_POST['unlist_editor_task']) ){
	//
	$pointcontr = new MVC\pointcontr;
	$utility = new MVC\utility;
	//
	$subject = $_POST['pt_subject'];
	$moduel = $_POST['pt_moduel'];
	$chapter = $_POST['pt_chapter'];
	//
	$result = $pointcontr -> unlist_editor_task($subject,$moduel,$chapter);
	//
	if($result == 1 ){
		echo $utility->general_alert_eval('alert_popup_fresh','success');
	}else{
		echo $utility->general_alert_eval('alert_warning','something went wrong');
	}
	die;
}
//MVC:
if(isset($_SESSION['u_admin']) and isset($_POST['unlist_editor_task_question']) ){
	//
	$questioncontr = new MVC\questioncontr;
	$utility = new MVC\utility;
	//
	$subject = $_POST['q_subject'];
	$moduel = $_POST['q_moduel'];
	$chapter = $_POST['q_chapter'];
	//
	if(isset($_POST['q_topic'])){
		$topic = $_POST['q_topic'];
	}else{$topic='';}
	//
	if(isset($_POST['q_point'])){
		$q_point = $_POST['q_point'];
	}else{$q_point='';}
	//
	$result = $questioncontr -> unlist_editor_task($subject,$moduel,$chapter,$topic,$q_point);
	//
	if($result == 1 ){
		echo $utility->general_alert_eval('alert_popup_fresh','success');
	}else{
		echo $utility->general_alert_eval('alert_warning','something went wrong');
	}
	die;
}
//MVC:
if(isset($_SESSION['u_admin']) and isset($_POST['admin_review_redirect']) ){
	//
	$usercontr = new MVC\usercontr;
	$utility = new MVC\utility;
	//
	$editor_unique = $_POST['editor_unique_id'];
	$subject = $_POST['pt_subject'];
	$moduel = $_POST['pt_moduel'];
	$chapter = $_POST['pt_chapter'];
	//
	$result = $usercontr -> admin_review_redirect($editor_unique,$subject,$moduel,$chapter);
	//
	//
	$status = $result['status'];
	$msg = $result['msg'];
	//
	if($status == 1 ){
		echo $utility->general_alert_eval('headder_redirect',$msg);
	}else{
		echo $utility->general_alert_eval('alert_warning',$msg);
	}
	die;
}
//MVC:
if(isset($_SESSION['u_admin']) and isset($_POST['admin_review_redirect_question']) ){
	//
	$pointcontr = new MVC\pointcontr;
	$usercontr = new MVC\usercontr;
	$utility = new MVC\utility;
	//
	$editor_unique = $_POST['editor_unique_id'];
	$subject = $_POST['q_subject'];
	$moduel = $_POST['q_moduel'];
	$chapter = $_POST['q_chapter'];
		//
	if(isset($_POST['q_topic'])){
		$topic = $_POST['q_topic'];
	}else{$topic='';}
	//
	if(isset($_POST['q_point'])){
		$pt_unique_id = $_POST['q_point'];
	}else{$pt_unique_id='';}
	//
	$result = $usercontr -> admin_review_question_redirect($editor_unique,$subject,$moduel,$chapter,$topic,$pt_unique_id,$pointcontr);
	//
	//
	$status = $result['status'];
	$msg = $result['msg'];
	//
	if($status == 1 ){
		echo $utility->general_alert_eval('headder_redirect',$msg);
	}else{
		echo $utility->general_alert_eval('alert_warning',$msg);
	}
	die;
}
//MVC:
if(isset($_SESSION['u_admin']) and isset($_POST['admin_accept_work']) ){
	//
	$pointcontr = new MVC\pointcontr;
	$usercontr = new MVC\usercontr;
	$filehandle = new MVC\filehandle;
	$utility = new MVC\utility;
	$mailer = new PHPMailer(true);
	//
	$editor_unique = $_POST['editor_unique_id'];
	$subject = $_POST['pt_subject'];
	$moduel = $_POST['pt_moduel'];
	$chapter = $_POST['pt_chapter'];
	//
	$result = $usercontr -> admin_accept_work($editor_unique,$subject,$moduel,$chapter,$pointcontr,$filehandle,$utility,$mailer);
	//
	if($result == 1 ){
		echo $utility->general_alert_eval('alert_popup_freshome','Success');
	}else{
		echo $utility->general_alert_eval('alert_warning','Something went wrong');
	}
	die;
}
//MVC:
if(isset($_SESSION['u_admin']) and isset($_POST['admin_accept_work_question']) ){
	//
	$questioncontr = new MVC\questioncontr;
	$usercontr = new MVC\usercontr;
	$filehandle = new MVC\filehandle;
	$utility = new MVC\utility;
	$mailer = new PHPMailer(true);
	//
	$editor_unique = $_POST['editor_unique_id'];
	$subject = $_POST['q_subject'];
	$moduel = $_POST['q_moduel'];
	$chapter = $_POST['q_chapter'];
	//
	if(isset($_POST['q_topic'])){
		$topic = $_POST['q_topic'];
	}else{$topic='';}
	//
	if(isset($_POST['pt_unique_id'])){
		$pt_unique_id = $_POST['pt_unique_id'];
	}else{$pt_unique_id='';}
	//
	$result = $usercontr -> admin_accept_work_question($editor_unique,$subject,$moduel,$chapter,$topic,$pt_unique_id,$questioncontr,$filehandle,$utility,$mailer);
	//
	if($result == 1 ){
		echo $utility->general_alert_eval('alert_popup_freshome','Success');
	}else{
		echo $utility->general_alert_eval('alert_warning','Something went wrong');
	}
	die;
}
//MVC:
if(isset($_SESSION['u_admin']) and isset($_POST['admin_reject_work']) ){
	//
	$usercontr = new MVC\usercontr;
	$utility = new MVC\utility;
	$mailer = new PHPMailer(true);
	//
	$editor_unique = $_POST['editor_unique_id'];
	$subject = $_POST['pt_subject'];
	$moduel = $_POST['pt_moduel'];
	$chapter = $_POST['pt_chapter'];
	//
	$result = $usercontr -> admin_reject_work($editor_unique,$subject,$moduel,$chapter,$utility,$mailer);
	//
	if($result == 1 ){
		echo $utility->general_alert_eval('alert_popup_freshome','success');
	}else{
		echo $utility->general_alert_eval('alert_warning','Something went wrong');
	}
	die;
}
//MVC:
if(isset($_SESSION['u_admin']) and isset($_POST['reject_work_question']) ){
	//
	$usercontr = new MVC\usercontr;
	$utility = new MVC\utility;
	$mailer = new PHPMailer(true);
	//
	$editor_unique = $_POST['editor_unique_id'];
	$subject = $_POST['q_subject'];
	$moduel = $_POST['q_moduel'];
	$chapter = $_POST['q_chapter'];
	//
	if(isset($_POST['q_topic'])){
		$topic = $_POST['q_topic'];
	}else{$topic='';}
	//
	if(isset($_POST['pt_unique_id'])){
		$pt_unique_id = $_POST['pt_unique_id'];
	}else{$pt_unique_id='';}
	//
	$result = $usercontr -> reject_work_question($editor_unique,$subject,$moduel,$chapter,$topic,$pt_unique_id,$utility,$mailer);
	//
	if($result == 1 ){
		echo $utility->general_alert_eval('alert_popup_freshome','success');
	}else{
		echo $utility->general_alert_eval('alert_warning','Something went wrong');
	}
	die;
}
//MVC:
if(isset($_SESSION['u_admin']) and isset($_POST['initiate_editor_payout']) ){
	//
	$usercontr = new MVC\usercontr;
	$paypal = new MVC\paypal;
	$utility = new MVC\utility;
	$mailer = new PHPMailer(true);
	//
	$editor_unique = $_POST['editor_unique_id'];
	$subject = $_POST['pt_subject'];
	$moduel = $_POST['pt_moduel'];
	$chapter = $_POST['pt_chapter'];
	//
	$result = $usercontr -> initiate_editor_payout($editor_unique,$subject,$moduel,$chapter,$paypal,$mailer);
	//
	$status = $result['status'];
	$msg = $result['msg'];
	//
	if($status == 1 ){
		echo $utility->general_alert_eval('alert_popup_fresh',$msg);
	}else{
		echo $utility->general_alert_eval('alert_warning',$msg);
	}
	die;
}
//MVC:
if(isset($_SESSION['u_admin']) and isset($_POST['initiate_editor_payout_bnkak']) ){
	//
	$usercontr = new MVC\usercontr;
	$paypal = new MVC\paypal;
	$utility = new MVC\utility;
	$mailer = new PHPMailer(true);
	//
	$editor_unique = $_POST['editor_unique_id'];
	$subject = $_POST['pt_subject'];
	$moduel = $_POST['pt_moduel'];
	$chapter = $_POST['pt_chapter'];
	//
	$result = $usercontr -> initiate_editor_payout_bnkak($editor_unique,$subject,$moduel,$chapter,$paypal,$mailer);
	//
	$status = $result['status'];
	$msg = $result['msg'];
	//
	if($status == 1 ){
		echo $utility->general_alert_eval('alert_popup_fresh',$msg);
	}else{
		echo $utility->general_alert_eval('alert_warning',$msg);
	}
	die;
}
//MVC:
if(isset($_SESSION['u_admin']) and isset($_POST['affiliate_payout']) ){
	//
	$usercontr = new MVC\usercontr;
	$paypal = new MVC\paypal;
	$utility = new MVC\utility;
	$mailer = new PHPMailer(true);
	//
	$result = $usercontr -> affiliate_payout($paypal,$mailer);
	//
	$status = $result['status'];
	$msg = $result['msg'];
	//
	if($status == 1 ){
		echo $utility->general_alert_eval('alert_popup_fresh',$msg);
	}else{
		echo $utility->general_alert_eval('alert_warning',$msg);
	}
	die;
}
//MVC:
if(isset($_SESSION['u_admin']) and isset($_POST['initiate_editor_payout_question']) ){
	//
	$usercontr = new MVC\usercontr;
	$paypal = new MVC\paypal;
	$utility = new MVC\utility;
	$mailer = new PHPMailer(true);
	//
	$editor_unique = $_POST['editor_unique_id'];
	$subject = $_POST['q_subject'];
	$moduel = $_POST['q_moduel'];
	$chapter = $_POST['q_chapter'];
	//
	if(isset($_POST['q_topic'])){
		$topic = $_POST['q_topic'];
	}else{$topic='';}
	//
	if(isset($_POST['q_point'])){
		$pt_unique_id = $_POST['q_point'];
	}else{$pt_unique_id='';}
	//
	$result = $usercontr -> initiate_editor_payout_question($editor_unique,$subject,$moduel,$chapter,$topic,$pt_unique_id,$paypal,$mailer);
	//
	$status = $result['status'];
	$msg = $result['msg'];
	//
	if($status == 1 ){
		echo $utility->general_alert_eval('alert_popup_fresh',$msg);
	}else{
		echo $utility->general_alert_eval('alert_warning',$msg);
	}
	die;
}
//MVC:
if(isset($_SESSION['u_admin']) and isset($_POST['initiate_editor_payout_question_bnkak']) ){
	//
	$usercontr = new MVC\usercontr;
	$utility = new MVC\utility;
	$mailer = new PHPMailer(true);
	//
	$editor_unique = $_POST['editor_unique_id'];
	$subject = $_POST['q_subject'];
	$moduel = $_POST['q_moduel'];
	$chapter = $_POST['q_chapter'];
	//
	if(isset($_POST['q_topic'])){
		$topic = $_POST['q_topic'];
	}else{$topic='';}
	//
	if(isset($_POST['q_point'])){
		$pt_unique_id = $_POST['q_point'];
	}else{$pt_unique_id='';}
	//
	$result = $usercontr -> initiate_editor_payout_question_bnkak($editor_unique,$subject,$moduel,$chapter,$topic,$pt_unique_id,$mailer);
	//
	$status = $result['status'];
	$msg = $result['msg'];
	//
	if($status == 1 ){
		echo $utility->general_alert_eval('alert_popup_fresh',$msg);
	}else{
		echo $utility->general_alert_eval('alert_warning',$msg);
	}
	die;
}
//MVC:
if((isset($_SESSION['u_admin']) or isset($_SESSION['write_perm'])) and isset($_POST['add_new_question']) ){
	//
	$questioncontr = new MVC\questioncontr;
	$pointcontr = new MVC\pointcontr;
	$utility = new MVC\utility;
	$filehandle = new MVC\filehandle;
	//
	$filter = $_POST['filter'];
	$subject = $_POST['q_subject'];
	$moduel = $_POST['q_moduel'];
	$chapter = $_POST['q_chapter'];
	//
	if(isset($_POST['q_topic'])){
		$topic = $_POST['q_topic'];
	}else{$topic='';}
	//
	if(isset($_POST['pt_unique_id'])){
		$pt_unique_id = $_POST['pt_unique_id'];
	}else{$pt_unique_id='';}
	//
	$result = $questioncontr -> add_new_question($filter,$subject,$moduel,$chapter,$topic,$pt_unique_id,$pointcontr,$utility,$filehandle);
	//
	if($result == 1 ){
		echo $utility->general_alert_eval('alert_popup_fresh','Success');
	}else{
		echo $utility->general_alert_eval('alert_warning','Something went wrong');
	}
	die;
}
//MVC:
if(isset($_SESSION['u_editor']) and isset($_POST['add_new_question_editor']) ){
	//
	$questioncontr = new MVC\questioncontr;
	$pointcontr = new MVC\pointcontr;
	$utility = new MVC\utility;
	$filehandle = new MVC\filehandle;
	//
	$filter = $_POST['filter'];
	$subject = $_POST['q_subject'];
	$moduel = $_POST['q_moduel'];
	$chapter = $_POST['q_chapter'];
	//
	if(isset($_POST['q_topic'])){
		$topic = $_POST['q_topic'];
	}else{$topic='';}
	//
	if(isset($_POST['pt_unique_id'])){
		$pt_unique_id = $_POST['pt_unique_id'];
	}else{$pt_unique_id='';}
	//
	$result = $questioncontr -> add_new_question_editor($filter,$subject,$moduel,$chapter,$topic,$pt_unique_id,$pointcontr,$utility,$filehandle);
	//
	if($result == 1 ){
		echo $utility->general_alert_eval('alert_popup_fresh','Success');
	}else{
		echo $utility->general_alert_eval('alert_warning','Something went wrong');
	}
	die;
}
//MVC:
if(isset($_SESSION['u_admin']) and isset($_POST['assign_editor_question']) ){
	//
	$questioncontr = new MVC\questioncontr;
	$pointcontr = new MVC\pointcontr;
	$utility = new MVC\utility;
	$filehandle = new MVC\filehandle;
	//
	$filter = $_POST['filter'];
	$subject = $_POST['q_subject'];
	$moduel = $_POST['q_moduel'];
	$chapter = $_POST['q_chapter'];
	$task_payment_amount = $_POST['task_payment_amount'];
	//
	if(isset($_POST['q_topic'])){
		$topic = $_POST['q_topic'];
	}else{$topic='';}
	//
	if(isset($_POST['pt_unique_id'])){
		$pt_unique_id = $_POST['pt_unique_id'];
	}else{$pt_unique_id='';}
	//
	$result = $questioncontr -> assign_editor_question($filter,$subject,$moduel,$chapter,$topic,$pt_unique_id,$task_payment_amount,$pointcontr);
	//
	if($result == 1 ){
		echo $utility->general_alert_eval('alert_popup_fresh','Success');
	}else{
		echo $utility->general_alert_eval('alert_warning','Something went wrong');
	}
	die;
}
//MVC:
if((isset($_SESSION['u_admin']) or isset($_SESSION['u_editor']))and isset($_POST['update_question_info']) ){
	//
	$questioncontr = new MVC\questioncontr;
	$utility = new MVC\utility;
	$filehandle = new MVC\filehandle;
	$securityhandle = new MVC\securityhandle;
	//
	$q_unique_id = $_POST['q_unique_id'];
	$new_input = $_POST['new_input'];
	//
	$result = $questioncontr -> update_question_info($q_unique_id,$new_input,$filehandle,$securityhandle);
	//
	if($result == 1 ){
		echo $utility->general_alert_eval('alert_popup_fresh','Success');
	}else{
		echo $utility->general_alert_eval('alert_warning','Something went wrong');
	}
	die;
}
//MVC: publish moduels to the users
if(isset($_SESSION['u_admin']) and isset($_POST['publish_moduel'])){
	//
	$pointcontr = new MVC\pointcontr;
	//
	$subject = $_POST['pt_subject'];
	$moduel = $_POST['pt_moduel'];
	//
	$result = $pointcontr->publish_moduel($subject,$moduel);
	//
	if($result ==1){
		echo $utility->general_alert_eval('alert_popup_fresh','success');
	}else{
		echo $utility->general_alert_eval('alert_warning','failure');
	}
	die;
	
}
//MVC: un_poublish moduels to the users
if(isset($_SESSION['u_admin']) and isset($_POST['unpublish_moduel'])){
	//
	$pointcontr = new MVC\pointcontr;
	//
	$subject = $_POST['pt_subject'];
	$moduel = $_POST['pt_moduel'];
	//
	$result = $pointcontr->unpublish_moduel($subject,$moduel);
	//
	if($result ==1){
		echo $utility->general_alert_eval('alert_popup_fresh','success');
	}else{
		echo $utility->general_alert_eval('alert_warning','failure');
	}
	die;
	
}	
//MVC: un_poublish moduels to the users
if(isset($_SESSION['u_admin']) and isset($_POST['delete_cache'])){
	//
	$filehandle = new MVC\filehandle;
	$utility = new MVC\utility;
	//
	$result = $filehandle->delete_cache_html($utility);
	//
	if($result ==1){
		echo $utility->general_alert_eval('alert_popup_fresh','success');
	}else{
		echo $utility->general_alert_eval('alert_warning','failure');
	}
	die;
	
}
//MVC:
if(isset($_SESSION['u_editor']) and isset($_POST['update_question_info_editor']) ){
	//
	$questioncontr = new MVC\questioncontr;
	$utility = new MVC\utility;
	$filehandle = new MVC\filehandle;
	$securityhandle = new MVC\securityhandle;
	//
	$q_unique_id = $_POST['q_unique_id'];
	$new_input = $_POST['new_input'];
	//
	$result = $questioncontr -> update_question_info_editor($q_unique_id,$new_input,$filehandle,$securityhandle);
	//
	if($result == 1 ){
		echo $utility->general_alert_eval('alert_popup_fresh','Success');
	}else{
		echo $utility->general_alert_eval('alert_warning','Something went wrong');
	}
	die;
}
//MVC:
if((isset($_SESSION['u_admin']) or isset($_SESSION['write_perm'])) and isset($_POST['delete_question']) ){
	//
	$questioncontr = new MVC\questioncontr;
	$utility = new MVC\utility;
	$filehandle = new MVC\filehandle;
	//
	$q_unique_id = $_POST['q_unique_id'];
	//
	$result = $questioncontr -> delete_question($q_unique_id,$filehandle);
	//
	if($result == 1 ){
		echo $utility->general_alert_eval('alert_popup_fresh','Success');
	}else{
		echo $utility->general_alert_eval('alert_warning','Something went wrong');
	}
	die;
}
//MVC:
if(isset($_POST['locate_unique_question']) ){
	//
	$questioncontr = new MVC\questioncontr;
	$pointcontr = new MVC\pointcontr;
	$utility = new MVC\utility;
	//
	$q_unique_id = $_POST['q_unique_id'];
	//
	$result = $questioncontr -> locate_unique_question($q_unique_id,$pointcontr);
	//
	if($result !== 0 ){
		echo $utility->general_alert_eval('new_headder_redirect',$result);
	}else{
		echo $utility->general_alert_eval('alert_warning','Something went wrong');
	}
	die;
}
//MVC:
if(isset($_SESSION['u_admin']) and isset($_POST['alert_editors_for_work']) ){
	//
	$usercontr = new MVC\usercontr;
	$utility = new MVC\utility;
	$filehandle = new MVC\filehandle;
	$mailer = new PHPMailer(true);
	//
	$result = $usercontr -> alert_editors_for_work($utility,$mailer);
	//
	if($result == 1 ){
		echo $utility->general_alert_eval('alert_popup_fresh','Success');
	}else{
		echo $utility->general_alert_eval('alert_warning','Something went wrong');
	}
	die;
}
//MVC:
if(isset($_SESSION['u_editor']) and isset($_POST['delete_question_editor']) ){
	//
	$questioncontr = new MVC\questioncontr;
	$utility = new MVC\utility;
	$filehandle = new MVC\filehandle;
	//
	$q_unique_id = $_POST['q_unique_id'];
	//
	$result = $questioncontr -> delete_question_editor($q_unique_id,$filehandle);
	//
	if($result == 1 ){
		echo $utility->general_alert_eval('alert_popup_fresh','Success');
	}else{
		echo $utility->general_alert_eval('alert_warning','Something went wrong');
	}
	die;
}
//MVC:
if(isset($_SESSION['u_id']) and isset($_POST['editor_paypalemail_input']) ){
	//now works for any user not just editors
	$usercontr = new MVC\usercontr;
	$utility = new MVC\utility;
	$paypal = new MVC\paypal;
	//
	$editor_paypal_email = $_POST['editor_paypalemail_input'];
	$u_id = $_SESSION['u_id'];
	$editor_unique = $_SESSION['user_unique_id'];
	//
	$result = $usercontr -> editor_paypalemail_input($u_id,$editor_unique,$editor_paypal_email,$paypal);
	//
	if($result == 1 ){
		echo $utility->general_alert_eval('alert_popup_fresh','Success');
	}else{
		echo $utility->general_alert_eval('alert_warning','Something went wrong');
	}
	die;
}
//MVC:
if(isset($_SESSION['u_id']) and isset($_POST['editor_bnkak_input']) ){
	//now works for any user not just editors
	$usercontr = new MVC\usercontr;
	$utility = new MVC\utility;
	$paypal = new MVC\paypal;
	//
	$editor_bnkak= $_POST['editor_bnkak_input'];
	$u_id = $_SESSION['u_id'];
	$editor_unique = $_SESSION['user_unique_id'];
	//
	$result = $usercontr -> editor_bnkak_input($u_id,$editor_unique,$editor_bnkak);
	//
	if($result == 1 ){
		echo $utility->general_alert_eval('alert_popup_fresh','Success');
	}else{
		echo $utility->general_alert_eval('alert_warning','Something went wrong');
	}
	die;
}
//MVC:
if(isset($_SESSION['u_id']) and isset($_POST['editor_paypalverification_input']) ){
	//
	$usercontr = new MVC\usercontr;
	$utility = new MVC\utility;
	//
	$editor_paypalverification = $_POST['editor_paypalverification_input'];
	$u_id = $_SESSION['u_id'];
	$editor_unique = $_SESSION['user_unique_id'];
	//
	$result = $usercontr -> editor_paypalverification_input($u_id,$editor_unique,$editor_paypalverification);
	//
	if($result == 1 ){
		echo $utility->general_alert_eval('alert_popup_fresh','Success');
	}else{
		echo $utility->general_alert_eval('alert_warning','Something went wrong');
	}
	die;
}
//MVC:
if(isset($_POST['question_page_redirect']) and isset($_POST['q_filter_type'])){
	//
	$questionview = new MVC\questionview;
	$pointcontr = new MVC\pointcontr;
	$utility = new MVC\utility;
	//
	$filter_type = $_POST['q_filter_type'];
	$pt_unique_id = $_POST['pt_unique_id'];
	//
	$result = $questionview ->question_page_redirect($filter_type,$pt_unique_id,$pointcontr);
	//
	$status = $result['status'];
	$msg = $result['msg'];
	//
	if($status == 1 ){
		echo $utility->general_alert_eval('headder_redirect',$msg);
	}else{
		echo $utility->general_alert_eval('alert_warning',$msg);
	}
	die;
}
//MVC:
if(isset($_POST['editor_contract_application']) and isset($_SESSION['user_unique_id'])){
	//
	$usercontr = new MVC\usercontr;
	$filehandle = new MVC\filehandle;
	$utility = new MVC\utility;
	$mailer = new PHPMailer(true);
	//
	$editor_unique_id = $_SESSION['user_unique_id'];
	$signature_confirmation = $_POST['signature_confirmation'];
	//
	$result = $usercontr ->process_editor_contract($editor_unique_id,$signature_confirmation,$utility,$mailer);
	//
	$status = $result['status'];
	$msg = $result['msg'];
	//
	if($status == 1 ){
		echo $utility->general_alert_eval('alert_popup_freshome',$msg);
	}else{
		echo $utility->general_alert_eval('alert_warning',$msg);
	}
	die;
}
//MVC:
if(isset($_POST['initiate_membership_payment']) and isset($_SESSION['user_unique_id'])){
	//
	$usercontr = new MVC\usercontr;
	$paypal = new MVC\paypal;
	$utility = new MVC\utility;
	//
	$option = $_POST['membership_option'];
	//
	$result = $usercontr-> pay_for_membership($paypal,$option);
	//
	$status = $result['status'];
	$msg = $result['msg'];
	//
	if($status == 1 ){
		echo $utility->general_alert_eval('headder_redirect',$msg);
	}else{
		echo $utility->general_alert_eval('alert_warning',$msg);
	}
	die;
}
//MVC:
if(isset($_POST['finalise_membership_payment']) and isset($_SESSION['user_unique_id'])){
	//
	$usercontr = new MVC\usercontr;
	$paypal = new MVC\paypal;
	$utility = new MVC\utility;
	//
	$payer_id = $_POST['paypal_payer_id'];
	$token = $_POST['paypal_token'];
	$hash = $_SESSION['PP_hash'];
	//
	$result = $usercontr-> pay_for_membership_approval($payer_id,$token,$hash,$paypal);
	//
	if($result == 1 ){
		echo $utility->general_alert_eval('alert_popup_fresh','Success: You are now a memeber');
	}else{
		echo $utility->general_alert_eval('alert_warning','Something went wrong: Transaction failed');
	}
	die;
}
//MVC:
if(isset($_POST['apply_discount_code']) and isset($_SESSION['u_id'])){
	//
	$utility = new MVC\utility;
	$usercontr = new MVC\usercontr;
	//
	$affiliates = $usercontr -> get_affiliates();
	//
	$code =$_POST['discount_code'];
	//
	if(in_array($code,$affiliates)){
		$_SESSION['membership_discount'] = $usercontr -> affiliate_discount;
		$_SESSION['discount_affiliate'] = $code;
		$result = 1;
	}else{
		$result = 0;
	}
	//
	if($result == 1 ){
		echo $utility->general_alert_eval('alert_popup_fresh','Success');
	}else{
		echo $utility->general_alert_eval('alert_warning','Invalid code');
	}
	die;
}
//MVC:
if(isset($_POST['cancel_membership_payment']) and isset($_SESSION['user_unique_id'])){
	//
	$utility = new MVC\utility;
	//
	unset($_SESSION['PP_hash']);
	unset($_SESSION['bought_credits']);
	//
	echo $utility->general_alert_eval('headder_redirect','http://practicepractice.net/main/checkout?payment_cancelled=1');
}
//MVC:
if(isset($_POST['editor_claimtask']) and isset($_SESSION['u_editor'])){
	//
	$usercontr = new MVC\usercontr;
	$filehandle = new MVC\filehandle;
	$utility = new MVC\utility;
	//
	$editor_unique = $_POST['editor_unique_id'];
	$subject = $_POST['pt_subject'];
	$moduel = $_POST['pt_moduel'];
	$chapter = $_POST['pt_chapter'];
	//
	$result = $usercontr -> editor_claimtask($editor_unique,$subject,$moduel,$chapter);
	//
	if($result == 1 ){
		echo $utility->general_alert_eval('alert_popup_fresh','success');
	}else{
		echo $utility->general_alert_eval('alert_warning','Cannot claim task');
	}
	die;
}
//MVC:
if(isset($_POST['editor_claimtask_question']) and isset($_SESSION['u_editor'])){
	//
	$usercontr = new MVC\usercontr;
	$filehandle = new MVC\filehandle;
	$utility = new MVC\utility;
	//
	$editor_unique = $_POST['editor_unique_id'];
	$subject = $_POST['q_subject'];
	$moduel = $_POST['q_moduel'];
	$chapter = $_POST['q_chapter'];
	//
	if(isset($_POST['q_topic'])){
		$topic = $_POST['q_topic'];
	}else{$topic='';}
	//
	if(isset($_POST['q_point'])){
		$q_point = $_POST['q_point'];
	}else{$q_point='';}
	//
	$result = $usercontr -> editor_claimtask_question($editor_unique,$subject,$moduel,$chapter,$topic,$q_point);
	//
	if($result == 1 ){
		echo $utility->general_alert_eval('alert_popup_fresh','success');
	}else{
		echo $utility->general_alert_eval('alert_warning','Cannot claim task');
	}
	die;
}
//MVC:
if(isset($_POST['editor_droptask']) and isset($_SESSION['u_editor'])){
	//
	$usercontr = new MVC\usercontr;
	$pointcontr = new MVC\pointcontr;
	$filehandle = new MVC\filehandle;
	$utility = new MVC\utility;
	//
	$editor_unique = $_POST['editor_unique_id'];
	$subject = $_POST['pt_subject'];
	$moduel = $_POST['pt_moduel'];
	$chapter = $_POST['pt_chapter'];
	//
	$result = $usercontr -> editor_droptask($editor_unique,$subject,$moduel,$chapter,$pointcontr,$filehandle,$utility);
	//
	if($result == 1 ){
		echo $utility->general_alert_eval('alert_popup_fresh','success');
	}else{
		echo $utility->general_alert_eval('alert_warning','Something went wrong');
	}
	die;
}
//MVC:
if(isset($_POST['editor_droptask_question']) and isset($_SESSION['u_editor'])){
	//
	$usercontr = new MVC\usercontr;
	$questioncontr = new MVC\questioncontr;
	$filehandle = new MVC\filehandle;
	$utility = new MVC\utility;
	//
	$editor_unique = $_POST['editor_unique_id'];
	$subject = $_POST['q_subject'];
	$moduel = $_POST['q_moduel'];
	$chapter = $_POST['q_chapter'];
	//
	if(isset($_POST['q_topic'])){
		$topic = $_POST['q_topic'];
	}else{$topic='';}
	//
	if(isset($_POST['q_point'])){
		$q_point = $_POST['q_point'];
	}else{$q_point='';}
	//
	$result = $usercontr -> editor_droptask_question($editor_unique,$subject,$moduel,$chapter,$topic,$q_point,$questioncontr,$filehandle,$utility);
	//
	if($result == 1 ){
		echo $utility->general_alert_eval('alert_popup_fresh','success');
	}else{
		echo $utility->general_alert_eval('alert_warning','Something went wrong');
	}
	die;
}
//MVC:
if(isset($_POST['editor_cancelself_contract']) and isset($_POST['user_unique_id'])){
	//
	$usercontr = new MVC\usercontr;
	$filehandle = new MVC\filehandle;
	$utility = new MVC\utility;
	$mailer = new PHPMailer(true);
	//
	$editor_unique = $_POST['user_unique_id'];
	//
	$result = $usercontr -> editor_cancelself_contract($editor_unique,$utility,$mailer);
	//
	$status = $result['status'];
	$msg = $result['msg'];
	//
	if($status == 1 ){
		echo $utility->general_alert_eval('headder_redirect',$msg);
	}else{
		echo $utility->general_alert_eval('alert_warning',$msg);
	}
	die;
}
//MVC:
if(isset($_POST['letEditor_work']) and isset($_SESSION['u_editor'])){
	//
	$usercontr = new MVC\usercontr;
	$pointcontr = new MVC\pointcontr;
	$filehandle = new MVC\filehandle;
	$utility = new MVC\utility;
	//
	$editor_unique = $_POST['editor_unique_id'];
	$subject = $_POST['pt_subject'];
	$moduel = $_POST['pt_moduel'];
	$chapter = $_POST['pt_chapter'];
	//
	$result = $usercontr -> LetEditor_work($editor_unique,$subject,$moduel,$chapter,$pointcontr,$utility,$filehandle);
	//
	$status = $result['status'];
	$msg = $result['msg'];
	//
	if($status == 1 ){
		echo $utility->general_alert_eval('headder_redirect',$msg);
	}else{
		echo $utility->general_alert_eval('alert_warning',$msg);
	}
	die;
}
//MVC:
if(isset($_POST['letEditor_work_question']) and isset($_SESSION['u_editor'])){
	//
	$usercontr = new MVC\usercontr;
	$pointcontr = new MVC\pointcontr;
	$questioncontr = new MVC\questioncontr;
	$filehandle = new MVC\filehandle;
	$utility = new MVC\utility;
	//
	$editor_unique = $_POST['editor_unique_id'];
	$subject = $_POST['q_subject'];
	$moduel = $_POST['q_moduel'];
	$chapter = $_POST['q_chapter'];
	//
	if(isset($_POST['q_topic'])){
		$topic = $_POST['q_topic'];
	}else{$topic='';}
	//
	if(isset($_POST['q_point'])){
		$q_point = $_POST['q_point'];
	}else{$q_point='';}
	//
	$result = $usercontr -> letEditor_work_question($editor_unique,$subject,$moduel,$chapter,$topic,$q_point,$questioncontr,$pointcontr,$utility,$filehandle);
	//
	$status = $result['status'];
	$msg = $result['msg'];
	//
	if($status == 1 ){
		echo $utility->general_alert_eval('headder_redirect',$msg);
	}else{
		echo $utility->general_alert_eval('alert_warning',$msg);
	}
	die;
}
//MVC:
if(isset($_POST['editor_submit_work']) and isset($_SESSION['u_editor'])){
	//
	$usercontr = new MVC\usercontr;
	$utility = new MVC\utility;
	//
	$editor_unique = $_POST['editor_unique_id'];
	$subject = $_POST['pt_subject'];
	$moduel = $_POST['pt_moduel'];
	$chapter = $_POST['pt_chapter'];
	//
	$result = $usercontr -> editor_submit_work($editor_unique,$subject,$moduel,$chapter);
	//
	if($status == 1 ){
		echo $utility->general_alert_eval('alert_popup_freshome','success');
	}else{
		echo $utility->general_alert_eval('alert_warning','Something went wrong');
	}
	die;
}
//MVC:
if(isset($_POST['submit_work_question']) and isset($_SESSION['u_editor'])){
	//
	$usercontr = new MVC\usercontr;
	$utility = new MVC\utility;
	//
	$subject = $_POST['q_subject'];
	$moduel = $_POST['q_moduel'];
	$chapter = $_POST['q_chapter'];
		//
	if(isset($_POST['q_topic'])){
		$topic = $_POST['q_topic'];
	}else{$topic='';}
	//
	if(isset($_POST['q_point'])){
		$q_point = $_POST['q_point'];
	}else{$q_point='';}
	
	//
	$result = $usercontr -> submit_work_question($subject,$moduel,$chapter,$topic,$q_point);
	//
	if($status == 1 ){
		echo $utility->general_alert_eval('alert_popup_freshome','success');
	}else{
		echo $utility->general_alert_eval('alert_warning','Something went wrong');
	}
	die;
}
//MVC:
if(isset($_POST['user_login']) and isset($_POST['uid'])  and isset($_POST['pwd']) ){
	//initiation
	$utility = new MVC\utility;
	$usercontr = new MVC\usercontr;
	//
	$uid = $_POST['uid'];
	$pwd = $_POST['pwd'];
	//result = array(status => 0 or 1  , msg  => 'string');
	$result = $usercontr->user_login($uid,$pwd);
	//
	$status = $result['status'];
	$msg = $result['msg'];
	//
	if($status == 1 ){
		echo $utility->general_alert_eval('alert_popup_freshome',$msg);
	}else{
		echo $utility->general_alert_eval('alert_warning',$msg);
	}
	die;
	
}
//MVC:
if(isset($_POST['user_logoff'])){
	//initiation
	$utility = new MVC\utility;
	$usercontr = new MVC\usercontr;
	//
	$result = $usercontr->user_logoff();
	//
	if($result == 1 ){
		echo $utility->general_alert_eval('alert_popup_freshome','Logged off');
	}else{
		echo $utility->general_alert_eval('alert_warning','Something went wrong');
	}
	die;
}
//MVC:
if(isset($_POST['user_recover_password']) and isset($_POST['email'])){
	//
	$usercontr = new MVC\usercontr();
	$utility = new MVC\utility;
	$mailer = new PHPMailer(true);
	//
	$email = $_POST['email'];
	//
	$result = $usercontr ->password_recovery($email,$utility,$mailer);
	//
	$status = $result['status'];
	$msg = $result['msg'];
	//
	if($status == 1 ){
		echo $utility->general_alert_eval('alert_popup_freshome',$msg);
	}else{
		echo $utility->general_alert_eval('alert_warning',$msg);
	}
	die;
	
}
//MVC:
if(isset($_POST['apply_new_pwd']) and isset($_POST['vkey']) and isset($_POST['pwd1']) and isset($_POST['pwd2']) ){
	//
	$usercontr = new MVC\usercontr();
	$utility = new MVC\utility;
	//
	$vkey = $_POST['vkey'];
	$pwd1 = $_POST['pwd1'];
	$pwd2 = $_POST['pwd2'];
	//
	$result = $usercontr->update_to_new_pwd($vkey,$pwd1,$pwd2);
	//
	$status = $result['status'];
	$msg = $result['msg'];
	//
	if($status == 1 ){
		echo $utility->general_alert_eval('alert_popup_freshome',$msg);
	}else{
		echo $utility->general_alert_eval('alert_warning',$msg);
	}
	die;
}
//MVC:
if(isset($_POST['verify_newuser']) and isset($_POST['verification_key'])){
	//
	$usercontr = new MVC\usercontr;
	$utility = new MVC\utility;
	//
	$key = $_POST['verification_key'];
	//
	$result = $usercontr->usert_verification($key);
	//
	if($result == 1 ){
		echo $utility->general_alert_eval('alert_popup_freshome','sucess, you can now login.');
	}else{
		echo $utility->general_alert_eval('alert_warning','Something went wrong, your account cannot be verified.');
	}
	die;
}
//MVC; user_newsignup
if(isset($_POST['user_newsignup'])){
	//
	$usercontr = new MVC\usercontr;
	$utility = new MVC\utility;
	$mailer = new PHPMailer(true);
	//form input variables 
	$uid =  $_POST['username'];
	$first = $_POST['firstname'];
	$last =  $_POST['lastname'];
	$email =  $_POST['email1'];
	$re_email =  $_POST['email2'];
	$pwd =  $_POST['pwd1'];
	$re_pwd =  $_POST['pwd2'];
	$u_vkey = md5(time().$first);
	//
	$result = $usercontr->new_user_signup($uid,$first,$last,$email,$re_email,$pwd,$re_pwd,$u_vkey,$utility,$mailer);
	//
	$status = $result['status'];
	$msg = $result['msg'];
	//
	if($status == 1 ){
		echo $utility->general_alert_eval('alert_popup_info_fresh',$msg);
	}else{
		echo $utility->general_alert_eval('alert_warning',$msg);
	}
	die;
}
//MVC:
if(isset($_POST['user_unique']) and isset($_POST['setting_q_show'])){
	//
	$usercontr = new MVC\usercontr();
	$utility = new MVC\utility();
	//
	$new_status = $_POST['setting_q_show'];
	$user_unique = $_POST['user_unique'];
	//
	$result = $usercontr-> db_setting_attempted_update($user_unique,$new_status);
	//
	if ($result == 1){
		echo $utility->general_alert_eval('alert_popup_info_fresh','You WILL see practiced questions.');
	}else{
		echo $utility->general_alert_eval('alert_popup_info_fresh','You WILL NOT see practiced questions.');
	}
	die();
}
//MVC:
if(isset($_POST['delete_mylist_items'])){
	$usercontr = new MVC\usercontr();
	$utility = new MVC\utility;
	//
	$result = $usercontr->setting_delete_mylist_file();
	//
	if($result ==1){
		echo  $utility->general_alert_eval('alert_popup','Success: Your spec list was deleted, start another one !');
	}else{
		echo $utility->general_alert_eval('alert_warning','Cannot clear history: No history is present.');
	}
	die;
}
//MVC:
if(isset($_POST['add_newSpec_moduel']) and isset($_POST['subject'])){
	$pointcontr= new MVC\pointcontr;
	$filehandle= new MVC\filehandle;
	$utility= new MVC\utility;
	//
	$subject = $_POST['subject'];
	$moduel = $_POST['moduel'];
	//
	$result= $pointcontr->add_moduel($subject,$moduel,$filehandle,$utility);
	//
	
	if($result ==1){
		echo $utility->general_alert_eval('alert_popup_fresh','success');
	}else{
		echo $utility->general_alert_eval('alert_warning','failure');
	}
	die;
}
//MVC:
if(isset($_POST['add_newSpec_chapter']) and isset($_POST['subject'])){
	$pointcontr= new MVC\pointcontr;
	$filehandle= new MVC\filehandle;
	$utility= new MVC\utility;
	//
	$subject = $_POST['subject'];
	$moduel = $_POST['moduel'];
	$chapter = $_POST['chapter'];
	//
	$result = $pointcontr->add_chapter($subject,$moduel,$chapter,$filehandle,$utility);
	//
	if($result ==1){
		echo $utility->general_alert_eval('alert_popup_fresh','success');
	}else{
		echo $utility->general_alert_eval('alert_warning','failure');
	}
	die;
	
	
	
	
}
//MVC: 
if((isset($_SESSION['u_admin']) or (isset($_POST['editor_updates']) and isset($_SESSION['u_editor']))) and isset($_POST['add_newSpec_topic']) and isset($_POST['subject'])){
	$pointcontr= new MVC\pointcontr;
	$filehandle= new MVC\filehandle;
	$utility= new MVC\utility;
	//
	$subject = $_POST['subject'];
	$moduel = $_POST['moduel'];
	$chapter = $_POST['chapter'];
	$topic = $_POST['topic'];
	//
	if(isset($_POST['editor_updates'])){
		$editor_unique = $_SESSION['user_unique_id'];
		$pointcontr->db_table = ' points_editors ';
		$pointcontr->info_src = "users/$editor_unique/specifications";
	}
	//
	$result = $pointcontr->add_topic($subject,$moduel,$chapter,$topic,$filehandle,$utility);
	//
	if($result ==1){
		echo $utility->general_alert_eval('alert_popup_fresh','success');
	}else{
		echo $utility->general_alert_eval('alert_warning','failure');
	}
	die;	
}
//MVC:
if(isset($_POST['update_moduel_name']) and !empty($_POST['new_input'])){
	//
	$pointcontr = new MVC\pointcontr;
	$filehandle= new MVC\filehandle;
	$utility= new MVC\utility;
	//
	$new = $_POST['new_input'];
	$new = ucfirst(strtolower($new));
	$subject = $_POST['subject'];
	$moduel = $_POST['moduel'];
	$chapter = $_POST['chapter'];
	//
	$result = $pointcontr->update_moduel_name($subject,$moduel,$new,$chapter,$filehandle,$utility);
	//
	$status = $result['status'];
	$msg = $result['msg'];
	//
	if($status == 1 ){
		echo $utility->general_alert_eval('headder_redirect',$msg);
	}else{
		echo $utility->general_alert_eval('alert_warning',$msg);
	}
	die;
	
	
}
//MVC: 
if(isset($_POST['update_chapter_name']) and !empty($_POST['new_input'])){
	//
	$pointcontr = new MVC\pointcontr;
	$filehandle= new MVC\filehandle;
	$utility= new MVC\utility;
	//
	$subject = $_POST['subject'];
	$moduel = $_POST['moduel'];
	$chapter = $_POST['chapter'];
	$new = $_POST['new_input'];
	$new = ucfirst(strtolower($new));
	//
	$result = $pointcontr->update_chapter_name($subject,$moduel,$chapter,$new,$filehandle,$utility);
	//
	$status = $result['status'];
	$msg = $result['msg'];
	//
	if($status == 1 ){
		echo $utility->general_alert_eval('headder_redirect',$msg);
	}else{
		echo $utility->general_alert_eval('alert_warning',$msg);
	}
	die;
	
	
}
//MVC:
if((isset($_SESSION['u_admin']) or (isset($_POST['editor_updates']) and isset($_SESSION['u_editor']))) and isset($_POST['update_topic_name']) and !empty($_POST['new_input'])){
	//
	$pointcontr = new MVC\pointcontr;
	$filehandle= new MVC\filehandle;
	$utility= new MVC\utility;
	//
	$subject = $_POST['subject'];
	$moduel = $_POST['moduel'];
	$chapter = $_POST['chapter'];
	$topic = $_POST['topic'];
	$new = $_POST['new_input'];
	$new = ucfirst(strtolower($new));
	//
	if(isset($_POST['editor_updates'])){
		$editor_unique = $_SESSION['user_unique_id'];
		$pointcontr->db_table = ' points_editors ';
		$pointcontr->info_src = "users/$editor_unique/specifications";
	}
	//
	$result = $pointcontr->update_topic_name($subject,$moduel,$chapter,$topic,$new,$filehandle,$utility);
	//
	if($result ==1){
		echo $utility->general_alert_eval('alert_popup_fresh','success');
	}else{
		echo $utility->general_alert_eval('alert_warning','failure');
	}
	die;
	
}
//MVC: 
if(isset($_POST['update_topicField_selection'])){
	$pointcontr = new MVC\pointcontr;
	//
	if(isset($_POST['subject']) and !empty($_POST['subject']) and isset($_POST['moduel']) and !empty($_POST['moduel'])){
		$subject = $_POST['subject'];
		$moduel = $_POST['moduel'];
		//
		$out = $pointcontr->update_topicField_selection($subject,$moduel);
		echo $out;
		die;
	}
}
//MVC: 
if(isset($_POST['update_topicField_selection_question'])){
	$questioncontr = new MVC\questioncontr;
	$usercontr = new MVC\usercontr;
	$utility = new MVC\utility;
	//
	if(isset($_POST['subject']) and !empty($_POST['subject']) and isset($_POST['moduel']) and !empty($_POST['moduel'])){
		$subject = $_POST['subject'];
		$moduel = $_POST['moduel'];
		//
		$out = $questioncontr->update_topicField_selection_question($subject,$moduel,$utility,$usercontr);
		echo $out;
		die;
	}
	
	
	
}
//MVC: resetting the user password
if(isset($_POST['reset_userPassword'])){
	$usercontr = new MVC\usercontr();
	$utility = new MVC\utility;
	//
	$current = $_POST['current'];
	$new = $_POST['new'];
	$new2 = $_POST['new2'];
	//
	$result =  $usercontr->reset_userPassword($current,$new,$new2);
	//
	if($result ==1){
		echo $utility->general_alert_eval('alert_popup_fresh','success');
	}else{
		echo $utility->general_alert_eval('alert_warning','failure');
	}
	die;
}
//MVC:filter the spec page by the unique id
if(isset($_POST['user_list_specfilter'])){
	//
	$pointcontr = new MVC\pointcontr;
	$utility = new MVC\utility;
	//
	if(isset($_POST['user_unique']) and !empty($_POST['user_unique']) and isset($_POST['pt_subject']) and !empty($_POST['pt_subject'])){
		$user_unique = $_POST['user_unique'];
		$pt_subject = $_POST['pt_subject'];
		//
		$link = $pointcontr->filter_spec_user_unique_id($pt_subject,$user_unique);
		
		echo $utility->general_redirect($link);
		die;
	}else{
		echo $utility->general_alert_eval('alert_warning','Empty input.');
	}
	
}
//MVC: make the mindmap structure
if(isset($_POST['make_mindmap'])){
	//
	$pointcontr = new MVC\pointcontr;
	$questioncontr = new MVC\questioncontr;
	$usercontr = new MVC\usercontr;
	$filehandle = new MVC\filehandle;
	$utility = new MVC\utility;
	//
	$subject = $_POST['pt_subject'];
	//
	if(isset($_POST['pt_moduel'])){
		$moduel = $_POST['pt_moduel'];
	}else{$moduel='';}
	//
	if(isset($_POST['pt_chapter'])){
		$chapter = $_POST['pt_chapter'];
	}else{$chapter='';}
	//
	$array = $pointcontr->make_mindmap($subject,$moduel,$chapter,$usercontr,$filehandle,$questioncontr,$utility);
	$json = json_encode($array);
	echo $json;
	die;
	
}
//MVC: contact form email
if(isset($_POST['contact_form'])){
	//
	$utility = new MVC\utility;
	$mailer = new PHPMailer(true);
	//
	$name = $_POST['name'];
	$toemail = $_POST['email'];
	$subject = $_POST['con_subject'];
	$message = $_POST['message'];
	//
	$result =  $utility->contact_form($name,$toemail,$subject,$message,$mailer);
	//
	if($result ==1){
		echo $utility->general_alert_eval('alert_popup_fresh','success');
	}else{
		echo $utility->general_alert_eval('alert_warning','failure');
	}
	
	die;
	
}
//MVC: mark question and input into database
if(isset($_POST['mark_question']) and isset($_SESSION['user_unique_id'])){
	//
	$questioncontr = new MVC\questioncontr;
	$utility = new MVC\utility;
	//
	$question_unique_id = $_POST['q_unique_id'];
	$mark = $_POST['mark'];
	$total = $_POST['total_mark'];
	$user_unique_id = $_SESSION['user_unique_id'];
	//
	$result = $questioncontr -> mark_question($user_unique_id,$question_unique_id,$mark,$total);
	//
	if($result ==1){
		echo $utility->general_alert_eval('alert_popup_fresh','success');
	}else{
		echo $utility->general_alert_eval('alert_warning','failure');
	}
	
	die;
}
//MVC: change question visibility setting
if(isset($_POST['toggle_visibility']) and isset($_SESSION['user_unique_id'])){
	//
	$usercontr = new MVC\usercontr;
	$utility = new MVC\utility;
	//
	$user_unique_id = $_SESSION['user_unique_id'];
	//
	$result = $usercontr -> toggle_question_visibility($user_unique_id);
	//
	if($result ==1){
		echo $utility->general_alert_eval('alert_popup_fresh','success');
	}else{
		echo $utility->general_alert_eval('alert_warning','failure');
	}
	
	die;
}
//MVC: change question visibility setting
if(isset($_POST['toggle_level']) and isset($_SESSION['user_unique_id'])){
	//
	$usercontr = new MVC\usercontr;
	$utility = new MVC\utility;
	//
	$user_unique_id = $_SESSION['user_unique_id'];
	//
	$result = $usercontr -> toggle_level($user_unique_id);
	//
	if($result ==1){
		echo $utility->general_alert_eval('alert_popup_fresh','success');
	}else{
		echo $utility->general_alert_eval('alert_warning','failure');
	}
	
	die;
}
//MVC: get user progress in chapter
if(isset($_POST['make_progress_bar']) and isset($_SESSION['u_id'])){
	//
	$questioncontr = new MVC\questioncontr;
	$usercontr = new MVC\usercontr;
	$utility = new MVC\utility;
	//
	if(!isset($_POST['pt_subject'])){
		$subject = '';
	}else{
		$subject = $_POST['pt_subject'];
	}if(!isset($_POST['pt_moduel'])){
		$moduel = '';
	}else{
		$moduel = $_POST['pt_moduel'];
	}if(!isset($_POST['pt_chapter'])){
		$chapter = '';
	}else{
		$chapter = $_POST['pt_chapter'];
	}if(!isset($_POST['pt_topic'])){
		$topic = '';
	}else{
		$topic = $_POST['pt_topic'];
	}
	
	
	
	$user_unique = $usercontr -> user;
	$user_level = $usercontr -> user_level;
	$user_status = $usercontr -> user_status;
	//
	$info = $questioncontr -> get_general_progress($user_unique,$user_level,$user_status,$subject,$moduel,$chapter,$topic,$utility);
	//
	$myJSON = $info;
	echo json_encode($myJSON);
	die;
	
}
//MVC: get 
if(isset($_POST['paper_maker_get_chapters'])){
	$questioncontr = new MVC\questioncontr;
	$usercontr = new MVC\usercontr;
	$utility = new MVC\utility;
	//
	if(isset($_POST['q_subject']) and !empty($_POST['q_subject']) and isset($_POST['q_moduel_array']) and !empty($_POST['q_moduel_array'])){
		$subject = $_POST['q_subject'];
		$moduels =$arr =json_decode(urldecode($_POST['q_moduel_array'])); ;
		//
		$out = $questioncontr->update_question_maker_chapters($subject,$moduels,$utility,$usercontr);
		if($out != 0){
			echo json_encode($out);
		}else{
			echo 0;
		}
		die;
	}else{
		echo 0;
	}
	
	
	
}
//MVC: 
if(isset($_POST['get_paper_papermaker'])){
	$questioncontr = new MVC\questioncontr;
	$usercontr = new MVC\usercontr;
	$utility = new MVC\utility;
	//
	if(isset($_POST['q_subject_array']) and !empty($_POST['q_subject_array'])){
		$q_level= json_decode(urldecode($_POST['q_level_array']));
		$q_subject = json_decode(urldecode($_POST['q_subject_array']));
		$q_moduel = json_decode(urldecode($_POST['q_moduel_array']));
		$q_chapter = json_decode(urldecode($_POST['q_chapter_array']));
		$q_type = json_decode(urldecode($_POST['q_type_array']));
		$q_difficulty = json_decode(urldecode($_POST['q_difficulty_array']));
		$q_is_exam = json_decode(urldecode($_POST['q_is_exam_array']));
		//
		$result = $questioncontr->get_paper_papermaker($q_level,$q_subject,$q_moduel,$q_chapter,$q_type,$q_difficulty,$q_is_exam,$utility,$usercontr);
		
		echo $result;
		die;
	}else{
		echo 0;
	}
	
	
	
}
//MVC: 
if(isset($_POST['organise_paper_input'])){
	$filehandle = new MVC\filehandle;
	$utility = new MVC\utility;
	//
	$paper_name = $_POST['paper_detailed_name'];
	function creator($root,$paper){
			//
			$dir = $root.'/papers_pre/'.$paper.'/';
			if (is_dir($dir)) {
				if ($dh = opendir($dir)) {
					$paper = array();
					while (($file = readdir($dh)) !== false) {
						if($file != '.' and $file != '..' and is_file($dir.$file) ){
							$type = substr($file,0,1);
							$number = substr($file,1,2);
							$paper["$number"][$type][]= $file;
						}
					}
					//
					closedir($dh);
					asort($paper);
					return $paper;
				}else{
					return 0;
				}
			}else{return 0;}
		}
	//
	$paper = creator($root,$paper_name);
	//
	if($paper == 0 or empty($paper) ){
		echo $utility->general_alert_eval('alert_warning','failure');die;
	}
	//specifying question general details
	foreach($paper as $key => $arr){
			//
			$q_unique = $utility -> tag_generator();
			$q_dir = "$root/papers_pre/$paper_name/$q_unique";
			$paper[$key]['q_dir'] = $q_dir;
			$paper[$key]['q_unique'] = $q_unique;
			$paper[$key]['txt_loc'] = "$q_dir/type_question.tag_$q_unique.txt";
			$paper[$key]['files_dir'] = "$q_dir/files";
			$paper[$key]['q_origin'] = "$paper_name".'_'."$key";
			$origin = $paper[$key]['q_origin'];
			$level = substr($origin,0,strpos($origin,'_'));
			$q_origin = str_replace('_','-', $origin);
			$paper[$key]['q_level'] = $level;
			$paper[$key]['q_head'] = 
"
<my_q_head q_level='$level' q_origin='$q_origin' q_type='unknown' q_difficulty='1'> </my_q_head>

<my_question part='head'></my_question>
";
			//
			asort($arr['q']);
			$paper[$key]['myquestion'] =
"

<my_question part='x' part_mark='00'>

";
			foreach($arr['q'] as $k => $value){
				//
				$html = 
"

<my_img name='$value' style='width:100%;max-width:100%;' class='past_paper_img'></my_img>

";
				$paper["$key"]['myquestion'] .= $html;
			}
			//
			$paper["$key"]['myquestion'] .= '</my_question>';
			//
			asort($arr['a']);
			$paper[$key]['myanswer'] ="<my_answer part ='x'>";
			foreach($arr['a'] as $k => $value){
				//
				$html = 
"

<my_img name='$value'  style='width:100%;max-width:100%;' class='past_paper_img'></my_img>

";
				$paper["$key"]['myanswer'] .= $html;
			}
			$paper["$key"]['myanswer'] .= '</my_answer>';
			//
			
			
		}
	//
	foreach($paper as $key => $arr){
		//
		if(!is_dir($arr['q_dir'])){
			$dir = $arr['q_dir'];
			mkdir($dir);
			if(!is_dir($arr['files_dir'])){
				$files_dir = $arr['files_dir'];
				mkdir($files_dir);
			}
			$file_loc = $arr['txt_loc'];
			if(!is_file($file_loc)){
				$txt = fopen("$file_loc", "a");
				fwrite($txt, $arr['q_head']);
				fwrite($txt, $arr['myquestion']);
				fwrite($txt, $arr['myanswer']);
				fclose($txt);
			}
		}
		//
		foreach($arr['q'] as $key => $question){
			//
			$link = $root.'/papers_pre/'.$paper_name.'/'.$question;
			$dest = $arr['files_dir']."/$question";
			//
			rename("$link", "$dest");
		}
		//
		foreach($arr['a'] as $key => $answer){
			//
			$link = $root.'/papers_pre/'.$paper_name.'/'.$answer;
			$dest = $arr['files_dir']."/$answer";
			//
			rename("$link", "$dest");
		}
	}
	//Place the questions in the working dir
	$dir_destination = $root."/specifications/universal/A_Maths/B_25_eidtor_tutorial/C_02_question/questions";
	if(!is_dir($dir_destination)){
		mkdir($dir_destination,0755, true);
	}
	//
	
	//
	$dir = $root."/papers_pre/$paper_name/";
	$dest = $dir_destination;
	if($filehandle ->recurse_copy($dir,$dest)){
		if($filehandle ->rrmdir($dir)){
			echo $utility->general_alert_eval('alert_popup','success');
		}else{
			echo $utility->general_alert_eval('alert_warning','failure');
		}
	}
	
	die;
}
//MVC: 
if(isset($_POST['relocate_question_files'])){
	$questioncontr = new \MVC\questioncontr;
	$pointcontr = new \MVC\pointcontr;
	$filehandle = new MVC\filehandle;
	$utility = new MVC\utility;
	//
	$pt_unique_id = $_POST['pt_unique_id'];
	$q_unique_id = $_POST['q_unique_id'];
	//
	$result = $questioncontr -> relocate_question_files($q_unique_id,$pt_unique_id,$filehandle,$pointcontr,$utility);
	//
	if($result == 1){
		echo $utility->general_alert_eval('alert_popup_fresh','success');
	}else{
		echo $utility->general_alert_eval('alert_warning','failure');
	}
	die;
}
//MVC: 
if(isset($_POST['relocate_question_files2'])){
	$questioncontr = new \MVC\questioncontr;
	$pointcontr = new \MVC\pointcontr;
	$filehandle = new MVC\filehandle;
	$utility = new MVC\utility;
	//
	$pt_unique_id = $_POST['pt_unique_id'];
	$q_unique_id = $_POST['q_unique_id'];
	//
	$result = $questioncontr -> relocate_question_files2($q_unique_id,$pt_unique_id,$filehandle,$pointcontr,$utility);
	//
	if($result == 1){
		echo $utility->general_alert_eval('alert_popup_fresh','success');
	}else{
		echo $utility->general_alert_eval('alert_warning','failure');
	}
	die;
}
//
if(isset($_POST['create_flashcard']) and isset($_SESSION['user_membership'])){
	if($_SESSION['user_membership'] > 0){
		//
		$flashcardcontr = new \MVC\flashcardcontr;
		$pointcontr = new \MVC\pointcontr;
		$utility = new MVC\utility;
		//
		$user_unique_id =$_SESSION['user_unique_id'];
		$pt_unique_id = $_POST['create_flashcard'];
		$question = $_POST['flash_question'];
		$answer = $_POST['flash_answer'];
		//
		$result = $flashcardcontr -> create_flashcard($user_unique_id,$pt_unique_id,$question,$answer,$pointcontr);
		//
		if($result == 1){
			echo $utility->general_alert_eval('alert_popup','success');
		}else{
			echo $utility->general_alert_eval('alert_warning','failure');
		}
		die;
	}else{
		echo $utility->general_alert_eval('alert_warning','A membership is required to use this feature.');die;
	}
}
//
if(isset($_POST['create_review'])){
	//
	$usercontr = new \MVC\usercontr;
	$utility = new MVC\utility;
	//
	$name = $_POST['reviewer_name'];
	$rating = $_POST['reviewer_rating'];
	$review = $_POST['reviewer_review'];
	//
	$result = $usercontr -> create_review($name,$rating,$review);
	//
	if($result == 1){
		echo $utility->general_alert_eval('alert_popup','Success. Thanks you');
	}else{
		echo $utility->general_alert_eval('alert_warning','Failure. Something went wrong, try again.');
	}
	die;
}
//
if(isset($_POST['delete_flashcard']) and isset($_SESSION['user_unique_id'])){
	//
	$flashcardcontr = new \MVC\flashcardcontr;
	$utility = new MVC\utility;
	//
	$user_unique_id =$_SESSION['user_unique_id'];
	$flashcard_id = $_POST['delete_flashcard'];
	
	//
	$result = $flashcardcontr -> delete_flashcard($flashcard_id,$user_unique_id);
	//
	if($result == 1){
		echo $utility->general_alert_eval('alert_popup_fresh','success');
	}else{
		echo $utility->general_alert_eval('alert_warning','failure');
	}
	die;
	
}
//
if(isset($_POST['mark_flashcard']) and isset($_SESSION['user_unique_id'])){
	//
	$flashcardcontr = new \MVC\flashcardcontr;
	$utility = new MVC\utility;
	//
	$user_unique_id =$_SESSION['user_unique_id'];
	$status = $_POST['mark_flashcard'];
	$fc_id = $_POST['flashcard_id'];
	
	//
	$result = $flashcardcontr -> mark_flashcard($fc_id,$user_unique_id,$status);
	//
	if($result == 1){
		echo 1;
	}else{
		echo 0;
	}
	die;
	
}
//
if(isset($_POST['reset_flashcards_progress']) and isset($_SESSION['user_unique_id'])){
	//
	$flashcardcontr = new \MVC\flashcardcontr;
	$utility = new MVC\utility;
	//
	$user_unique_id =$_SESSION['user_unique_id'];	
	//
	$result = $flashcardcontr -> reset_flashcards_progress($user_unique_id);
	//
	if($result == 1){
		echo $utility->general_alert_eval('alert_popup','success');
	}else{
		echo $utility->general_alert_eval('alert_warning','failure');
	}
	die;
	
}
//



?>



















