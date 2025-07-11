<?php include 'function-file/db_connect.php' ?>
<?php
if(isset($_GET['id'])){
$qry = $conn->query("SELECT * FROM schedules where id= ".$_GET['id']);
foreach($qry->fetch_array() as $k => $val){
	$$k=$val;
}
if(!empty($repeating_data)){
$rdata= json_decode($repeating_data);
	foreach($rdata as $k => $v){
		 $$k = $v;
	}
	$dow_arr = isset($dow) ? explode(',',$dow) : '';
	// var_dump($start);
}
}
?>
<style>
	
	
</style>
<div class="container-fluid">	
	<form action="" id="manage-schedule">
		<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
		<div class="col-lg-12">
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label for="" class="control-label">Staff</label>
						<select name='id_no[]' class="custom-select select2" multiple id='id_no'>
							<option value='0' <?php echo isset($id_no) && $id_no == '0' ? 'selected' : '' ?>>All</option>
							<?php
								if(isset($id)){
									$selected_options_query = $conn->query("SELECT id_no FROM schedules WHERE id = '$id'");
									$selected_options_row = $selected_options_query->fetch_assoc();
									if ($selected_options_row) {
										$selected_options = explode(",", $selected_options_row['id_no']);
									} else {
										$selected_options = [];
									}
								}else{
									$selected_options = [];
								}
								$staff = $conn->query("SELECT *,concat(lastname,', ',firstname,' ',middlename) as name FROM staff order by concat(lastname,', ',firstname,' ',middlename) asc");
								while($row= $staff->fetch_array()){
									echo "<option value='".$row['id_no']."'";
									if(in_array($row['id_no'], $selected_options)) echo " selected";
									echo ">".$row['name']."</option>";
								}
							?>
						</select>
					</div>
					<div class="form-group">
						<label for="title" class="control-label">Title</label>
						<input type="text" autocomplete="off" id="title" class="form-control" name="title" value="<?php echo isset($title) ? $title : ''?>">
					</div>
					<div class="form-group">
						<label for="" class="control-label">Shift Type</label>
						<select name="shift_type" id="" class="custom-select">
						<option value="Opening" <?php echo isset($shift_type) && $shift_type == 'Opening' ? 'selected' : '' ?>>Opening</option>
						<option value="Closing" <?php echo isset($shift_type) && $shift_type == 'Closing' ? 'selected' : '' ?>>Closing</option>
						<option value="Morning shift" <?php echo isset($shift_type) && $shift_type == 'Morning Shift' ? 'selected' : '' ?>>Morning Shift</option>
						<option value="Night shift" <?php echo isset($shift_type) && $shift_type == 'Night Shift' ? 'selected' : '' ?>>Night Shift</option>
						</select>
					</div>
					<!-- <div class="form-group">
						<div class="form-check">
						<input class="form-check-input" type="checkbox" value="1" id="is_repeating" name="is_repeating" <?php echo (isset($is_repeating) && $is_repeating == 1) ? 'checked' : ''; ?>>
						  <label class="form-check-label" for="type">
						   	Weekly Schedule
						  </label>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group for-repeating"  style="display: none">
						<label for="dow" class="control-label">Days of Week</label>
						<select name="dow[]" id="dow" class="custom-select select2" multiple="multiple">
							<?php 
							$dow = array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");
							for($i = 0; $i < 7;$i++):
							?>
							<option value="<?php echo $i ?>"  <?php echo isset($dow_arr) && in_array($i,$dow_arr) ? 'selected' : ''  ?>><?php echo $dow[$i] ?></option>
						<?php endfor; ?>
						</select>
					</div>
					<div class="form-group for-repeating"  style="display: none">
						<label for="" class="control-label">Month From</label>
						<input type="month" name="month_from" id="month_from" class="form-control" value="<?php echo isset($start) ? date("Y-m",strtotime($start)) : '' ?>">
					</div>
					<div class="form-group for-repeating"  style="display: none">
						<label for="" class="control-label">Month To</label>
						<input type="month" name="month_to" id="month_to" class="form-control" value="<?php echo isset($end) ? date("Y-m",strtotime($end)) : '' ?>">
					</div> -->
					<div class="form-group for-nonrepeating">
						<label for="" class="control-label">Schedule Date</label>
						<input type="date" name="schedule_date" id="schedule_date" class="form-control" value="<?php echo isset($schedule_date) ? $schedule_date : '' ?>">
					</div>
					<div class="form-group for-nonrepeating">
						<label for="" class="control-label">Schedule End</label>
						<input type="date" name="schedule_end" id="schedule_end" class="form-control" value="<?php echo isset($schedule_end) ? $schedule_end : '' ?>">
					</div>
					<div class="form-group">
						<label for="" class="control-label">Time From</label>
						<input type="time" name="time_from" id="time_from" class="form-control" value="<?php echo isset($time_from) ? $time_from : '' ?>">
					</div>
					<div class="form-group">
						<label for="" class="control-label">Time To</label>
						<input type="time" name="time_to" id="time_to" class="form-control" value="<?php echo isset($time_to) ? $time_to : '' ?>">
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
<div class="imgF" style="display: none " id="img-clone">
			<span class="rem badge badge-primary" onclick="rem_func($(this))"><i class="fa fa-times"></i></span>
	</div>
