<?php
include('../function-file/db_connect.php');
include('../function-file/admin_class.php');
$id_no = $_SESSION['login_id_no'];
$sql = "UPDATE `time-off-request` SET notifications = 'requested' where notifications = 'remarked' and id_no = '$id_no'";
$resp = mysqli_query($conn, $sql);
if ($resp) {
    echo "Success";
} else {
    echo "Failed";
}
?>