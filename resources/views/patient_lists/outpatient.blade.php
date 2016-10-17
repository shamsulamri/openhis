<div class='panel panel-default'>
<div class='panel-heading'>
	<h3 class='panel-title'>Outpatient</h3>
</div>
<div class='panel-body'>
@if ($outpatient_lists->total()>0)
<table class="table table-hover">
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
					<td width='20%'>
							{{ date('d F, H:i', strtotime($list->created_at)) }}
							<br>
							<small>
							<?php $ago =$dojo->diffForHumans($list->created_at); ?> 
							{{ $ago }}
							</small>	
					</td>
					<td>
						{{$list->patient_name}}
					@if ($list->patient_birthdate)
					<br>
					<small>
					{{ $dojo->getAge($list->patient_birthdate) }}, 
					{{ $list->gender_name }}
					</small>
					@endif
						<br>
						<small>{{ $list->patient_mrn }}</small>
					</td>
					<td align='right'>
							@if (empty($list->discharge_id))
								@if ($list->consultation_status==1)
									@if ($user_id == $list->user_id)
											<a class='btn btn-warning btn-xs' href='{{ URL::to('consultations/'.$list->consultation_id.'/edit') }}'>Resume</a>
									@endif
								@else
									<a class='btn btn-default btn-xs' href='{{ URL::to('consultations/create?encounter_id='. $list->encounter_id) }}'>&nbsp;&nbsp;&nbsp;Start&nbsp;&nbsp;&nbsp;</a>
								@endif
							@else
								@if ($user_id == $list->user_id)
										<a class='btn btn-default btn-xs' href='{{ URL::to('consultations/'.$list->consultation_id.'/edit') }}'>&nbsp;&nbsp;&nbsp;Edit&nbsp;&nbsp;&nbsp;</a>
								@endif
							@endif
					</td>
			</tr>
			@endif
		@endforeach
</table>
@else
				<p>
				No case
				</p>
@endif
</div>
</div>
