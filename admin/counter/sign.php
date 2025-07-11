
<?php
include('../function-file/db_connect.php');
include('../function-file/admin_class.php');
date_default_timezone_set('Asia/Manila');
$datetime = strtotime(date('Y-m-d G:i:s ', strtotime("now")));

$query = "SELECT f.sign_flag, s.schedule_date, s.time_from 
    FROM staff f 
    INNER JOIN schedules s  
    WHERE f.id_no = '{$_SESSION['login_id_no']}' OR FIND_IN_SET('{$_SESSION['login_id_no']}',f.id_no) > 0 
    ORDER BY s.date_created DESC 
    LIMIT 1";

$result = $conn->query($query);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $sign_flag = $row['sign_flag'];
    $schedule_time = strtotime($row['schedule_date'] . " " . $row['time_from']);
    if ($datetime - 3600 < $schedule_time) {   
        echo $sign_flag;
    }   
}
?>
