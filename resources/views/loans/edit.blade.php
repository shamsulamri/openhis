@extends('layouts.app')

@section('content')
<h1>
Edit Loan
</h1>
@include('common.errors')
{{ Form::model($loan, ['route'=>['loans.update',$loan->loan_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
	@include('loans.loan')
{{ Form::close() }}

@endsection
