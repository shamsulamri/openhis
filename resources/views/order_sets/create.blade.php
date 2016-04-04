@extends('layouts.app')

@section('content')
<h1>
New Order Set
</h1>
@include('common.errors')
<br>
{{ Form::model($order_set, ['url'=>'order_sets', 'class'=>'form-horizontal']) }} 
	@include('order_sets.order_set')
{{ Form::close() }}

@endsection
