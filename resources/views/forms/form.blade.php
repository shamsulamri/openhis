
    <div class='form-group  @if ($errors->has('form_name')) has-error @endif'>
        <label for='form_name' class='col-sm-3 control-label'>Name<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('form_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'100']) }}
            @if ($errors->has('form_name')) <p class="help-block">{{ $errors->first('form_name') }}</p> @endif
        </div>
    </div>
    <div class='form-group  @if ($errors->has('form_results')) has-error @endif'>
        <label for='form_results' class='col-sm-3 control-label'>N results</label>
        <div class='col-sm-9'>
            {{ Form::text('form_results', null, ['class'=>'form-control','placeholder'=>'Number of record to display on graph ','maxlength'=>'100']) }}
            @if ($errors->has('form_results')) <p class="help-block">{{ $errors->first('form_results') }}</p> @endif
        </div>
    </div>
    <div class='form-group  @if ($errors->has('form_has_graph')) has-error @endif'>
        <label for='form_has_graph' class='col-sm-3 control-label'>Has graph</label>
        <div class='col-sm-9'>
			{{ Form::checkbox('form_has_graph', '1') }} 
        </div>
    </div>
    <div class='form-group  @if ($errors->has('form_visible')) has-error @endif'>
        <label for='form_visible' class='col-sm-3 control-label'>Visible</label>
        <div class='col-sm-9'>
			{{ Form::checkbox('form_visible', '1') }} 
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/forms" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
