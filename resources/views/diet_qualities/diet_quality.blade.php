
    <div class='form-group  @if ($errors->has('qc_date')) has-error @endif'>
        <label for='qc_date' class='col-sm-3 control-label'>Date<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
			<input id="qc_date" name="qc_date" type="text">
            @if ($errors->has('qc_date')) <p class="help-block">{{ $errors->first('qc_date') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('period_code')) has-error @endif'>
        <label for='period_code' class='col-sm-3 control-label'>Period<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::select('period_code', $period,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('period_code')) <p class="help-block">{{ $errors->first('period_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('class_code')) has-error @endif'>
        <label for='class_code' class='col-sm-3 control-label'>Class<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::select('class_code', $class,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('class_code')) <p class="help-block">{{ $errors->first('class_code') }}</p> @endif
        </div>
    </div>

	<hr>
    <div class='form-group  @if ($errors->has('qc_texture')) has-error @endif'>
        {{ Form::label('qc_texture', 'Texture',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::select('qc_texture', $rating,null, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('qc_texture')) <p class="help-block">{{ $errors->first('qc_texture') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('qc_texture_note')) has-error @endif'>
        {{ Form::label('qc_texture_note', 'Note',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::textarea('qc_texture_note', null, ['class'=>'form-control','placeholder'=>'','rows'=>'2']) }}
            @if ($errors->has('qc_texture_note')) <p class="help-block">{{ $errors->first('qc_texture_note') }}</p> @endif
        </div>
    </div>

	<hr>
    <div class='form-group  @if ($errors->has('qc_taste')) has-error @endif'>
        {{ Form::label('qc_taste', 'Taste',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::select('qc_taste', $rating,null, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('qc_taste')) <p class="help-block">{{ $errors->first('qc_taste') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('qc_taste_note')) has-error @endif'>
        {{ Form::label('qc_taste_note', 'Note',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::textarea('qc_taste_note', null, ['class'=>'form-control','placeholder'=>'','rows'=>'2']) }}
            @if ($errors->has('qc_taste_note')) <p class="help-block">{{ $errors->first('qc_taste_note') }}</p> @endif
        </div>
    </div>

	<hr>
    <div class='form-group  @if ($errors->has('qc_aroma')) has-error @endif'>
        {{ Form::label('qc_aroma', 'Aroma',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::select('qc_aroma', $rating,null, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('qc_aroma')) <p class="help-block">{{ $errors->first('qc_aroma') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('qc_aroma_note')) has-error @endif'>
        {{ Form::label('qc_aroma_note', 'Note',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::textarea('qc_aroma_note', null, ['class'=>'form-control','placeholder'=>'','rows'=>'2']) }}
            @if ($errors->has('qc_aroma_note')) <p class="help-block">{{ $errors->first('qc_aroma_note') }}</p> @endif
        </div>
    </div>

	<hr>
    <div class='form-group  @if ($errors->has('qc_color')) has-error @endif'>
        {{ Form::label('qc_color', 'Color',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::select('qc_color', $rating,null, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('qc_color')) <p class="help-block">{{ $errors->first('qc_color') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('qc_color_note')) has-error @endif'>
        {{ Form::label('qc_color_note', 'Note',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::textarea('qc_color_note', null, ['class'=>'form-control','placeholder'=>'','rows'=>'2']) }}
            @if ($errors->has('qc_color_note')) <p class="help-block">{{ $errors->first('qc_color_note') }}</p> @endif
        </div>
    </div>

	<hr>
    <div class='form-group  @if ($errors->has('qc_size')) has-error @endif'>
        {{ Form::label('qc_size', 'Size',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::select('qc_size', $rating,null, ['class'=>'form-control','maxlength'=>'10']) }}
            @if ($errors->has('qc_size')) <p class="help-block">{{ $errors->first('qc_size') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('qc_size_note')) has-error @endif'>
        {{ Form::label('qc_size_note', 'Note',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::textarea('qc_size_note', null, ['class'=>'form-control','placeholder'=>'','rows'=>'2']) }}
            @if ($errors->has('qc_size_note')) <p class="help-block">{{ $errors->first('qc_size_note') }}</p> @endif
        </div>
    </div>

	<hr>
    <div class='form-group  @if ($errors->has('qc_comments')) has-error @endif'>
        {{ Form::label('qc_comments', 'Comments',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::textarea('qc_comments', null, ['class'=>'form-control','placeholder'=>'','rows'=>'2']) }}
            @if ($errors->has('qc_comments')) <p class="help-block">{{ $errors->first('qc_comments') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('qc_suggestion')) has-error @endif'>
        {{ Form::label('qc_suggestion', 'Suggestion',['class'=>'col-sm-3 control-label']) }}
        <div class='col-sm-9'>
            {{ Form::textarea('qc_suggestion', null, ['class'=>'form-control','placeholder'=>'','rows'=>'2']) }}
            @if ($errors->has('qc_suggestion')) <p class="help-block">{{ $errors->first('qc_suggestion') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/diet_qualities" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
	<script>
		$(function(){
				$('#qc_date').combodate({
						format: "DD/MM/YYYY",
						template: "DD MMMM YYYY",
						value: '{{ $diet_quality->qc_date }}',
						maxYear: '{{ $minYear+5 }}',
						minYear: '{{ $minYear }}',
						customClass: 'select'
				});    
		});
	</script>
