<!--@extends('layouts.main')
@section('contents')  
<ul>
<?php 
foreach($users as $user)
{
echo '<li>'.$user->username.'		'.$user->membership_end.'</li><br/>';
}

?>
</ul>
@stop()
-->



@extends('admin.forms.form')

@section('custom_foot')
@parent
<script>
	jQuery(document).ready(function($) {  

	});
</script>
@stop