
@extends('layouts.app')

@section('content')
<style>
table, th, td {
   	border: none;
	border-bottom: none !important;
}
</style>
@include('patients.id')
@if ($appointment_id == null)
<h1>Appointment</h1>
<br>
@can('module-patient')
<a href='/patients/{{ $patient->patient_id }}' class='btn btn-default'>Return</a>
@endcan
@can('module-ward')
<a href='/ward_discharges/create/{{ $admission_id }}' class='btn btn-default'>Return</a>
@endcan
<br>
<br>
<form action='/appointment_services/{{ $patient->patient_id }}/0?admission_id={{ $admission_id }}' method='post'>
	{{ Form::select('services', $menu_services, $service, ['class'=>'form-control']) }}
	<br>
	<button class="btn btn-primary" type="submit" value="Submit">Refresh</button>
	<input type='hidden' name="_token" value="{{ csrf_token() }}">
</form>
<br>
@else
<h2>Edit Appointment</h2>
<br>
<h4>
Current appointment slot on {{ date('l d F, h:i a', strtotime($appointment->appointment_datetime )) }}
</h4>
<br>
@endif
@if ($services != null) 
		<table width='100%'> 
			<tr>
			<td width='30%' >
				<a href='/appointment_services/{{ $patient->patient_id }}/0{{ $service_path }}?admission_id={{ $admission_id }}' class='btn btn-default btn-xs'>This Week</a>
				<a href='/appointment_services/{{ $patient->patient_id }}/2{{ $service_path }}?admission_id={{ $admission_id }}' class='btn btn-default btn-xs'>2/52</a>
				<a href='/appointment_services/{{ $patient->patient_id }}/4{{ $service_path }}?admission_id={{ $admission_id }}' class='btn btn-default btn-xs'>1/12</a>
				<a href='/appointment_services/{{ $patient->patient_id }}/12{{ $service_path }}?admission_id={{ $admission_id }}' class='btn btn-default btn-xs'>3/12</a>
			</td>
			<td width='70' align='right'>
			@if ($selected_week-1>=0)
				<a href='/appointment_services/{{ $patient->patient_id }}/{{ $selected_week-1 }}{{ $service_path }}?admission_id={{ $admission_id }}' class='btn btn-warning btn-xs'><span class='glyphicon glyphicon-chevron-left' aria-hidden='true'></span></a>
			@else
				<a href='#' class='btn btn-warning btn-xs disabled'><span class='glyphicon glyphicon-chevron-left' aria-hidden='true'></span></a>
			@endif
			</td>
			@foreach ($week as $day)
			<td align='middle' width='80'>
				<h4>
				@if ($day==$today)
					<strong>{{ $day->format('D') }}</strong>
				@else
					{{ $day->format('D') }}
				@endif
				<br><small>{{ $day->format('d M') }}</small>
				</h4>
			</td>
			@endforeach
			<td width='50'>
				<a href='/appointment_services/{{ $patient->patient_id }}/{{ $selected_week+1 }}{{ $service_path }}?admission_id={{ $admission_id }}' class='btn btn-warning btn-xs'><span class='glyphicon glyphicon-chevron-right' aria-hidden='true'></span></a>
			</td>
			</tr>
		</table>	
@foreach ($services as $service)
		<hr>
		<table width='100%'> 
		
		<tr valign='top'>
				<td width='30%' rowspan='50'>
					<h4>
					<a href='/appointment/search?services={{ $service->service_id }}'>{{ $service->service_name }}</a>
					</h4>
				</td>
		</tr>
<?php 
		$start_time = explode(':', $service->service_start);
		$end_time = explode(':', $service->service_end);
		$slot_time = new DateTime('today');
		$slot_time->setTime($start_time[0],$start_time[1],0);
		$slot_end = new DateTime('today');
		$slot_end->setTime($end_time[0],$end_time[1],0);
		$duration=$service->service_duration;

		while ($slot_time<$slot_end) {
				if ($slot_time>=$slot_end) {
						break;
				}
				$appointments = $appointment_model->getAppointments($service->service_id);
		?>
		<tr >
				<td width='70'>
				</td>
				@foreach ($week as $day)
					<?php 
						$btn_class="";
						$showDay=false;
						$dayWeek = $day->format('D');
						if ($service->service_monday==1 && $dayWeek=='Mon') $showDay=true;
						if ($service->service_tuesday==1 && $dayWeek=='Tue') $showDay=true;
						if ($service->service_wednesday==1 && $dayWeek=='Wed') $showDay=true;
						if ($service->service_thursday==1 && $dayWeek=='Thu') $showDay=true;
						if ($service->service_friday==1 && $dayWeek=='Fri') $showDay=true;
						if ($service->service_saturday==1 && $dayWeek=='Sat') $showDay=true;
						if ($service->service_sunday==1 && $dayWeek=='Sun') $showDay=true;
						$slot=$day->format('Ymd').''.$slot_time->format('Hi');
						$index = array_search($slot, array_column($appointments, 'appointment_slot'));
					?>
					<td align='middle' width='80' height='33'>
					@if ($day>=$today && $showDay==true)
						@if ($index===FALSE)
							@if ($day==$today)
								<a href='#' class='btn btn-default btn-sm disabled'>{{ $slot_time->format('h:i a') }}</a>
							@else
								@if ($appointment_id == null)
								<a href='/appointments/create/{{ $patient->patient_id }}/{{ $service->service_id }}/{{ $slot }}/{{ $admission_id }}' class='btn btn-default btn-sm'>{{ $slot_time->format('h:i a') }}</a>
								@else
								<a href='/appointments/{{ $appointment_id }}/edit/{{ $slot }}' class='btn btn-default btn-sm'>{{ $slot_time->format('h:i a') }}</a>
								@endif
							@endif
						@else
						<a href='#' class='btn btn-success btn-sm disabled'>{{ $slot_time->format('h:i a') }}</a>
						{{ Log::info( $appointments[$index]['appointment_id'] ) }}
						@endif
					@else
							@if ($day<$today && $showDay==true)
								<?php
								if ($index===FALSE) {
									$btn_class = 'btn btn-default btn-sm disabled';
								} else {
									$btn_class='btn btn-success btn-sm disabled';
								}
								?>
								<a href='#' class='{{ $btn_class }}'>{{ $slot_time->format('h:i a') }}</a>
							@else
								-	
							@endif
					@endif
					</td>
				@endforeach
				<td width='50'>
				</td>
		</tr>
		<?php 
				$slot_time->add(new DateInterval('PT'.$duration.'M'));
			} 
		?>
		</table>
@endforeach
<br>
@endif
@endsection
