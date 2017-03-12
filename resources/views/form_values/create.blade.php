@extends('layouts.app')

@section('content')
@include('patients.id')
<h1>
@if ($value_id)
	Edit
@else
	New
@endif
{{ $form->form_name }}
</h1>
<br>

{{ Form::model(null, ['url'=>'form/entry', 'class'=>'form-horizontal']) }} 
@foreach ($properties as $property)
    <div class='form-group'>
        <label for='{{ $property->property->property_code }}' class='col-sm-3 control-label'>{{ $property->property->property_name }}</label>
        <div class='col-sm-3'>
				@if ($property->property->property_type != "List")
					{{ Form::text($property->property->property_code, $json[$property->property_code], ['class'=>'form-control','placeholder'=>'','maxlength'=>'100']) }}
				@endif
				@if ($property->property->property_type == "List")
					<?php 
						$list = explode(";",$property->property->property_list);
						$list = array_combine($list, $list);
					?>
						{{ Form::select($property->property->property_code, $list, $json[$property->property_code]) }}
				@endif
        </div>
	
		<label class='control-label'>{{ $property->property->property_unit }}</label>
    </div>
@endforeach

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/form/{{ $form->form_code }}/{{ $encounter_id }}" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>

	{{ Form::hidden('form_code', $form->form_code) }}
	{{ Form::hidden('encounter_id', $encounter_id) }}
	{{ Form::hidden('value_id', $value_id) }}
{{ Form::close() }}
@endsection
