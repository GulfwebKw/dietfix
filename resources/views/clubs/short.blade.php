<div class="col_one_third bottommargin-lg">
    <div class="feature-box center media-box fbox-bg">
        <div class="fbox-media">
            <a href="{{ url('clubs/view/' . $club->id) }}">
                @if ($club->logo)
                    <img src="{{ url('resize?w=400&h=300&r=1&c=1&src=media/clubs/' . $club->logo) }}" alt="{{ $club->{'title'.LANG} }}">
                @else
                    <img src="{{ url('resize?w=400&h=300&r=1&c=1&src=media/default.png') }}" alt="{{ $club->{'title'.LANG} }}">
                @endif
            </a>
        </div>
        <div class="fbox-desc">
            <h3>{{ $club->{'title'.LANG} }}<span class="subtitle">{{ $club->province->{'title'.LANG} }} / {{ $club->area->{'title'.LANG} }}</span></h3>
            <p><a href="{{ url('clubs/view/' . $club->id) }}" class="btn btn-default">{{ trans('main.Read More') }}</a></p>
        </div>
    </div>
</div>
