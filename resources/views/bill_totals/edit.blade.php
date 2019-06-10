@extends('layouts.app')

@section('content')
<h1>
Edit Bill Total
</h1>
@include('common.errors')
<br>
{{ Form::model($bill_total, ['route'=>['bill_totals.update',$bill_total->encounter_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('bill_totals.bill_total')
{{ Form::close() }}

@endsection
