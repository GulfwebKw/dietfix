@extends('layouts.main')

@section('contents')
	
	<h3 class="hero">
		{{ trans('main.Users') }}
	</h3>
	{{ HTML::script('cpassets/jquery-ui-1.11.4/external/jquery/jquery.js') }}


	
	{{ Form::open(['url' => 'menu/users','method' => 'get','id'=>'userForm']) }}
	<div class="form-group">
		<label for="filterAllUser" class="control-label">Show All Users</label>
		<input type="checkbox" @if(session()->get('typeFilterUser',false)) checked @endif value="1" id="filterAllUser" name="filterAllUser" >
	</div>

	<div class="table-responsive">
		<table class="table table-striped table-hover table-bordered">
			<thead>
				<tr>
					<th><i class="fa fa-spoon"></i></th>
					<th>{{ trans('main.ID') }}</th>
					<th>{{ trans('main.Username') }}</th>
					<th>{{ trans('main.Note') }}</th>
					<th>{{ trans('main.Phone') }}</th>
					<th>{{ trans('main.Clinic') }}</th>
					<th>{{ trans('main.Height') }}</th>
					<th>{{ trans('main.Weight') }}</th>
					<th>{{ trans('main.BMI') }}</th>
					<th>{{ trans('main.Membership End') }}</th>
					<th>{{ trans('main.Actions') }}</th>
				</tr>
				<tr>
					<th></th>
					<th>{{ Form::text('id',Input::get('id'),['class' => 'form-control']) }}</th>
					<th>{{ Form::text('username',Input::get('username'),['class' => 'form-control']) }}</th>
					<th>{{ Form::text('note',Input::get('note'),['class' => 'form-control']) }}</th>
					<th>{{ Form::text('phone',Input::get('phone'),['class' => 'form-control']) }}</th>
					<th></th>
					<th>{{ Form::text('height',Input::get('height'),['class' => 'form-control']) }}</th>
					<th>{{ Form::text('weight',Input::get('weight'),['class' => 'form-control']) }}</th>
					<th>{{ Form::text('bmi',Input::get('bmi'),['class' => 'form-control']) }}</th>
					<th>{{ Form::text('membership_end',Input::get('membership_end'),['class' => 'form-control']) }}</th>
					<th>{{ Form::button('<i class="fa fa-search"></i>', array('type' => 'submit', 'class' => 'btn btn-default yellow')) }}</th>
				</tr>
			</thead>
			<tbody>
			@if ($users->isEmpty())
				<tr>
					<td colspan="10">{{ trans('main.No Results') }}</td>
				</tr>
			@else
			@foreach ($users as $user)
				<tr>
					<td> <?php  // previous code @if ($user->orders->count() > 0) ?>
						@if ($user->error_mark > 0)
							<a href="{{ url('menu/user-menu/'.$user->id) }}" class="btn btn-danger btn-xs">*</a>
						@endif
					</td>
					<td>{{ $user->id }}</td>
					<td><a href="{{ url('user/profile/'.$user->id) }}">{{ $user->username }}</a></td>
					<td>{{ $user->note }}</td>
					<td>{{ $user->phone }}</td>
					<td>{{ $user->clinic->{'title'.LANG} }}</td>
					<td>{{ $user->height }}</td>
					<td>{{ $user->weight }}</td>
					<td>{{ $user->bmi }}</td>
					<td>{{ $user->membership_end }}</td> 
					<td nowrap="nowrap">
					<a href="{{ url('menu/user-menu/'.$user->id) }}" class="btn btn-primary btn-xs"><i class="fa fa-spoon"></i></a>
					<a href="{{ url('user/profile/'.$user->id) }}" class="btn btn-primary btn-xs"><i class="fa fa-user"></i></a>
					@if(Auth::user()->clinic->can_appointment)
					<a href="{{ url('appointments?user='.$user->id) }}" class="btn btn-primary btn-xs"><i class="fa fa-clock-o"></i></a>
					@endif
					@if($user->autoapprove_menus > 0)
					<input type="checkbox" onchange="autoapproveMenu({{ $user->id }})"  class="userid-{{ $user->id }}" checked="true"> </input>
					@else
					<input type="checkbox" onchange="autoapproveMenu({{ $user->id }})"  class="userid-{{ $user->id }}"> </input>
					@endif
					</td>
				</tr>
			@endforeach
			@endif
			</tbody>
		</table>
	</div>
	{{ Form::close() }}
	
	{{ $users->links() }}



	<script type="text/javascript">

		function autoapproveMenu(userId) {
			event.preventDefault();
			console.log(userId);
			var isChecked = $(".userid-"+userId).is(":checked");
			$.ajax({
				url: "auto_approve-menu",
				data: {userid : userId, isChecked: isChecked},
				type: "POST"
			}).done(function(msg){
				if(msg != null)
				{
					var json = $.parseJSON(msg);
					if(json["result"]=== "SUCCESS")
					{
						$(".userid-"+userId).parent().siblings().eq(0).empty();
						console.log($(".userid-"+userId).eq(0).val());
					}
				}

			});

		}
		$(document).ready(function(){
			$(".table.table-striped.table-hover.table-bordered > tbody  > tr").each(function(k, v){
				var useridVal = $(this).children().eq(1).text();
				var selectorName = ".userid-"+useridVal;
				if($(this).children().eq(0).text().indexOf("*")>-1)
				{
					// enable the auto approve toggle
					console.log($(this).find(".auto_approve"));
					var useridVal = $(this).children().eq(1).text();
					console.log($(this).children().eq(1).text());
					var selectorName = ".userid-"+useridVal;
					$(selectorName).removeAttr("checked");
				}
			});

			$("#filterAllUser").on("change",function () {
					$("#userForm").submit();
			});
		});
	</script>
	
@stop