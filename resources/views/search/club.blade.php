{{ Form::open(['method' => 'get','url' => url('search/clubs'), 'class' => 'awesomebg']) }}
	<table class="table nbbtable">
		<thead>
			<tr>
				<td>
					<h3>{{ trans('main.Search Clubs\\Camps') }}</h3>
				</td>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>
					{{ Form::text('q', null, ['placeholder' => 	trans('main.Search'), 'class' => 'form-control'])}}
				</td>
			</tr>
			<tr>
				<td>
					<select name="area_id" id="area2" class="form-control chosen">
						<option value="">{{ trans('main.Area') }}</option>
						@foreach ($provinces_with_areas as $province)
							@if (!$province->areas->isEmpty())
							<optgroup label="{{ $province->{'title'.LANG} }}">
								@foreach($province->areas as $area)
								<option value="{{ $area->id }}">{{ $area->{'title'.LANG} }}</option>
								@endforeach
							</optgroup>
							@endif
						@endforeach
					</select>
				</td>
			</tr>
			<tr>
				<td>
					{{ Form::select('period', $periods_select , null, ['placeholder' => 	trans('main.Period'), 'class' => 'form-control'])}}
				</td>
			</tr>
			<tr>
				<td>
					{{ Form::select('activity_id', $activities , null, ['placeholder' => 	trans('main.Activities'), 'class' => 'form-control'])}}
				</td>
			</tr>
			<tr>
				<td class="text-center">
					{{ Form::button(trans('main.Search'), ['type' => 	trans('main.submit'), 'class' => 'btn btn-primary btn-success btn-block'])}}
				</td>
			</tr>
		</tbody>
	</table>
{{ Form::close() }}