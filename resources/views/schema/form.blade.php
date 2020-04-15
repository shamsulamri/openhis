
<h3>{{ $table }}</h3>

<form method='POST' action='/schema/post' class='form-horizontal'>
<?php
	$show = true;
?>
    <div class='form-group'>
        <div class="col-sm-9">
         	<a class="btn btn-default" href="/schema/index/{{ $table }}" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
@foreach ($fields as $field)
	
	<?php
		$record_value = null;
		if (!empty($record)) {
			$record_field = $field->Field;
			$record_value = $record->$record_field;
		}
	?>
	@if ($field->Key == 'PRI' and $field->Extra == 'auto_increment' ) 
				<?php $show = false ?>		
	@endif

	@if ($field->Type == 'timestamp')
				<?php $show = false ?>		
	@endif

	@if ($show)
    <div class='form-group  @if ($errors->has($field->Field)) has-error @endif'>
		<label for='{{ $field->Field }}' class='col-sm-3 control-label'>{{ $field->Field }}
			@if ($field->Null == "NO")
			<span style='color:red;'> *</span>
			@endif
		</label>
        <div class='col-sm-9'>
		@if ($field->Type == 'date')
				<?php
					$date_value = DojoUtility::dateReadFormat($record_value);
				?>
					<div class="input-group date">
						{{ Form::text($field->Field,$date_value, ['class'=>'form-control','data-mask'=>'99/99/9999','id'=>$field->Field]) }}
						<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					</div>
		@elseif ($field->Type == 'time')
				<div id="{{ $field->Field }}" name="{{ $field->Field }}" class="input-group clockpicker" data-autoclose="true">
						{{ Form::text($field->Field, null, ['class'=>'form-control','data-mask'=>'99:99']) }}
						<span class="input-group-addon">
							<span class="fa fa-clock-o"></span>
						</span>
				</div>
		@elseif ($field->Type == 'tinyint(1)')
							{{ Form::checkbox('patient_is_unknown', '1',['class'=>'checkbox']) }} 
		@elseif ($field->Type == 'smallint(4)' || $field->Type == 'tinyint(4)')
            {{ Form::text($field->Field, null, ['class'=>'form-control','placeholder'=>'','onkeypress'=>'return isNumber(event)']) }}
		@elseif ($field->Key == 'MUL')
			@if (!empty($menus[$field->Field]))
				{{ Form::select($field->Field, $menus[$field->Field],$record_value, ['id'=>$field->Field,'class'=>'form-control','maxlength'=>'1']) }}
			@else
				{{ Form::text($field->Field, null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'100']) }}
			@endif
		@else
            {{ Form::text($field->Field, $record_value, ['class'=>'form-control','placeholder'=>'','maxlength'=>'100']) }}
		@endif

            @if ($errors->has($field->Field)) <p class="help-block">{{ $errors->first($field->Field) }}</p> @endif
        </div>
    </div>
	@endif
<?php
	$show = true;
?>
@endforeach

    <div class='form-group'>
        <div class="col-sm-9">
         	<a class="btn btn-default" href="/schema/index/{{ $table }}" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
	<input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
	{{ Form::hidden('table_name', $table) }}
	@if (!empty($id))
		<input type="hidden" name="id" id="id" value="{{ $id }}" />
	@endif
</form>

	<script>
@foreach ($fields as $field)
		@if ($field->Type == 'date')
				$('#{{ $field->Field }}').datepicker({
						format: "dd/mm/yyyy",
						todayBtn: "linked",
						keyboardNavigation: false,
						forceParse: false,
						calendarWeeks: true,
						autoclose: true
				});
		@endif
@endforeach

	function isNumber(evt) {
		    evt = (evt) ? evt : window.event;
			    var charCode = (evt.which) ? evt.which : evt.keyCode;
			    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
						        return false;
								    }
	    return true;
	}

	$('.clockpicker').clockpicker();
	</script>
