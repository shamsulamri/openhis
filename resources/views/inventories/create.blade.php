@extends('layouts.app')

@section('content')
<h1>
New Inventory
</h1>
@include('common.errors')
<br>
{{ Form::model($inventory, ['url'=>'inventories', 'class'=>'form-horizontal']) }} 
    
	@include('inventories.inventory')
{{ Form::close() }}

@endsection
