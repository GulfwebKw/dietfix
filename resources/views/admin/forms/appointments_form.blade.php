@extends('admin.forms.form')

@section('custom_head_include')
    {{ HTML::style('assets/datepicker/css/datepicker.css') }}
    {{ HTML::style('assets/time-picker/jquery.timepicker.css') }}
    {{ HTML::style('cpassets/uploadifive/uploadifive.css') }}
@stop
@section('custom_foot')
    {{ HTML::script('http://momentjs.com/downloads/moment.js') }}
    {{ HTML::script('assets/datepicker/js/bootstrap-datepicker.js') }}
    {{ HTML::script('assets/time-picker/jquery.timepicker.min.js') }}
    {{ HTML::script('cpassets/uploadifive/jquery.uploadifive.min.js') }}
    {{ HTML::script('script.js') }}

    <script>




        function hourChanged () {
            var doctor_id = $('#doctor_id').val();
            $('#hour').on('changeTime', function() {
                check_hour();
            });
        }

        function check_hour () {
            var doctor_id = $('#doctor_id').val();
            var dated = $('#date').val();
            var data = {'doctor' : doctor_id, 'date' : dated, 'hour' : $('#hour').val()};
            $('button').prop('disabled',true);
            $.post(SITEPATH+'/appointments/check-hour', data).done(function( data ) {
                if (!data.result) {
                    alert('{{ trans('main.Please choose another time') }}');
                } else {
                    $('button').prop('disabled',false);
                }
            });
        }
        function hourCreated(start,end) {
            $('#hour').timepicker({
                'step': 15,
                'useSelect': true,
                'minTime': start,
                'maxTime': end
            });
        }
        function getDoctorTimes() {
            var doctor_id = $('#doctor_id').val();

            var weekday = weekdays();
            var day_of_weeks = swap(weekday);

            $.getJSON(SITEPATH+'/appointments/doctor-times/'+doctor_id, null, function(json, textStatus) {
                var accepted_days = [];
                $.each(json.times_sorted, function(index, val) {
                    accepted_days.push(parseInt(day_of_weeks[index]));
                });
                var nowTemp = new Date();
                var old_date_val = $('#date').val();
                $('#date').remove();
                $('.date_holder').html('<input class="datepicker form-control" id="date" name="date" type="text">');
                var date = $('#date').datepicker({
                    onRender: function(date) {
                        return ($.inArray(date.getDay(), accepted_days) >= 0 && date.valueOf() > moment()) ? '' : 'disabled';

                    },
                    format: 'yyyy-mm-dd'
                }).on('changeDate', function(ev) {
                    date.hide();
                    check_hour();
                    var start = json.times_sorted[weekday[ev.date.getDay()]].from;
                    var end = json.times_sorted[weekday[ev.date.getDay()]].to;
                    hourCreated(start,end);
                    hourChanged();
                }).data('datepicker');
            });
        }

        function setTimePicker () {
            var doctor_id = $('#doctor_id').val();
            var date = $('#date').val();
            if (date && doctor_id) {
                $.getJSON(SITEPATH+'/appointments/doctor-date-times/'+doctor_id+'/'+date, null, function(json, textStatus) {
                    if (json.result) {
                        hourCreated(json.result.from,json.result.to);
                    }
                    hourChanged();
                });
            }
        }

        setTimePicker();
        $("#date").click(function(){
            getDoctorTimes();
        });

        $('#doctor_id').change(function(event) {
            getDoctorTimes();
        });




        $('#filess').uploadifive({
            'auto' : true,
            'multi' : true,
            'formData' : {
                'timestamp' : '{{ time() }}',
                'token' : '{{ md5('SecUre!tN0w' . time()) }}',
                'folder' : 'media/files/',
                'fileExt' : 'doc,docx,xls,xlsx,pdf,png,jpg,gif,zip,rar,jpeg,bmp',
            },
            'queueID' : 'queuefiless',
            'uploadScript' : '{{ url('upload_files') }}',
            'onUploadComplete' : function(file, data) {
                var rname=data;
                var fullpath= '{{ url('resize?w=30&h=30&src=media/files') }}'+"/"+rname;
                var link= '{{ url('resize?w=500&r=1&src=media/files') }}'+"/"+rname;

                // Photo Handling Vars
                var linkhrefstart = '';
                var linkhrefend = '';

                // Multi Field Name Handling
                var fieldname = 'filesss[]';

                // Paragraph Setup
                var p = '';
                var p=p+'<div class="uploadedImg">';
                var p=p+'<input type="checkbox" checked="checked" value="'+rname+'" name="' + fieldname + '" >';
                var p=p+linkhrefstart;
                var p=p+'<img src="'+fullpath+'">';
                var p=p+linkhrefend;
                var p=p+'<br /><small>'+rname+"</small>";
                var p=p+'</div>';

                $('#filess-thumbnails').append(p);

                $('.mesg').fadeOut('slow');	}
        });

        $(document).ready(function(){
            var date = $('#date').datepicker({
                onRender: function(date) {
                    return '';
                    // return (date.valueOf() >= moment()) ? '' : 'disabled';

                },
                dateFormat: 'yyyy-mm-dd'
            }).on('changeDate', function(ev) {
                date.hide();
            }).data('datepicker');
        });



    </script>
@stop
@if (!isset($item))
    <?php $item = new App\Models\Clinic\Appointment; ?>
@endif
@section('forms1')
    <div class="control-group form-group">
        {{ Form::label('doctor_id', trans('main.Dietitian') , array('class' => 'control-label col-sm-4')) }}




        <div class="controls col-sm-8">

            <select name="doctor_id" id="doctor_id" class="chosen form-control>

                @if(isset($doctors))
                    @foreach($doctors as $itemS)
                        <option  value="{{$itemS['id']}}" >{{$itemS['username']}}</option>
                    @endforeach

                @endif

            </select>


            {{--{{ Form::select('doctor_id', @$doctors , $item->doctor_id, array('class' => 'chosen form-control','id' => 'doctor_id')) }}--}}
        </div>
    </div>

    <div class="control-group form-group">
        {{ Form::label('date', trans('main.Date') , array('class' => 'control-label col-sm-4')) }}
        <div class="controls col-sm-8 date_holder">
            {{ Form::text('date', $item->date, array('class' => 'datepicker form-control','id' => 'date')) }}
        </div>
    </div>

    <div class="control-group form-group">
        {{ Form::label('hour', trans('main.Time') , array('class' => 'control-label col-sm-4')) }}
        <div class="controls col-sm-8">
            {{ Form::text('hour', $item->hour, array('class' => 'timepicker form-control','id' => 'hour')) }}
        </div>
    </div>
@stop