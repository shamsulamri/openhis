@extends('layouts.app')

@section('content')	

@if (!empty($consultation))
@can('module-consultation')
		@include('consultations.panel')		
		<h1>Plan</h1>
@endcan
@endif

<ul class="nav nav-tabs">
  <li @if ($plan=='laboratory') class="active" @endif><a href="plan?plan=laboratory">Laboratory</a></li>
  <li @if ($plan=='imaging') class="active" @endif><a href="plan?plan=imaging">Imaging</a></li>
  <li><a href="plan?plan=procedure">Procedures</a></li>
  <li><a href="medication">Medications</a></li>
  <li @if ($plan=='fee_consultant') class="active" @endif><a href="plan?plan=fee_consultant">Fees</a></li>
  <li class="active"><a href="disuccsion">Discussion</a></li>
  <li><a href="make">Orders</a></li>
</ul>
<br>
	{{ Form::textarea('consultation_plan', 
				$consultation->consultation_plan,
				['id'=>'consultation_plan', 'name'=>'consultation_plan', 'class'=>'form-control','rows'=>'15', 'style'=>'font-size: 1.2em']) }}


<br>
<div id='saveHTML' class='text text-success pull-right'></div>

<meta name="csrf-token" content="{{ csrf_token() }}">
<script>
		keypressCount = 0;
$(document).ready(function(){

			$(document).on('focusout', 'textarea', function(e) {
					var id = e.currentTarget.name;
					updatePlan(id);
			});

			$(document).on('keypress', 'textarea', function(e) {
					$('#saveHTML').html("");
					keypressCount += 1;
					console.log(keypressCount);
					if (keypressCount>50) {
							updatePlan();
							keypressCount=0;
					}


					if (e.key=='.') {
							updatePlan();
							keypressCount=0;
					}

					if (e.charCode==13) {
							updatePlan();
							keypressCount=0;
					}
			});

			function updatePlan() {
					var data = $('#consultation_plan').val();
					data = encodeURIComponent(data);

					var dataString = "consultation_plan="+data+"&consultation_id={{ $consultation->consultation_id }}";


					$.ajax({
						type: "POST",
						headers: {'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')},
						url: "{{ route('order.post_plan') }}",
						data: dataString,
						success: function(data){
							$('#saveHTML').html("Saved...");
							console.log("History saved....");
						}
					});

			}

});
</script>
@endsection
