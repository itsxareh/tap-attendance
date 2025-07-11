<?php
include('function-file/db_connect.php');
if(isset($_GET['id']) && $_GET['id'] > 0){
    $qry = $conn->query("SELECT * from `time-off-request` where id = '{$_GET['id']}' ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_assoc() as $k => $v){
            $$k=$v;
        }
    }
}
?>
<div class="container-fluid">
    <form action="" id="time-off-action-form">
    <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <div class="form-group mb-3">
            <label for="status" class="control-label">Status</label>
            <select name="stats" id="stats" class="custom-select rounded-0">
                <option value="pending" <?php echo isset($stats) && $stats == 'pending' ? 'selected' : '' ?>>Pending</option>
                <option value="approved" <?php echo isset($stats) && $stats == 'approved' ? 'selected' : '' ?>>Approve</option>
                <option value="declined" <?php echo isset($stats) && $stats == 'declined' ? 'selected' : '' ?>>Decline</option>
            </select>
        </div>
        <div class="form-group mb-3">
            <label for="admin_remark" class="control-label">Remark</label>
            <textarea name="admin_remark" id="admin_remark" type="admin_remark" class="form-control rounded-0 form no-resize" rows="3"
                value="<?php echo isset($admin_remark) ? $admin_remark : ''; ?>" required><?php echo isset($admin_remark) ? $admin_remark : ''; ?></textarea>
        </div>

    </form>
</div>
<script>
$(document).ready(function() {
    $('#time-off-action-form').submit(function(e){
		e.preventDefault()
		start_load()
		$('#msg').html('')
		$.ajax({
			url:'function-file/ajax.php?action=decide_time_off',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
            error: err => {
            console.log(err)
            alert_toast("An error occured.", 'error');
            end_load();
            },
            success: function(resp) {
                if (resp) {
                    alert_toast("Data saved successfully.", 'success')
                    setTimeout(function(){
                        location.reload();
                    }, 100) 
                } else {
                    alert_toast("An error occured.", 'error');
                    end_load();
                }
            }
		})
	})
})
</script>