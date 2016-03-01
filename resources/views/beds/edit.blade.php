@extends('layouts.app')

@section('content')
<h1>
Edit Bed
</h1>
@include('common.errors')
<br>
{{ Form::model($bed, ['route'=>['beds.update',$bed->bed_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='bed_code' class='col-sm-2 control-label'>bed_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::label('bed_code', $bed->bed_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('beds.bed')
{{ Form::close() }}

@endsection
