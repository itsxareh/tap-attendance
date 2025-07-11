<!DOCTYPE html>
<html lang="en">
<?php 
session_start();
include('../admin/function-file/db_connect.php');
ob_start();
ob_end_flush();
?>
<head>
	<link rel="shortcut icon" type="x-icon" href="../admin/images/staff-icon.png">
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Staff Schedules</title>
 	

<?php include('./header.php'); ?>
<?php 
if(isset($_SESSION['login_id']))
header("location:index.php");

?>

</head>
<style>
	main .main-image{
		width: 100%;
		height: 100%;
		position: absolute;
	}

	main{
		width: 100vw;
		height: 100vh;
		align-items: center;
		justify-content: center;
	}
	.card-body {
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
	}
	button:hover {
		background-color: black !important;
		color: white !important;
		border: 1px solid white !important;
	}
	.card {
		width: 100%;
		height: 100%;
		display: flex;
		align-items: center;
		justify-content: center;
		background-color: transparent;
	}
</style>

<body>


  <main id="main" class="">
  	<img src="../admin/images/login-bg.jpg" class="main-image">
  			<div class="card">
  				<div class="card-body">		
  					<form id="login-form" >
					  <h4><b class="text-4xl">Stamp your attendance now!</b></h4>
  						<div class="form-group">
  							<label for="id_no" class="control-label mt-4">Please enter your ID No.</label>
  							<input type="text" id="id_no" name="id_no" class="form-control" autocomplete="off">
  						</div>
  						<center><button class="text-lg btn-sm btn-block btn-wave col-md-4 p-2 login">Login</button></center>
						<div class="form-footer text-center mt-4 mr-2">
						<p class="text-muted text-lg"><a href="../admin/login.php">Go to Admin Panel</a></p>
                		</div>
					</form>
  				</div>
  			</div>
  		</div>
   

  </main>

  <a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>


</body>
<script>
	$('#login-form').submit(function(e){
		e.preventDefault()
		$('#login-form button[type="button"]').attr('disabled',true).html('Logging in...');
		if($(this).find('.alert-danger').length > 0 )
			$(this).find('.alert-danger').remove();
		$.ajax({
			url:'../admin/function-file/ajax.php?action=login_staff',
			method:'POST',
			data:$(this).serialize(),
			error:err=>{
				console.log(err)
		$('#login-form button[type="button"]').removeAttr('disabled').html('Login');

			},
			success:function(resp){
				if(resp == 1){
					location.href ='index.php?page=home';
				}else{
					$('#login-form').prepend('<div class="alert alert-danger">ID Number is incorrect.</div>')
					$('#login-form button[type="button"]').removeAttr('disabled').html('Login');
				}
			}
		})
	})
</script>	
</html>