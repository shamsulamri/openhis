
    <div class='form-group  @if ($errors->has('purchase_id')) has-error @endif'>
        <label for='purchase_id' class='col-sm-2 control-label'>purchase_id<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('purchase_id', null, ['class'=>'form-control','placeholder'=>'',]) }}
            @if ($errors->has('purchase_id')) <p class="help-block">{{ $errors->first('purchase_id') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('invoice_number')) has-error @endif'>
        <label for='invoice_number' class='col-sm-2 control-label'>invoice_number<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::text('invoice_number', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'20']) }}
            @if ($errors->has('invoice_number')) <p class="help-block">{{ $errors->first('invoice_number') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('invoice_date')) has-error @endif'>
        {{ Form::label('invoice_date', 'invoice_date',['class'=>'col-md-2 control-label']) }}
        <div class='col-md-10'>
            <input id="invoice_date" name="invoice_date" type="text">
            @if ($errors->has('invoice_date')) <p class="help-block">{{ $errors->first('invoice_date') }}</p>     @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-offset-2 col-sm-10">
            <a class="btn btn-default" href="/stock_receives" role="button">Cancel</a>
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>
<script>
        $(function(){
                 $('#invoice_date').combodate({
                         format: "DD/MM/YYYY",
                         template: "DD MMMM YYYY",
                         value: '{{ $stock_receive->invoice_date }}',
                         maxYear: 2016,
                         minYear: 1900,
                         customClass: 'select'
                 });    
         });
</script>