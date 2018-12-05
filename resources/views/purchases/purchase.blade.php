
    <div class='form-group  @if ($errors->has('document_code')) has-error @endif'>
        <label for='document_code' class='col-sm-2 control-label'>document_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
			{{ Form::select('document_code', $documents) }}
            @if ($errors->has('document_code')) <p class="help-block">{{ $errors->first('document_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('supplier_code')) has-error @endif'>
        <label for='supplier_code' class='col-sm-2 control-label'>supplier_code<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::select('supplier_code', $supplier,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('supplier_code')) <p class="help-block">{{ $errors->first('supplier_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('purchase_date')) has-error @endif'>
        <label for='purchase_date' class='col-sm-2 control-label'>Purchase Date</label>
        <div class='col-sm-10'>
			<div class="input-group date">
				{{ Form::text('purchase_date',null, ['class'=>'form-control','data-mask'=>'99/99/9999','id'=>'purchase_date']) }}
				<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
			</div>
			@if ($errors->has('purchase_date')) <p class="help-block">{{ $errors->first('purchase_date') }}</p> @endif
        </div>
    </div>


    <div class='form-group  @if ($errors->has('purchase_description')) has-error @endif'>
        {{ Form::label('purchase_description', 'purchase_description',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('purchase_description', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'65535']) }}
            @if ($errors->has('purchase_description')) <p class="help-block">{{ $errors->first('purchase_description') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('purchase_reference')) has-error @endif'>
        {{ Form::label('purchase_reference', 'purchase_reference',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('purchase_reference', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'65535']) }}
            @if ($errors->has('purchase_reference')) <p class="help-block">{{ $errors->first('purchase_reference') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/purchases" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>

	<script>
		$('#purchase_date').datepicker({
				format: "dd/mm/yyyy",
				todayBtn: "linked",
				keyboardNavigation: false,
				forceParse: false,
				calendarWeeks: true,
				autoclose: true
		});
	</script>
