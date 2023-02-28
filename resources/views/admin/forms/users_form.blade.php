@extends('admin.forms.form')



@section('forms2')

	

	@if(isset($item->$_pk))

		<?php $val2 = @unserialize(\App\User::getMeta($item,'adminPermission')); ?>

	@endif

	@if(isset($item->$_pk) && $item->role_id == 10)
	<?php $menus = App\Models\Admin\AdminMenu::all(); ?>

	
	<div class="control-group permissions">

		{{ Form::label('adminPermission', trans('main.Admin Permissions') , array('class' => 'control-label')) }}

		<div class="controls">
			<a href="#" class="btn btn-primary green pull-right flip allCheckBoxes"><i class="fa fa-star"></i> {{ trans('main.All Permissions') }}</a> 

			<table class="table table-striped table-bordered table-advance table-hover">

				<tbody>

				@foreach ($menus as $k => $v)
				<tr>

					<td>

					{{ $v->{'menuTitle'.ucfirst(LANG_SHORT)} }}

					</td>

					<td>

					@lang('main.Add')

					<?php $current = (isset($val2[$v->id]) && !empty($val2[$v->id])) ? $val2[$v->id] : false; ?>

					<?php $id = $v->id; ?>

					{{ Form::checkbox('adminPermission['.$id.'][]' , 'add',  ($current && in_array('add', $current)) ? true : false  , array('class' => 'toggle row'.$id)) }}

					</td>

					<td>

					

					@lang('main.Edit')

					{{ Form::checkbox('adminPermission['.$id.'][]' , 'edit',  ($current && in_array('edit', $current)) ? true : false  , array('class' => 'toggle row'.$id)) }}

					</td>

					<td>



					@lang('main.Delete')

					{{ Form::checkbox('adminPermission['.$id.'][]' , 'delete',  ($current && in_array('delete', $current)) ? true : false  , array('class' => 'toggle row'.$id)) }}

					</td>

					<td>

					

					@lang('main.View')

					{{ Form::checkbox('adminPermission['.$id.'][]' , 'view',  ($current && in_array('view', $current)) ? true : false  , array('class' => 'toggle row'.$id)) }}

					</td>

					<td>

					
					@lang('main.Print')
					{{ Form::checkbox('adminPermission['.$id.'][]' , 'print',  ($current && in_array('print', $current)) ? true : false  , array('class' => 'toggle row'.$id)) }}
					</td>

					<td>
					
					@lang('main.Export')
					{{ Form::checkbox('adminPermission['.$id.'][]' , 'export',  ($current && in_array('export', $current)) ? true : false  , array('class' => 'toggle row'.$id)) }}
					</td>
					<td>
					

					@lang('main.All')

					{{ Form::checkbox('adminPermission['.$id.'][]' , 'all',  null  , array('class' => 'toggle checkAll')) }}

					</td>

				</tr>

				@endforeach

				</tbody>

			</table>

		</div>

	</div>
	@endif


@stop

@section('custom_foot')
@parent
<script>
	jQuery(document).ready(function($) {  
		$('.checkAll').change(function () {
			var rel = $(this).attr('rel');
			var className = '.row'+rel;
			var checkboxes = $(className);
			if($(this).is(':checked')) {
				$(checkboxes).prop('checked',true);
			} else {
				$(checkboxes).prop('checked',false);
			}
		});
		var clickedBefore = false;
		$('.allCheckBoxes').click(function () {
			var checkboxes = $('.toggle');
			if(clickedBefore == false) {
				$(checkboxes).prop('checked',true);
				clickedBefore = true;
			} else {
				$(checkboxes).prop('checked',false);
				clickedBefore = false;
			}
			return false;
		});
	});

	@if(isset($item->$_pk) && $item->role_id > 1)
		$('#height_holder,#weight_holder,#salt_holder,#sex_holder,#package_id_holder,#bmi_holder,#membership_start_holder,#membership_end_holder').hide();
	@endif

</script>
@stop