@extends('layouts.app')

@section('content')
<h1>
New Bed Charge
</h1>
@include('common.errors')
<br>
{{ Form::model($bed_charge, ['url'=>'bed_charges', 'class'=>'form-horizontal']) }} 
    
	@include('bed_charges.bed_charge')
{{ Form::close() }}

@endsection
