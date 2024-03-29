@extends('layouts.main')
@section('custom_head_include')
{{ HTML::style('assets/datepicker/css/datepicker.css') }}
@stop
@section('custom_foot')
{{ HTML::script('http://momentjs.com/downloads/moment.js') }}
{{ HTML::script('assets/datepicker/js/bootstrap-datepicker.js') }}

<script>
		var date = $('#date').datepicker({
		  onRender: function(date) {
		  	return '';
		    // return (date.valueOf() >= moment()) ? '' : 'disabled';

		  },
		  format: 'yyyy-mm-dd'
		}).on('changeDate', function(ev) {
		  date.hide();
		}).data('datepicker');

</script>
@stop

@section('contents')
	{{ Form::open(array('url' => url('kitchen/get-'.$type), 'method' => 'get', 'class' => 'form-inline form-bordered form-row-stripped spaceForm', 'files' => true, 'role' => 'form')) }}

		{{ Form::label('date', trans('main.Date')) }}
		<div class="control-group form-group">
			<div class="controls">
				{{ Form::text('date', null, array('class' => 'datepicker form-control','id' => 'date')) }}
			</div>
		</div>


		<div class="control-group form-group">
			{{ Form::button('<i class="fa fa-search"></i>', array('class' => 'btn btn-primary','id' => 'submit','type' => 'submit')) }}
		</div>

	{{ Form::close() }}
        <div class="page">
	     	<div class="table-responsive">
            <table class="table table-hover">
              	 <tbody>
				      <tr>
						  <th colspan="1">{{ trans('main.Id') }}</th>
						  <th colspan="1">{{ trans('main.Name') }}</th>
			             <th colspan="1">{{ trans('main.Phone') }}</th>
                          <th colspan="1">{{ trans('main.Package') }}</th>
                          <th colspan="1">Download</th>
		                </tr>
						@if (empty($orders))
					    	<tr><td colspan="4">{{ trans('main.No Results') }}</td></tr>
						    @else
							@foreach ($orders as $user)
								<tr>
									<td colspan="1">{{ $user->id }}</td>
									<td colspan="1">{{ $user->username }}</td>
									<td colspan="1">{{ $user->mobile_number }}</td>
									<td colspan="1">{{ $user->package}}</td>
                                    <td colspan="1"><a href="{{ route('pkReportPDF' , $user->id) }}"><i class="fa fa-download"></i></a></td>
								</tr>
							@endforeach
					    @endif
   	         </tbody>
		       </table>
		</div>
			<hr>
			<br>
			<h4>Package Usage</h4>
			<div class="table-responsive">
				<table class="table table-hover">
					<tbody>
					<tr>
						<th colspan="1">#</th>
						<th colspan="1">{{ trans('main.Package') }}</th>
						<th colspan="1">Count</th>
					</tr>
					@if (empty($orders))
						<tr><td colspan="4">{{ trans('main.No Results') }}</td></tr>
					@else
						@php $i=0;@endphp
						@foreach ($repForPack as $pack=>$arrayUsage)
							<tr>
								<td colspan="1">{{ ++$i}}</td>
								<td colspan="1">{{ $pack }}</td>
								<td colspan="1">{{ count($arrayUsage) }}</td>
							</tr>
						@endforeach
					@endif
					</tbody>
				</table>
			</div>
	    </div>

	<div class="page-break"></div>
@stop
