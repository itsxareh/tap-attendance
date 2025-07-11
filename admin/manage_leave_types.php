<?php 
include('function-file/db_connect.php');
if(isset($_GET['id'])){
    $leave_type = $conn->query("SELECT * FROM leave_types where id ='{$_GET['id']}' ");
    if($leave_type->num_rows > 0){
        foreach($leave_type->fetch_assoc() as $k =>$v){
        $meta[$k] = $v;
        }
    }
}   

?>
<div class="container-fluid">
	<div id="msg"></div>
	<form action="" id="manage_leave_types">	
		<input type="hidden" name="id" value="<?php echo isset($meta['id']) ? $meta['id']: '' ?>">
		<div class="form-group">
			<label for="leave_type">Leave Type</label>
			<input type="text" name="leave_type" id="leave_type" class="form-control" value="<?php echo isset($meta['leave_type']) ? $meta['leave_type']: '' ?>" required>
		</div>
		<div class="form-group">
			<label for="description">Description</label>
			<input type="text" name="description" id="description" class="form-control" value="<?php echo isset($meta['description']) ? $meta['description']: '' ?>" required  autocomplete="off">
		</div>
	</form>
</div>
<script>
	$('#manage_leave_types').submit(function(e){
		e.preventDefault();
		start_load()
		$.ajax({
			url:'function-file/ajax.php?action=save_leave_type',
			method:'POST',
			data:$(this).serialize(),
			success:function(resp){
				if(resp ==1){
					alert_toast("Data successfully saved",'success')
					setTimeout(function(){
						location.reload()
					},100)
				}else{
					$('#msg').html('<div class="alert alert-danger">Leave Type already exist.</div>')
					end_load()
				}
			}
		})
	})

</script>