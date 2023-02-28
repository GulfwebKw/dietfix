@extends('admin.forms.form')

@section('forms2')
	
	@if(isset($item) && isset($item->$_pk))
	<div class="portlet box purple">
		<div class="portlet-title">
			<div class="caption">
				<i class="fa fa-sitemap"></i>{{ trans('main.Subjects') }}

			</div>
			<div class="tools">
				<a href="javascript:;" class="collapse"></a>
				<a href="javascript:;" class="remove"></a>
			</div>
		</div>
		<div class="portlet-body">
			<div class="table-responsive" id="sub1">
				<a href="{{ url(ADMIN_FOLDER.'/teachers_activites/add/'.$item->id) }}" class="pull-right flip btn green popup">{{ trans('main.Add') }}</a>
				<table class="table table-striped table-hover" id="sub-grid">
					<thead>
						<tr class="flip-content">
							<th width="50%">{{ trans('main.Subject') }}</th>
							<th width="10%">{{ trans('main.Year') }}</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<tr class="waiting text-center" style="display:none;"><td colspan="3">{{ trans('main.Please Wait') }}</td></tr>
					@if(!$item->teachers_activities->isEmpty())
					@foreach($item->teachers_activities as $activity)
						<tr>
							<td>
								{{ $activity->subject->{'title'.LANG} }}

							</td>
							<td>
								{{ $activity->year->{'title'.LANG} }}

							</td>
							<td nowrap="nowrap">
								<a href="{{ url(ADMIN_FOLDER.'/teachers_activites/edit/'.$activity->id).'/'.$item->id }}" class="btn blue popup">{{ trans('main.Edit') }}</a>
								<a href="{{ url(ADMIN_FOLDER.'/teachers_activites/delete/'.$activity->id.'/'.$item->id) }}" class="btn red">{{ trans('main.Delete') }}</a>
							</td>
						</tr>
					@endforeach
					@else
						<tr>
							<td colspan="4">{{ trans('main.No Results') }}</td>
						</tr>
					@endif
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<!--DOC: Aplly "modal-cached" class after "modal" class to enable ajax content caching-->
	<div class="modal fade" id="ajax" role="basic" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
				<button type="button" class="closed btn btn-primary red pull-right flip"><span aria-hidden="true"><i class="fa fa-times"></i></span></button>
				</div>
				<div class="modal-body">
					
			      	<iframe src="" style="zoom:0.60;margin:0 auto;" width="99.6%" height="550" frameborder="0" id="toShowIn"></iframe>

				</div>
			</div>
		</div>
	</div>
	<!-- /.modal -->
	@endif
@stop

@section('custom_foot')
@parent
<script>
	jQuery(document).ready(function($) {  
		$('#sub1').delegate('.popup','click',function(){
			$('#toShowIn').attr("src",'');
			var url = $(this).attr('href');
		    $('#ajax').on('show', function () {

		        $('#toShowIn').attr("src",url);
		      
			});
		    $('#ajax').modal({show:true});
		    return false;
		});
		@if (isset($item))
		$('button.closed').click(function () {
			$('#ajax').modal('hide');
			$('body').removeClass('modal-open');
			$('#sub-grid tbody tr').not('.waiting').remove();
			$('#sub-grid tbody tr.waiting').fadeIn();
			$.getJSON('{{ url(ADMIN_FOLDER . '/teachers/activities-json/' . $item->id) }}', null, function(json, textStatus) {
				$('#sub-grid tbody tr.waiting').fadeOut();
			  $.each(json, function(index, val) {
			  	 var row = '<tr><td>';
			  	 row += val.subject.title{{ LANG }};
			  	 row += '</td><td>';
			  	 row += val.year.title{{ LANG }};
			  	 row += '</td><td nowrap="nowrap">';
			  	 row += '<a href="{{ url(ADMIN_FOLDER.'/teachers_activites/edit') }}/' + val.id + '/{{ $item->id }}" class="btn blue popup">{{ trans('main.Edit') }}</a> <a href="{{ url(ADMIN_FOLDER.'/teachers_activites/delete') }}/' + val.id + '/{{ $item->id }}" class="btn red">{{ trans('main.Delete') }}</a>';
			  	 row += '</td></tr>';
			  	 $('#sub-grid tbody').append(row);
			  });
			});
			// reloadgrid({{ $item->id }});
		});
		@endif
	});

</script>
@stop

<?php /*

@section('forms2')
		@if (!empty($current_relations))
			@foreach ($current_relations as $y => $s)
				<div class="control-group form-group col-sm-6">
					<label for="years{{ $y }}" class="control-label col-sm-4">{{ trans('main.Years') }}</label>
					<div class="controls col-sm-8">
						<select name="years[]" id="years{{ $y }}" class="chosen">
							<option value="0">{{ trans('main.Choose') }}</option>
							@foreach ($all_years as $year)
							<option value="{{ $year->id }}" @if($y = $year->id)selected="selected"@endif>{{ $year->{'title'.LANG} }}</option>
							@endforeach
						</select>			
					</div>
				</div>
				<div class="control-group form-group col-sm-6">
					<label for="subjects{{ $y }}" class="control-label col-sm-4">{{ trans('main.Subjects') }}</label>
					<div class="controls col-sm-8">
						<select name="subjects[]" id="subjects{{ $y }}" class="chosen">
							<option value="0">{{ trans('main.Choose') }}</option>
							@foreach ($all_subjects as $subject)
							<option value="{{ $subject->id }}" @if($s = $subject->id)selected="selected"@endif>{{ $subject->{'title'.LANG} }}</option>
							@endforeach
						</select>			
					</div>
				</div>
				<div class="clearfix"></div>
			@endforeach
		@endif
		@for ($i = 0; $i < (10 - count($current_relations)); $i++)
		<div class="control-group form-group col-sm-6">
			<label for="years{{ $i }}" class="control-label col-sm-4">{{ trans('main.Years') }}</label>
			<div class="controls col-sm-8">
				<select name="years[]" id="years{{ $i }}" class="chosen">
					<option value="0">{{ trans('main.Choose') }}</option>
					@foreach ($all_years as $year)
					<option value="{{ $year->id }}">{{ $year->{'title'.LANG} }}</option>
					@endforeach
				</select>			
			</div>
		</div>
		<div class="control-group form-group col-sm-6">
			<label for="subjects{{ $i }}" class="control-label col-sm-4">{{ trans('main.Subjects') }}</label>
			<div class="controls col-sm-8">
				<select name="subjects[]" id="subjects{{ $i }}" class="chosen">
					<option value="0">{{ trans('main.Choose') }}</option>
					@foreach ($all_subjects as $subject)
					<option value="{{ $subject->id }}">{{ $subject->{'title'.LANG} }}</option>
					@endforeach
				</select>			
			</div>
		</div>
		<div class="clearfix"></div>
		@endfor


@stop
*/ ?>