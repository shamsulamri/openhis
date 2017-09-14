@extends('layouts.app')

@section('content')
<h1>
Edit Request
</h1>

{{ Form::model($loan, ['route'=>['loans.update',$loan->loan_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
	@include('loans.loan')
{{ Form::close() }}

@endsection
