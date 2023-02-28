<ul>
	@if (isset($siteMenus['mainmenu']))
	@foreach ($siteMenus['mainmenu'] as $menu)
		<li>
			<a href="{{ ($menu->internal) ? url($menu->url) : $menu->url }}">{{ $menu->titleAr }}</a>
			@if (!$menu->links->isEmpty())
				@foreach ($menu->links as $link)
					<a href="{{ ($link->internal) ? url($link->url) : $link->url }}">{{ $link->titleAr }}</a>
				@endforeach
			@endif
		</li>
	@endforeach
	@endif
</ul>