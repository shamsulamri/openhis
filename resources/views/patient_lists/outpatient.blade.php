
<h4>{{ $location->location_name }}</h4>
@if ($outpatient_lists->total()>0)
<table class="table table-hover">
		<thead>
		<tr>
			<th>Date</th>
			@if ($location->encounter_code =='emergency')
			<th>Triage</th>
			@endif
			<th>Patient</th>
			<th>Panel</th>
			<th>Queue Number</th>
		</tr>
 		</thead>
		<tbody>
		@foreach ($outpatient_lists as $list)
			@if ($user_id==$list->user_id || empty($list->user_id))
			<?php $status='' ?>
			@if (!empty($list->discharge_id))
					<?php $status='success' ?>
			@else
				@if ($list->consultation_status==1)
					<?php $status='warning' ?>
				@endif
			@endif
			<tr class='{{ $status }}'>
					<td width='12%'>
							{{ date('d F, H:i', strtotime($list->created_at)) }}
							<br>
							<small>
							<?php $ago =$dojo->diffForHumans($list->created_at); ?> 
							{{ $ago }}
							</small>	
					</td>
					@if ($location->encounter_code =='emergency')
						@if ($encounter = $encounterHelper->getActiveEncounter($list->patient_id))
							<td>
								<table>
									<tr>
									@if ($encounter->triage)	
											<td bgcolor='{{ $encounter->triage->triage_color }}' width='40' height='40' align='center'>
											</td>
									@endif
									</tr>
								</table>
							</td>
						@else
							<td>
							</td>
						@endif
					@endif
					<td>
					<a href='{{ URL::to('queues/'. $list->queue_id . '/edit?refer=1') }}'>
						{{$list->patient_name}}
					</a>
					@if ($list->patient_birthdate)
					<br>
					<small>
					{{ $dojo->getAge($list->patient_birthdate) }}, 
					{{ $list->gender_name }}
					</small>
					@endif
						<br>
						<small>{{ $list->patient_mrn }}</small>
						@if ($consultation = $encounterHelper->getLastConsultation($list->patient_id))
								<br>
								Last Seen by {{ $consultation->user->name }} {{ DojoUtility::diffForHumans($consultation->created_at) }}
						@endif
						@if (!empty($list->queue_description))	
						<br>
						{{ $list->queue_description }}
						@endif
					</td>
					<td>
							{{ $list->sponsor_name }}
					</td>
					<td>
						{{ $list->encounter_description }}
					</td>
					<td align='right'>

							@if (empty($list->discharge_id))
								@if ($list->consultation_status==1)
									@if ($user_id == $list->user_id)
											<a class='btn btn-warning' href='{{ URL::to('consultations/'.$list->consultation_id.'/edit') }}'>Resume</a>
									@endif
								@else
									<a class='btn btn-default' href='{{ URL::to('consultations/create?encounter_id='. $list->encounter_id) }}'>&nbsp;&nbsp;&nbsp;Start&nbsp;&nbsp;&nbsp;</a>
								@endif
							@else
								@if ($user_id == $list->user_id)
										<a class='btn btn-default' href='{{ URL::to('consultations/'.$list->consultation_id.'/edit') }}'>&nbsp;&nbsp;&nbsp;Edit&nbsp;&nbsp;&nbsp;</a>
								@endif
							@endif
					</td>
			</tr>
			@endif
		@endforeach
		</tbody>
</table>
@else
				<p>
				No case
				</p>
@endif
