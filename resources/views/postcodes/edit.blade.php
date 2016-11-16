@extends('layouts.app')

@section('content')
<h1>
Edit Postcode
</h1>
@include('common.errors')
<br>
{{ Form::model($postcode, ['route'=>['postcodes.update',$postcode->postcode],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-2 control-label'>postcode<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::label('postcode', $postcode->postcode, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('postcodes.postcode')
{{ Form::close() }}

@endsection
