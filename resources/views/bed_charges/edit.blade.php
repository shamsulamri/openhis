@extends('layouts.app')

@section('content')
<h1>
Edit Bed Charge
</h1>
@include('common.errors')
<br>
{{ Form::model($bed_charge, ['route'=>['bed_charges.update',$bed_charge->charge_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('bed_charges.bed_charge')
{{ Form::close() }}

@endsection
