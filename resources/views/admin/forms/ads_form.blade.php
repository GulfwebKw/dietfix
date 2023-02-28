@extends('admin.forms.form')

@section('forms2')
	@section('custom_foot')
	@parent
		<script>
		@if (isset($item->$_pk))
		var startval = $('#section_id').val();
		if (startval) {
			$.getJSON( '{{ url(ADMIN_FOLDER.'/sections/cats') }}/'+startval, function( data ) {
			  $('#category_id').html('');
			  $.each( data, function( key, val ) {
			    $('#category_id').append( "<option value='" + val.id + "'>" + val.title + "</option>" );
			  });
			 
			  $('#category_id').val('{{ $item->category_id }}');
			});
		}
		@endif

		$('#section_id').change(function () {
			var section_id = $(this).val();
			
			$.getJSON( '{{ url(ADMIN_FOLDER.'/sections/cats') }}/'+section_id, function( data ) {
			  $('#category_id').html('');
			  $.each( data, function( key, val ) {
			    $('#category_id').append( "<option value='" + val.id + "'>" + val.title + "</option>" );
			  });
			 

			});
		});
		

		</script>
	@stop

@stop