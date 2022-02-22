<?php 

namespace MVC;
use mysqli;
class utility extends dbh{ 
	public function cond_check($sesh){
		$this -> maitanance = 0;
	}
	//util: compares arrays and returns non matching tags in first array
	public function find_stray($Servertags,$Dbtags){
		//return non matching that should be deleted by comparing the two arrays
		$arr =  array_diff($Dbtags,$Servertags);
		$load = array();
		foreach($arr as $tag){
			$load[] = "'$tag'";
		}
		return $load;
	}
	//
	public function run_query($query){
		return mysqli_query($this->conn,$query);
	}
	//util: tag generator
	public function tag_generator(){
		$uniqid = uniqid();
		$rand_start = rand(1,5);
		$rand_8_char = substr($uniqid,$rand_start,8);
		$somenumber = rand(10,99);
		$ID = $rand_8_char.$somenumber;	
		return str_shuffle($ID);
	}
	//util: makes an array associative as you please with the keys
	public function make_assoc($array,$ordered_keys){
		$return = array();
		$pos = 0;
		foreach($array as $arr){
			$it = 0;
			$temp = array();
			foreach($ordered_keys as $key){
				$temp[$key] = $arr[$it];
				$it+=1;
			}
			$return[] = $temp;
			$pos = $pos +1;
		}
		return $return;
	}
	//util: makes an array associative as you please with the keys takes array(..) and array(..)
	public function make_assoc2($array,$ordered_keys){
		$return = array();
		$pos = 0;
		foreach($array as $arr){
			$return[$ordered_keys[$pos]] = $arr;
			$pos = $pos +1;
		}
		return $return;
		
	}	
	//util: send email
	public function send_mail($php_mailer,$to,$head,$message){
		require "$this->root/includes/bootstrap.php";
		$mail = $php_mailer;
		$mail->isSMTP();
		//$mail->SMTPDebug = \PHPMailer\PHPMailer\SMTP::DEBUG_SERVER;
		$mail->SMTPSecure = 'tls';
		$mail->SMTPAuth = true;
		$mail->Host = 'email-smtp.eu-west-1.amazonaws.com';
		$mail->Port = 587;
		$mail->Username = $this-> mail_username; 
		$mail->Password = $this-> mail_password; 
		$mail->setFrom('admin@practicepractice.net','Admin');
		$mail->IsHTML(true); 
		$mail->clearAddresses();
		$mail->addAddress($to); 
		$mail->Subject = $head;
		//twig email template
		$email  = $twig->render('email.t.html',['message' => $message]);
		$mail->Body = $email;
		//send the message, check for errors
		if (!$mail->send()) {
			return 0;
		}else{
			return 1; 
		}
	}
	//util: send email
	public function reveive_mail($php_mailer,$name,$from,$head,$message){
		require "$this->root/includes/bootstrap.php";
		$mail = $php_mailer;
		$mail->isSMTP();
		//$mail->SMTPDebug = \PHPMailer\PHPMailer\SMTP::DEBUG_SERVER;
		$mail->SMTPSecure = 'tls';
		$mail->SMTPAuth = true;
		$mail->Host = 'email-smtp.eu-west-1.amazonaws.com';
		$mail->Port = 587;
		$mail->Username = $this-> mail_username; 
		$mail->Password = $this-> mail_password; 
		$mail->setFrom('admin@practicepractice.net','Contact Form');
		$mail->IsHTML(true); 
		$mail->clearAddresses();
		$mail->addAddress('admin@practicepractice.net'); 
		$mail->AddReplyTo($from, $name);
		$mail->Subject = $head;
		//twig email template
		$email  = $twig->render('email.t.html',['message' => $message]);
		$mail->Body = $email;
		//send the message, check for errors
		if (!$mail->send()) {
			return 0;
		}else{
			return 1; 
		}
	}
	//util: send email
	public function send_mail_attachement($php_mailer,$to,$head,$message,$file){
		require "$this->root/includes/bootstrap.php";
		$mail = $php_mailer;
		$mail->isSMTP();
		//$mail->SMTPDebug = \SMTP::DEBUG_SERVER;
		$mail->SMTPSecure = 'tls';
		$mail->SMTPAuth = true;
		$mail->Host = 'email-smtp.eu-west-1.amazonaws.com';
		$mail->Port = 587;
		$mail->Username = $this-> mail_username; 
		$mail->Password = $this-> mail_password; 
		$mail->setFrom('admin@practicepractice.net','Admin');
		$mail->IsHTML(true); 
		$mail->clearAddresses();
		$mail->addAddress($to); 
		$mail->Subject = $head;
		//twig email template
		$email  = $twig->render('email.t.html',['message' => $message]);
		$mail->Body = $email;
		$mail->AddAttachment($file, $name = 'Contract',  $encoding = 'base64', $type = 'application/pdf');
		//send the message, check for errors
		if (!$mail->send()) {
			return 0;
		}else{
			return 1; 
		}
	}
	//util : remove html <hidden> tag. not an official tag, just custom made by me;
	public function remove_hidden_tag($description){
		return substr($description,strpos($description,'</hidden>')+9, strlen($description));
	}
	//util : alert
	public function general_alert_eval($alert_type,$msg){
		
		return "
				view.$alert_type('$msg');
		";
	}
	//util
	public function general_redirect($link){
		return "
				view.headder_redirect('$link');
		";
	}
	//util : from the haystack, extract the element type with the following element number, eg extract the 3rd <img> 
	public function loacte_html_element($haystack, $element_type, $element_number){
		/*** explode the string ***/
		$arr = explode($element_type, $haystack);
		/*** check the search is not out of bounds ***/
		switch( $element_number )
		{
			case $element_number == 0:
			return false;
			break;
				
			case $element_number > max(array_keys($arr)):
			return false;
			break;
				
			default:
			return strlen(implode($element_type, array_slice($arr, 0, $element_number)));
		}
	}
	//
	public function make_display_header($input){
		$mod_disp = str_replace('_',' ',$input);
		$mod_disp = substr($mod_disp, 3);
		return ucfirst($mod_disp);
	}	
	//a-b-c-d-e-f
	public function make_display_paper($input){
		$arr = explode("-" , $input);
		//$arr = array_flip($arr); 
		//$arr = array_change_key_case($arr, CASE_UPPER); 
		//$arr = array_flip($arr); 
		return implode(' ', $arr);
	}
	//
	public function track_impressions(){
		//
		if(!isset($_SESSION['u_admin']) and !isset($_SESSION['u_editor'])){
			//
			$date = date('d/m/Y G', time());
			
			//
			$check_q = "select id from Impressions where date = '$date'";
			$run = $this->run_query($check_q);
			$count = mysqli_num_rows($run);
			//see if hour exists
			if($count > 0 ){
				$query = "update Impressions set count = count + 1 where date = '$date'";
			}else{
				$query = "insert into Impressions (date,count) VALUE ('$date',1)";
			}
			//
			$this->run_query($query);
			
			
			$uri = $_SERVER['HTTP_REFERER'];
			$uri = str_replace("https://www.practicepractice.net/",'',$uri);
			if(empty($uri)){
				$uri = '/';
			}
			$check_q = "select id from Impressions_page where date = '$date' and uri = '$uri'";
			//
			$run = $this->run_query($check_q);
			$count = mysqli_num_rows($run);
			//see if hour exists
			if($count > 0 ){
				$query = "update Impressions_page set count = count + 1 where date = '$date' and uri = '$uri'";
			}else{
				$query = "insert into Impressions_page (date,uri,count) VALUE ('$date','$uri',1)";
			}
			$this->run_query($query);
		}
		
	}
	//
	public function get_impressions_data(){
		//get the date 24 hours ago including the hour
		$date = date('d/m/Y h', time() - 86400);
		//
		if(isset($_SESSION['u_admin'])){
			//get data points
			$query = "SELECT date,count FROM (select * from Impressions where 1 order by `id` desc limit 25) AS table_alias  order by `id` asc";
			//
			$run = $this->run_query($query);
			//
			$xs = array();
			$ys = array();
			while ($result = mysqli_fetch_assoc($run)){
				$xlabel = $result['date'];
				$ylabel = $result['count'];
				//
				$xs[] = $xlabel;
				$ys[] = $ylabel;
			}
			//
			//get data points
			return array('xs' => $xs,'ys' => $ys);
		}
		
	}
	//
	public function contact_form($name,$toemail,$subject,$input,$mailer){
		//
		$subject = "Contact: $subject";
		$message ="Email: $toemail <br><br> Message: <br><Br> $input";
		//
		$result = $this->reveive_mail($mailer,$name,$toemail,$subject,$message);
		//
		if($result == 1){
			$confirmation = "Contact confirmation";
			$message = "Your email about ($subject) has been received and we will be in contact with you shortly.<br><br>Thanks<br><br>Admin.";
			$result2 = $this->send_mail($mailer,$toemail,$confirmation,$message);
			
			return $result2;
		}else{
			return $result;
		}
	}
	//converts time from seconds to d/h/s
	public function history_time_calculator($time){
		//
		$secs = time() - $time ;
		$mins = $secs / 60;
		$hrs = $mins /60;
		$days = $hrs /24;
		if ($days >=1){
			$hrs = round(( $days - floor($days))*24);
			$time = floor($days).' d '.$hrs.' h ';
		}elseif ($hrs >=1){
			$mins = round(( $hrs - floor($hrs))*60);
			$time = floor($hrs).' h '.$mins.' m ';
		}else{
			$secs = round(( $mins - floor($mins))*60);
			$time = floor($mins).' m '.$secs.' s ';
		}
		return $time;
	}
	//
	private function hslToRgb($h, $s, $l){
		#    var r, g, b;
		if($s == 0){
			$r = $g = $b = $l; // achromatic
		}else{
			if($l < 0.5){
				$q =$l * (1 + $s);
			} else {
				$q =$l + $s - $l * $s;
			}
			$p = 2 * $l - $q;
			$r = $this->hue2rgb($p, $q, $h + 1/3);
			$g = $this->hue2rgb($p, $q, $h);
			$b = $this->hue2rgb($p, $q, $h - 1/3);
		}
		$return=array(floor($r * 255), floor($g * 255), floor($b * 255));
		return $return;
	}
	//
	private function hue2rgb($p, $q, $t){
		//
		if($t < 0) { $t++; }
		if($t > 1) { $t--; }
		if($t < 1/6) { return $p + ($q - $p) * 6 * $t; }
		if($t < 1/2) { return $q; }
		if($t < 2/3) { return $p + ($q - $p) * (2/3 - $t) * 6; }
		return $p;
	}
	//
	public function numberToColorHsl($i, $min, $max) {
		//
		$ratio = $i;
		//
		if ($min> 0 || $max < 1) {
			if ($i < $min) {
				$ratio = 0;
			} elseif ($i > $max) {
				$ratio = 1;
			} else {
				$range = $max - $min;
				$ratio = ($i-$min) / $range;
			}
		}
		// as the function expects a value between 0 and 1, and red = 0° and green = 120°
		// we convert the input to the appropriate hue value
		$hue = $ratio * 1.2 / 3.60;
		//if (minMaxFactor!=1) hue /= minMaxFactor;
		//console.log(hue);

		// we convert hsl to rgb (saturation 100%, lightness 50%)
		$rgb = $this-> hslToRgb($hue, 1, .5);
		// we format to css value and return
		return 'rgb('.$rgb[0].','.$rgb[1].','.$rgb[2].')'; 
	}
	//
	public function array_in_query($array){
		if(isset($array) and !empty($array) and is_array($array)){
			//
			return implode("','",$array);
		}else{
			return 0;
		}
	}
	//
	public function array_in_like_origin_query($array){
		if(isset($array) and !empty($array) and is_array($array)){
			//
			return implode("-%-%') or q_origin like ('%-%-",$array);
		}else{
			return 0;
		}
	}
	//
	public function collapse_paper_array($paper){
		//array(user_unique,subject,marks,time,array(qs))
		//(`user_unique_id`,`subject`,`marks`,`time`,`q_0`,`q_1`,`q_2`,`q_3`,`q_4`,`q_5`,`q_6`,`q_7`,`q_8`,`q_9`)
		$user = $paper['user_unique_id'];
		$paper_unique = $this->tag_generator();
		$subject = $paper['subject'];
		$marks = $paper['marks'];
		$time = $paper['time'];
		//
		$qs = $paper['qs_unique'];
		while(count($qs) !== 10){
			$qs[] = null;
		}
		$collapsed_qs_list =implode("','",$qs);
		//
		$string = "'$user','$paper_unique','$subject','$marks','$time','$collapsed_qs_list'";
		return array('query'=>$string, 'paper_unique'=>$paper_unique);
		
	}
	//
	public function YouTube_Check_ID($videoID){
		#
		$url = "https://www.youtube.com/oembed?url=https://www.youtube.com/watch?v=$videoID&format=json";
		#
		$file_headers = @get_headers($url);
		foreach ($file_headers as $header){
			if($header == 'HTTP/1.0 200 OK'){
				return 1;
			}elseif($header == 'HTTP/1.0 400 Bad Request'){
				return 0;
			}
		}
	}
}
