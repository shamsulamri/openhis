	<div class='form-group  @if ($errors->has('consultation_notes')) has-error @endif'>
        <div class='col-sm-12'>
        {{ Form::label('Notes', 'Notes',['class'=>'control-label']) }}
            {{ Form::textarea('consultation_notes', null, ['class'=>'form-control','placeholder'=>'','maxlength'=>'65535']) }}
            @if ($errors->has('consultation_notes')) <p class="help-block">{{ $errors->first('consultation_notes') }}</p> @endif
        </div>
    </div>

    <div class='form-group'>
        <div class="col-sm-12">
            {{ Form::submit('Save', ['class'=>'btn btn-primary']) }}
        </div>
    </div>

	<br>
@if (count($notes)>0)
<table class="table table-hover">
 <thead>
	<tr> 
    <th>Seen at</th>
    <th>Note</th>
	</tr>
  </thead>
	<tbody>
	@foreach ($notes as $note)
	<tr>
			<td class='col-xs-2'>
					{{ date('d F Y, H:i', strtotime($note->created_at)) }}
			</td>
			<td>
						{!! str_replace(chr(13), "<br>", $note->consultation_notes) !!}
			</td>
	</tr>
@endforeach
</tbody>
</table>
@endif
    {{ Form::hidden('encounter_id', null, ['class'=>'form-control','placeholder'=>'',]) }}
    {{ Form::hidden('user_id', null, ['class'=>'form-control','placeholder'=>'',]) }}
