<?php
include 'function-file/db_connect.php';
if(isset($_GET['id'])){
	$qry = $conn->query("SELECT * FROM staff where id=".$_GET['id'])->fetch_array();
	foreach($qry as $k =>$v){
		$$k = $v;
	}
}
?>
<div class="container-fluid">
	<form action="" id="manage-staff">
		<div id="msg"></div>
				<input type="hidden" name="id" value="<?php echo isset($_GET['id']) ? $_GET['id']:'' ?>" class="form-control">
		<div class="row form-group">
			<div class="col-md-4">
						<label class="control-label">ID No.</label>
						<input type="number" name="id_no" class="form-control" value="<?php echo isset($id_no) ? $id_no:'' ?>" >
						<small><i>Leave this blank if you want to a auto generate ID no.</i></small>
					</div>
			<div class="col-md-4">
					<label class="control-label">Status</label>
				<select name="status" required="" class="custom-select" id="">
					<option <?php echo isset($status) && $status == 'Crew Trainer' ? 'selected' : '' ?>>Crew Trainer</option>
					<option <?php echo isset($status) && $status == 'Maintenance' ? 'selected' : '' ?>>Maintenance</option>
					<option <?php echo isset($status) && $status == 'Probationary' ? 'selected' : '' ?>>Probationary</option>
					<option <?php echo isset($status) && $status == 'Regular' ? 'selected' : '' ?>>Regular</option>
				</select>
			</div>
			<div class="col-md-4">
					<label class="control-label">Position</label>
				<select name="position" required="" class="custom-select" id="">
					<option <?php echo isset($position) && $position == 'Manager' ? 'selected' : '' ?>>Manager</option>
					<option <?php echo isset($position) && $position == 'Crew Trainer' ? 'selected' : '' ?>>Crew Trainer</option>
					<option <?php echo isset($position) && $position == 'Production' ? 'selected' : '' ?>>Production</option>
					<option <?php echo isset($position) && $position == 'Service Crew' ? 'selected' : '' ?>>Service Crew</option>
					<option <?php echo isset($position) && $position == 'French Fries' ? 'selected' : '' ?>>French Fries</option>
					<option <?php echo isset($position) && $position == 'Customer Area' ? 'selected' : '' ?>>Customer Area</option>
					<option <?php echo isset($position) && $position == 'Drive-Thru' ? 'selected' : '' ?>>Drive-Thru</option>
					<option <?php echo isset($position) && $position == 'Maintenance' ? 'selected' : '' ?>>Maintenance</option>
				</select>
			</div>
		</div>
		<div class="row form-group">
			<div class="col-md-4">
				<label class="control-label">Last Name</label>
				<input type="text" name="lastname" class="form-control" value="<?php echo isset($lastname) ? $lastname:'' ?>" required>
			</div>
			<div class="col-md-4">
				<label class="control-label">First Name</label>
				<input type="text" name="firstname" class="form-control" value="<?php echo isset($firstname) ? $firstname:'' ?>" required>
			</div>
			<div class="col-md-4">
				<label class="control-label">Middle Name</label>
				<input type="text" name="middlename" class="form-control" value="<?php echo isset($middlename) ? $middlename:'' ?>">
			</div>
		</div>
		<div class="row form-group">
			<div class="col-md-4">
				<label class="control-label">Email</label>
				<input type="email" name="email" class="form-control" value="<?php echo isset($email) ? $email:'' ?>" required>
			</div>
			<div class="col-md-4">
				<label class="control-label">Contact #</label>
				<input type="number" name="contact" class="form-control" value="<?php echo isset($contact) ? $contact:'' ?>" required>
			</div>
			<div class="col-md-4">
				<label class="control-label">Gender</label>
				<select name="gender" required="" class="custom-select" id="">
					<option <?php echo isset($gender) && $gender == 'Male' ? 'selected' : '' ?>>Male</option>
					<option <?php echo isset($gender) && $gender == 'Female' ? 'selected' : '' ?>>Female</option>
				</select>
			</div>
		</div>
		<div class="row form-group">
			<div class="col-md-4">
				<label class="control-label">Age</label>
				<input type="number" name="age" class="form-control" value="<?php echo isset($age) ? $age:'' ?>" required>
			</div>
			<div class="col-md-4">
				<label class="control-label">Birthdate</label>
				<input type="date" name="birthdate" id="birthdate" class="form-control" value="<?php echo isset($birthdate) ? $birthdate : '' ?>">
			</div>
			<div class="col-md-4">
				<label class="control-label">Date joined</label>
				<input type="date" name="datejoined" id="datejoined" class="form-control" value="<?php echo isset($datejoined) ? $datejoined : '' ?>">
			</div>
		</div>
		<div class="row form-group">
			<div class="col-md-12">
				<label class="control-label">Address</label>
				<textarea name="address" class="form-control"><?php echo isset($address) ? $address : '' ?></textarea>
			</div>

	</form>
</div>

<script>
	$('#manage-staff').submit(function(e){
		e.preventDefault()
		start_load()
		$.ajax({
			url:'function-file/ajax.php?action=save_staff',
			method:'POST',
			data:$(this).serialize(),
			success:function(resp){
				if(resp == 1){
					alert_toast("Data successfully saved.",'success')
					setTimeout(function(){
						location.reload()
					},100)
				}else if(resp == 2){
					$('#msg').html('<div class="alert alert-danger">ID No already existed.</div>')
					end_load();
				}
			}
		})
	})
</script>