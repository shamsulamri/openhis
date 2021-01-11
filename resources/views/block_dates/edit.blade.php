@extends('layouts.app')

@section('content')
<h1>
Edit Block Slot
</h1>

<br>
{{ Form::model($block_date, ['route'=>['block_dates.update',$block_date->block_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
	@include('block_dates.block_date')
{{ Form::close() }}

@endsection
