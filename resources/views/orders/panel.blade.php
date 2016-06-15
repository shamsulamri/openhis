

@if (Auth::user()->authorization->module_consultation==1)
		@include('consultations.panel')		
@else
		@include('patients.id')
		<h2>Edit Orders</h2>
		<br>
@endif


