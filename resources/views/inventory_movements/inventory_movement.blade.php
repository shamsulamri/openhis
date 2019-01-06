
    <div class='form-group  @if ($errors->has('move_code')) has-error @endif'>
        <label for='move_code' class='col-sm-2 control-label'>Movement Type<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::select('move_code', $move,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('move_code')) <p class="help-block">{{ $errors->first('move_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('store_code')) has-error @endif'>
        <label for='store_code' class='col-sm-2 control-label'>Store<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::select('store_code', $store,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('store_code')) <p class="help-block">{{ $errors->first('store_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('tag_code')) has-error @endif'>
        <label for='tag_code' class='col-sm-2 control-label'>Tag</label>
        <div class='col-sm-10'>
            {{ Form::select('tag_code', $tag,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('tag_code')) <p class="help-block">{{ $errors->first('tag_code') }}</p> @endif
        </div>
    </div>


    <div class='form-group  @if ($errors->has('move_description')) has-error @endif'>
        {{ Form::label('move_description', 'Description',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::textarea('move_description', null, ['class'=>'form-control','placeholder'=>'','rows'=>'2']) }}
            @if ($errors->has('move_description')) <p class="help-block">{{ $errors->first('move_description') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/inventory_movements" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
