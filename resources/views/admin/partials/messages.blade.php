@if(isset($messages))

    @foreach($messages as $message)

        <script>

            jQuery(document).ready(function($) {

                $.gritter.add({

                title: '',

                text: '{{ $message }}'

                });

            });

        </script>

    @endforeach

@endif

@if(session()->has('messages'))

    @foreach(session()->get('messages') as $message)

        <script>

            jQuery(document).ready(function($) {

                $.gritter.add({

                title: '',

                text: '{{ $message }}'

                });

            });

        </script>

    @endforeach

@endif

@if(isset($errors))

	@foreach($errors->all() as $error)

		<script>

            jQuery(document).ready(function($) {

                $.gritter.add({

                title: '',

                text: '{{ $error }}'

                });

            });

        </script>

	@endforeach

@endif