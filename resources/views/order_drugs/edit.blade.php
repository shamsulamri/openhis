@extends('layouts.app2')

@section('content')
@include('common.errors')

{{ Form::model($order_drug, ['route'=>['order_drugs.update',$order_drug->id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
	@include('order_drugs.order_drug')
{{ Form::close() }}

@endsection
