<style>
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
<div class="container-fluid">
	
	<div class="row">
	<div class="col-lg-12">
				<div class="card">
					<div class="card-header">
						<b>Staffs</b>
						<span class="">

							<button class="btn btn-outline-success text-success btn-block btn-sm col-sm-2 float-right" type="button" id="new_user">
					<i class="fa fa-plus"></i> New user</button>
				</span>
					</div>
					<div class="card-body">
				<table class=" table-bordered col-md-13">
				<thead>
				<tr>
					<th class="text-center">#</th>
					<th>Name</th>
					<th>Username</th>
					<th>Type</th>
					<th class="text-center">Action</th>
				</tr>
			</thead>
			<tbody>
				<?php
 					include 'function-file/db_connect.php';
 					$type = array("","Admin","Manager","Alumnus/Alumna");
 					$users = $conn->query("SELECT * FROM users order by name asc");
 					$i = 1;
 					while($row= $users->fetch_assoc()):
				 ?>
				 <tr>
				 	<td data-title="#" class="td text-center">
				 		<?php echo $i++ ?>
				 	</td>
				 	<td data-title="Name" class="td">
				 		<?php echo ucwords($row['name']) ?>
				 	</td>
				 	
				 	<td data-title="Username" class="td">
				 		<?php echo $row['username'] ?>
				 	</td>
				 	<td data-title="Type" class="td">
				 		<?php echo $type[$row['type']] ?>
				 	</td>
					 <td class="text-center">
						<button class="btn btn-sm btn-outline-success edit_user" href="javascript:void(0)" type="button" data-id="<?php echo $row['id'] ?>" >Edit</button>
						<button class="btn btn-sm btn-outline-success delete_user" href="javascript:void(0)" type="button" data-id="<?php echo $row['id'] ?>">Delete</button>
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
	$('table').dataTable();
$('#new_user').click(function(){
	uni_modal('New User','manage_user.php')
})
$('.edit_user').click(function(){
	uni_modal('Edit User','manage_user.php?id='+$(this).attr('data-id'))
})
$('.delete_user').click(function(){
		_conf("Are you sure to delete this user?","delete_user",[$(this).attr('data-id')])
})
$('.table').dataTable({
		columnDefs: [{
			orderable: false,
			targets: [4]
		}],
		initComplete: function(settings, json) {
			$('.table').find('th, td').addClass('')
		},
		drawCallback: function(settings) {
			$('.table').find('th, td').addClass('')
		}
    });
	function delete_user($id){
		start_load()
		$.ajax({
			url:'function-file/ajax.php?action=delete_user',
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