
<?php
include('../function-file/db_connect.php');
include('../function-file/admin_class.php');
$qry = $conn->query("SELECT p. *, CONCAT(e.lastname, ', ' , e.firstname,' ', COALESCE(e.middlename,'')) as fullname from `attendance` p inner join staff e on p.id_no = e.id_no where p.schedule_id = '{$id}'");
if($qry->num_rows > 0){
    foreach($qry->fetch_assoc() as $k => $v){
        $$k=$v;
    }
}
?>  
