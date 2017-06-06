@extends('layouts.app')

@section('content')
<h1>
Edit Diet Therapeutic
</h1>
@include('common.errors')
<br>
{{ Form::model($diet_therapeutic, ['route'=>['diet_therapeutics.update',$diet_therapeutic->therapeutic_code],'method'=>'PUT', 'class'=>'form-horizontal']) }} 
    
    <div class='form-group @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-2 control-label'>Code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::label('therapeutic_code', $diet_therapeutic->therapeutic_code, ['class'=>'control-label']) }}
        </div>
    </div>
    
	@include('diet_therapeutics.diet_therapeutic')
{{ Form::close() }}

@endsection
