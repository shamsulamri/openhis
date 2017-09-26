
    <div class='form-group  @if ($errors->has('census_date')) has-error @endif'>
        {{ Form::label('census_date', 'census_date',['class'=>'col-md-2 control-label']) }}
        <div class='col-md-10'>
            <input id="census_date" name="census_date" type="text">
            @if ($errors->has('census_date')) <p class="help-block">{{ $errors->first('census_date') }}</p>     @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('diet_code')) has-error @endif'>
        <label for='diet_code' class='col-sm-2 control-label'>diet_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::select('diet_code', $diet,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('diet_code')) <p class="help-block">{{ $errors->first('diet_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('census_count')) has-error @endif'>
        <label for='census_count' class='col-sm-2 control-label'>census_count<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('census_count', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('census_count')) <p class="help-block">{{ $errors->first('census_count') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/diet_censuses" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
<script>
        $(function(){
                 $('#census_date').combodate({
                         format: "DD/MM/YYYY",
                         template: "DD MMMM YYYY",
                         value: '{{ $diet_census->census_date }}',
                         maxYear: 2016,
                         minYear: 1900,
                         customClass: 'select'
                 });    
         });
</script>