@extends('layouts.app')

@section('content')
<h1>
New Order Set
</h1>

<br>
{{ Form::model($order_set, ['url'=>'order_sets', 'class'=>'form-horizontal']) }} 
	@include('order_sets.order_set')
{{ Form::close() }}

@endsection
