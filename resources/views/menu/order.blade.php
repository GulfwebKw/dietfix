@extends('layouts.main')
@section('custom_foot')
	{{ HTML::script('assets/js/jquery.validate.min.js') }}

	<script>
		var date = new Date();
		var day_number = date.getDay();
		var next_day = (day_number == 6) ? 0 : day_number+1;
		var next2days = $('.date'+day_number+',.date'+next_day);
		next2days.addClass('noaction');

		// $('.noaction').click(function(event) {
		// 	event.preventDefault();
		// 	$("#messages").html('<div class="alert alert-danger">{{ trans('main.Can not modify next couple of days') }}</div>');
		// });

		$(".showdate").click(function  (e) {
			e.preventDefault();
			var p = $(this).attr('data-package');
			var d = $(this).attr('data-day');
			var u = $(this).attr('data-user');


			$.get(APP_URL+'/menu/package-day-html/'+p+'/'+d+'/'+u, null, function(json, textStatus) {
				$('#content-model').html(json);
				$('.modal').modal();
				$('.item-radio').click(function(){
					$(this).parent().parent().find('.item-checks').prop('checked', false);
				});
			});
		});
		$(".showdate2").click(function  (e) {
			e.preventDefault();
			var p = $(this).attr('data-package');
			var d = $(this).attr('data-day');
			var u = $(this).attr('data-user');

			// var meals = [];
			$.getJSON(APP_URL+'/menu/package-day/'+p+'/'+d+'/'+u, null, function(json, textStatus) {
				$('.modal-title').html(json.day['title'+LANG]);
				var html = '<form action="'+APP_URL+'/menu/save" id="submitday" method="post" accept-charset="utf-8">';
				html += '<input type="hidden" name="day" value="' + d + '">';
				html += '<input type="hidden" name="package" value="' + p + '">';
				html += '<input type="hidden" name="user" value="' + u + '">';
				$.each(json.meals, function(i, meal) {
					html += '<h3 class="text-center meal-row">\
					' + meal['title'+LANG] + '\
		  	 </h3>';

					// meals.push(meal.id);

					html += '<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">';
					if(meal.categories != undefined) {
						$.each(meal.categories, function(ii, category) {
							html += '<div class="panel panel-default">\
			  	 	<div class="panel-heading" role="tab" id="headingTwo">\
			  	 	<h4 class="panel-title">\
			  	 	<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse' + category.id + '" aria-expanded="false" aria-controls="collapse' + category.id + '">' + category['title'+LANG] + '</a>\
			  	 	</h4>\
			  	 	</div>\
			  	 	<div id="collapse' + category.id + '" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo"><div class="panel-body">';
							if(category.items != undefined) {
								$.each(category.items, function(iii, item) {
									if(item['selected'])
										var select = ' checked="checked"';
									else
										var select = '';
									html += '<div class="order-item form-group">\
					  	 	<input type="radio" '+select+'name="items[' + meal.id + '][item]" id="item' + item.id + '" required="required" class="pull-left flip item-radio" value="' + category.id + '|' + item.id + '">\
					  	 	<img src="' + APP_URL + '/resize?w=100&h=100&r=1&c=1&src=media/items/' + item.photo + '" class="pull-left flip" alt="' + item['title'+LANG] + '">\
					  	 	<h3>' + item['title'+LANG] + '</h3>\
					  	 	<p>' + item['details'+LANG] + '</p>\
					  	 	';
									if(item.addons != undefined) {
										$.each(item.addons, function(iiii, addon) {
											if(addon['selected'])
												var select = ' checked="checked"';
											else
												var select = '';
											html += '<input type="checkbox" '+select+'name="items[' + meal.id + '][addons][]" class="item-checks" id="addon' + item.id + '-' + addon.id + '" value="' + addon.id + '">\
							  	 	' + addon['title'+LANG] + '\
							  	 	';
										});
									}
									html += '</div>';
								});
							}
							html += '</div>\
					    </div>\
					  </div>';
						});
					}

					html += '</div>';

				});
				html += '</form>';


				$('.modal-body').html(html);
				$('.modal').modal();
				$('.item-radio').click(function(){
					$('.item-checks').prop('checked', false);
				});



				$('.saveForm').click(function(event) {

					event.preventDefault();

					var form = $("#submitday").serialize();

					$.ajax({
						type: "POST",
						url: APP_URL+"/menu/save",
						data: form
					}).done(function( msg ) {
						if(msg.result) {
							$('.modal').modal('hide');
							if ($('.saveForm').hasClass('noactionsave')) {
								$("#messages").html('<div class="alert alert-info">{{ trans('main.Saved') }}<br>{{ trans('main.Your changes will not be effected for this week') }}</div>');
							} else {
								$("#messages").html('<div class="alert alert-success">{{ trans('main.Saved') }}</div>');
							}
							console.log(11111);
							window.location.reload(false);
						}
					});
				});

			});




			if ($(this).hasClass('noaction')) {
				$('.saveForm').addClass('noactionsave');
			}
		});
	</script>
@stop
@section('contents')
	<div class="col-md-6 col-md-push-3">
		@foreach ($days as $day)
			<div>
				<h3>
					<a href="#" data-day="{{ $day->id }}" data-package="{{ $package->id }}" data-user="{{ $user->id }}" class="btn @if($filled_days[$day->id])btn-primary @else btn-danger @endif  btn-lg btn-block showdate date{{ $day->day_number }}">{{ $day->{'title'.LANG} }}</a>
				</h3>
			</div>
		@endforeach

	</div>
	<div class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title"></h4>
				</div>
				<div id="content-model"></div>
				{{-- <div class="modal-body">
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="button" class="btn btn-primary saveForm">Save changes</button>
                </div> --}}
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

@stop