@extends('layouts.app')

@section('content')
<h1>
New Bill Material
</h1>
@include('common.errors')
<br>
{{ Form::model($bill_material, ['url'=>'bill_materials', 'class'=>'form-horizontal']) }} 
    
	@include('bill_materials.bill_material')
{{ Form::close() }}

@endsection
