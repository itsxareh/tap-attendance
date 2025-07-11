<?php
include('../function-file/db_connect.php');
include('../function-file/admin_class.php');
$id_no = $_SESSION['login_id_no'];
$sql = "UPDATE `staff` 
SET notificationaa = 
    CASE 
        WHEN notificationaa = 'warning' THEN 'warned'
        WHEN notificationaa = 'penalize' THEN 'penalized'
        ELSE notificationaa 
    END 
WHERE notificationaa IN ('warning', 'penalize');";
$resp = mysqli_query($conn, $sql);
if ($resp) {
    echo "Success";
} else {
    echo "Failed";
}
?>