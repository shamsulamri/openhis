
    <div class='form-group  @if ($errors->has('document_code')) has-error @endif'>
        <label for='document_code' class='col-sm-2 control-label'>Document<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
			{{ Form::select('document_code', $documents, null, ['id'=>'document_code','onchange'=>'documentChanged()']) }}
            @if ($errors->has('document_code')) <p class="help-block">{{ $errors->first('document_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('supplier_code')) has-error @endif'>
        <label for='supplier_code' class='col-sm-2 control-label'>Supplier<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::select('supplier_code', $supplier,null, ['class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('supplier_code')) <p class="help-block">{{ $errors->first('supplier_code') }}</p> @endif
			Not required for purchase request
        </div>
    </div>

    <div class='form-group  @if ($errors->has('store_code')) has-error @endif'>
        <label for='store_code' class='col-sm-2 control-label'>Store</label>
        <div class='col-sm-10'>
            {{ Form::select('store_code', $store,null, ['id'=>'store_code','class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('store_code')) <p class="help-block">{{ $errors->first('store_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('purchase_description')) has-error @endif'>
        {{ Form::label('purchase_description', 'Description',['class'=>'col-sm-2 control-label']) }}
        <div class='col-sm-10'>
            {{ Form::text('purchase_description', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'65535']) }}
            @if ($errors->has('purchase_description')) <p class="help-block">{{ $errors->first('purchase_description') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('purchase_reference')) has-error @endif'>
        {{ Form::label('purchase_reference', 'Reference',['class'=>'col-sm-2 control-label']) }}
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

		function documentChanged() {
				document_code = document.getElementById('document_code').value;
				store = document.getElementById('store_code');
				switch (document_code) {
						case 'goods_receive':
								store.disabled = false;
								store.value = '{{ $store_code }}';
								break;
						case 'purchase_invoice':
								store.disabled = false;
								store.value = '{{ $store_code }}';
								break;
						case 'purchase_request':
								store.disabled = false;
								store.value = '{{ $store_code }}';
								break;
						default:
							store.disabled = true;
				}
		}

		document.getElementById('store_code').disabled = true;
		documentChanged();
	</script>
