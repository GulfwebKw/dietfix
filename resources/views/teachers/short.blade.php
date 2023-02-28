<div class="entry clearfix">
    <div class="entry-image hidden-sm">
        <a href="{{ url('teachers/view/' . $teacher->id) }}">
            @if ($teacher->logo)
                <img src="{{ url('resize?w=400&h=300&r=1&c=1&src=media/teachers/' . $teacher->logo) }}" alt="{{ $teacher->{'title'.LANG} }}">
            @else
                <img src="{{ url('resize?w=400&h=300&r=1&c=1&src=media/default.png') }}" alt="{{ $teacher->{'title'.LANG} }}">
            @endif
        </a>
    </div>
    <div class="entry-c">
        <div class="entry-title">
            <h2><i class="pull-right flip fa fa-{{ strtolower($teacher->gender) }}"></i> <a href="{{ url('teachers/view/' . $teacher->id) }}">{{ $teacher->{'title'.LANG} }}</a></h2>
        </div>
        @if (!$teacher->teachers_activities->isEmpty())
        <ul class="entry-meta clearfix">
            @foreach ($teacher->teachers_activities as $activitie)
                <li>{{ $activitie->subject->{'title'.LANG} }} {{ $activitie->year->{'title'.LANG} }}</li>
            @endforeach
        </ul>
        @endif
        <div class="entry-content">
            <a href="{{ url('teachers/view/' . $teacher->id) }}" class="btn btn-danger">{{ trans('main.Read More') }}</a>
        </div>
    </div>
</div>

