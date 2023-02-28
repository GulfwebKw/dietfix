@extends('layouts.main')
@section('contents')
<div class="block-content block-content-small-padding">
<div class="comment-wrap">


        @foreach($days as $d)
                <h1 style=\"background-color:#ffffff;\">{{$d->date."-".date('l',strtotime($d->date))}}</h1>
                @foreach($d->orders as $od)
                        <h3>{{ optional($od->meal)->{"title".LANG} }}</h3>
                                 <img src="/resize?w=100&amp;h=100&amp;r=1&amp;c=1&amp;src=/media/items/{{optional($od->item)->photo}}" class="" alt="{{ optional($od->meal)->{"title".LANG} }}">
                        {{optional($od->item)->{"title".LANG} }}
                        <br>
                @endforeach
                <table>
                        <tr><td></td></tr>
                </table>
        @endforeach


<div class="clearfix"></div>
</div><!-- /.block-content-inner -->
</div>
@stop
