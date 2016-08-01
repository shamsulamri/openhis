@extends('layouts.app')

@section('content')
<h1>
Edit Admission Bed
</h1>
@include('common.errors')
<br>
{{ Form::model($admission_bed, ['route'=>['admission_beds.update',$admission_bed->bed_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-3 control-label'>bed_code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::label('bed_code', $admission_bed->bed_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('admission_beds.admission_bed')
{{ Form::close() }}

@endsection
