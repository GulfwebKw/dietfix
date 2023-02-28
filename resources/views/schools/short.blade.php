<div class="col_one_third bottommargin-lg">
    <div class="feature-box center media-box fbox-bg">
        <div class="fbox-media">
            <a href="{{ url('schools/view/' . $school->id) }}">
                @if ($school->logo)
                    <img src="{{ url('resize?w=400&h=300&r=1&c=1&src=media/schools/' . $school->logo) }}" alt="{{ $school->{'title'.LANG} }}">
                @else
                    <img src="{{ url('resize?w=400&h=300&r=1&c=1&src=media/default.png') }}" alt="{{ $school->{'title'.LANG} }}">
                @endif
            </a>
        </div>
        <div class="fbox-desc">
            <h3>{{ $school->{'title'.LANG} }}<span class="subtitle">{{ $school->province->{'title'.LANG} }} / {{ $school->area->{'title'.LANG} }}</span></h3>
            <p><a href="{{ url('schools/view/' . $school->id) }}" class="btn btn-default">{{ trans('main.Read More') }}</a></p>
        </div>
    </div>
</div>
