@extends('layouts.app')

@section('content')
<h1>
New Bill Total
</h1>
@include('common.errors')
<br>
{{ Form::model($bill_total, ['url'=>'bill_totals', 'class'=>'form-horizontal']) }} 
    
	@include('bill_totals.bill_total')
{{ Form::close() }}

@endsection
