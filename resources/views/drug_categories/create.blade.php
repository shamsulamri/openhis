@extends('layouts.app')

@section('content')
<h1>
New Drug Category
</h1>
@include('common.errors')
<br>
{{ Form::model($drug_category, ['url'=>'drug_categories', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('category_code')) has-error @endif'>
        <label for='category_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('category_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20']) }}
            @if ($errors->has('category_code')) <p class="help-block">{{ $errors->first('category_code') }}</p> @endif
        </div>
    </div>    
    
	@include('drug_categories.drug_category')
{{ Form::close() }}

@endsection
