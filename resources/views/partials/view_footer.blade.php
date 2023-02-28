<div class="col-md-8">
    <div class="col-md-4 col-padding10 text-center dark" style="background:rgb(231, 76, 60);">
    	<i class="icon-eye"></i> {{ $item->views }}
    </div>
    <div class="col-md-4 col-padding10 text-center dark" style="background:rgb(52, 73, 94);">
    	{{ $item->province->{'title'.LANG} }}
    </div>
    <div class="col-md-4 col-padding10 text-center dark" style="background:rgb(26, 188, 156);">
    	{{ $item->area->{'title'.LANG} }}
    </div>
</div>
<div class="col-md-4">
    <div class="col-md-6 text-center dark" style="background:rgb(200, 178, 29);">
    	<a href="mailto:{{ $item->email }}" class="btn green">{{ trans('main.Mail Me') }}</a>
    </div>
    <div class="col-md-6 text-center dark" style="background:rgb(77, 198, 234);">
    	<a href="tel:{{ $item->phone }}" class="btn green">{{ $item->phone }}</a>
    </div>

</div>