@if(!$slideshow->isEmpty())
<div class="slider">
  <ul>
    @foreach($slideshow as $slide)
    <li>
      <div class="item">
        <a href="{{ $slide->url }}"><img src="{{ url('media/slideshow/'.$slide->photo) }}" alt="{{ $slide->{'title'.ucfirst('LANG_SHORT')} }}"></a>
        @if($slide->{'title'.ucfirst(LANG_SHORT)} && $slide->{'content'.ucfirst(LANG_SHORT)})
        <div class="container">
          <div class="info">
            <h3>{{ $slide->{'title'.ucfirst(LANG_SHORT)} }}</h3>
            {{ $slide->{'content'.ucfirst(LANG_SHORT)} }}

          </div>
        </div>
        @endif
      </div>
    </li>
    @endforeach
  </ul>
</div>
@endif
