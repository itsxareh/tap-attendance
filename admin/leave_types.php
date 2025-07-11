<?php 
include('function-file/db_connect.php');
if(isset($_GET['id'])){
    $user = $conn->query("SELECT * FROM leave_types where id =".$_GET['id']);
    foreach($user->fetch_assoc() as $k =>$v){
        $meta[$k] = $v;
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
<div class="container-fluid announce tables">
    <div class="col-lg-8 tables">
            <div class="card">
                <div class="card-header">
            <b>Time-off Types</b>
            <span><button class="btn btn-success text-success btn-block btn-sm col-sm-2 float-right" type="button" id="create_new">
                <i class="fa fa-plus"></i>Add New</button></span>
                </div>
            <div class="card-body">
                <table class="table table-bordered table-condensed table-hover">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>Leave Type</th>
                            <th>Description</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $i = 1;
                            $qry = $conn->query("SELECT * FROM leave_types");
                            while($row = $qry->fetch_assoc()):
                        ?>
                        <tr>
                            <td data-title="#" class="td text-center"><?php echo $i++; ?></td>
                            <td data-title="Leave Type" class="td"><?php echo $row['leave_type'] ?></td>
                            <td data-title="Description" class="td"><?php echo $row['description']?></td>
                            <td data-title="Action" class="text-center">
                                <button class="btn btn-sm btn-outline-success edit_data" href="javascript:void(0)" type="button" data-id="<?php echo $row['id'] ?>" >Edit</button>
                                <button class="btn btn-sm btn-outline-success delete_data" href="javascript:void(0)" type="button" data-id="<?php echo $row['id'] ?>" >Delete</button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    $('#create_new').click(function() {
        uni_modal("Add New Leave Type", "manage_leave_types.php?id=<?= isset($id) ? $id: '' ?>");
    })
    $('.edit_data').click(function() {
        uni_modal("Edit Leave Type", "manage_leave_types.php?id=" + $(this).attr('data-id'));
    })
    $('.delete_data').click(function() {
        _conf("Are you sure to delete this leave type?", "delete_leave_type", [$(this).attr('data-id')])
    })
    $('.table').dataTable({
		columnDefs: [{
			orderable: false,
			targets: [3]
		}],
		initComplete: function(settings, json) {
			$('.table').find('th, td').addClass('')
		},
		drawCallback: function(settings) {
			$('.table').find('th, td').addClass('')
		}
    });
})

function delete_leave_type($id) {
    start_load()
    $.ajax({
        url: "../admin/function-file/ajax.php?action=delete_leave_type",
        method: "POST",
        data: {id: $id},
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