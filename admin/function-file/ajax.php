<?php
ob_start();
$action = $_GET['action'];
include 'admin_class.php';
$crud = new Action();
if($action == 'login'){
	$login = $crud->login();
	if($login)
		echo $login;
}
if($action == 'login_staff'){
	$login_staff = $crud->login_staff();
	if($login_staff)
		echo $login_staff;
}
if($action == 'login2'){
	$login = $crud->login2();
	if($login)
		echo $login;
}
if($action == 'logout'){
	$logout = $crud->logout();
	if($logout)
		echo $logout;
}
if($action == 'logout2'){
	$logout = $crud->logout2();
	if($logout)
		echo $logout;
}
if($action == 'signin'){
	$signin = $crud->signin();
	if($signin)
		echo $signin;
}
if($action == 'checkout'){
	$checkout = $crud->checkout();
	if($checkout)
		echo $checkout;
}
if($action == 'save_user'){
	$save = $crud->save_user();
	if($save)
		echo $save;
}
if($action == 'delete_user'){
	$save = $crud->delete_user();
	if($save)
		echo $save;
}
if($action == 'signup'){
	$save = $crud->signup();
	if($save)
		echo $save;
}
if($action == 'update_account'){
	$save = $crud->update_account();
	if($save)
		echo $save;
}
if($action == "save_settings"){
	$save = $crud->save_settings();
	if($save)
		echo $save;
}
if($action == "save_staff"){
	$save = $crud->save_staff();
	if($save)
		echo $save;
}
if($action == "delete_staff"){
	$save = $crud->delete_staff();
	if($save)
		echo $save;
}
if($action == "save_schedule"){
	$save = $crud->save_schedule();
	if($save)
		echo $save;
}
if($action == "delete_schedule"){
	$delete = $crud->delete_schedule();
	if($delete)
		echo $delete;
}
if($action == "save_announcement"){
	$get = $crud->save_announcement();
	if($get)
		echo $get;
}
if($action == "get_schedule"){
	$get = $crud->get_schedule();
	if($get)
		echo $get;
}
if($action == "save_time_off"){
	$save = $crud->save_time_off();
	if($save)
		echo $save;
}
if($action == "decide_time_off"){
	$save = $crud->decide_time_off();
	if($save)
		echo $save;
}
if($action == "delete_time_off"){
	$save = $crud->delete_time_off();
	if($save)
		echo $save;
}
if($action == "save_leave_type"){
	$save = $crud->save_leave_type();
	if($save)
		echo $save;
}
if($action == "delete_leave_type"){
	$save = $crud->delete_leave_type();
	if($save)
		echo $save;
}
if($action == "delete_forum"){
	$save = $crud->delete_forum();
	if($save)
		echo $save;
}

if($action == "save_comment"){
	$save = $crud->save_comment();
	if($save)
		echo $save;
}
if($action == "delete_comment"){
	$save = $crud->delete_comment();
	if($save)
		echo $save;
}

if($action == "save_event"){
	$save = $crud->save_event();
	if($save)
		echo $save;
}
if($action == "delete_event"){
	$save = $crud->delete_event();
	if($save)
		echo $save;
}	
if($action == "participate"){
	$save = $crud->participate();
	if($save)
		echo $save;
}
ob_end_flush();
?>
