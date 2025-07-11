<?php
    include('../admin/function-file/db_connect.php');
    if(isset($_GET['id']) && $_GET['id'] > 0){
        $qry = $conn->query("SELECT * from `time-off-request` where id = '{$_GET['id']}' ");
        if($qry->num_rows > 0){
            foreach($qry->fetch_assoc() as $k => $v){
                $$k=$v;
            }
        }
    }
?>
<div class="container fluid">
        <form action="" id="request-form">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <div class="card-body">
        <div class="form-group">
            <label for="example-date-input" class="col-form-label">Starting Date</label>
            <input class="form-control" type="date" value="<?php echo isset($from_date) ? $from_date : ''; ?>" data-inputmask="'alias': 'date'" required id="example-date-input" name="from_date">
        </div>
        <div class="form-group">
            <label for="example-date-input" class="col-form-label">End Date</label>
            <input class="form-control" type="date" value="<?php echo isset($to_date) ? $to_date : ''; ?>" data-inputmask="'alias': 'date'" required id="example-date-input" name="to_date">
        </div>
        <div class="form-group">
            <label class="col-form-label">Time-Off Type</label>
            <select class="custom-select select-2" id="leave_type" name="leave_type" autocomplete="off">
                <option value="">Click any option here...</option>
                <?php
                $leavetype = $conn->query("SELECT * FROM leave_types");
                while($row= $leavetype->fetch_array()):
                ?>
                <option value="<?php echo ($row['leave_type'])?>" <?php echo isset($leave_type) && $leave_type == $row['leave_type'] ? 'selected' : '' ?>><?php echo ucwords($row['leave_type']) ?><?php echo ucwords($row['description']) ? " - ".$row['description'] : " " ?></option>
            <?php endwhile; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="description" class="col-form-label">Describe Your Conditions</label>
            <textarea name="description" id="description" type="text" class="form-control form" rows="3" required><?php echo isset($description) ? $description : ''; ?></textarea>
        </div>
        </div>
    </form>    
</div>
<script>
    $('#to_date').on('change', function(){
            var startDate = $('#from_date').val();
            var endDate = $('#to_date').val();
            if (endDate < startDate){
                alert_toast('End date should be greater than start date.', 'danger');
            $('#to_date').val('');
            }
        });
    $('#request-form').submit(function(e){
		e.preventDefault()
        if ($('#leave_type').val() == ""){
            alert_toast("Please choose a type.", 'danger');
            return false;
        }
		start_load()
		$('#msg').html('')
		$.ajax({
			url:'../admin/function-file/ajax.php?action=save_time_off',
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
                    alert_toast("Your time-off application has been applied.", 'success')
                    setTimeout(function(){
                        location.reload();
                    }, 100) 
                } else {
                    alert_toast("Could not process this time. Please try again later", 'error');
                    end_load();
                }
            }
		})
	})
</script>