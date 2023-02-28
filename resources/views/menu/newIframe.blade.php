{{ HTML::script('assets/js/jquery.validate.min.js') }}

<form action="{{ url('/menu/order/saveOrder') }}" id="submitday" method="post" accept-charset="utf-8">
    <div class="modal-body">
        {{--        <input type="hidden" name="day" value="{{ Request::segment(4) }}">--}}
        <input type="hidden" name="package" value="{{$user->package_id}}">
        <input type="hidden" name="dateId" value="{{$dateId}}">
        <?php if(session()->has('lang')){ $lang = ucfirst(session()->get('lang'));}else{$lang="En";} ?>
        <?php $i=1; ?>
        @foreach($validItems as $i=>$meal)
            <h3 class="text-center meal-row">{{ $meal['title'.$lang] }}</h3>
            <div class="panel-group" id="accordion{{ $i }}" role="tablist" aria-multiselectable="true">
                @if (isset($meal->categories))
                    @foreach ($meal->categories as $ii => $category)
                        @if($category->active==1 && $category->items->count()>=1)
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="heading{{ $category->id }}">
                                    <h4 class="panel-title">
                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion{{ $i }}" href="#collapse{{ $category->id }}" aria-expanded="false" aria-controls="collapse{{ $category->id }}">{{ $category['title'.$lang] }}</a>
                                    </h4>
                                </div>

                                <div id="collapse{{ $category->id}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                                    <div class="panel-body">

                                        @foreach ($category->items as $iii => $item)

                                            @if(array_key_exists($meal->id,$selectedItem))
                                                @if($selectedItem[$meal->id]['catId']== $category->id)
                                                    @if($selectedItem[$meal->id]['itemId']==$item->id)
                                                        <?php $select = ' checked="checked"'; ?>
                                                    @else
                                                        <?php $select = ''; ?>
                                                    @endif

                                                @else
                                                    <?php $select = ''; ?>

                                                @endif

                                            @else
                                                <?php $select = ''; ?>
                                            @endif




                                            <div class="order-item form-group">
                                                <input type="radio" {{ $select }} name="items[{{$meal->id}}][item]" id="item{{$item->id}}" class="pull-left flip item-radio" value="{{$category->id}} | {{$item->id}}" required>
                                                <img src="{{ !empty($item->photo)?url('/resize?w=100&h=100&r=1&c=1&src=/media/items/'.$item->photo):url('/images/no-image.jpg')}}" class="pull-left flip" alt="{{ $item->titleEn }}" style="max-width:100px;max-height:100px;">
                                                <h3>{{ $item['title'.$lang] }}</h3>
                                                <p>{{ $item['details'.$lang] }}</p>
                                                @if (isset($item->addons))
                                                    @foreach ($item->addons as $iiii => $addon)

                                                        @if(array_key_exists($meal->id,$selectedItem))
                                                            @if($selectedItem[$meal->id]['catId']== $category->id)

                                                                @if(in_array($addon->id,$selectedItem[$meal->id]['addons']->toArray()))
                                                                    <?php $select2 = ' checked="checked"'; ?>
                                                                @else
                                                                    <?php $select2 = ''; ?>
                                                                @endif
                                                            @else
                                                                <?php $select2 = ''; ?>
                                                            @endif

                                                        @else
                                                            <?php $select2 = ''; ?>
                                                        @endif

                                                        <input type="checkbox" {{ $select2 }} name="items[{{ $meal->id}}][addons][]" class="item-checks" id="addon{{$item->id}}- {{ $addon->id }}" value="{{ $addon->id }}">
                                                        {{ $addon['title'.$lang] }}
                                                    @endforeach
                                                @endif
                                            </div>
                                        @endforeach

                                    </div>
                                </div>

                            </div>
                        @endif
                    @endforeach
                @endif

            </div>
       <?php $i++;?>
        @endforeach

        <input type="hidden" name="total_meal" id="total_meal" value="{{$i}}" />

    </div>
    <div class="modal-footer">
        <img src="http://www.dietfix.com/images/loader.gif"  id="gifloading" style="display:none;" width="50"/>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        {{--        @if($package['day']['day_number'] == date('w')) noactionsave @endif--}}
        <input type="submit"   value="{{ trans('main.Save') }}" class="btn btn-primary saveForm ">
    </div>
</form>

<script>
    $( ".saveForm" ).click(function(event) {
        $("#gifloading").show();
        event.preventDefault();
        var form = $( "#submitday" );
        form.validate();
        if(form.valid()) {
            var form = $("#submitday").serialize();

            $.ajax({
                type: "POST",
                url:"/menu/order/saveOrder",
                data: form
            }).done(function( msg ) {
                if(msg.result) {
                    $('.modal').modal('hide');
                    if ($('.saveForm').hasClass('noactionsave')) {
                        $("#messages").html('<div class="alert alert-info">{{ trans('main.Saved') }}<br>{{ trans('main.Your changes will not be effected for this Day') }}</div>');
                    } else {
                        $("#messages").html('<div class="alert alert-success">{{ trans('main.Saved') }}</div>');
                    }
					$("#gifloading").hide();
                    window.location.reload(false);

                }else{
				$("#gifloading").hide();
				alert('Please Select Item For All Meal')
				}
            });
        }else{
            alert('Please Select Item For All Meal');
        }
    });

</script>
