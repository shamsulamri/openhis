@extends('layouts.app')

@section('content')
<h1>
New Loan
</h1>

<br>
{{ Form::model($loan, ['url'=>'loans', 'class'=>'form-horizontal']) }} 
    
	@include('loans.loan')
{{ Form::close() }}

@endsection
