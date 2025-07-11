<?php include 'function-file/db_connect.php' ?>
<?php
if(isset($_GET['id'])){
	$qry = $conn->query("SELECT *,concat(lastname,', ',firstname,' ',middlename) as name FROM staff where id=".$_GET['id'])->fetch_array();
	foreach($qry as $k =>$v){
		$$k = $v;
	}
}

?>
<div class="container-fluid">
	<p><b>Name:</b> <?php echo ucwords($name) ?></p>
	<p><b>Status:</b> </i> <?php echo $status ?></p>
	<p><b>Position:</b> </i> <?php echo $position ?></p>
	<p><b>Gender:</b> <?php echo ucwords($gender) ?></p>
	<p><b>Email:</b> </i> <?php echo $email ?></p>
	<p><b>Contact:</b> </i> <?php echo $contact ?></p>
	<p><b>Address:</b> </i> <?php echo $address ?></p>
	<p><b>Age:</b> </i> <?php echo $age ?></b></p>
	<p><b>Birthdate:</b> </i> <?php echo $birthdate ?></p>
	<p><b>Date joined:</b> </i> <?php echo $datejoined ?></p>
	<hr class="divider">
</div>
	<div class="modal-footer display">
		<button class="btn float-right btn-outline-secondary" type="button" data-dismiss="modal">Close</button>
	</div>
<style>
	p{
		margin:unset;
	}
	#uni_modal .modal-footer{
		display: none;
	}
	#uni_modal .modal-footer.display {
		display: block;
	}
</style>
<script>
	
</script>