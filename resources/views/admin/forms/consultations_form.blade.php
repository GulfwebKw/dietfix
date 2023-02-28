@extends('admin.form')

@section('forms2')
	@section('custom_foot')
	@parent
		<script>
		@if(Request::segment(3) == 'add' && null !== Request::segment(4) && is_numeric(Request::segment(4)))
		$('#user_id').val('{{ Request::segment(4) }}');
		@endif
		</script>
	@stop

@stop