@extends('layouts.app')

@section('content')
@if ($encounter_id>0)
@include('patients.id_only')
@else
@include('patients.id')
@endif
<h1>
New Payment 
</h1>

{{ Form::model($payment, ['url'=>'payments', 'class'=>'form-horizontal']) }} 
    
	@include('payments.payment')
{{ Form::close() }}

@endsection
