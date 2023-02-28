@extends('layouts.main')

@section('contents')
	<div class="col-md-12">
		<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

			<?php $i=0; ?>
			@foreach ($menu as $day => $meals)
					<div class="panel panel-default">
						<div class="panel-heading" role="tab" id="headingThree">
							<h4 class="panel-title">
								<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse{{ $i }}" aria-expanded="true" aria-controls="collapse{{ $i }}">
									{{ $day }} - {{date('l',strtotime($day))}}
								</a>
							</h4>
						</div>

						<div id="collapse{{ $i }}" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingThree">
							<div class="panel-body order-item">
								@foreach ($meals as $meal => $item)
									<h3>{{ $meal }}</h3>
									<img src="{{ !empty($item['order']['item']['photo'])?url(RESIZE_PATH.'?w=100&h=100&r=1&c=1&src=media/items/' . $item['order']['item']['photo']):url('/images/no-image.jpg') }}" class="pull-left flip" alt="{{ $item['item'] }}" style="max-width:100px;max-height:100px;">
									<a href="#" class="btn btn-primary pull-right flip edit-order" data-date="{{$day}}" data-user="{{$user->id}}" data-id="{{ $item['order']['id'] }}"><i class="fa fa-edit"></i></a>
									@if ($item['order']['approved'] == 0)
										<a href="#" class="btn btn-success pull-right flip approve-order" data-id="{{ $item['order']['id'] }}"><i class="fa fa-check"></i></a>
									@endif

									<h3>{{ $item['item'] }}@if ($item['order']['portion'])
											<sup><code>{{ $item['order']['portion']['title'.LANG] }}</code></sup>@endif</h3>
									<p>{{ $item['order']['item']['details'.LANG] }}</p>
									<div class="clearfix"></div>

									@if (count($item['order']['addons']) > 0)
										<div class="alert alert-info">
											@foreach ($item['order']['addons'] as $addon)
												{{ $addon['title'.LANG] }}<br>
											@endforeach
										</div>
									@endif

								@endforeach
							</div>
						</div>
					</div>
					<?php $i++; ?>
			@endforeach
			<a href="{{ url('menu/approve-all/' . $user->id )}}" class="btn btn-block btn-success"><i class="fa fa-check"></i> <i class="fa fa-check"></i> {{ trans('main.Approve All') }}</a>


		</div>
	</div>



	<div class="modal fade" style=" overflow: auto !important;">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title"></h4>
				</div>
				<div id="content-model" class="modal-body">

				</div>



			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div>

@stop





@section('custom_foot')

<script>

	// $('.noaction').click(function(event) {
	// 	event.preventDefault();
	// 	$("#messages").html('<div class="alert alert-danger">{{ trans('main.Can not modify next couple of days') }}</div>');
	// });



	$(".edit-order").click(function  (e) {
		e.preventDefault();
		var orderId = $(this).attr('data-id');
		var user = $(this).attr('data-user');
		var date = $(this).attr('data-date');


		$('#content-model').html("<div align='center'><div class=\"loader\"></div></div>");
		  $('.modal').modal();

		$.get('/menu/order/listHtmlDoctor/'+user+"/"+orderId+"/"+date, null, function(json, textStatus) {
			$('#content-model').html(json);
			$('.modal').modal();
			$('.item-radio').click(function(){
				$(this).parent().parent().find('.item-checks').prop('checked', false);
			});
		});



	});
	$(".approve-order").click(function  (e) {
		e.preventDefault();
		var id = $(this).attr('data-id');
		$.getJSON(APP_URL+'/menu/approve-order/'+id, null, function(json, textStatus) {
  			$("#messages").html('<div class="alert alert-success">{{ trans('main.Saved') }}</div>');
		});
	});
</script>
@stop
