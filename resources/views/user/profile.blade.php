@extends('layouts.main')

@section('contents')
	
	<h3 class="hero">
		{{ $user->username }}
	</h3>
	{{ Form::open(array('url' => 'user/saveprofile','class' => 'form-horizontal')) }}
	{{ Form::hidden('user_id',$user->id) }}
	<table class="table table-striped table-hover table-bordered table-responsive">
		<tbody>
			<tr>
				<th class="col-md-6">{{ trans('main.Username') }}</th>
				<td class="col-md-6">{{ $user->username }}</td>
			</tr>
			<tr>
				<th class="col-md-6">{{ trans('main.Email') }}</th>
				<td class="col-md-6">{{ $user->email }}</td>
			</tr>
			<tr>
				<th class="col-md-6">{{ trans('main.Phone') }}</th>
				<td class="col-md-6">{{ $user->phone }}</td>
			</tr>
			<tr>
				<th class="col-md-6">{{ trans('main.Country') }}</th>
				<td class="col-md-6">{{ $user->country->{'title'.LANG} }}</td>
			</tr>
			<tr>
				<th class="col-md-6">{{ trans('main.Province') }}</th>
				<td class="col-md-6">{{ $user->province->{'title'.LANG} }}</td>
			</tr>
			<tr>
				<th class="col-md-6">{{ trans('main.Area') }}</th>
				<td class="col-md-6">{{ $user->area->{'title'.LANG} }}</td>
			</tr>
			<tr>
				<th class="col-md-6">{{ trans('main.Address') }}</th>
				<td class="col-md-6">{{ $user->address }}</td>
			</tr>
			<tr>
				<th class="col-md-6">{{ trans('main.Sex') }}</th>
				<td class="col-md-6">{{ $user->sex }}</td>
			</tr>
			<tr>
				<th class="col-md-6">{{ trans('main.Salt') }}</th>
				<td class="col-md-6">{{ Form::select('salt', $salts ,$user->salt, ['class' => 'col-sm-3 form-control'])}}</td>
			</tr>
			<tr>
				<th class="col-md-6">{{ trans('main.Height') }}</th>
				<td class="col-md-6">{{ Form::text('height', $user->height, ['class' => 'col-sm-3 form-control'])}}</td>
			</tr>
			<tr>
				<th class="col-md-6">{{ trans('main.Weight') }}</th>
				<td class="col-md-6">{{ Form::text('weight', $user->weight, ['class' => 'col-sm-3 form-control'])}}</td>
			</tr>
			<tr>
				<th class="col-md-6">{{ trans('main.BMI') }}</th>
				<td class="col-md-6">{{ $user->bmi }}</td>
			</tr>
			<tr>
				<th class="col-md-6">{{ trans('main.Package') }}</th>
				<td class="col-md-6">{{ $user->package->{'title'.LANG} }}</td>
			</tr>
			<tr>
				<th class="col-md-6">{{ trans('main.Membership Start') }}</th>
				<td class="col-md-6">{{ $user->membership_start }}</td>
			</tr>
			<tr>
				<th class="col-md-6">{{ trans('main.Membership End') }}</th>
				<td class="col-md-6">{{ $user->membership_end }}</td>
			</tr>
			<tr>
				<th class="col-md-6">{{ trans('main.Created Date') }}</th>
				<td class="col-md-6">{{ $user->created_at }}</td>
			</tr>
		</tbody>
	</table>
	<div class="text-center">
		{{ Form::submit(trans('main.Change'), array('class' => 'btn btn-primary btn-inversed')) }}
	</div>
	{{ Form::close() }}

	
@stop