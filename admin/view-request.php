<?php
include ('../admin/function-file/db_connect.php');
if(isset($_GET['id']) && $_GET['id'] > 0){
    $qry = $conn->query("SELECT * from `time-off-request` where id = '{$_GET['id']}' ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            $$k=$v;
        }
    }
}
?>
<style>
    #uni_modal .modal-footer{
        display:none;
    }
    .container-fluid {
        width: 100%;
    }
</style>
<div class="container-fluid">
    <dl>
        <dt class="text-muted"><b>Start Date</b></dt>
        <dd class="pl-4"><?= isset($from_date) ? $from_date : "" ?></dd>
        <dt class="text-muted"><b>End Date</b></dt>
        <dd class="pl-4"><?= isset($to_date) ? $to_date : "" ?></dd>
        <dt class="text-muted"><b>Time-Off Type</b></dt>
        <dd class="pl-4"><?= isset($leave_type) ? $leave_type : "" ?></dd>
        <dt class="text-muted"><b>Description</b></dt>
        <dd class="pl-4"><?= isset($description) ? $description : "" ?></dd>
    </dl>
    <div class="clear-fix mb-3"></div>
    <div class="text-right">
        <button class="btn btn-dark text-black bg-gradient-dark btn-flat" type="button" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
    </div>
</div>