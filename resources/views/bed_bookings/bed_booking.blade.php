    <div class='form-group  @if ($errors->has('book_date')) has-error @endif'>
        <label for='book_date' class='col-sm-3 control-label'>Date<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
			<div class="input-group date">
				<input data-mask="99/99/9999" name="book_date" id="book_date" type="text" class="form-control" value="{{ DojoUtility::dateReadFormat($bed_booking->book_date) }}">
				<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
			</div>
				@if ($errors->has('book_date')) <p class="help-block">{{ $errors->first('book_date') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('user_id')) has-error @endif'>
        <label for='user_id' class='col-sm-3 control-label'>Consultant<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::select('user_id', $consultants,null, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('user_id')) <p class="help-block">{{ $errors->first('user_id') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('ward_code')) has-error @endif'>
        <label for='ward_code' class='col-sm-3 control-label'>Ward<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::select('ward_code', $ward, null, ['id'=>'ward_code','onchange'=>'wardChanged()','class'=>'form-control']) }}
            @if ($errors->has('ward_code')) <p class="help-block">{{ $errors->first('ward_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('class_code')) has-error @endif'>
        <label for='class_code' class='col-sm-3 control-label'>Class<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::select('class_code', $class,null, ['id'=>'class_code', 'class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('class_code')) <p class="help-block">{{ $errors->first('class_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('priority_code')) has-error @endif'>
        <label for='class_code' class='col-sm-3 control-label'>Priority</label>
        <div class='col-sm-9'>
            {{ Form::select('priority_code', $priority,null, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('class_code')) <p class="help-block">{{ $errors->first('class_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('book_description')) has-error @endif'>
        {{ Form::label('book_description', 'Description',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::textarea('book_description', null, ['class'=>'form-control','placeholder'=>'','rows'=>'4']) }}
            @if ($errors->has('book_description')) <p class="help-block">{{ $errors->first('book_description') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
			@can('module-ward')
            <a class="btn btn-default" href="/admissions" role="button">Cancel</a>
			@endcan
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>

	{{ Form::hidden('patient_id', $patient->patient_id) }}
	{{ Form::hidden('admission_id', $admission_id ) }}
	{{ Form::hidden('book', $book ) }}

<script>
		$('#book_date').datepicker({
				format: "dd/mm/yyyy",
				todayBtn: "linked",
				keyboardNavigation: false,
				forceParse: false,
				calendarWeeks: true,
				autoclose: true
		});

		function wardChanged() {
			wardCode = document.getElementById('ward_code').value;

			classes = [
				@foreach($ward_classes as $class)
					'{{ $class->ward_code }}:{{ $class->class_code }}:{{ $class->wardClass->class_name }}',
				@endforeach
			]

			var classSelect = document.getElementById('class_code');

			clearList(classSelect);

			for (var i=0;i<classes.length;i++) {
					values = classes[i].split(":")
					if (wardCode==values[0]) {
							addList(classSelect,values[1], values[2]);
					}
			}
		}

		function clearList(selectedList) {
				var i;
				for(i=selectedList.options.length-1;i>=0;i--)
				{
					selectedList.remove(i);
				}
		}

	function addList(selectedList, value, text ) {
		var optn = document.createElement("OPTION");
		optn.text = text;
		optn.value = value;

		selectedList.options.add(optn);
	}  
</script>
