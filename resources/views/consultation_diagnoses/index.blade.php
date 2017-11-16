@extends('layouts.app')

@section('content')
@include('consultations.panel')
<h1>Diagnoses</h1>
<div id='responseHTML'>
</div>
{{ Form::text('diagnosis_clinical', null, ['id'=>'diagnosis_clinical','class'=>'form-control','placeholder'=>'','rows'=>'3']) }}

<meta name="csrf-token" content="{{ csrf_token() }}">
	<script>
		$(document).ready(function(){
			$('#diagnosis_clinical').keypress(function(e){
				if (e.which==13) {
					var value = $('#diagnosis_clinical').val();
					var dataString = "diagnosis_clinical="+value+"&id={{ $consultation->consultation_id }}";
					console.log(dataString);

					$.ajax({
						type: "POST",
						headers: {'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')},
						url: "/consultation_diagnoses",
						data: dataString,
						success: function(data){
							console.log(data);
							$('#responseHTML').html(data);
						}
					});
					$('#diagnosis_clinical').val('');
				}
			});
			

			$.get('/consultation_diagnoses/encounter', function(data){
					$('#responseHTML').html(data);
			})

		});

	</script>
@endsection
