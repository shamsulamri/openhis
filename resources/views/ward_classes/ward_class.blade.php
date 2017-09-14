
    <div class='form-group  @if ($errors->has('class_name')) has-error @endif'>
        <label for='class_name' class='col-sm-3 control-label'>Name<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('class_name', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('class_name')) <p class="help-block">{{ $errors->first('class_name') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('class_price')) has-error @endif'>
        <label for='class_price' class='col-sm-3 control-label'>Price<span style='color:red;'> *</span></label>
        <div class='col-sm-9'>
            {{ Form::text('class_price', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'50']) }}
            @if ($errors->has('class_price')) <p class="help-block">{{ $errors->first('class_price') }}</p> @endif
        </div>
    </div>
    <div class='form-group  @if ($errors->has('class_diet')) has-error @endif'>
        <label for='class_diet' class='col-sm-3 control-label'>Diet</label>
        <div class='col-sm-9'>
            {{ Form::select('class_diet', $diet_classes,null, ['id'=>'class_diet','class'=>'form-control']) }}
            @if ($errors->has('class_diet')) <p class="help-block">{{ $errors->first('class_diet') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="/ward_classes" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>

	<script>


         $(document).ready(function(){
             $("#ward_class_form").validate({
                 rules: {
                     class_price: {
                         number: true
                     },
				 }
             });
        });

    </script>