<script>
$(document).ready(function() {
$('#schedule_end, #time_to').on('change', function() {
	if($('#schedule_date').is(':visible') && $('#schedule_end').is(':visible')){
		var scheduleDate = $('#schedule_date').val();
		var scheduleEnd = $('#schedule_end').val();
		var timeFrom = $("#time_from").val();
		var timeTo = $("#time_to").val();
		
		if(scheduleEnd < scheduleDate){
			alert_toast("End schedule must be greater than date schedule" , 'danger');
			$('#schedule_end').val('');
		} else if(scheduleEnd == scheduleDate){
			if (timeTo < timeFrom){
			alert_toast("End time must be greater than start time" , 'danger');
			$('#time_to').val('');
			}
		}
	} 	
});
// $('#month_to, #time_to').on('change', function() {
//     if($('#month_from').is(':visible') && $('#month_to').is(':visible')){
//         var monthDate = $('#month_from').val();
//         var monthEnd = $('#month_to').val();
//         var timeFrom = $("#time_from").val();
//         var timeTo = $("#time_to").val();

//         if(monthEnd < monthDate){
//             alert_toast("End month must be greater than date month" , 'danger');
//             $('#month_to').val('');
//         } else if (timeTo < timeFrom){
// 			alert_toast("End time must be greater than start time" , 'danger');
// 			$('#time_to').val('');
// 		}
//     }
// });

	// if('<?php echo isset($id) ? 1 : 0 ?>' == 1){
	// 	if($('#is_repeating').prop('checked') == true){
	// 		$('.for-repeating').show()
	// 		$('.for-nonrepeating').hide()
	// 	}else{
	// 		$('.for-repeating').hide()
	// 		$('.for-nonrepeating').show()
	// 	}
	// }
	// $('#is_repeating').change(function(){
	// 	if($(this).prop('checked') == true){
	// 		$('.for-repeating').show()
	// 		$('.for-nonrepeating').hide()
	// 	}else{
	// 		$('.for-repeating').hide()
	// 		$('.for-nonrepeating').show()
	// 	}
	// })
	$('.select2').select2({
		placeholder:'Please Select Here',
		width:'100%'
	})
	
	$('#manage-schedule').submit(function(e){
		e.preventDefault()
		if($('#schedule_date').is(':visible') && $('#schedule_end').is(':visible')){
			var scheduleDate = $('#schedule_date').val();
			var scheduleEnd = $('#schedule_end').val();
			var timeFrom = $("#time_from").val();
			var timeTo = $("#time_to").val();
			
			if(scheduleEnd < scheduleDate){
				alert_toast("End schedule must be greater than date schedule" , 'danger');
				$('#schedule_end').val('');
				return false;
			} else if(scheduleEnd == scheduleDate){
				if (timeTo < timeFrom){
				alert_toast("End time must be greater than start time" , 'danger');
				$('#time_to').val('');
				return false;
				}
			}
		} 	
		start_load()
		$('#msg').html('')
		$.ajax({
			url:'function-file/ajax.php?action=save_schedule',
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
			success:function(resp){
				console.log(resp)
				if(resp==1){
					alert_toast("Schedule successfully saved",'success')
					setTimeout(function(){
						location.reload()
					},100)

				}
				
			}
		})
	})
});
</script>