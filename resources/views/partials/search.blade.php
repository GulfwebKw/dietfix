@section('custom_foot')
@parent
	{{ HTML::script('assets/js/search.js') }}
@stop

<div class="block-content block-content-small-padding" id="search">
	<div class="block-content-inner">
	{{ Form::open(array('url' => url('property/search'), 'class' => 'form-signin login-form')) }}
      <div class="form-group col-sm-2">
      	<div class="select-wrapper">
      		{{ Form::select('aqar_section_id', $searchSections, null, array('class' => 'form-control col-sm-12 ','id' => 'aqar_section_id')) }}
      	</div>
      </div><!-- /.form-group -->
      <div class="form-group col-sm-2">
      	<div class="select-wrapper">
      		{{ Form::select('city_id', $searchCities, null, array('class' => 'form-control col-sm-12 ','id' => 'city_id')) }}
      	</div>
      </div><!-- /.form-group -->

      <div class="form-group col-sm-2">
      	<div class="select-wrapper">
      		{{ Form::select('area_id', array(), null, array('class' => 'form-control col-sm-12','id' => 'area_id')) }}
      	</div>
      </div><!-- /.form-group -->

      <div class="form-group col-sm-2">
      	<div class="select-wrapper">
      		{{ Form::select('aqar_type_id', $searchTypes, null, array('class' => 'form-control col-sm-12','id' => 'aqar_type_id')) }}
      	</div>
      </div><!-- /.form-group -->

      <div class="form-group col-sm-2">
      	<div class="select-wrapper">
      		{{ Form::select('min_price', array(), null, array('class' => 'form-control col-sm-12','id' => 'min_price')) }}
      	</div>
      </div><!-- /.form-group -->

      <div class="form-group col-sm-2">
      	<div class="select-wrapper">
      		{{ Form::select('max_price', array(), null, array('class' => 'form-control col-sm-12','id' => 'max_price')) }}
      	</div>
      </div><!-- /.form-group -->

      <div class="form-group col-sm-10">
      	<div id="search-type">
			{{ trans('main.Search Method') }}
			<input type="radio" name="searchType" id="SearchViewTypePhotos" value="photos" checked="checked">
			<label for="SearchViewTypePhotos">{{ trans('main.By Photos') }}</label>
			<input type="radio" name="searchType" id="SearchViewTypeList" value="list">
			<label for="SearchViewTypeList">{{ trans('main.By List') }}</label>
		</div>
      </div><!-- /.form-group -->
		
      <div class="form-group col-sm-2">
      	{{ Form::submit(trans('main.Search'), array('class' => 'btn btn-primary btn-inversed btn-block')) }}
      </div><!-- /.form-group -->
    {{ Form::close() }}
	</div>
</div>