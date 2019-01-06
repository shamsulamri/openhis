@extends('layouts.app')

@section('content')
<h1>
Edit Stock Tag
</h1>
@include('common.errors')
<br>
{{ Form::model($stock_tag, ['route'=>['stock_tags.update',$stock_tag->tag_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-2 control-label'>tag_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::label('tag_code', $stock_tag->tag_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('stock_tags.stock_tag')
{{ Form::close() }}

@endsection
