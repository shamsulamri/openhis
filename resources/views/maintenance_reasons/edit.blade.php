@extends('layouts.app')

@section('content')
<h1>
Edit Maintenance Reason
</h1>
@include('common.errors')
<br>
{{ Form::model($maintenance_reason, ['route'=>['maintenance_reasons.update',$maintenance_reason->reason_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-3 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::label('reason_code', $maintenance_reason->reason_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('maintenance_reasons.maintenance_reason')
{{ Form::close() }}

@endsection
