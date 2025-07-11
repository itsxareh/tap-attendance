
<?php
include('../admin/function-file/db_connect.php');
?>

<style>
   span.float-right.summary_icon {
    font-size: 3rem;
    position: absolute;
    right: 1rem;
    color: #ffffff96;
}
    .imgs{
		margin: .5em;
		max-width: calc(100%);
		max-height: calc(100%);
	}
	.imgs img{
		max-width: calc(100%);
		max-height: calc(100%);
		cursor: pointer;
	}
	#imagesCarousel,#imagesCarousel .carousel-inner,#imagesCarousel .carousel-item{
		height: 60vh !important;background: black;
	}
	#imagesCarousel .carousel-item.active{
		display: flex !important;
	}
	#imagesCarousel .carousel-item-next{
		display: flex !important;
	}
	#imagesCarousel .carousel-item img{
		margin: auto;
	}
	#imagesCarousel img{
		width: auto!important;
		height: auto!important;
		max-height: calc(100%)!important;
		max-width: calc(100%)!important;
	}
    .datetime {
        padding: 0.5em;
        color: #000000;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.25);
        border-radius:  4px;
        border-right: 10px #6c757d solid;
        font-family: 'Inter', sans-serif ;
        margin-top: 10px;
    }
    .time, .date {
        color: #6c757d;
    }
    .greet {
        font-size: 4.5vw;
    }
    .today {
        font-size: 3vw;
    }
    .checkout, .signin {
        width: 150px; 
        height: 150px; 
        display:flex; 
        align-items: center; 
        justify-content: center; 
        border-radius: 50% !important; 
        font-size: 1.5em; 
        margin: 10px;
    }
    

</style>
	<div class="row mt-3 mx-3">
        <div class="col-lg-12 tables">             
            <div class="datetime">
                <div class="greet"><p>Hi, <?php echo ucwords($_SESSION['login_name'])?>!</p></div>
                <hr>
                <div class="stamp" style="margin: 50px 0;">
                    <center>
                        <b><p class="text-2xl">Stamp your attendance now!</p></b>
                        <button class="signin btn btn-success" onclick="signin()">
                            <span class='icon-field'><i class="fas fa-fw fa-sign-in-alt fa-2x"></i></span>
                        </button>
                        <b><p>Turn in</p></b>
                    </center>
                </div>
                <div class="stamped" style="margin: 50px 0; display:none;">
                    <center>
                        <b><p class="text-2xl">Thank you, have a good day ahead!</p></b>
                        <button  class="checkout btn btn-danger">
                            <span class='icon-field'><i class="fas fa-fw fa-sign-out-alt fa-2x"></i></span>
                        </button>
                        <b><p>Check out</p></b>
                    </center>
                </div>
                <div class="today flex align-items-center justify-content-around">
                    <div class="time"></div>
                    <div class="date"></div>
                </div>
            </div> 			
        </div>
    </div>
<script>
$(document).ready(function() {
    checkSignFlag();
});
function checkSignFlag() {
    $.ajax({
        type: "GET",
        url: "../admin/counter/sign.php",
        success: function(sign_flag) {
            console.log(sign_flag);
            if (sign_flag == 1) {
                $('.stamp').show();
                $('.stamped').hide();
                $('.checkout').attr("disabled", true);
            } else if (sign_flag == 2) {
                $('.stamped').show();
                $('.stamp').hide();
                $('.checkout').attr("disabled", false);
            } else if (sign_flag == 3) {
                $('.stamp').hide();
                $('.stamped').show();
                $('.checkout').attr("disabled", true);
            } else {
                $('.stamp').show();
                $('.stamped').hide();
                $('.signin').attr("disabled", true);
            }
        }
    });
    setTimeout(checkSignFlag, 500); // Check for sign_flag every 0.5 seconds
} 
    function signin(){
        $.ajax({
            type: "POST",
            url: "../admin/function-file/ajax.php?action=signin",
            data: { id_no: <?php echo $_SESSION['login_id_no']; ?>},
            error: err => {
            console.log(err)
            alert_toast("An error occured.", 'error');
            end_load();
            },
            success: function(resp){
                console.log(resp);
                if (resp) {
                    alert_toast("Attendance Saved.", 'success')
                }
            }
        });
    }
$('.checkout').click(function() {
    _conf("Are you sure you want to check out? Make sure you are done with your work.", "checkout")
})
    function checkout() {
		start_load()
        $.ajax({
            type: "POST",
            url: "../admin/function-file/ajax.php?action=checkout",
            data: { id_no: <?php echo $_SESSION['login_id_no']; ?>},
            error: err => {
                console.log(err)
                alert_toast("An error occured.", 'error');
                end_load();
            },
            success: function(resp) {
                console.log(resp)
                if (resp) {
                    alert_toast("Checkout Success.", 'success');
                    $('#confirm_modal').modal('hide')
                    end_load();
                }
            }
        });
    }
    const timeElement = document.querySelector(".time");
    const dateElement = document.querySelector(".date");

    function formatTime(date){
        const hours12 = date.getHours() % 12 || 12;
        const minutes = date.getMinutes();
        const seconds = date.getSeconds();
        const isAm = date.getHours() < 12; 

        return `${hours12.toString().padStart(2, "0")}:${minutes.toString().padStart(2, "0")}:${seconds.toString().padStart(2, "0")} ${isAm ? "AM" : "PM"}`;
    }
    function formatDate(date){
        const DAYS = [
            "Sunday",
            "Monday",
            "Tuesday",
            "Wednesday",
            "Thursday",
            "Friday",
            "Saturday"
        ];
        const MONTHS = [
            "January",
            "February",
            "March",
            "April",
            "May",
            "June",
            "July",
            "August",
            "September",
            "October",
            "November",
            "December"
        ];

        return `${DAYS[date.getDay()]}, ${
            MONTHS[date.getMonth()]
        } ${date.getDate()}, ${date.getFullYear()}`;
        }

        setInterval(() => {
        const now = new Date();

        timeElement.textContent = formatTime(now);
        dateElement.textContent = formatDate(now);
        }, 200);

/*  $('#manage-records').submit(function(e){
        e.preventDefault()
        start_load()
        $.ajax({
            url:'function-file/ajax.php?action=save_track',
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            success:function(resp){
                resp=JSON.parse(resp)
                if(resp.status==1){
                    alert_toast("Data successfully saved",'success')
                    setTimeout(function(){
                        location.reload()
                    },800)

                }
                
            }
        })
    })
    $('#tracking_id').on('keypress',function(e){
        if(e.which == 13){
            get_person()
        }
    })
    $('#check').on('click',function(e){
            get_person()
    })
    function get_person(){
            start_load()
        $.ajax({
                url:'function-file/ajax.php?action=get_pdetails',
                method:"POST",
                data:{tracking_id : $('#tracking_id').val()},
                success:function(resp){
                    if(resp){
                        resp = JSON.parse(resp)
                        if(resp.status == 1){
                            $('#name').html(resp.name)
                            $('#address').html(resp.address)
                            $('[name="person_id"]').val(resp.id)
                            $('#details').show()
                            end_load()

                        }else if(resp.status == 2){
                            alert_toast("Unknow tracking id.",'danger');
                            end_load();
                        }
                    }
                }
            })
}*/
</script>