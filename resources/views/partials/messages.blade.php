
@if(session()->has('message'))
   <div class="block-content block-content-small-padding">
    @foreach(session()->get('message') as $k =>  $m)

        <div class="alert alert-{{ (!is_numeric($k)) ? $k : 'info' }}">

                      {!! $m !!}

        </div>

    @endforeach

</div>
@endif


@if(session()->has('error'))


<div class="block-content block-content-small-padding">

    @if(is_string(session()->get('error')))

        <div class="alert alert-danger">

            {!! session()->get('error') !!}
        </div>

          @else
        @foreach(session()->get('error') as $e)
            <div class="alert alert-danger">

                          {!! $e !!}
            </div>

        @endforeach

       @endif

</div>

@endif

@if(isset($messages))

<div class="block-content block-content-small-padding">

    @foreach($messages as $type => $message)

        <div class="alert alert-{{ (isset($type) && !is_numeric($type)) ? $type : 'success' }} fade in">

                      {!! $message !!}

        </div>

    @endforeach

</div>

@endif

@if($errors->count()>=1)

<div class="block-content block-content-small-padding">

    <div class="alert alert-danger">

        <ul>

        @foreach ($errors->all('<li>:message</li>') as $k => $message)

            {!! $message !!}

        @endforeach

        </ul>

    </div>

</div>

@endif
