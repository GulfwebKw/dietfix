<div class="col-sm-3 col-md-3 rotation employee">
  <div class="default">
    <div class="image">
      <a href="{{ url('shop/view/'.$shop->id) }}">
      <img class="replace-2x" src="{{ url('resize?w=270&amp;h=270&amp;r=1&amp;c=1&amp;src=media/shops/'.$shop->photo) }}" alt="" title="" width="270" height="270">
      </a>
    </div>
    <div class="description">
      <div class="vertical">
        <h3 class="name">
        <a href="{{ url('shop/view/'.$shop->id) }}">
        {{ $shop->{'title'.LANG} }}
        </a>
        </h3>
      </div>
    </div>
  </div>
  <div class="employee-hover">
    <h3 class="name">
    <a href="{{ url('shop/view/'.$shop->id) }}">
    {{ $shop->{'title'.LANG} }}
    </a>
    </h3>
    <div class="image">
      <a href="{{ url('shop/view/'.$shop->id) }}">
      <img class="replace-2x" src="{{ url('resize?w=270&amp;h=270&amp;r=1&amp;c=1&amp;src=media/shops/'.$shop->photo) }}" alt="" title="" width="270" height="270">
      </a>
    </div>
    <div>
      <p>{{ $shop->{'details'.LANG} }}</p>
      <div class="contact"><b>{{ trans('main.Views') }}: </b>{{ $shop->views }}</div>
      <div class="contact"><b>{{ trans('main.Phone') }}: </b><a href="tel:{{ $shop->phone }}">{{ $shop->phone }}</a></div>
    </div>
  </div><!-- .employee-hover -->
</div><!-- .employee  