{{ Form::open(['method' => 'get','url' => url('search/teachers'), 'class' => 'awesomebg']) }}
	<table class="table nbbtable">
		<thead>
			<tr>
				<td>
					<h3>{{ trans('main.Search Teachers') }}</h3>
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
					<select name="area_id" id="area3" class="form-control chosen">
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
					{{ Form::select('gender', $genders_select , null, ['placeholder' => 	trans('main.Gender'), 'class' => 'form-control'])}}
				</td>
			</tr>
			<tr>
				<td>
					{{ Form::select('subject_id', $subjects , null, ['placeholder' => 	trans('main.Subject'), 'class' => 'form-control'])}}
				</td>
			</tr>
			<tr>
				<td>
					{{ Form::select('year_id', $years , null, ['placeholder' => 	trans('main.Grade'), 'class' => 'form-control'])}}
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