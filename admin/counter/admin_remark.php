<?php
include('../function-file/db_connect.php');
$sql = "UPDATE `time-off-request` SET notifications = 'requested' where notifications = 'request'";
$resp = mysqli_query($conn, $sql);
if ($resp) {
    echo "Success";
} else {
    echo "Failed";
}
?>