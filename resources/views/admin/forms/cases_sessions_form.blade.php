@extends('admin.form')

@section('forms2')
	@section('custom_foot')
	@parent
		<script>
		@if((Request::segment(3) == 'add' || Request::segment(3) == 'add2') && null !== Request::segment(4) && is_numeric(Request::segment(4)))
		$('#case_id').val('{{ Request::segment(4) }}');
		@endif
		$('#status_id').on('change', function(evt, params) {
			var info = $('#info');
			var currentkey = params.selected - 1;
			var currenttext = evt.currentTarget[currentkey].innerText;
			console.log(currentkey);
			console.log(currenttext);

			var currentInfo = info.val();
			if(currentInfo == '') {
				info.val(currenttext);
			}
		});
		</script>
	@stop

@stop