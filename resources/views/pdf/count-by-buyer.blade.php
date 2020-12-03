<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title> Count by Buyer </title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
        integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
        integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous">
    </script>
    <style>
        body {
            font-family: 'Helvetica';
            font-size: 13px;
        }
    </style>
</head>
<body class="mt-0 p-0">
    <div class="text-center">
        <h4> Count by Buyer Reports </h4>
        <small>  from  <strong>  {{ $startDate }} </strong>  to <strong> {{ $endDate }} </strong>  </small>
    </div>

    <div class="mt-4">
        <div class="table-sm">
            <table class="table table-bordered">
                <tr>
                    <th width="6%"> No. </th>
                    <th width="14%"> Buyer Name </th>
                    <th width="12%"> # Lead </th>
                    <th width="12%"> # Accepted </th>
                    <th width="12%"> # Rejected </th>
                    <th width="12%"> # Error </th>
                    <th width="14%"> % Redirected </th>
                    <th width="18%"> % Not Redirected </th>
                </tr>
    
                @foreach ($buyerStats as $buyerStat)
                    <tr class="bg-light">
                        <td> {{ $loop->iteration }}. </td>
                        <td> {{ $buyerStat['name'] }} </td>
                        <td class="text-center"> {{ $buyerStat['total_lead'] }} </td>
                        <td class="text-center"> {{ $buyerStat['total_accepted'] }} </td>
                        <td class="text-center"> {{ $buyerStat['total_rejected'] }} </td>
                        <td class="text-center"> {{ $buyerStat['total_error'] }} </td>
                        <td class="text-center"> {{ $buyerStat['redirected'] }} </td>
                        <td class="text-center"> {{ $buyerStat['not_redirect'] }} </td>
                    </tr>    

                    <tr>
                        <td colspan="8" class="p-2">

                            <table class="table">
                                <tr class="bg-light">
                                    <td> Panda Channel </td>
                                    <td> # Lead </td>
                                    <td> # Accepted </td>
                                    <td> # Rejected </td>
                                    <td> # Error </td>
                                    <td> % Redirected </td>
                                    <td> % Not Redirected </td>
                                </tr>
                                @if(!empty($buyerStat['panda_stats']))
                                    @foreach ($buyerStat['panda_stats'] as $stat)
                                        <tr>
                                            <td> {{ $stat['name'] }} </td>
                                            <td> {{ $stat['total_lead'] }} </td>
                                            <td> {{ $stat['total_accepted'] }} </td>
                                            <td> {{ $stat['total_rejected'] }} </td>
                                            <td> {{ $stat['total_error'] }} </td>
                                            <td> {{ $stat['redirected'] }} </td>
                                            <td> {{ $stat['not_redirect'] }} </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7">
                                            No leads available.
                                        </td>
                                    </tr>
                                @endif
                                
                            </table>

                            <table class="table">
                                <tr class="bg-light">
                                    <td> Vertical Channel </td>
                                    <td> # Lead </td>
                                    <td> # Accepted </td>
                                    <td> # Rejected </td>
                                    <td> # Error </td>
                                    <td> % Redirected </td>
                                    <td> % Not Redirected </td>
                                </tr>

                                @if(!empty($buyerStat['vertical_stats']))
                                    @foreach ($buyerStat['vertical_stats'] as $stat)
                                        <tr>
                                            <td> {{ $stat['name'] }} </td>
                                            <td> {{ $stat['total_lead'] }} </td>
                                            <td> {{ $stat['total_accepted'] }} </td>
                                            <td> {{ $stat['total_rejected'] }} </td>
                                            <td> {{ $stat['total_error'] }} </td>
                                            <td> {{ $stat['redirected'] }} </td>
                                            <td> {{ $stat['not_redirect'] }} </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7">
                                            No leads available.
                                        </td>
                                    </tr>
                                @endif
                            </table>

                            <table class="table">
                                <tr class="bg-light">
                                    <td> Plat Channel </td>
                                    <td> # Lead </td>
                                    <td> # Accepted </td>
                                    <td> # Rejected </td>
                                    <td> # Error </td>
                                    <td> % Redirected </td>
                                    <td> % Not Redirected </td>
                                </tr>

                                @if(!empty($buyerStat['plat_stats']))
                                    @foreach ($buyerStat['plat_stats'] as $stat)
                                        <tr>
                                            <td> {{ $stat['name'] }} </td>
                                            <td> {{ $stat['total_lead'] }} </td>
                                            <td> {{ $stat['total_accepted'] }} </td>
                                            <td> {{ $stat['total_rejected'] }} </td>
                                            <td> {{ $stat['total_error'] }} </td>
                                            <td> {{ $stat['redirected'] }} </td>
                                            <td> {{ $stat['not_redirect'] }} </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7">
                                            No leads available.
                                        </td>
                                    </tr>
                                @endif
                            </table>

                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
        

    </div>
</body>

</html>