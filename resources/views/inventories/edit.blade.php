@extends('layouts.app2')

@section('content')
<h3>
Edit Inventory
</h3>
@include('common.errors')
<br>
{{ Form::model($inventory, ['route'=>['inventories.update',$inventory->inv_id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('inventories.inventory')
{{ Form::close() }}

@endsection
