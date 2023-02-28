<div class="entry clearfix">
    <div class="entry-image hidden-sm">
        <a href="{{ url('schools/view/' . $school->id) }}">
            @if ($school->logo)
                <img src="{{ url('resize?w=400&h=300&r=1&c=1&src=media/schools/' . $school->logo) }}" alt="{{ $school->{'title'.LANG} }}">
            @else
                <img src="{{ url('resize?w=400&h=300&r=1&c=1&src=media/default.png') }}" alt="{{ $school->{'title'.LANG} }}">
            @endif
        </a>
        <div class="col_half nobottommargin">
            {{ trans('main.Province') }} : {{ $school->province->{'title'.LANG} }}
        </div>
        <div class="col_half nobottommargin col_last">
            {{ trans('main.Area') }} : {{ $school->area->{'title'.LANG} }}
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="entry-c">
        <div class="entry-title">
            <h2><a href="{{ url('schools/view/' . $school->id) }}">{{ $school->{'title'.LANG} }}</a></h2>
        </div>
        <div class="entry-content">
            <p class="nobottommargin">
            {{ nl2br(mb_substr($school->{'details'.LANG}, 0,150,'utf8')) }} ...
            </p>
            @if (!$school->genres->isEmpty())
            <ul class="entry-meta clearfix">
                <li>{{ trans('main.Gender') }}</li>
                @foreach ($school->genres as $genre)
                    <li>{{ $genre->{'title'.LANG} }}</li>
                @endforeach
            </ul>
            @endif
            @if (!$school->ages->isEmpty())
            <ul class="entry-meta clearfix">
                <li>{{ trans('main.Grade') }}</li>
                @foreach ($school->ages as $age)
                    <li>{{ $age->{'title'.LANG} }}</li>
                @endforeach
            </ul>
            @endif
            @if (!$school->activities->isEmpty())
            <ul class="entry-meta clearfix">
                <li>{{ trans('main.Activity') }}</li>
                @foreach ($school->activities as $activity)
                    <li>{{ $activity->{'title'.LANG} }}</li>
                @endforeach
            </ul>
            @endif
            <a href="{{ url('schools/view/' . $school->id) }}" class="pull-right flip btn btn-danger">{{ trans('main.Read More') }}</a>
        </div>
    </div>
</div>
