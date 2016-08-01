@extends('layouts.app')

@section('content')
<h1>
New Admission Bed
</h1>
@include('common.errors')
<br>
{{ Form::model($admission_bed, ['url'=>'admission_beds', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('bed_code')) has-error @endif'>
        <label for='bed_code' class='col-sm-3 control-label'>bed_code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('bed_code', null, ['class'=>'form-control','placeholder'=>'', 'maxlength'=>'20.0']) }}
            @if ($errors->has('bed_code')) <p class="help-block">{{ $errors->first('bed_code') }}</p> @endif
        </div>
    </div>    
    
	@include('admission_beds.admission_bed')
{{ Form::close() }}

@endsection
