@extends('layouts.frontend')

@section('top_js')
    {!! NoCaptcha::renderJs() !!}
@endsection
@section('content')

    @component('frontend.components.breadcrumb')
        @slot('title')
            CONTACTS
        @endslot
    @endcomponent


        <p>
        @if(isset($contact->map))
            <iframe src="{!! $contact->map !!}" width="100%" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
        @endif


        <div class="row">
            <div class="span4">
                  <h2>Contact info</h2>
                  <p>{{$contact->address}}</p>
                    <h5>
                        Telephone: {{$contact->telephone}}<br />
                        Mobile: {{$contact->mobile}}<br />
                        Fax: {{$contact->fax}}<br />
                        E-mail: <a href="mailto:{{$contact->email}}">{{$contact->email}}</a>
                        <br />
                    </h5>
           </div>

             <div class="span8">
        <h2>Contact form</h2>
        <div role="form" class="wpcf7" id="wpcf7-f208-p2082-o1" dir="ltr">
            <form action="{{route('contact_post')}}" method="post" class="wpcf7-form" novalidate>
                <input type="hidden" name="action" value="contactx">
                <div class="row-fluid">
                    <p class="span4 field"><span class="wpcf7-form-control-wrap your-name"><input type="text" name="name" value="{{old('name')}}" size="40" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" aria-required="true" aria-invalid="false" placeholder="Name:" /></span> </p>
                    <p class="span4 field"><span class="wpcf7-form-control-wrap your-email"><input type="email" name="email" value="{{old('email')}}" size="40" class="wpcf7-form-control wpcf7-text wpcf7-email wpcf7-validates-as-required wpcf7-validates-as-email" aria-required="true" aria-invalid="false" placeholder="E-mail:" /></span> </p>
                    <p class="span4 field"><span class="wpcf7-form-control-wrap your-phone"><input type="text" name="phone" value="{{old('phone')}}" size="40" class="wpcf7-form-control wpcf7-text" aria-invalid="false" placeholder="Phone:" /></span> </p>
                </div>
                <p class="field"><span class="wpcf7-form-control-wrap your-message"><textarea name="message" cols="40" rows="10" class="wpcf7-form-control wpcf7-textarea" aria-invalid="false" placeholder="Message:">{{old('message')}}</textarea></span> </p>


                   {!! NoCaptcha::display() !!}
                <p class="field">
                        @if ($errors->has('g-recaptcha-response'))
                            <span class="help-block">
                                <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                            </span>
                        @endif
                </p>

                <p class="submit-wrap"><input type="reset" value="clear" class="btn btn-primary" /><input type="submit" value="send" class="wpcf7-form-control  btn btn-primary" /></p>

                    @if ($errors->any())
                        <div class="wpcf7-response-output " >
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                @if(session()->has('message'))
                    <div class="wpcf7-response-output " >
                        {{session()->get('message')}}
                    </div>
                @endif


            </form>
        </div>
    </div>
        </div>







@endsection