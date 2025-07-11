<?php
include('../function-file/db_connect.php');
include('../function-file/admin_class.php');
$id_no = $_SESSION['login_id_no'];
$sql = "UPDATE `staff` 
SET notificationa = 
    CASE 
        WHEN notificationa = 'warning' THEN 'warned'
        WHEN notificationa = 'penalize' THEN 'penalized'
        ELSE notificationa 
    END 
WHERE id_no = '$id_no'";
$resp = mysqli_query($conn, $sql);
if ($resp) {
    echo "Success";
} else {
    echo "Failed";
}
?>