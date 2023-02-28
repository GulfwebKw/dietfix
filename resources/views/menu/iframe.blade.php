{{ HTML::script('assets/js/jquery.validate.min.js') }}

<form action="{{ url('menu/save') }}" id="submitday" method="post" accept-charset="utf-8">
	<div class="modal-body">
	<input type="hidden" name="day" value="{{ Request::segment(4) }}">
	<input type="hidden" name="package" value="{{ Request::segment(3) }}">
	<input type="hidden" name="user" value="{{ Request::segment(5) }}">


	@foreach ($package['meals'] as $i => $meal)
		<h3 class="text-center meal-row">{{ $meal['title'.LANG] }}</h3>
		<div class="panel-group" id="accordion{{ $i }}" role="tablist" aria-multiselectable="true">
			@if (isset($meal['categories']))
				@foreach ($meal['categories'] as $ii => $category)
                   @if($category['active']==1)
					<div class="panel panel-default">
				  	 	<div class="panel-heading" role="tab" id="heading{{ $category['id'] }}">
					  	 	<h4 class="panel-title">
					  	 		<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion{{ $i }}" href="#collapse{{ $category['id'] }}" aria-expanded="false" aria-controls="collapse{{ $category['id'] }}">{{ $category['title'.LANG] }}</a>
					  	 	</h4>
			  	 		</div>
			  	 		<div id="collapse{{ $category['id'] }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
			  	 			<div class="panel-body">
								@if (isset($category['items']))
									@foreach ($category['items'] as $iii => $item)
										@if($item['selected'])
				  	 						<?php $select = ' checked="checked"'; ?>
				  	 					@else
				  	 						<?php $select = ''; ?>
				  	 					@endif
				  	 					<div class="order-item form-group">
								  	 		<input type="radio" {{ $select }} name="items[{{ $meal['id'] }}][item]" id="item{{ $item['id'] }}" class="pull-left flip item-radio" value="{{ $category['id'] }}|{{ $item['id'] }}" required>
								  	 		<img src="{{ url('/resize?w=100&h=100&r=1&c=1&src=/media/items/'.$item['photo']) }}" class="pull-left flip" alt="{{ $item['title'.LANG] }}">
								  	 		<h3>{{ $item['title'.LANG] }}</h3>
								  	 		<p>{{ $item['details'.LANG] }}</p>
								  	 		@if (isset($item['addons']))
												@foreach ($item['addons'] as $iiii => $addon)
													@if($addon['selected'])
							  	 						<?php $select2 = ' checked="checked"'; ?>
							  	 					@else
							  	 						<?php $select2 = ''; ?>
							  	 					@endif
							  	 					<input type="checkbox" {{ $select2 }} name="items[{{ $meal['id'] }}][addons][]" class="item-checks" id="addon{{ $item['id'] }}-{{ $addon['id'] }}" value="{{ $addon['id'] }}">
							  	 					{{ $addon['title'.LANG] }}
												@endforeach
											@endif
								  	 	</div>
									@endforeach
								@endif
			  	 			</div>
			  	 		</div>
			  	 	</div>
                  @endif
				@endforeach
			@endif
		</div>
	@endforeach
	</div>
	<div class="modal-footer">
        <img src="http://www.dietfix.com/members/public/cpassets/images/load.gif"  id="gifloading" style="display:none;"/>
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		<input type="submit" value="{{ trans('main.Save') }}" class="btn btn-primary saveForm @if($package['day']['day_number'] == date('w')) noactionsave @endif">
	</div>
</form>

<script>
	// jQuery.validator.setDefaults({
	//   debug: true,
	//   success: "valid"
	// });


	$( ".saveForm" ).click(function(event) {
	    $("#gifloading").show();
		event.preventDefault();
		var form = $( "#submitday" );
		form.validate();
		if (form.valid()) {
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
			  		window.location.reload(false);

			  	}
			  });
		  }
	});
	// $('#submitday').validator().on('submit', function (e) {
	// 	console.log(e);
	// 	console.log($(this));
	//   if (e.isDefaultPrevented()) {
	//     // handle the invalid form...
	//   } else {
	//   	e.preventDefault();
	//     var form = $("#submitday").serialize();

	// 	$.ajax({
	// 	    type: "POST",
	// 	    url: APP_URL+"/menu/save",
	// 	    data: form
	// 	  }).done(function( msg ) {
	// 	  	if(msg.result) {
	// 	  		$('.modal').modal('hide');
	// 	  		if ($('.saveForm').hasClass('noactionsave')) {
	// 	  			$("#messages").html('<div class="alert alert-info">{{ trans('main.Saved') }}<br>{{ trans('main.Your changes will not be effected for this week') }}</div>');
	// 	  		} else {
	// 	  			$("#messages").html('<div class="alert alert-success">{{ trans('main.Saved') }}</div>');
	// 	  		}
	// 	  	}
	// 	  });
	//   }
	// });
	// $('.saveForm').click(function(event) {

	// 	event.preventDefault();

	// 	var form = $("#submitday").serialize();

	// 	$.ajax({
	// 	    type: "POST",
	// 	    url: APP_URL+"/menu/save",
	// 	    data: form
	// 	  }).done(function( msg ) {
	// 	  	if(msg.result) {
	// 	  		$('.modal').modal('hide');
	// 	  		if ($('.saveForm').hasClass('noactionsave')) {
	// 	  			$("#messages").html('<div class="alert alert-info">{{ trans('main.Saved') }}<br>{{ trans('main.Your changes will not be effected for this week') }}</div>');
	// 	  		} else {
	// 	  			$("#messages").html('<div class="alert alert-success">{{ trans('main.Saved') }}</div>');
	// 	  		}
	// 	  	}
	// 	  });
	// });
</script>
