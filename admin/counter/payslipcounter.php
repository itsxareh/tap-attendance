<?php
include('../function-file/db_connect.php');
$sql = "UPDATE `payslip` SET notifications = 'read'";
$resp = mysqli_query($conn, $sql);
if ($resp) {
    echo "Success";
} else {
    echo "Failed";
}
?>