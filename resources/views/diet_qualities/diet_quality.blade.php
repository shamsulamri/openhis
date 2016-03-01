
    <div class='form-group  @if ($errors->has('qc_date')) has-error @endif'>
        <label for='qc_date' class='col-sm-2 control-label'>qc_date<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('qc_date', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('qc_date')) <p class="help-block">{{ $errors->first('qc_date') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('period_code')) has-error @endif'>
        <label for='period_code' class='col-sm-2 control-label'>period_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::select('period_code', $period,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('period_code')) <p class="help-block">{{ $errors->first('period_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('class_code')) has-error @endif'>
        <label for='class_code' class='col-sm-2 control-label'>class_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::select('class_code', $class,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('class_code')) <p class="help-block">{{ $errors->first('class_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('qc_texture')) has-error @endif'>
        {{ Form::label('qc_texture', 'qc_texture',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('qc_texture', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'20']) }}
            @if ($errors->has('qc_texture')) <p class="help-block">{{ $errors->first('qc_texture') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('qc_texture_note')) has-error @endif'>
        {{ Form::label('qc_texture_note', 'qc_texture_note',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('qc_texture_note', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'65535']) }}
            @if ($errors->has('qc_texture_note')) <p class="help-block">{{ $errors->first('qc_texture_note') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('qc_taste')) has-error @endif'>
        {{ Form::label('qc_taste', 'qc_taste',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('qc_taste', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'20']) }}
            @if ($errors->has('qc_taste')) <p class="help-block">{{ $errors->first('qc_taste') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('qc_taste_note')) has-error @endif'>
        {{ Form::label('qc_taste_note', 'qc_taste_note',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('qc_taste_note', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'65535']) }}
            @if ($errors->has('qc_taste_note')) <p class="help-block">{{ $errors->first('qc_taste_note') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('qc_aroma')) has-error @endif'>
        {{ Form::label('qc_aroma', 'qc_aroma',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('qc_aroma', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'20']) }}
            @if ($errors->has('qc_aroma')) <p class="help-block">{{ $errors->first('qc_aroma') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('qc_aroma_note')) has-error @endif'>
        {{ Form::label('qc_aroma_note', 'qc_aroma_note',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('qc_aroma_note', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'65535']) }}
            @if ($errors->has('qc_aroma_note')) <p class="help-block">{{ $errors->first('qc_aroma_note') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('qc_color')) has-error @endif'>
        {{ Form::label('qc_color', 'qc_color',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('qc_color', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'20']) }}
            @if ($errors->has('qc_color')) <p class="help-block">{{ $errors->first('qc_color') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('qc_color_note')) has-error @endif'>
        {{ Form::label('qc_color_note', 'qc_color_note',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('qc_color_note', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'65535']) }}
            @if ($errors->has('qc_color_note')) <p class="help-block">{{ $errors->first('qc_color_note') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('qc_size')) has-error @endif'>
        {{ Form::label('qc_size', 'qc_size',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('qc_size', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'20']) }}
            @if ($errors->has('qc_size')) <p class="help-block">{{ $errors->first('qc_size') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('qc_size_note')) has-error @endif'>
        {{ Form::label('qc_size_note', 'qc_size_note',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('qc_size_note', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'65535']) }}
            @if ($errors->has('qc_size_note')) <p class="help-block">{{ $errors->first('qc_size_note') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('qc_comments')) has-error @endif'>
        {{ Form::label('qc_comments', 'qc_comments',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('qc_comments', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'65535']) }}
            @if ($errors->has('qc_comments')) <p class="help-block">{{ $errors->first('qc_comments') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('qc_suggestion')) has-error @endif'>
        {{ Form::label('qc_suggestion', 'qc_suggestion',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('qc_suggestion', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'65535']) }}
            @if ($errors->has('qc_suggestion')) <p class="help-block">{{ $errors->first('qc_suggestion') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/diet_qualities" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
