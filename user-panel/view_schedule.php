<?php include '../admin/function-file/db_connect.php' ?>
<?php
if(isset($_GET['id'])){
	$qry = $conn->query("SELECT * FROM schedules where id=".$_GET['id'])->fetch_array();
	foreach($qry as $k =>$v){
		$$k = $v;
	}
}

?>
<div class="container-fluid">
	<p>Schedule for: <b><?php echo ucwords($title) ?></b></p>
	<p>Schedule Date: </i> <b><?php echo date('Y-m-d',strtotime("$schedule_date"))?></b></p>
	<p>Schedule End: </i> <b><?php echo date('Y-m-d',strtotime("$schedule_end"))?></b></p>
	<p>Time Start: </i> <b><?php echo date('h:i A',strtotime("2020-01-01 ".$time_from)) ?></b></p>
	<p>Time End: </i> <b><?php echo date('h:i A',strtotime("2020-01-01 ".$time_to)) ?></b></p>
	<hr class="divider">
</div>
<div class="modal-footer display">
	<div class="row">
		<div class="col-md-12">
			<button class="btn float-right btn-secondary" type="button" data-dismiss="modal">Close</button>
		</div>
	</div>
</div>
<style>
	p{
		margin:unset;
	}
	#uni_modal .modal-footer{
		display: none;
	}
	#uni_modal .modal-footer.display {
		display: block;
	}
</style>
<script>
	$('#edit').click(function(){
		uni_modal('Edit Schedule','manage_schedule.php?id=<?php echo $id ?>','mid-large')
	})
	$('#delete_schedule').click(function(){
		_conf("Are you sure to delete this schedule?","delete_schedule",[$(this).attr('data-id')])
	})
	
	function delete_schedule($id){
		start_load()
		$.ajax({
			url:'../admin/function-file/ajax.php?action=delete_schedule',
			method:'POST',
			data:{id:$id},
			error: err => {
            console.log(err)
            alert_toast("An error occured.", 'error');
            end_load();
            },
        success: function(resp) {
			console.log(resp)
            if (resp) {
                alert_toast("Data deleted successfully.", 'success')
                setTimeout(function(){
                    location.reload();
                }, 100) 
            } else {
                alert_toast("An error occured.", 'error');
                end_load();
            }
        }
		})
	}
</script>