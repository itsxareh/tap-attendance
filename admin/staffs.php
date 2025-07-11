<?php include('function-file/db_connect.php');?>

<div class="container-fluid">
<style>
	input[type=checkbox]
{
  /* Double-sized Checkboxes */
  -ms-transform: scale(1.5); /* IE */
  -moz-transform: scale(1.5); /* FF */
  -webkit-transform: scale(1.5); /* Safari and Chrome */
  -o-transform: scale(1.5); /* Opera */
  transform: scale(1.5);
  padding: 10px;
}
	.table th, .table td {
		padding: 5px 2px;
		vertical-align: middle;
    }

	@media (max-width: 564px){
		.card-body tbody, .card-body tr, .card-body td {
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
		<div class="row">
			<div class="col-md-12 staff-table">
				<div class="card">
					<div class="card-header">
						<b>Staffs</b>
						<span class="">

							<button class="btn btn-outline-success btn-block btn-sm col-sm-2 float-right" type="button" id="new_staff">
					<i class="fa fa-plus"></i> New staff</button>
				</span>
					</div>
					<div class="card-body">	
						<table class="table table-bordered table-condensed table-hover">
							<thead>
								<tr>
									<th class="text-center">#</th>
									<th class="text-center">ID No</th>
									<th class="text-center">Name</th>
									<th class="text-center">Position</th>
									<th class="text-center">Status</th>
									<th class="text-center">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$i = 1;
								$staff =  $conn->query("SELECT *,concat(lastname,', ',firstname,' ',middlename) as name from staff order by concat(lastname,', ',firstname,' ',middlename) asc");
								while($row= $staff->fetch_assoc()):
								?>
								<tr>
									
									<td data-title= "#" class="td text-center"><?php echo $i++ ?></td>
									<td data-title= "ID No." class="td">
										 <p><?php echo $row['id_no'] ?></p> 
										 
									</td>
									<td data-title= "Name" class="td">
										 <p><?php echo ucwords($row['name']) ?></b></p>
									</td>
									<td data-title= "Position" class="td">
										 <p><?php echo $row['position'] ?></p>
									</td>
									<td data-title= "Status" class="td text-center">
										<p>
											<?php 
												$notificationa = $row['notificationa'];
												if ($notificationa == 'warning' || $notificationa == 'warned') {
													$notificationa = '<span class="badge badge-warning bg-gradient px-3 rounded-pill">Warning</span>';
												} elseif ($notificationa == 'penalize' || $notificationa == 'penalized') {
													$notificationa = '<span class="badge badge-danger bg-gradient px-3 rounded-pill">Penalize</span>';
												} else {
													$notificationa = '<span class="badge badge-success bg-gradient px-3 rounded-pill">Good</span>';
												}
												echo ($notificationa);
											?>
										</p>
									</td>
									</td>
									<td data-title="Action" class="text-center">
										<button class="btn btn-sm btn-outline-success view_staff" type="button" data-id="<?php echo $row['id'] ?>" >View info</button>
										<button class="btn btn-sm btn-outline-dark edit_staff" type="button" data-id="<?php echo $row['id'] ?>" >Edit</button>
										<button class="btn btn-sm btn-outline-danger delete_staff" type="button" data-id="<?php echo $row['id'] ?>">Delete</button>
									</td>
								</tr>
								<?php endwhile; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<!-- Table Panel -->
		</div>
	</div>	
</div>
<style>
	
	td{
		vertical-align: middle !important;
	}
	td p{
		margin: unset
	}
	img{
		max-width:100px;
		max-height: 150px;
	}
</style>
<script>
	$(document).ready(function(){
		$('table').dataTable()
	})
	$('#new_staff').click(function(){
		uni_modal("New Entry","manage_staff.php")
	})
	$('.view_staff').click(function(){
		uni_modal("Staff Details","view_staff.php?id="+$(this).attr('data-id'),'')
		
	})
	$('.edit_staff').click(function(){
		uni_modal("Manage Job Post","manage_staff.php?id="+$(this).attr('data-id'))
		
	})
	$('.delete_staff').click(function(){
		_conf("Are you sure to delete this staff?","delete_staff",[$(this).attr('data-id')],'mid-large')
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

	function delete_staff($id){
		start_load()
		$.ajax({
			url:'function-file/ajax.php?action=delete_staff',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully deleted",'success')
					setTimeout(function(){
						location.reload()
					},100)

				}
			}
		})
	}
</script>