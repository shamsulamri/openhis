@extends('layouts.app')

@section('content')
<h1>
Edit Product Group
</h1>
@include('common.errors')
<br>
{{ Form::model($product_group, ['route'=>['product_groups.update',$product_group->group_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-2 control-label'>group_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::label('group_code', $product_group->group_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('product_groups.product_group')
{{ Form::close() }}

@endsection
