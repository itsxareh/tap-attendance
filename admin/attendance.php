<?php 
include('function-file/db_connect.php');
if(isset($_GET['schedule_id'])){
    $qry = $conn->query("SELECT * FROM `schedules` where id = '{$_GET['schedule_id']}' ");
    if($qry->num_rows > 0){
        foreach($qry->fetch_array() as $k=> $v){
            if(!is_numeric($k))
            $$k = $v;
        }
    }
}
?>
<style>
        .table th, .table td {
        padding: 5px 2px;
        vertical-align: middle;
    }

    	@media (max-width: 564px){
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
<div class="card card-outline card-primary rounded-0 shadow">
    <div class="card-header row justify-content-center">
        <h3 class="card-title pl-3">Attendance</h3>
    </div>
    <div class="card-body">
            <table class="table table-bordered table-stripped">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th>Staff</th>
                        <th>In Time</th>
                        <th>In Status</th>
                        <th>Out Time</th>
                        <th>Out Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
					$i = 1;
						$qry = $conn->query("SELECT p. *, CONCAT(e.lastname, ', ' , e.firstname,' ', COALESCE(e.middlename,'')) as fullname from `attendance` p inner join staff e on p.id_no = e.id_no where p.schedule_id = '{$id}'");
						while($row = $qry->fetch_assoc()):
					?>
                    <tr>
                        <td data-title="#" class="td text-center"><?php echo $i++; ?></td>
                        <td data-title="Staff" class="td"><?php echo $row['fullname'] ?></td>
                        <td data-title="In Time" class="td"><?php echo date("H:i:s", strtotime($row['in_time'])) ?></td>
                        <td data-title="In Status" class="td text-center">
                            <?php 
                            switch($row['in_status']){
                                case 'Early':
                                    echo '<span class="badge badge-success border bg-gradient px-3 rounded-pill">Early</span>';
                                    break;
                                case 'Late':
                                    echo '<span class="badge badge-dark bg-gradient px-3 rounded-pill">Late</span>';
                                    break;
                            }
                            ?>
                        </td>
                        <td data-title="Out Time" class="td"><?php echo date("H:i:s", strtotime($row['out_time'])) ?></td>
                        <td data-title="Out Status" class="td text-center">
                            <?php 
                            switch($row['out_status']){
                                case 'Early':
                                    echo '<span class="badge badge-success border bg-gradient px-3 rounded-pill">Early</span>';
                                    break;
                                case 'Overtime':
                                    echo '<span class="badge badge-dark bg-gradient px-3 rounded-pill">Late/Overtime</span>';
                                    break;
                            }
                            ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    $('#create_new').click(function() {
        uni_modal("Add New Payslip", "manage_payslip.php?payroll_id=<?= isset($id) ? $id: '' ?>", 'large');
    })
    $('.edit_data').click(function() {
        uni_modal("Edit Payslip", "manage_payslip.php?payroll_id=<?= isset($id) ? $id: '' ?>&id=" + $(this).attr('data-id'));
    })
    $('.view_data').click(function() {
        uni_modal("View Payslip", "view-payslip.php?id=" + $(this).attr('data-id'));
    })
    $('.delete_data').click(function() {
        _conf("Are you sure to delete this payslip permanently?", "delete_payslip", [$(this).attr(
            'data-id')],'large')
    })
    $('.print_btn').click(function(){
        var nw = window.open("print_payslip.php?id=" + $(this).attr('data-id'),"_blank","height=500,width=800")
        setTimeout(function(){
            nw.print()
            setTimeout(function(){
                nw.close()
                },100)
        },100)
    })
    $('.table').dataTable({
		columnDefs: [{
			orderable: false,
			targets: [5]
		}],
		initComplete: function(settings, json) {
			$('.table').find('th, td').addClass('')
		},
		drawCallback: function(settings) {
			$('.table').find('th, td').addClass('')
		}
    });
})
function delete_payslip($id) {
    start_load()
    $.ajax({
        url: "function-file/master_payroll.php?f=delete_payslip",
        method:'POST',
			data:{id:$id},
            error: err => {
            console.log(err)
            alert_toast("An error occured.", 'error');
            end_load();
            },
            success: function(resp) {
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