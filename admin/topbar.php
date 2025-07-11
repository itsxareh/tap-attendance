<?php 
include("function-file/db_connect.php");
?>
<style>
	.collapse a{
		text-indent: 10px;
	}
	a{
		border-radius: 3px;
	}
  .noti > ul > li {
    position: relative;
    display: inline-block;
  }
  .noti > ul > li .dropdown-check {
    display: none;
  }
  .noti > ul > li .dropdown-check:checked ~ .dropdown {
    visibility: visible;
    opacity: 1;
  }
  .noti > ul > li > a > .count {
    position: absolute;
    right: 6px;
    top: 1px;
    border-radius: 50%;
    font-size: 0.8rem;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #fff;
    color: #ff0000;
    width: 12px;
    height: 12px;
    cursor: default;
  }
  .noti > ul > li > a {
    color: #fff;
    font-size: 1.5rem;
    display: inline-block;
  }
  .noti > ul > li > a > label{
    cursor: pointer;
  }
  .noti ul li .dropdown {
    position: absolute;
    top: 100%;
    left: -150px;
    background-color: #fff;
    border: 1px solid #ccc;
    padding: 1rem;
    visibility: hidden; 
    opacity: 0;
    width: 225px;
    transition: 0.3s;
  }
  .noti ul li .dropdown li {
    margin-bottom: 1rem;
    border-bottom: 1px solid #ccc;
    padding-bottom: 1rem;
  }
  .noti ul li .dropdown li a:hover {
    color: black;
    text-decoration: none;
}
  .noti ul li .dropdown li:last-child {
    margin-bottom: 0;
    padding-bottom: 0;
    border-bottom: 0;
  }
  .menu-btn {
    position: absolute;
    left: 5px;
    width: 20px;
    display: none;
    cursor: pointer;
    z-index: 10;
  }
  .system {
    font-size: 16px;
    white-space: nowrap;
    margin-left: 0.3em;
    color: white;

  }
  .navibar { 
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .topbar {
    width: 100%;
    align-items: center;
    justify-content: space-between;
  }
  .title-logo{
    max-height: 4rem;
    display: flex;
    align-items: center;
    width: 100%;
    z-index: 10;
  }
  .title-logo .logo img{
    min-width: 3.5rem;
    max-height: 3.5rem;
    min-height: 3.5rem;
  }
  .top-bar {
    padding: 0px !important;
  }

  @media (max-width: 1090px){
    .menu-btn {
      display: block;
    }
    .top-bar {
    padding-left: 15px !important;
    }
    .navibar {
      position: absolute;
      top: -500px;
      left: -15px;
      right: 0;
      width: 100vw;
      background-color: #28a745;
      display: flex;
      flex-direction: column;
      align-items:center;
      border-bottom-right-radius: 50px;
      border-bottom-left-radius: 50px;
      transition: all .50s ease;
    }
    .navibar li a{
      display: block;
      transition: all .50s ease;
    }
    .navibar.open {
      top: 110%;
    }
  }
</style>

<nav class="navbar navbar-light fixed-top bg-success" style="padding:0;min-height: 3.5rem">
<div class="container-fluid top-bar mt-2 mb-2">
    <img class="menu-btn" src="../admin/images/menu-icon.png">
  	    <div class="topbar col-lg-12 items-center grid grid-cols-6 gap-2">
            <div class="title-logo">
                <div class="logo">
                <a href="index.php?page=home"><img class="" src="images/staff-icon.png"></a>
                </div>
                <div class="title">
                <a class="system" href="index.php?page=home">Attendance Manager</a>
                </div>
            </div>
            <div class="col-span-4 flex items-center justify-center">
                <div class="flex whitespace-nowrap">
                    <ul class="navibar">
                        <li><a href="index.php?page=home" class="nav-item nav-home"><span class='icon-field'><i class="fa fa-home"></i></span> Home</a></li>
                        <?php 
                        if(!empty($_SESSION['login_type'])){
                            if($_SESSION['login_type'] == 1 | 2){
                            echo "<li><a href='index.php?page=staffs' class='nav-item nav-staffs'><span class='icon-field'><i class='fa fa-users'></i></span> Staffs</a></li>
                            <li><a href='index.php?page=schedule' class='nav-item nav-schedule'><span class='icon-field'><i class='fa fa-calendar-day'></i></span> Schedule</a></li>
                            <li><a href='index.php?page=time-off-requests' class='nav-item nav-time-off-requests'><span class='icon-field'><i class='fa fa-calendar-times'></i></span> Time-off Requests</a></li>			
                            <li><a href='index.php?page=leave_types' class='nav-item nav-leave_types'><span class='icon-field'><i class='fa fa-calendar-minus'></i></span> Time-off Types</a></li>	
                            <li><a href='index.php?page=users' class='nav-item nav-users'><span class='icon-field'><i class='fa fa-user-tie'></i></span>Users</a></li>";
                            }
                        }
                        ?>
                    </ul>
                </div>
            </div>
            <div class="items-center flex flex-1 justify-end noti">
                <ul>
                    <li>
                        <?php
                        $sql = "SELECT p.id_no, p.date_created, CONCAT(e.lastname, ', ' , e.firstname,' ', COALESCE(e.middlename,'')) as fullname, p.notifications FROM `time-off-request` p INNER JOIN staff e ON p.id_no = e.id_no WHERE p.notifications = 'request' GROUP BY p.id_no
                        union SELECT a.schedule_date, a.in_time, CONCAT(e.lastname, ', ' , e.firstname,' ', COALESCE(e.middlename,'')) as fullname, e.notificationaa FROM staff e INNER JOIN ( SELECT id_no, MAX(schedule_date) as schedule_date, MAX(in_time) as in_time FROM attendance GROUP BY id_no ) a ON e.id_no = a.id_no WHERE e.notificationaa = 'warning'or e.notificationaa = 'penalize'
                        ORDER BY date_created DESC LIMIT 5;";
                        $res = mysqli_query($conn, $sql);
                        ?>
                        <a href="#" id="notifications"><label for="check"><i class="fa fa-bell mr-3" aria-hidden="true"></i></label>
                        <span class="count text-black-50"><?php echo mysqli_num_rows($res); ?></span></a>
                        <input type="checkbox" class="dropdown-check" id="check"/>
                        <ul class="dropdown">
                            <?php   
                              if (mysqli_num_rows($res) < 1){
                                echo "No notifications.";
                              } else {
                                $warning_notifications = array();
                                $penalize_notifications = array();
                                $request_notifications = array();
                                while($row = mysqli_fetch_assoc($res)){
                                    if($row['notifications'] == 'warning'){
                                        $warning_notifications[] = array(
                                            'fullname' => $row['fullname'],
                                            'id_no' => $row['id_no']
                                        );
                                    } else if($row['notifications'] == 'penalize'){
                                      $penalize_notifications[] = array(
                                          'fullname' => $row['fullname'],
                                          'id_no' => $row['id_no']
                                      );
                                    } elseif($row['notifications'] == 'request'){
                                        $request_notifications[] = array(
                                            'fullname' => $row['fullname'],
                                            'date_created' => $row['date_created']
                                        );
                                    }
                                }
                                if(count($warning_notifications) > 0){
                                  if (count($warning_notifications) == 1){
                                    echo '<li><a class="schedule" href="./?page=schedule">';
                                    echo $warning_notifications[0]['fullname'] . ' received a warning notification for being late three(3) times.';
                                    echo '</a></li>';
                                  } elseif(count($warning_notifications) == 2){
                                    echo '<li><a class="schedule" href="./?page=schedule">';
                                    echo $warning_notifications[0]['fullname'] . ' and ' . $warning_notifications[1]['fullname'] . ' received a warning notification for being late three(3) times.';
                                    echo '</a></li>';
                                  } else {
                                    echo '<li><a class="schedule" href="./?page=schedule">';
                                    echo $warning_notifications[0]['fullname'] . ', ' . $warning_notifications[1]['fullname'] . ', and ' . (count($warning_notifications) - 2) . ' others received a warning notification for being late three(3) times.';
                                    echo '</a></li>';
                                  }
                                }
                                if(count($penalize_notifications) > 0){
                                  if (count($penalize_notifications) == 1){
                                    echo '<li><a class="schedule" href="./?page=schedule">';
                                    echo $penalize_notifications[0]['fullname'] . ' received a penalize notification for being late five(5) times.';
                                    echo '</a></li>';
                                  } elseif(count($penalize_notifications) == 2){
                                    echo '<li><a class="schedule" href="./?page=schedule">';
                                    echo $penalize_notifications[0]['fullname'] . ' and ' . $penalize_notifications[1]['fullname'] . ' received a penalize notification for being late five(5) times.';
                                    echo '</a></li>';
                                  } else {
                                    echo '<li><a class="schedule" href="./?page=schedule">';
                                    echo $penalize_notifications[0]['fullname'] . ', ' . $penalize_notifications[1]['fullname'] . ', and ' . (count($penalize_notifications) - 2) . ' others received a penalize notification for being late five(5) times.';
                                    echo '</a></li>';
                                  }
                                }
                                if(count($request_notifications) > 0){
                                  if(count($request_notifications) == 1){
                                    echo '<li><a class="time-off" href="./?page=time-off-requests">';
                                    echo $request_notifications[0]['fullname'] . ' requested for a time-off';
                                    echo '<br>';
                                    echo '<small>' . $request_notifications[0]['date_created'] . '</small>';
                                    echo '</a></li>';
                                  } elseif(count($request_notifications) == 2){
                                    echo '<li><a class="time-off" href="./?page=time-off-requests">';
                                    echo $request_notifications[0]['fullname'] . ' and ' . $request_notifications[1]['fullname'] . ' requested for a time-off.';
                                    echo '<br>';
                                    echo '<small>' . $request_notifications[0]['date_created'] . '</small>';
                                    echo '</a></li>';
                                  } else {
                                    echo '<li><a class="time-off" href="./?page=time-off-requests">';
                                    echo $request_notifications[0]['fullname'] . ', ' . $request_notifications[1]['fullname'] . ', and ' . (count($request_notifications) - 2) . ' others requested for a time-off';
                                    echo '<br>';
                                    echo '<small>' . $request_notifications[0]['date_created'] . '</small>';
                                    echo '</a></li>';
                                  }
                                }
                            }
                            ?>
                        </ul>
                    </li>
                </ul>
            <div class="dropdown">
                <a href="#" class="user text-white dropdown-toggle"  id="account_settings" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i><?php echo ucwords($_SESSION['login_name'])?></i> </a>
                <div class="dropdown-menu" aria-labelledby="account_settings">
                    <a class="dropdown-item" href="javascript:void(0)" id="manage_my_account"><i class="fa fa-cog"></i> Manage Account</a>
                    <a class="dropdown-item" href="function-file/ajax.php?action=logout"><i class="fa fa-power-off"></i> Logout</a>
                </div>
            </div>
        </div>
    </div>
</div>
</nav>


<script>
  const menu = document.querySelector('.menu-btn');
  const navbar = document.querySelector('.navibar');
  
  menu.onclick = () => {
    menu.classList.toggle('bx-x')
    navbar.classList.toggle('open')
  }
  
    $(".time-off").on("click", function(){
      $.ajax({
        url: "counter/admin_remark.php",
        success: function(resp){
          console.log(resp);
        }
      })
    })
    $(".schedule").on("click", function(){
      $.ajax({
        url: "counter/adminwarningcounter.php",
        success: function(resp){
          console.log(resp);
        }
      })
    })

	$('.nav_collapse').click(function(){
		console.log($(this).attr('href'))
		$($(this).attr('href')).collapse()
	})
	$('.nav-<?php echo isset($_GET['page']) ? $_GET['page'] : '' ?>').addClass('active')
  
  $('#manage_my_account').click(function(){
    uni_modal("Manage Account","manage_user.php?id=<?php echo $_SESSION['login_id'] ?>&mtype=own")
  })
</script>