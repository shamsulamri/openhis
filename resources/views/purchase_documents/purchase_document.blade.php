
    <div class='form-group  @if ($errors->has('document_name')) has-error @endif'>
        <label for='document_name' class='col-sm-2 control-label'>document_name<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('document_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'100']) }}
            @if ($errors->has('document_name')) <p class="help-block">{{ $errors->first('document_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('document_prefix')) has-error @endif'>
        <label for='document_prefix' class='col-sm-2 control-label'>document_prefix<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('document_prefix', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'100']) }}
            @if ($errors->has('document_prefix')) <p class="help-block">{{ $errors->first('document_prefix') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/purchase_documents" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
