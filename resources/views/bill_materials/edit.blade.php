@extends('layouts.app2')

@section('content')
<h1>
Edit Bill Material
</h1>
@include('common.errors')
<br>
{{ Form::model($bill_material, ['route'=>['bill_materials.update',$bill_material->id],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
	@include('bill_materials.bill_material')
{{ Form::close() }}

@endsection
