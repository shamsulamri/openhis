@extends('layouts.app')

@section('content')
<h1>
New Block Slot
</h1>

<br>
{{ Form::model($block_date, ['url'=>'block_dates', 'class'=>'form-horizontal']) }} 
	@include('block_dates.block_date')
{{ Form::close() }}

@endsection
