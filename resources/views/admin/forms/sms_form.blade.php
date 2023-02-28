@extends('admin.forms.form')



@section('custom_foot')
@parent
<script>

	

	jQuery(document).ready(function($) {  

		add_counter('content',140);
		
	});

</script>
@stop