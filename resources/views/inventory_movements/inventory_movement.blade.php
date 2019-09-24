
    <div class='form-group  @if ($errors->has('move_code')) has-error @endif'>
        <label for='move_code' class='col-sm-2 control-label'>Movement Type<span style='color:red;'> *</span></label>
        <div class='col-sm-10'>
            {{ Form::select('move_code', $move,null, ['id'=>'movement_code','class'=>'form-control','onchange'=>'movementChanged()']) }}
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
            {{ Form::select('tag_code', $tag,null, ['id'=>'tag_code','class'=>'form-control','onchange'=>'tagChanged()']) }}
            @if ($errors->has('tag_code')) <p class="help-block">{{ $errors->first('tag_code') }}</p> @endif
        </div>
    </div>

    <div class='form-group  @if ($errors->has('target_store')) has-error @endif'>
        <label for='target_store' class='col-sm-2 control-label'>Target Store</label>
        <div class='col-sm-10'>
            {{ Form::select('target_store', $target_stores,null, ['id'=>'target_store','class'=>'form-control','maxlength'=>'20']) }}
            @if ($errors->has('target_store')) <p class="help-block">{{ $errors->first('target_store') }}</p> @endif
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

<script>
		var tag = document.getElementById('tag_code');
		var target_store = document.getElementById('target_store');
		var movement = document.getElementById('movement_code');

		function tagChanged() {
				if (tag.value == 'transfer') {
					target_store.disabled = false;
				} else {
					target_store.value = '';
					target_store.disabled = true;
				}

		}

	
		function movementChanged() {
			tags = [
				@foreach($tags as $tag)
					'{{ $tag->move_code }}:{{ $tag->tag_code }}:{{ $tag->tag_name }}',
				@endforeach
			]

			clearList(tag);

			if (movement.value != '') {
					addList(tag, '', '');
					for (var i=0;i<tags.length;i++) {
							values = tags[i].split(":")
							if (movement.value==values[0]) {
									addList(tag,values[1], values[2]);
							}
					}
			}
			if (tag.length == 0) {
					tag.disabled = true;
			} else {
					tag.disabled = false;
			}
		}

		function clearList(selectedList) {
				var i;
				for(i=selectedList.options.length-1;i>=0;i--)
				{
						selectedList.remove(i);
				}
		}

		function addList(selectedList, value, text ) {
				var optn = document.createElement("OPTION");
				optn.text = text;
				optn.value = value;

				selectedList.options.add(optn);
		}  

		if (!tag.value) {
			target_store.disabled = true;
			clearList(tag);
			tag.disabled = true;
		}

		movementChanged();
</script>
