@extends('layouts.app')

@section('content')
@include('patients.id')
<h1>
New Refund
</h1>
<br>
{{ Form::model($refund, ['url'=>'refunds', 'class'=>'form-horizontal']) }} 
	@include('refunds.refund')
{{ Form::close() }}

@endsection
