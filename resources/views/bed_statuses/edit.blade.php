@extends('layouts.app')

@section('content')
<h1>
Edit Bed Status
</h1>
@include('common.errors')
<br>
{{ Form::model($bed_status, ['route'=>['bed_statuses.update',$bed_status->status_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-2 control-label'>status_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::label('status_code', $bed_status->status_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('bed_statuses.bed_status')
{{ Form::close() }}

@endsection