@extends('layouts.app')

@section('content')
<h1>
Edit Care Organisation
</h1>
@include('common.errors')
<br>
{{ Form::model($care_organisation, ['route'=>['care_organisations.update',$care_organisation->organisation_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-2 control-label'>organisation_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::label('organisation_code', $care_organisation->organisation_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('care_organisations.care_organisation')
{{ Form::close() }}

@endsection
