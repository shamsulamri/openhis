@extends('layouts.app')

@section('content')
<h1>
Edit Bill Aging
</h1>
@include('common.errors')
<br>
{{ Form::model($bill_aging, ['route'=>['bill_agings.update',$bill_aging->encounter_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('bill_agings.bill_aging')
{{ Form::close() }}

@endsection
