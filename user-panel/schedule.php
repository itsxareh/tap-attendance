<?php include ('../admin/function-file/db_connect.php'); ?>

<div class="container-fluid tables">
	<div class="col-lg-12">
		<div class="card">
			<div class="card-body">
				<div id="calendar"></div>
			</div>
		</div>
	</div>
</div>
<style>
	.tables {
		padding: 0;
	}
		@media (max-width: 530px){
	.fc .fc-toolbar {
		display: block;
	}
	.fc .fc-button-group, .fc-toolbar-title{
		display: flex !important;
	}
	.fc-toolbar-title{
		justify-content: center !important;
	}
	.fc-today-button {
		margin-top: 3px !important;
		text-align: center !important;
		width: 100%;
	}
	.fc-direction-ltr .fc-toolbar > * > :not(:first-child) {
    margin-left: 0;
	}
	.fc-toolbar-chunk {
		margin-bottom: 3px;
	}
	.fc-dayGridMonth-view, .fc-dayGridMonth-button {
		display: none !important;
	}
}
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar;
    	start_load()
		 $.ajax({
		 	url:'../admin/function-file/ajax.php?action=get_schedule',
		 	method:'POST',
		 	data:{id_no: '<?php echo $_SESSION['login_id_no'] ?>'},
		 	success:function(resp){
		 		if(resp){
		 			resp = JSON.parse(resp)
		 					var evt = [];
		 			if(resp.length > 0){
		 					Object.keys(resp).map(k=>{
		 						var obj = {};
		 							obj['title']=resp[k].title
		 							obj['data_id']=resp[k].id
		 							obj['data_station']=resp[k].station
		 							obj['data_description']=resp[k].description
		 							if(resp[k].is_repeating == 1){
		 							obj['daysOfWeek']=resp[k].dow
		 							obj['startRecur']=resp[k].start
		 							obj['endRecur']=resp[k].end
									obj['startTime']=resp[k].time_from
		 							obj['endTime']=resp[k].time_to
		 							}else{
		 							obj['start']=resp[k].schedule_date+'T'+resp[k].time_from;
		 							obj['end']=resp[k].schedule_end+'T'+resp[k].time_to;
		 							}
		 							
		 							evt.push(obj)
		 					})
							 console.log(evt)

		 		}
		 				  calendar = new FullCalendar.Calendar(calendarEl, {
				          headerToolbar: {
				            left: 'prev,next today',
				            center: 'title',	
				            right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
				          },
				          initialDate: '<?php echo date('Y-m-d') ?>',
				          weekNumbers: true,
				          navLinks: true,
				          editable: false,
				          selectable: true,
				          nowIndicator: true,
				          dayMaxEvents: true, 
				          events: evt,
				          eventClick: function(e,el) {
							   var data =  e.event.extendedProps;
								uni_modal('View Schedule Details','view_schedule.php?id='+data.data_id)

							  }
				        });
		 	}
		 	},complete:function(){
		 		calendar.render()
		 		end_load()
		 	}
		 })
		 })
</script>