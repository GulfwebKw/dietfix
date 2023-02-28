@extends('layouts.main')

@section('contents')
<div class="block-content block-content-small-padding">
    <div class="block-content-inner">
        <div class="row">
            <h2 class="center">{{ trans('main.FAQ') }}</h2>
            <div class="panel-group" id="accordion">
                @foreach($faq as $item)

                <div class="panel panel-default">
                    <div class="panel-heading active">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapse{{ $item->id }}" class="collapsed">
                                <i class="icon-plus"></i> 
                                {{ $item->{'farQuestion'.ucfirst(LANG_SHORT)} }}

                            </a>
                        </h4>
                    </div><!-- /.panel-heading -->

                    <div id="collapse{{ $item->id }}" class="panel-collapse collapse">
                        <div class="panel-body">
                            {{ $item->{'farAnswer'.ucfirst(LANG_SHORT)} }}

                        </div><!-- /.panel-body -->
                    </div><!-- /.panel-heading -->
                </div><!-- /.panel -->
                @endforeach
            </div><!-- /.panel-group -->
        </div><!-- /.row -->
    </div><!-- /.block-content-inner -->
</div>


@stop