<?php include 'function-file/db_connect.php' ?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@500&family=Roboto:wght@500&display=swap" rel="stylesheet">
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
    .datetime{
        font-size: 6vw;
        padding: 0.5em;
        color: #000000;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.25);
        border-radius:  4px;
        border-right: 10px #28a745 solid;
        font-family: 'Inter', sans-serif ;
        margin-top: 10px;
    }
    .time {
        font-size: 9.5vw;
        color: #6c757d;
    }
    .date {
        font-size: 4.5vw;
    }
</style>

	<div class="row mt-3 mx-3">
        <div class="col-lg-12 tables">             
                <div class="datetime">
                    Hey, there, <?php echo ucwords($_SESSION['login_name'])?>!
                    <hr>
                    <div class="time"></div>
                    <div class="date"></div>
                </div> 			
        </div>
    </div>
<script>
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