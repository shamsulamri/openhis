
    <div class='form-group  @if ($errors->has('patient_id')) has-error @endif'>
        <label for='patient_id' class='col-sm-2 control-label'>patient_id<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('patient_id', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('patient_id')) <p class="help-block">{{ $errors->first('patient_id') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('bed_code')) has-error @endif'>
        {{ Form::label('bed_code', 'bed_code',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::select('bed_code', $bed,null, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('bed_code')) <p class="help-block">{{ $errors->first('bed_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('book_date')) has-error @endif'>
        <label for='book_date' class='col-sm-2 control-label'>book_date<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('book_date', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('book_date')) <p class="help-block">{{ $errors->first('book_date') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('book_description')) has-error @endif'>
        {{ Form::label('book_description', 'book_description',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('book_description', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'65535']) }}
            @if ($errors->has('book_description')) <p class="help-block">{{ $errors->first('book_description') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/bed_bookings" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
