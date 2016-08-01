
    <div class='form-group'>
        {{ Form::label('registration_name', 'registration_name',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('registration_name', null, ['class'=>'form-control','placeholder'=>'']) }}
        </div>
    </div>

    <div class='form-group'>
        {{ Form::label('billing_group', 'billing_group',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::text('billing_group', null, ['class'=>'form-control','placeholder'=>'']) }}
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/registrations" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
