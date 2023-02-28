@extends('admin.forms.form')

@section('forms2')

	@if (!$options->isEmpty())
	@foreach ($options as $option)
		@if (!$option->values->isEmpty())
		<div class="control-group form-group">
			{{ Form::label('options_'.$option->id, $option->{'title'.ucfirst(LANG_SHORT)} , array('class' => 'control-label col-sm-4')) }}
			<div class="controls col-sm-8">
				<select class="form-control" name="options[{{ $option->id }}]" id="options_{{ $option->id }}">
					@foreach ($option->values as $value)
					<option value="{{ $value->id }}">{{ $value->{'title'.ucfirst(LANG_SHORT)} }}</option>
					@endforeach
				</select>
			</div>
		</div>
		@endif
	@endforeach
	@endif
	
@stop

@section('custom_foot')
@parent
<script>
	jQuery(document).ready(function($) { 
		@if (isset($item->$_pk) && !$item->options_values->isEmpty())
			@foreach ($item->options_values as $options_values)
				$('#options_{{ $options_values->option_id }}').val('{{ $options_values->value_id }}');
			@endforeach
		@endif 
	});
</script>
@stop