@extends('layouts.app')

@section('content')
<h1>
Edit Employer
</h1>
@include('common.errors')
<br>
{{ Form::model($employer, ['route'=>['employers.update',$employer->employer_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-2 control-label'>employer_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::label('employer_code', $employer->employer_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('employers.employer')
{{ Form::close() }}

@endsection
