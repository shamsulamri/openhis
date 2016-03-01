@extends('layouts.app')

@section('content')
<h1>
New Diet Complain
</h1>
@include('common.errors')
<br>
{{ Form::model($diet_complain, ['url'=>'diet_complains', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('complain_id')) has-error @endif'>
        <label for='complain_id' class='col-sm-2 control-label'>complain_id<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('complain_id', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20.0']) }}
            @if ($errors->has('complain_id')) <p class="help-block">{{ $errors->first('complain_id') }}</p> @endif
        </div>
    </div>    
    
	@include('diet_complains.diet_complain')
{{ Form::close() }}

@endsection
