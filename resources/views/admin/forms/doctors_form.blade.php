@extends('admin.forms.form')



@section('forms2')

	


@if(isset($item->$_pk))
	<div class="portlet box purple">
		<div class="portlet-title">
			<div class="caption">
				<i class="fa fa-sitemap"></i>{{ trans('main.Times') }}

			</div>
			<div class="tools">
				<a href="javascript:;" class="collapse"></a>
				<a href="javascript:;" class="remove"></a>
			</div>
		</div>
		<div class="portlet-body">
			<div class="table-responsive">
				<a href="{{ url(ADMIN_FOLDER.'/doctors_times/add/'.$item->id) }}" class="pull-right flip btn green popup">{{ trans('main.Add') }}</a>
				<table class="table table-striped table-hover">
					<thead>
						<tr class="flip-content">
							<th width="50%">{{ trans('main.Day') }}</th>
							<th width="25%">{{ trans('main.Start') }}</th>
							<th width="25%">{{ trans('main.End') }}</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
					@if(!$item->times->isEmpty())
					@foreach($item->times as $time)
						<tr>
							<td>
								{{ $time->day->{'title'.LANG} }}

							</td>
							<td>
								{{ $time->start }}
							</td>
							<td>
								{{ $time->end }}
							</td>
							<td nowrap="nowrap">
								<a href="{{ url(ADMIN_FOLDER.'/doctors_times/edit/'.$time->id).'/'.$item->id }}" class="btn blue popup">{{ trans('main.Edit') }}</a>
								<a href="{{ url(ADMIN_FOLDER.'/doctors_times/delete/'.$time->id.'/'.$item->id) }}" class="btn red">{{ trans('main.Delete') }}</a>
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
	<div class="modal fade" id="ajax">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="closed btn btn-primary red pull-right flip"><span aria-hidden="true"><i class="fa fa-times"></i></span></button>
	      </div>
	      <div class="modal-body">
	      <iframe src="" style="zoom:0.60;margin:0 auto;" width="99.6%" height="550" frameborder="0" id="toShowIn"></iframe>
	      </div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	
	<!-- /.modal -->
	@endif




@stop

@section('custom_foot')
@parent
<script>
	jQuery(document).ready(function($) {  
		$('.popup').click(function(){
			$('#toShowIn').attr("src",'');
			var url = $(this).attr('href');
		    $('#ajax').on('show', function () {

		        $('#toShowIn').attr("src",url);
		      
			});
		    $('#ajax').modal({show:true});
		    return false;
		});
		$('button.closed').click(function () {
			$('#ajax').modal('hide');
			$('body').removeClass('modal-open');
		});
	});
</script>
@stop