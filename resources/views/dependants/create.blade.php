@extends('layouts.app2')

@section('content')
<h3>
New Dependant
</h3>

<br>
{{ Form::model($dependant, ['url'=>'dependants', 'class'=>'form-horizontal']) }} 
    
	@include('dependants.dependant')
{{ Form::close() }}

@endsection
