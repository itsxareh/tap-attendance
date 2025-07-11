<?php
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

session_start();
ini_set('display_errors', 1);
Class Action {
	private $db;

	public function __construct() {
		ob_start();
		include 'db_connect.php';
		$this->includes();
		$this->db = $conn;
	}
	function __destruct() {
	    $this->db->close();
	    ob_end_flush();
	}
	private function includes(){
		require_once '../../vendor/phpmailer/phpmailer/src/PHPMailer.php';
		require_once '../../vendor/phpmailer/phpmailer/src/Exception.php';
		require_once '../../vendor/phpmailer/phpmailer/src/SMTP.php';
	}
	function login(){
		extract($_POST);		
		$qry = $this->db->query("SELECT * FROM users where username = '".$username."' and password = '".md5($password)."' ");
		if($qry->num_rows > 0){
			foreach ($qry->fetch_array() as $key => $value) {
				if($key != 'password' && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			}
			if($_SESSION['login_type'] != 1 & 2){
				foreach ($_SESSION as $key => $value) {
					unset($_SESSION[$key]);
				}
				return 0;
				exit;
			}
				return 1;
		}else{
			return 3;
		}
	}
	function login_staff(){
		extract($_POST);		
		$qry = $this->db->query("SELECT *,concat(lastname,', ',firstname,' ',middlename) as name FROM staff where id_no = '".$id_no."' ");
		if($qry->num_rows > 0){
			foreach ($qry->fetch_array() as $key => $value) {
				if($key != 'password' && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			}
			return 1;
		}else{
			return 3;
		}
	}
	function login2(){
		extract($_POST);
		if(isset($email))
			$username = $email;
		$qry = $this->db->query("SELECT * FROM users where username = '".$username."' and password = '".md5($password)."' ");
		if($qry->num_rows > 0){
			foreach ($qry->fetch_array() as $key => $value) {
				if($key != 'passwors' && !is_numeric($key))
					$_SESSION['login_'.$key] = $value;
			}
		if($_SESSION['login_alumnus_id'] > 0){
			$bio = $this->db->query("SELECT * FROM alumnus_bio where id = ".$_SESSION['login_alumnus_id']);
			if($bio->num_rows > 0){
				foreach ($bio->fetch_array() as $key => $value) {
					if($key != 'passwors' && !is_numeric($key))
						$_SESSION['bio'][$key] = $value;
				}
			}
		}
		if($_SESSION['bio']['status'] != 1){
				foreach ($_SESSION as $key => $value) {
					unset($_SESSION[$key]);
				}
				return 2 ;
				exit;
			}
			return 1;
		}else{
			return 3;
		}
	}
	function logout(){
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:../login.php");
	}
	function logout2(){
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:../../user-panel/index.php");
	}
	function signin() {
		date_default_timezone_set('Asia/Manila');
    	$in_time = date('Y-m-d H:i:s', strtotime("now")); 
		extract($_POST);
		$id1 = $this->db->query("SELECT id FROM `schedules` WHERE id_no = '$id_no' OR id_no = 0 OR FIND_IN_SET('$id_no',id_no) > 0 ORDER BY date_created DESC LIMIT 1")->fetch_object()->id;
		$schedule_date = $this->db->query("SELECT schedule_date FROM `schedules` WHERE id_no = '$id_no' OR FIND_IN_SET('$id_no',id_no) > 0 ORDER BY date_created DESC LIMIT 1")->fetch_object()->schedule_date;
		$schedule_time = $this->db->query("SELECT time_from FROM `schedules` WHERE id_no = '$id_no' OR FIND_IN_SET('$id_no',id_no) > 0 ORDER BY date_created DESC LIMIT 1")->fetch_object()->time_from;
		$schedule_time = $schedule_date . " " . $schedule_time;
		if ($in_time < $schedule_time){
			$in_status = 'Early';
		} else {
			$in_status = 'Late';
		}
		$data = "schedule_id = '$id1'";
		$data .= ", id_no = '$id_no'";
		$data .= ", schedule_date = '$schedule_date' ";
		$data .= ", in_time = '$in_time' ";
		$data .= ", in_status = '$in_status' ";
		$save1 = $this->db->query("INSERT INTO `attendance` SET ".$data);
		$lates = $this->db->query("SELECT * FROM attendance WHERE id_no = '$id_no' and in_status = 'Late'");
		if (mysqli_num_rows($lates) > 0){
			if (mysqli_num_rows($lates) == 5){
				$notificationa = $this->db->query("UPDATE staff SET notificationa = 'penalize', notificationaa = 'penalize' WHERE id_no = '$id_no'");
				$mail = new PHPMailer(true);
				try{
					$mail->isSMTP();
					$mail->SMTPAuth = true; 
					$mail->Host = 'smtp.gmail.com';
					$mail->SMTPSecure = 'tls';
					$mail->Username = 'prosystemmanager@gmail.com';
					$mail->Password = 'yonvbunryomunije';
					$mail->Port = 587;
					$mail->setFrom('prosystemmanager@gmail.com');
					$mail->Subject = "Penalize Notification";
					$result = $this->db->query("SELECT email,concat(lastname,', ',firstname,' ',middlename) as name FROM staff WHERE id_no in ($id_no) and email LIKE '%@gmail.com'");
					$emails = [];
					while ($row = mysqli_fetch_assoc($result)) {
						$emails[] = ['email' => $row['email'], 'name' => $row['name']];
					}
					foreach ($emails as $email) {
					$mail->addAddress($email['email']);
					$mail->IsHTML(true);
					$mail->Body =
					"<html>
					<body>
						<div class='container' style='width: 100%'>
							<table align='center' style='width: 500px'>
								<tbody>
									<hr>
										<tbody align='center'>
										<tr style='text-align: center; margin-bottom: 5px;'>
											<td style=''><img style='width:50px; vertical-align:middle;' src='cid:logo'><span style='font-size:16px; margin-left: 5px;'>Attendance System<span></td>
										</tr>
										</tbody>
										<br/>
										<tr style='text-align:center;'>
											<td>Hi, <strong>".$email['name']."</strong>!</td><br/>
										</tr>
										<tr style='text-align:center;'>
											<td style='color: gray'>This email is for late attendance</td><br/>
										</tr>
											<br/>
										<tr style='text-align:center;'>
											<td><h2>You'll be penalize for being late five(5) times.</h2></td>
										</tr>
									<hr>
								</tbody>
							</table>
						</div>
					</body>
					</html>";
					$mail->addEmbeddedImage('../images/staff-icon.png', 'logo');
					$mail->send();
					$mail->clearAddresses();
					}
					$mail->smtpClose();
				} catch (Exception $e){
					echo "Mailer Error: {$mail->ErrorInfo}";
				}
			}
			else if (mysqli_num_rows($lates) == 3){
				$notificationa = $this->db->query("UPDATE staff SET notificationa = 'warning', notificationaa = 'warning' WHERE id_no = '$id_no'");
				$mail = new PHPMailer(true);
				try{
					$mail->isSMTP();
					$mail->SMTPAuth = true; 
					$mail->Host = 'smtp.gmail.com';
					$mail->SMTPSecure = 'tls';
					$mail->Username = 'prosystemmanager@gmail.com';
					$mail->Password = 'yonvbunryomunije';
					$mail->Port = 587;
					$mail->setFrom('prosystemmanager@gmail.com');
					$mail->Subject = "Warning Notification";
					$result = $this->db->query("SELECT email,concat(lastname,', ',firstname,' ',middlename) as name FROM staff WHERE id_no in ($id_no) and email LIKE '%@gmail.com'");
					$emails = [];
					while ($row = mysqli_fetch_assoc($result)) {
						$emails[] = ['email' => $row['email'], 'name' => $row['name']];
					}
					foreach ($emails as $email) {
					$mail->addAddress($email['email']);
					$mail->IsHTML(true);
					$mail->Body =
					"<html>
					<body>
						<div class='container' style='width: 100%'>
							<table align='center' style='width: 500px'>
								<tbody>
									<hr>
										<tbody align='center'>
										<tr style='text-align: center; margin-bottom: 5px;'>
											<td style=''><img style='width:50px; vertical-align:middle;' src='cid:logo'><span style='font-size:16px; margin-left: 5px;'>Attendance System<span></td>
										</tr>
										</tbody>
										<br/>
										<tr style='text-align:center;'>
											<td>Hi, <strong>".$email['name']."</strong>!</td><br/>
										</tr>
										<tr style='text-align:center;'>
											<td style='color: gray'>This email is for late attendance</td><br/>
										</tr>
											<br/>
										<tr style='text-align:center;'>
											<td><h2>You have a warning for being late three(3) times.</h2></td>
										</tr>
									<hr>
								</tbody>
							</table>
						</div>
					</body>
					</html>";
					$mail->addEmbeddedImage('../images/staff-icon.png', 'logo');
					$mail->send();
					$mail->clearAddresses();
					}
					$mail->smtpClose();
				} catch (Exception $e){
					echo "Mailer Error: {$mail->ErrorInfo}";
				}
			}
		}
		if($id1 === NULL && $id2 !== NULL) {
			$save2 = false;
			$save3 = $this->db->query("UPDATE staff SET sign_flag = 2 WHERE id_no = '$id_no'");
		} else {
			$save2 = $this->db->query("UPDATE schedules SET sign_flag = 2 WHERE id = '$id1'");
			$save3 = $this->db->query("UPDATE staff SET sign_flag = 2 WHERE id_no = '$id_no'");
		}
		if ($save1 && ($notificationa || $save3 || $save2) ) {
			echo json_encode(array('success' => true));
		} else {
			echo json_encode(array('success' => false, 'message' => 'Error'));
		}
	}
	function checkout() {
		date_default_timezone_set('Asia/Manila');
    	$out_time = date('Y-m-d G:i:s ', strtotime("now"));
		$id_no = $_SESSION['login_id_no'];
		extract($_POST);
		$schedule_end = $this->db->query("SELECT schedule_end FROM `schedules` WHERE id_no = '$id_no' OR FIND_IN_SET('$id_no',id_no) > 0 ORDER BY date_created DESC LIMIT 1")->fetch_object()->schedule_end;
		$schedule_time = $this->db->query("SELECT time_to FROM `schedules` WHERE id_no = '$id_no' OR FIND_IN_SET('$id_no',id_no) > 0 ORDER BY date_created DESC LIMIT 1")->fetch_object()->time_to;
		$id1 = $this->db->query("SELECT id FROM `attendance` WHERE id_no = '$id_no' ORDER BY in_time DESC LIMIT 1")->fetch_object()->id;
		$id2 = $this->db->query("SELECT id FROM `schedules` WHERE id_no = '$id_no' OR FIND_IN_SET('$id_no',id_no) > 0 ORDER BY date_created DESC LIMIT 1")->fetch_object()->id;
		$id3 = $this->db->query("SELECT id FROM `schedules` WHERE id_no = 0 ORDER BY date_created DESC LIMIT 1")->fetch_object()->id;
		$schedule_time = $schedule_end . " " . $schedule_time;
		if ($out_time < $schedule_time){
			$out_status = 'Early';
		} else {
			$out_status = 'Overtime';
		}
		$data = " out_status = '$out_status' ";
		$data .= ", out_time = '$out_time' ";
		$save1 = $this->db->query("UPDATE `attendance` SET ".$data. "WHERE id = ".$id1);
		if($id2 === NULL && $id3 !== NULL) {
			$save2 = false;
			$save3 = $this->db->query("UPDATE staff SET sign_flag = 3 WHERE id_no = '$id_no'");
		} else {
			$save2 = $this->db->query("UPDATE schedules SET sign_flag = 3 WHERE id = '$id2'");
			$save3 = $this->db->query("UPDATE staff SET sign_flag = 3 WHERE id_no = '$id_no'");
		}
		if ($save1 && ($save3 || $save2) ) {
			echo json_encode(array('success' => true));
		} else {
			echo json_encode(array('success' => false, 'message' => 'Error'));
		}
	}
	function save_user(){
		extract($_POST);
		$data = " name = '$name' ";
		$data .= ", username = '$username' ";
		if(!empty($password))
		$data .= ", password = '".md5($password)."' ";
		$data .= ", type = '$type' ";
		if($type == 1)
			$establishment_id = 0;

		$chk = $this->db->query("Select * from users where username = '$username' and id !='$id' ")->num_rows;
		if($chk > 0){
			return 2;
			exit;
		}
		if(empty($id)){
			$save = $this->db->query("INSERT INTO users set ".$data);
		}else{
			$save = $this->db->query("UPDATE users set ".$data." where id = ".$id);
		}
		if($save){
			return 1;
		}
	}
	function delete_user(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM users where id = ".$id);
		if($delete)
			return 1;
	}
	function signup(){
		extract($_POST);
		$data = " name = '".$firstname.' '.$lastname."' ";
		$data .= ", username = '$email' ";
		$data .= ", password = '".md5($password)."' ";
		$chk = $this->db->query("SELECT * FROM users where username = '$email' ")->num_rows;
		if($chk > 0){
			return 2;
			exit;
		}
			$save = $this->db->query("INSERT INTO users set ".$data);
		if($save){
			$uid = $this->db->insert_id;
			$data = '';
			foreach($_POST as $k => $v){
				if($k =='password')
					continue;
				if(empty($data) && !is_numeric($k) )
					$data = " $k = '$v' ";
				else
					$data .= ", $k = '$v' ";
			}
			if($_FILES['img']['tmp_name'] != ''){
							$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
							$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
							$data .= ", avatar = '$fname' ";

			}
			$save_alumni = $this->db->query("INSERT INTO alumnus_bio set $data ");
			if($data){
				$aid = $this->db->insert_id;
				$this->db->query("UPDATE users set alumnus_id = $aid where id = $uid ");
				$login = $this->login2();
				if($login)
				return 1;
			}
		}
	}
	function update_account(){
		extract($_POST);
		$data = " name = '".$firstname.' '.$lastname."' ";
		$data .= ", username = '$email' ";
		if(!empty($password))
		$data .= ", password = '".md5($password)."' ";
		$chk = $this->db->query("SELECT * FROM users where username = '$email' and id != '{$_SESSION['login_id']}' ")->num_rows;
		if($chk > 0){
			return 2;
			exit;
		}
			$save = $this->db->query("UPDATE users set $data where id = '{$_SESSION['login_id']}' ");
		if($save){
			$data = '';
			foreach($_POST as $k => $v){
				if($k =='password')
					continue;
				if(empty($data) && !is_numeric($k) )
					$data = " $k = '$v' ";
				else
					$data .= ", $k = '$v' ";
			}
			if($_FILES['img']['tmp_name'] != ''){
							$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
							$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
							$data .= ", avatar = '$fname' ";

			}
			$save_alumni = $this->db->query("UPDATE alumnus_bio set $data where id = '{$_SESSION['bio']['id']}' ");
			if($data){
				foreach ($_SESSION as $key => $value) {
					unset($_SESSION[$key]);
				}
				$login = $this->login2();
				if($login)
				return 1;
			}
		}
	}
	function save_settings(){
		extract($_POST);
		$data = " name = '".str_replace("'","&#x2019;",$name)."' ";
		$data .= ", email = '$email' ";
		$data .= ", contact = '$contact' ";
		$data .= ", about_content = '".htmlentities(str_replace("'","&#x2019;",$about))."' ";
		if($_FILES['img']['tmp_name'] != ''){
						$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['img']['name'];
						$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
					$data .= ", cover_img = '$fname' ";
		}
		// echo "INSERT INTO system_settings set ".$data;
		$chk = $this->db->query("SELECT * FROM system_settings");
		if($chk->num_rows > 0){
			$save = $this->db->query("UPDATE system_settings set ".$data);
		}else{
			$save = $this->db->query("INSERT INTO system_settings set ".$data);
		}
		if($save){
		$query = $this->db->query("SELECT * FROM system_settings limit 1")->fetch_array();
		foreach ($query as $key => $value) {
			if(!is_numeric($key))
				$_SESSION['settings'][$key] = $value;
		}
			return 1;
		}
	}
	function save_staff(){
		extract($_POST);
		$data = '';
		foreach($_POST as $k=> $v){
			if(!empty($v)){
				if($k !='id'){
					if(empty($data))
					$data .= " $k='{$v}' ";
					else
					$data .= ", $k='{$v}' ";
				}
			}
		}
		if(empty($id_no)){
			$i = 1;
			while($i == 1){
				$rand = mt_rand(1,99999999);
				$rand =sprintf("%'08d",$rand);
				$chk = $this->db->query("SELECT * FROM staff where id_no = '$rand' ")->num_rows;
				if($chk <= 0){
					$data .= ", id_no='$rand' ";
					$i = 0;
				}
			}
		}
		if(empty($id)){
			if(!empty($id_no)){
				$chk = $this->db->query("SELECT * FROM staff where id_no = '$id_no' ")->num_rows;
				if($chk > 0){
					return 2;
					exit;
				}
			}
			$save = $this->db->query("INSERT INTO staff set $data ");
		}else{
			if(!empty($id_no)){
				$chk = $this->db->query("SELECT * FROM staff where id_no = '$id_no' and id != $id ")->num_rows;
				if($chk > 0){
					return 2;
					exit;
				}
			}
			$save = $this->db->query("UPDATE staff set $data where id=".$id);
		}
		if($save)
			return 1;
	}
	function delete_staff(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM staff where id = ".$id);
		if($delete){
			return 1;
		}
	}
	function save_schedule(){
		extract($_POST);	
		$title = mysqli_real_escape_string($this->db, $title);
		$id_no = implode(",", $id_no);
		$data = " id_no = '$id_no' ";
		$data .= ", title = '$title' ";
		$data .= ", shift_type = '$shift_type' ";
		$data .= ", schedule_date = '$schedule_date' ";
		$data .= ", schedule_end = '$schedule_end' ";
		$data .= ", time_from = '$time_from' ";
		$data .= ", time_to = '$time_to' ";
		$data .= ", sign_flag = 1 ";
		if (empty($id) && empty($id_no) ) {
			$mail = new PHPMailer(true);
			try {
				$mail->isSMTP();
				$mail->SMTPAuth = true; 
				$mail->Host = 'smtp.gmail.com';
				$mail->SMTPSecure = 'tls';
				$mail->Username = 'prosystemmanager@gmail.com';
				$mail->Password = 'yonvbunryomunije';
				$mail->Port = 587;
				$mail->setFrom('prosystemmanager@gmail.com');
				$mail->Subject = "For $shift_type Schedule";
				$result = $this->db->query("SELECT email,concat(lastname,', ',firstname,' ',middlename) as name FROM staff WHERE email LIKE '%@gmail.com'");
				$emails = [];
				while ($row = mysqli_fetch_assoc($result)) {
					$emails[] = ['email' => $row['email'], 'name' => $row['name']];
				}
				foreach ($emails as $email) {
				$mail->addAddress($email['email']);
				$mail->IsHTML(true);
				$mail->Body =
				"<html>
				<body>
					<div class='container' style='width: 100%'>
						<table align='center' style='width: 500px'>
							<tbody>
								<hr>
									<tbody align='center'>
									<tr style='text-align: center; margin-bottom: 5px;'>
										<td style=''><img style='width:40px; vertical-align:middle;' src='cid:logo'><span style='font-size:16px; margin-left: 5px;'>Attendance System<span></td>
									</tr>
									</tbody>
									<br/>
									<br/>
									<tr style='text-align:center;'>
										<td>Hi, <strong>".$email['name']."</strong>!</td><br/>
									</tr>
									<tr style='text-align:center;'>
										<td style='color: gray'>Manager added a new schedule for everyone.</td><br/>
									</tr>
										<br/>
									<tr style='text-align:center;'>
										<td><h2>Check now the schedule for ". ($schedule_date == "" ? $month_from_date : $schedule_date) .".</h2></td>
									</tr>
								<hr>
							</tbody>
						</table>
					</div>
				</body>
				</html>";
				$mail->addEmbeddedImage('../images/staff-icon.png', 'logo');
				$mail->send();
				$mail->clearAddresses();
				}
				$mail->smtpClose();
			} catch (Exception $e){
				echo "Mailer Error: {$mail->ErrorInfo}";
			}
			$query = "INSERT INTO `schedules` SET ".$data;
			$query .= "; UPDATE staff SET notification = 'schedule', sign_flag = 1 WHERE TRUE";
			if (mysqli_multi_query($this->db, $query)) {
			  do {
				if ($result = mysqli_store_result($this->db)) {
				  mysqli_free_result($result);
				}
			  } while (mysqli_next_result($this->db));
			  return 1;
			}
		} elseif (empty($id)) {
			$mail = new PHPMailer(true);
			try{
				$mail->isSMTP();
				$mail->SMTPAuth = true; 
				$mail->Host = 'smtp.gmail.com';
				$mail->SMTPSecure = 'tls';
				$mail->Username = 'prosystemmanager@gmail.com';
				$mail->Password = 'yonvbunryomunije';
				$mail->Port = 587;
				$mail->setFrom('prosystemmanager@gmail.com');
				$mail->Subject = "$shift_type Schedule";
				$result = $this->db->query("SELECT email,concat(lastname,', ',firstname,' ',middlename) as name FROM staff WHERE id_no in ($id_no) and email LIKE '%@gmail.com'");
				$emails = [];
				while ($row = mysqli_fetch_assoc($result)) {
					$emails[] = ['email' => $row['email'], 'name' => $row['name']];
				}
				foreach ($emails as $email) {
				$mail->addAddress($email['email']);
				$mail->IsHTML(true);
				$mail->Body =
				"<html>
				<body>
					<div class='container' style='width: 100%'>
						<table align='center' style='width: 500px'>
							<tbody>
								<hr>
									<tbody align='center'>
									<tr style='text-align: center; margin-bottom: 5px;'>
										<td style=''><img style='width:50px; vertical-align:middle;' src='cid:logo'><span style='font-size:16px; margin-left: 5px;'>Attendance System<span></td>
									</tr>
									</tbody>
									<br/>
									<br/>
									<tr style='text-align:center;'>
										<td>Hi, <strong>".$email['name']."</strong>!</td><br/>
									</tr>
									<tr style='text-align:center;'>
										<td style='color: gray'>Manager added a new schedule for you.</td><br/>
									</tr>
										<br/>
									<tr style='text-align:center;'>
										<td><h2>Check now your schedule for ". ($schedule_date ? $schedule_date : "") .".</h2></td>
									</tr>
								<hr>
							</tbody>
						</table>
					</div>
				</body>
				</html>";
				$mail->addEmbeddedImage('../images/staff-icon.png', 'logo');
				$mail->send();
				$mail->clearAddresses();
				}
				$mail->smtpClose();
			} catch (Exception $e){
				echo "Mailer Error: {$mail->ErrorInfo}";
			}
			$query = "INSERT INTO `schedules` SET ".$data;
			$query .= "; UPDATE staff SET notification = 'schedule', sign_flag = 1 WHERE id_no IN (SELECT id_no FROM staff WHERE id_no IN ($id_no))";
			if (mysqli_multi_query($this->db, $query)) {
			  do {
				if ($result = mysqli_store_result($this->db)) {
				  mysqli_free_result($result);
				}
			  } while (mysqli_next_result($this->db));
			  return 1;
			}
		} elseif (empty($id_no)){
			$query = "UPDATE `schedules` SET ".$data." WHERE id=".$id;
			$query .= "; UPDATE staff SET notification = 'schedule', sign_flag = 1 WHERE TRUE";
			if (mysqli_multi_query($this->db, $query)) {
			  do {
				if ($result = mysqli_store_result($this->db)) {
				  mysqli_free_result($result);
				}
			  } while (mysqli_next_result($this->db));
			  return 1;
			}
		} else {
			$query = "UPDATE `schedules` SET ".$data." WHERE id=".$id;
			$query .= "; UPDATE staff SET notification = 'schedule', sign_flag = 1 WHERE id_no IN (SELECT id_no FROM staff WHERE id_no IN ($id_no)) ";
			if (mysqli_multi_query($this->db, $query)) {
			  do {
				if ($result = mysqli_store_result($this->db)) {
				  mysqli_free_result($result);
				}
			  } while (mysqli_next_result($this->db));
			  return 1;
			}
		}
}
	function delete_schedule(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM schedules where id = ".$id);
		if($delete){
			return 1;
		}
	}
	function get_schedule(){
		extract($_POST);
		$data = array();
		if ($_POST['id_no'] === 'all'){
			$qry = $this->db->query("SELECT * FROM schedules");
		} else 
		$qry = $this->db->query("SELECT * FROM schedules WHERE id_no = 0 OR id_no = '$id_no' OR FIND_IN_SET('$id_no',id_no) > 0");
		while($row=$qry->fetch_assoc()){
			$data[] = $row;
		}
			return json_encode($data);
	}
	function save_time_off(){
		extract($_POST);
		$id_no = $_SESSION['login_id_no'];
		$data = " id_no = '$id_no'";
		$description = mysqli_real_escape_string($this->db, $description);
		$data .= ", leave_type = '$leave_type' ";
		$data .= ", from_date = '$from_date' ";
		$data .= ", to_date = '$to_date' ";
		$data .= ", description = '$description' ";
		$data .= ", admin_remark = '---' ";
		if (empty($id)){
			$mail = new PHPMailer(true);
			try {
				$mail->isSMTP();
				$mail->SMTPAuth = true; 
				$mail->Host = 'smtp.gmail.com';
				$mail->SMTPSecure = 'tls';
				$mail->Username = 'prosystemmanager@gmail.com';
				$mail->Password = 'yonvbunryomunije';
				$mail->Port = 587;
				$mail->setFrom('prosystemmanager@gmail.com');
				$mail->Subject = "New Time-Off Request";
				$mail->addAddress('prosystemmanager@gmail.com');
				$mail->IsHTML(true);
				$mail->Body =
				"<html>
				<body>
					<div class='container' style='width: 100%'>
						<table align='center' style='width: 500px'>
							<tbody>
								<hr>
									<tbody align='center'>
									<tr style='text-align: center; margin-bottom: 5px;'>
										<td style=''><img style='width:50px; vertical-align:middle;' src='cid:logo'><span style='font-size:16px; margin-left: 5px;'>Attendance System<span></td>
									</tr>
									</tbody>
									<br/>
									<br/>
									<tr style='text-align:center;'>
										<td>Hi, <strong>Manager</strong>!</td><br/>
									</tr>
									<tr style='text-align:center;'>
										<td style='font-size: 14px;'><p><b>".$row['name']."</b> requested a time-off from <b>".$from_date."</b> to <b>".$to_date."</b>.</p></td>
									</tr>
									<br/>
									<tr style='text-align:center;'>
										<td style='color: gray;'>Reason</td>
									</tr>
									<tr style='text-align:center;'>
										<td><h3>".$leave_type."</h3></td>
									</tr>
									<tr style='text-align:center;'>
										<td><h2>".$description."</h2></td><br/>
									</tr>
								<hr>
							</tbody>
						</table>
					</div>
				</body>
				</html>";
				$mail->addEmbeddedImage('../images/staff-icon.png', 'logo');
				if (!$mail->send()) {
					echo 'Message could not be sent.';
					echo 'Mailer Error: ' . $mail->ErrorInfo;
				} else {
					echo 'Message has been sent';
				}
				$mail->clearAddresses();
				$mail->smtpClose();
			} catch (Exception $e){
				echo "Mailer Error: {$mail->ErrorInfo}";
			}
			$save = $this->db->query("INSERT INTO `time-off-request` set ".$data);
		} else {
			$save = $this->db->query("UPDATE `time-off-request` set ".$data." where id=".$id);
		}
		if($save)
			return 1;
	}
	function decide_time_off(){
		date_default_timezone_set('Asia/Manila');
    	$time_remark=date('Y-m-d G:i:s ', strtotime("now"));
		extract($_POST);
		$admin_remark = mysqli_real_escape_string($this->db, $admin_remark);
		$data = " notifications = 'remarked' ";
		$data .= ", admin_remark	 = '$admin_remark' ";
		$data .= ", stats = '$stats' ";	
		$data .= ", time_remark = '$time_remark' ";
		if (empty($id)){
			$save = $this->db->query("INSERT INTO `time-off-request` set ".$data);
		} else {
			$mail = new PHPMailer(true);
				try {
					$mail->isSMTP();
					$mail->SMTPAuth = true; 
					$mail->Host = 'smtp.gmail.com';
					$mail->SMTPSecure = 'tls';
					$mail->Username = 'prosystemmanager@gmail.com';
					$mail->Password = 'yonvbunryomunije';
					$mail->Port = 587;
					$mail->setFrom('prosystemmanager@gmail.com');
					$mail->Subject = "Time-Off Request";
					$result = $this->db->query("SELECT t.*, concat(lastname,', ',firstname,' ',middlename) as name, email FROM staff s INNER JOIN `time-off-request` t ON t.id_no = s.id_no WHERE t.id = $id and s.email LIKE '%@gmail.com' ");
					$emails = [];
					while ($row = mysqli_fetch_assoc($result)) {
						$emails[] = ['email' => $row['email'], 'name' => $row['name'], 'stats' => $row['stats']];
					}
					foreach ($emails as $email) {
					$mail->addAddress($email['email']);
					$mail->IsHTML(true);
					$mail->Body =
					"<html>
					<body>
						<div class='container' style='width: 100%'>
							<table align='center' style='width: 500px'>
								<tbody>
									<hr>
										<tbody align='center'>
										<tr style='text-align: center; margin-bottom: 5px;'>
											<td style=''><img style='width:50px; vertical-align:middle;' src='cid:logo'><span style='font-size:16px; margin-left: 5px;'>Attendance System<span></td>
										</tr>
										</tbody>
										<br/>
										<br/>
										<tr style='text-align:center;'>
											<td>Hi, <strong>".$email['name']."</strong>!</td><br/>
										</tr>
										<tr style='text-align:center;'>
											<td style='color: gray'>Your time-off request status has been ".$stats.".</td><br/>
										</tr>
											<br/>
										<tr style='text-align:center;'>
											<td><h2>".$admin_remark."</h2></td>
										</tr>
									<hr>
								</tbody>
							</table>
						</div>
					</body>
					</html>";
					$mail->addEmbeddedImage('../images/staff-icon.png', 'logo');
					$mail->send();
					$mail->clearAddresses();
					}
					$mail->smtpClose();
				} catch (Exception $e){
					echo "Mailer Error: {$mail->ErrorInfo}";
				}
			$save = $this->db->query("UPDATE `time-off-request` set ".$data." where id=".$id);
		}
		if($save)
			return 1;
	}
	function delete_time_off(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM `time-off-request` where id = ".$id);
		if($delete){
			return 1;
		}
	}
	function save_leave_type(){
		extract($_POST);
		$leave_type = mysqli_real_escape_string($this->db, $leave_type);
		$description = mysqli_real_escape_string($this->db, $description);
		$data = " leave_type = '$leave_type' ";
		$data .= ", description = '$description' ";
		if (empty($id)){
			$save = $this->db->query("INSERT INTO `leave_types` set ".$data);
		} else {
			$save = $this->db->query("UPDATE `leave_types` set ".$data." where id=".$id);
		}
		if($save)
			return 1;
	}
	function delete_leave_type(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM `leave_types` where id = ".$id);
		if($delete){
			return 1;
		}
	}
	function save_announcement() {
		date_default_timezone_set('Asia/Manila');
    	$date_created = date('Y-m-d G:i:s ', strtotime("now"));
		extract($_POST);
		$title = mysqli_real_escape_string($this->db, $title);
		$description = mysqli_real_escape_string($this->db, $description);
		$author = mysqli_real_escape_string($this->db, $author);
		$data = " title = '$title' ";
		$data .= ", description = '$description' ";
		$data .= ", author = '$author' ";
		$data .= ", date_created = '$date_created' ";
		if (empty($id)) {
		  $query = "INSERT INTO `announcement` SET ".$data;
		  $query .= "; UPDATE staff SET notifications = 'announcement' WHERE TRUE";
		  if (mysqli_multi_query($this->db, $query)) {
			do {
			  /* store first result set */
			  if ($result = mysqli_store_result($this->db)) {
				mysqli_free_result($result);
			  }
			} while (mysqli_next_result($this->db));
			return 1;
		  }
		} else {
		  $query = "UPDATE `announcement` SET ".$data." WHERE id=".$id;
		  $query .= "; UPDATE staff SET notifications = 'announcement' WHERE TRUE";
		  if (mysqli_multi_query($this->db, $query)) {
			do {
			  /* store first result set */
			  if ($result = mysqli_store_result($this->db)) {
				mysqli_free_result($result);
			  }
			} while (mysqli_next_result($this->db));
			return 1;
		  }
		}
	}
	function delete_forum(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM forum_topics where id = ".$id);
		if($delete){
			return 1;
		}
	}
	function save_comment(){
		extract($_POST);
		$data = " comment = '".htmlentities(str_replace("'","&#x2019;",$comment))."' ";

		if(empty($id)){
			$data .= ", topic_id = '$topic_id' ";
			$data .= ", user_id = '{$_SESSION['login_id']}' ";
			$save = $this->db->query("INSERT INTO forum_comments set ".$data);
		}else{
			$save = $this->db->query("UPDATE forum_comments set ".$data." where id=".$id);
		}
		if($save)
			return 1;
	}
	function delete_comment(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM forum_comments where id = ".$id);
		if($delete){
			return 1;
		}
	}
	function save_event(){
		extract($_POST);
		$data = " title = '$title' ";
		$data .= ", schedule = '$schedule' ";
		$data .= ", content = '".htmlentities(str_replace("'","&#x2019;",$content))."' ";
		if($_FILES['banner']['tmp_name'] != ''){
						$_FILES['banner']['name'] = str_replace(array("(",")"," "), '', $_FILES['banner']['name']);
						$fname = strtotime(date('y-m-d H:i')).'_'.$_FILES['banner']['name'];
						$move = move_uploaded_file($_FILES['banner']['tmp_name'],'assets/uploads/'. $fname);
					$data .= ", banner = '$fname' ";

		}
		if(empty($id)){

			$save = $this->db->query("INSERT INTO events set ".$data);
		}else{
			$save = $this->db->query("UPDATE events set ".$data." where id=".$id);
		}
		if($save)
			return 1;
	}
	function delete_event(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM events where id = ".$id);
		if($delete){
			return 1;
		}
	}
	function participate(){
		extract($_POST);
		$data = " event_id = '$event_id' ";
		$data .= ", user_id = '{$_SESSION['login_id']}' ";
		$commit = $this->db->query("INSERT INTO event_commits set $data ");
		if($commit)
			return 1;

	}
}
