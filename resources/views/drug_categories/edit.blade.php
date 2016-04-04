@extends('layouts.app')

@section('content')
<h1>
Edit Drug Category
</h1>
@include('common.errors')
<br>
{{ Form::model($drug_category, ['route'=>['drug_categories.update',$drug_category->category_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-2 control-label'>category_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::label('category_code', $drug_category->category_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('drug_categories.drug_category')
{{ Form::close() }}

@endsection