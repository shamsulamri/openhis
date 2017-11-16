
    <div class='form-group  @if ($errors->has('consultation_id')) has-error @endif'>
        <label for='consultation_id' class='col-sm-2 control-label'>consultation_id<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('consultation_id', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('consultation_id')) <p class="help-block">{{ $errors->first('consultation_id') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('annotation_image')) has-error @endif'>
        <label for='annotation_image' class='col-sm-2 control-label'>annotation_image<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('annotation_image', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'20']) }}
            @if ($errors->has('annotation_image')) <p class="help-block">{{ $errors->first('annotation_image') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('annotation_coordinates')) has-error @endif'>
        <label for='annotation_coordinates' class='col-sm-2 control-label'>annotation_coordinates<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('annotation_coordinates', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'4294967295']) }}
            @if ($errors->has('annotation_coordinates')) <p class="help-block">{{ $errors->first('annotation_coordinates') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/consultation_annotations" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
