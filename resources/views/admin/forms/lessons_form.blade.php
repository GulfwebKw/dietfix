@extends('admin.form')




@section('custom_foot')
@parent
<script>
	jQuery(document).ready(function($) {  
		@if (isset($item->province_id))
		var startval = $('#province_id').val();
		if (startval) {
			$.getJSON( '{{ url(ADMIN_FOLDER.'/provinces/cities') }}/'+province_id, function( data ) {
			  console.log(data);
			  $('#city_id').html('');
			  $.each( data, function( key, val ) {
			    $('#city_id').append( "<option value='" + val.id + "'>" + val.title + "</option>" );
			  });
			 
			  $('#city_id').val('{{ $item->city_id}}');
			});
		}
		@endif
		$('#province_id').change(function () {
			var province_id = $(this).val();
			console.log(province_id);
			
			$.getJSON( '{{ url(ADMIN_FOLDER.'/provinces/cities') }}/'+province_id, function( data ) {
			  console.log(data);
			  $('#city_id').html('');
			  $.each( data, function( key, val ) {
			    $('#city_id').append( "<option value='" + val.id + "'>" + val.title + "</option>" );
			  });
			 

			});
		});
		
	});


</script>
@stop