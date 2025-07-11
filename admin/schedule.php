<?php 
include('function-file/db_connect.php');
if(isset($_GET['id'])){
$user = $conn->query("SELECT * FROM schedules where id =".$_GET['id']);
	foreach($user->fetch_array() as $k =>$v){
		$meta[$k] = $v;
	}
}
?>
<style>
    .table th, .table td {
    padding: 5px 2px;
    vertical-align: middle;
    }

    @media (max-width: 760px){
    .card-body tbody, .card-body tr, .card-body td{
        display: block;
    }
    .card-body thead tr {
        position: absolute;
        top: -9999px;
        left: -9999px;
    }
    .card-body .td {
        position: relative;
        padding-left: 50%;
        border: none;
        border-bottom: 1px solid #eee;
    }
    .card-body .td::before {
        content: attr(data-title);
        position: absolute;
        left: 5px;
    }
    .card-body tr {
        border-bottom: 1px solid #ccc;
    }
    .td {
        text-align: end !important;
    }
}
</style>
<div class="container-fluid">
	<div class="row">
	    <div class="col-lg-12">
			<div class="card">
				<div class="card-header">
				<b>Schedules</b>
				<span class=""><button class="btn btn-outline-success  btn-block btn-sm col-sm-2 float-right" type="button" id="new_schedule">
				<i class="fa fa-plus"></i> New schedule</button>
				</span>
				</div>
    <div class="card-body">
		<?php 
			$months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
			for($m = 0; $m < count($months); $m++) {
			$month = date('m', strtotime("01-" . $months[$m] . "-2000"));
			$qry = $conn->query("SELECT schedules.*, GROUP_CONCAT(CONCAT(staff.lastname, ', ' , staff.firstname,' ', COALESCE(staff.middlename,'')) SEPARATOR ', ') as name FROM schedules 
			LEFT JOIN staff ON FIND_IN_SET(staff.id_no, schedules.id_no) > 0
			WHERE MONTH(schedules.schedule_date) = '$month'
			GROUP BY schedules.id
			ORDER BY schedules.date_created desc");
			if ($qry->num_rows > 0) {
				echo "<p style='font-size: 40px'; class='month text-center'>" . $months[$m] . "</p>";
		?>
        <table class="table table-bordered table-condensed table-hover">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
						<th>Staff</th>
                        <th>Start Date</th>
                        <th>End Date</th>
						<th>Start Time</th>
                        <th>End Time</th>
                        <th>Shift Type</th>
						<th>Date Added</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
				<?php 
					$i = 1;
						while($row = $qry->fetch_assoc()){
				?>
					<tr>
						<td data-title="#" class="td text-center"><?php echo $i++; ?></td>
						<td data-title="Staff" class="td">
							<?php 
								echo ($row['id_no'] == 0) ? 'All' : $row['name'];
							?>
						</td>
                        <td data-title="Start Date" class="td"><?php echo date("M d, Y", strtotime($row['schedule_date'])) ?></td>
                        <td data-title="End Date" class="td"><?php echo date("M d, Y", strtotime($row['schedule_end'] )) ?></td>				
						<td data-title="Start Time" class="td"><?php echo date("G:i:s", strtotime($row['time_from'])) ?></td>
                        <td data-title="End Time" class="td"><?php echo date("G:i:s", strtotime($row['time_to'])) ?></td>
                        <td data-title="Shift Type" class="td"><?php echo $row['shift_type'] ?></td>
						<td data-title="Date Added" class="td"><?php echo date("Y-m-d H:i:s",strtotime($row['date_created'])) ?></td>
						<td data-title="Action" class="text-center">
							<a class="btn btn-sm btn-outline-success text-success" href="./?page=attendance&schedule_id=<?= $row['id'] ?>" type="button" data-id="<?php echo $row['id'] ?>" >View</a>
                            <a class="btn btn-sm btn-outline-success text-success edit_schedule" type="button" data-id="<?php echo $row['id'] ?>" >Edit</a>
                            <a class="btn btn-sm btn-outline-success text-success delete_schedule" type="button" data-id="<?php echo $row['id'] ?>">Delete</a>
						</td>
                    </tr>
                    <?php   } ?>
                </tbody>
            </table>
			<?php
                } 
                } 
            ?>
        </div>
    </div>
</div>
</div>
</div>
<script>
	$(document).ready(function(){
		$('table').dataTable()
	})
	$('#new_schedule').click(function(){
		uni_modal("New Schedule","manage_schedule.php")
	})
	$('.view_schedule').click(function(){
		uni_modal("Schedule Details","view_schedule.php?id="+$(this).attr('data-id'))
		
	})
	$('.edit_schedule').click(function(){
		uni_modal("Manage Schedule","manage_schedule.php?id="+$(this).attr('data-id'))
		
	})
	$('.delete_schedule').click(function(){
		_conf("Are you sure to delete this schedule?","delete_schedule",[$(this).attr('data-id')],'mid-large')
	})
	$('.table').dataTable({
		columnDefs: [{
			orderable: false,
			targets: [7]
		}],
		initComplete: function(settings, json) {
			$('.table').find('th, td').addClass('')
		},
		drawCallback: function(settings) {
			$('.table').find('th, td').addClass('')
		}
    });

	function delete_schedule($id){
		start_load()
		$.ajax({
			url:'function-file/ajax.php?action=delete_schedule',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Schedule successfully deleted",'success')
					setTimeout(function(){
						location.reload()
					},100)

				}
			}
		})
	}
</script>