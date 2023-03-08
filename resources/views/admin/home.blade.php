@extends('admin.layouts.main')

@section('content')

    <div class="row">
        <div class="col-md-3 col-sm-12">
            <div class="margin-bottom-10"><strong>New User</strong></div>
            <div class="flip-scroll">
                <table class="table w-100 table-striped">
                    <tbody>
                    <tr>
                        <td class="text-left">
                            Daily
                        </td>
                        <td class="text-right">
                            {{ $animalistic_data['register']['daily'] }}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-left">
                            Weekly
                        </td>
                        <td class="text-right">
                            {{ $animalistic_data['register']['weekly'] }}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-left">
                            Monthly
                        </td>
                        <td class="text-right">
                            {{ $animalistic_data['register']['monthly'] }}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-left">
                            Quarterly
                        </td>
                        <td class="text-right">
                            {{ $animalistic_data['register']['quarterly'] }}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-left">
                            Yearly
                        </td>
                        <td class="text-right">
                            {{ $animalistic_data['register']['yearly'] }}
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-3 col-sm-12">
            <div class="margin-bottom-10"><strong>Membership</strong></div>
            <div class="flip-scroll">
                <table class="table w-100 table-striped">
                    <tbody>
                    <tr>
                        <td class="text-left">
                            Daily
                        </td>
                        <td class="text-right">
                            {{ $animalistic_data['membership']['daily'] }}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-left">
                            Weekly
                        </td>
                        <td class="text-right">
                            {{ $animalistic_data['membership']['weekly'] }}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-left">
                            Monthly
                        </td>
                        <td class="text-right">
                            {{ $animalistic_data['membership']['monthly'] }}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-left">
                            Quarterly
                        </td>
                        <td class="text-right">
                            {{ $animalistic_data['membership']['quarterly'] }}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-left">
                            Yearly
                        </td>
                        <td class="text-right">
                            {{ $animalistic_data['membership']['yearly'] }}
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-3 col-sm-12">
            <div class="margin-bottom-10"><strong>Birthday</strong></div>
            <div class="flip-scroll">
                <table class="table w-100 table-striped">
                    <tbody>
                    <tr>
                        <td class="text-left">
                            Today
                        </td>
                        <td class="text-right">
                            {{ $animalistic_data['birthday']['daily'] }}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-left">
                            Weekly
                        </td>
                        <td class="text-right">
                            {{ $animalistic_data['birthday']['weekly'] }}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-left">
                            Monthly
                        </td>
                        <td class="text-right">
                            {{ $animalistic_data['birthday']['monthly'] }}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-left">
                            Quarterly
                        </td>
                        <td class="text-right">
                            {{ $animalistic_data['birthday']['quarterly'] }}
                        </td>
                    </tr>
                    <tr>
                        <td class="text-left">
                            Yearly
                        </td>
                        <td class="text-right">
                            {{ $animalistic_data['birthday']['yearly'] }}
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-3 col-sm-12">
            <div class="margin-bottom-10"><strong>Payment</strong></div>
            <div class="flip-scroll">
                <table class="table w-100 table-striped">
                    <tbody>
                    <tr>
                        <td class="text-left">
                            Today
                        </td>
                        <td class="text-right">
                            {{ number_format($animalistic_data['payment']['daily']) }} KD
                        </td>
                    </tr>
                    <tr>
                        <td class="text-left">
                            Weekly
                        </td>
                        <td class="text-right">
                            {{ number_format($animalistic_data['payment']['weekly']) }} KD
                        </td>
                    </tr>
                    <tr>
                        <td class="text-left">
                            Monthly
                        </td>
                        <td class="text-right">
                            {{ number_format($animalistic_data['payment']['monthly']) }} KD
                        </td>
                    </tr>
                    <tr>
                        <td class="text-left">
                            Quarterly
                        </td>
                        <td class="text-right">
                            {{ number_format($animalistic_data['payment']['quarterly']) }} KD
                        </td>
                    </tr>
                    <tr>
                        <td class="text-left">
                            Yearly
                        </td>
                        <td class="text-right">
                            {{ number_format($animalistic_data['payment']['yearly']) }} KD
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <hr>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-12 col-sm-12">
            <canvas id="usersChart"></canvas>
        </div>
        <div class="col-md-12 col-sm-12">
            <canvas id="incomeMonth"></canvas>
        </div>
    </div>
@stop


@section('custom_foot')

    {{ HTML::script('https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js') }}
    <script>
        const usersEl = document.getElementById('usersChart');
        const usersChart = new Chart(usersEl, {
            type: 'line',
            data: {
                labels: [ "{!! implode('","' , array_keys($animalistic_data['graph']['register']) ) !!}"  ],
                datasets: [
                    {
                        label: 'New User',
                        data: [{{ implode(',' , array_values($animalistic_data['graph']['register']) ) }}],
                        borderColor: 'red',
                        fill: false,
                        tension: 0.4
                    },
                    {
                        label: 'MemberShip',
                        data: [{{ implode(',' , array_values($animalistic_data['graph']['memberShip']) ) }}],
                        borderColor: 'blue',
                        fill: false,
                        tension: 0.4
                    },
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'User chart Between ' + '{{  array_key_first($animalistic_data['graph']['register']) }}' + ' and ' + '{{  array_key_last($animalistic_data['graph']['register']) }}'
                    },
                },
                interaction: {
                    intersect: false,
                },
            }
        });


        const incomeMonthEl = document.getElementById('incomeMonth');
        const incomeMonth = new Chart(incomeMonthEl, {
            type: 'line',
            data: {
                labels: [ "{!! implode('","' , array_keys($animalistic_data['graph']['payment']) ) !!}"  ],
                datasets: [
                    {
                        label: 'Price In KD',
                        data: [{{ implode(',' , array_values($animalistic_data['graph']['payment']) ) }}],
                        borderColor: 'red',
                        fill: false,
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Payment between ' + '{{  array_key_first($animalistic_data['graph']['payment']) }}' + ' and ' + '{{  array_key_last($animalistic_data['graph']['payment']) }}'
                    },
                },
                interaction: {
                    intersect: false,
                },
            }
        });
    </script>

@endsection
