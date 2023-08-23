@extends('admin.forms.form')



@section('forms2')
    @if(count($days)>=1)
        <div class="control-group permissions">

            <div class="controls">

                <div class="controls">
                    <select name="monthes" id="monthes" class="form-control">
                        <option value="">{{ trans('main.Period') }}</option>
                        <option value="28">1 {{ trans('main.Month') }}</option>
                        <option value="56">2 {{ trans('main.Month') }}</option>
                        <option value="84">3 {{ trans('main.Month') }}</option>
                        <option value="112">4 {{ trans('main.Month') }}</option>
                        <option value="1">1 {{ trans('main.Days') }}</option>
                        <option value="2">2 {{ trans('main.Days') }}</option>
                        <option value="3">3 {{ trans('main.Days') }}</option>
                        <option value="4">4 {{ trans('main.Days') }}</option>
                        <option value="5">5 {{ trans('main.Days') }}</option>
                        <option value="6">6 {{ trans('main.Days') }}</option>
                        <option value="7">7 {{ trans('main.Days') }}</option>
                        <option value="8">8 {{ trans('main.Days') }}</option>
                        <option value="9">9 {{ trans('main.Days') }}</option>
                        <option value="10">10 {{ trans('main.Days') }}</option>
                        <option value="11">11 {{ trans('main.Days') }}</option>
                        <option value="12">12 {{ trans('main.Days') }}</option>
                        <option value="13">13 {{ trans('main.Days') }}</option>
                        <option value="14">14 {{ trans('main.Days') }}</option>
                        <option value="20">20 {{ trans('main.Days') }}</option>
                        <option value="24">24 {{ trans('main.Days') }}</option>
                    </select>

                    <table class="table table-striped table-bordered table-advance table-hover" id="days-table">
                        <tbody>


                        @for ($i = 0; $i < $days_deff; $i++)

                            <tr>
                                <td>
                                    <input type="checkbox"
                                           @if (in_array(date('Y-m-d',strtotime('+ '.$i . ' days',$first)),$days))
                                               checked="checked"
                                           @endif class="check-date dates{{ date('Y-m-d',strtotime('+ '.$i . ' days',$first)) }}" name="dates[]"
                                           value="{{ date('Y-m-d',strtotime('+ '.$i . ' days',$first)) }}"
                                           id="dates{{ date('Y-m-d',strtotime('+ '.$i . ' days',$first)) }}">
                                </td>
                                <td>
                                    {{ date('l Y-m-d',strtotime('+ '.$i . ' days',$first)) }}
                                </td>
                                <td style="width: 25%;">
                                    <button class="btn red btn-group-justified red" type="button"
                                            onclick="uncheck('.dates{{ date('Y-m-d',strtotime('+ '.$i . ' days',$first)) }}' , '{{ date('l Y-m-d',strtotime('+ '.$i . ' days',$first)) }}')">
                                        <i class="fa fa-trash"></i> Permanent Delete
                                    </button>
                                </td>
                            </tr>
                        @endfor
                        </tbody>
                    </table>

                </div>

            </div>
        </div>
    @endif

@stop

@section('custom_foot')
    @parent
    {{ HTML::script('js/moment.js') }}

    <!-- Modal -->
    <div class="modal fade" id="deleteModal" >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close"  onclick="forceCloseModal()"></button>
                    <h3 id="myModalLabel">Are you sure?</h3>
                </div>
                <div class="modal-body">
                    <p>You want to disable <span id="dateToDelete"></span> and do not add any new date!</p>
                </div>
                <div class="modal-footer">
                    <button class="btn" onclick="forceCloseModal()">Close</button>
                    <button class="btn blue" onclick="forceUncheck()">Delete with out new date</button>
                </div>
            </div>
        </div>
    </div>


    <script>
        var idOFunCheck = null

        function uncheck(id, name) {
            $('#dateToDelete').html(name);
            idOFunCheck = id;
            $('#deleteModal').modal('show');
        }

        function forceUncheck() {
            $(idOFunCheck).prop("checked", false);
            $(idOFunCheck).parent().removeClass("checked");
            $('body').removeClass('modal-open');
            $('#deleteModal').modal('hide');
        }
        function forceCloseModal(){
            $('body').removeClass('modal-open');
            $('#deleteModal').modal('hide');
        }

        function checkboxes() {

            $('#days-table').delegate('.check-date', 'click', function (ee) {
                var last_row = $('#days-table tbody tr').last();
                var last_row_date = last_row.find('input.check-date').val();
                if ($(this).is(':checked')) {
                    var current_row_date = moment(last_row_date, 'YYYY-MM-DD').subtract(1, 'days').format("YYYY-MM-DD");
                    $('#membership_end').val(current_row_date);
                    last_row.remove();
                } else {
                    var current_row_date = moment(last_row_date, 'YYYY-MM-DD').add(1, 'days').format("YYYY-MM-DD");
                    var current_row_date_with_week = moment(last_row_date, 'YYYY-MM-DD').add(1, 'days').format("dddd YYYY-MM-DD");
                    $('#membership_end').val(current_row_date);
                    var row = '<tr><td ><div ><span class="checker"><input  type="checkbox" checked="checked" class=" check-date dates' + current_row_date + '" name="dates[]" value="' + current_row_date + '" id="dates' + current_row_date + '"></span></div></td><td>' + current_row_date_with_week + '</td><td style="width: 25%;"><button class="btn red btn-group-justified red" type="button" onclick="uncheck(\'.dates' + current_row_date + '\' , \'' + current_row_date_with_week + '\')"><i class="fa fa-trash"></i> Permanent Delete</button></td></tr>';
                    $('#days-table tbody').append(row);
                }
            });
        }

        jQuery(document).ready(function ($) {
            $('#monthes').change(function () {

                // Add Expire Date
                var number = $(this).val();
                var membership_start = $('#membership_start').val();
                // var days = 28 * parseInt(number);
                var days = number;
                var membership_end = moment(membership_start, "YYYY-MM-DD").add(days, 'days').format("YYYY-MM-DD");
                $('#membership_end').val(membership_end);


                // Get Date Diff
                var moment_start = moment(membership_start, 'YYYY-MM-DD');
                var moment_end = moment(membership_end, 'YYYY-MM-DD');
                var days_between = moment_end.diff(moment_start, 'days');

                $('#days-table tbody').html('');
                for (var i = 0; i < days_between; i++) {
                    var current_row_date = moment(membership_start, 'YYYY-MM-DD').add(i, 'days').format("YYYY-MM-DD");
                    var current_row_date_with_week = moment(membership_start, 'YYYY-MM-DD').add(i, 'days').format("dddd YYYY-MM-DD");
                    var row = '<tr><td ><div ><span class="checker"><input type="checkbox" checked="checked" class=" check-date dates' + current_row_date + '" name="dates[]" value="' + current_row_date + '" id="dates' + current_row_date + '"></span></div></td><td>' + current_row_date_with_week + '</td><td style="width: 25%;"><button class="btn red btn-group-justified red" type="button" onclick="uncheck(\'.dates' + current_row_date + '\' , \'' + current_row_date_with_week + '\')"><i class="fa fa-trash"></i> Permanent Delete</button></td></tr>'
                    $('#days-table tbody').append(row);
                }
            });
            checkboxes();
        });

    </script>
@stop
