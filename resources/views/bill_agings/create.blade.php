@extends('layouts.app')

@section('content')
<h1>
New Bill Aging
</h1>
@include('common.errors')
<br>
{{ Form::model($bill_aging, ['url'=>'bill_agings', 'class'=>'form-horizontal']) }} 
    
	@include('bill_agings.bill_aging')
{{ Form::close() }}

@endsection
