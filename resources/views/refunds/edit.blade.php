@extends('layouts.app')

@section('content')
@include('patients.id')
<h1>
Edit Refund
</h1>
<br>
{{ Form::model($refund, ['route'=>['refunds.update',$refund->refund_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
	@include('refunds.refund')
{{ Form::close() }}

@endsection
