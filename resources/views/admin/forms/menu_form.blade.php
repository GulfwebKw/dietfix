@extends('admin.forms.form')



@section('forms2')

	



	<div class="control-group permissions">

		<div class="controls">

			<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
			<?php $i=0;?>
			@foreach ($menu as $day => $meals)
			  <div class="panel panel-default">
			    <div class="panel-heading" role="tab" id="headingThree">
			      <h4 class="panel-title">
			        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse{{ $i }}" aria-expanded="true" aria-controls="collapse{{ $i }}">
			         {{ $day }}
			        </a>
			      </h4>
			    </div>
			    <div id="collapse{{ $i }}" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingThree">
			      <div class="panel-body order-item">
                   <?php $j=1;?>
			        @foreach ($meals as $meal => $it)
						<h3>{{ $meal }} @if ($it['order']['updated_on']<>'0000-00-00 00:00:00' && $j==1)<span style="float:right; font-size:12px;">Last Modified : {{ $it['order']['updated_on'] }}-{{ $j }}</span>@endif</h3>
						<img src="{{ url('resize?w=100&h=100&r=1&c=1&src=media/items/' . $it['order']['item']['photo'] ) }}" class="pull-left flip" alt="{{ $it['item'] }}">
				  	 	<h3>{{ $it['item'] }}@if ($it['order']['portion'])
				  	 	<sup><code>{{ $it['order']['portion']['title'.LANG] }}</code></sup>@endif</h3>
				  	 	<p>{{ $it['order']['item']['details'.LANG] }}</p>
				  	 	<div class="clearfix"></div>
				  	 	@if (count($it['order']['addons']) > 0)
				  	 	<div class="alert alert-info">
				  	 		@foreach ($it['order']['addons'] as $addon)
				  	 			{{ $addon['title'.LANG] }}<br>
				  	 		@endforeach
				  	 	</div>
				  	 	@endif
                        <?php $j++; ?>
					@endforeach
                    <?php unset($j);?>
			      </div>
			    </div>
			  </div>
			  <?php $i++; ?>
			@endforeach
		</div>
		</div>

	</div>


@stop
