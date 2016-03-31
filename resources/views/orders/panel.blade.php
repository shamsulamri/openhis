

@if (Auth::user()->authorization->author_consultation==1)
		@include('patients.label')
		@include('consultations.panel')		
@else
		@include('patients.id')
		<h2>Edit Orders</h2>
		<br>
@endif


