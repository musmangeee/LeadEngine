@extends('layouts.public')
@section('content')

    <div class="card">
        <div class="card-body p-5">

            <div class="row mt-4">


                <div class="col-sm-6 pb-2">

                    @if(!empty($logo->value) && strpos($logo->value,'https') !== false)
                        <img style="width: 140px; height: auto;" src="{{ $logo->value }}" alt="">
                    @else
                        <img style="width: 140px; height: auto;" src="{{ route('tenant.media',$logo->value) }}" alt="">
                    @endif

                    <div class="mb-1 mt-3 font-weight-bold"> {{ $company_name['value'] ?? '' }} </div>

                    <div class="mb-1">{{ $company_first_name['value'] ?? '' }} {{ $company_last_name['value'] ?? '' }}</div>
                    <div class="mb-1">{{ $company_street['value'] ?? '' }}</div>
                    <div class="mb-1">{{ $company_city['value'] ?? '' }}, {{ $company_state['value'] ?? '' }} {{ $company_zip['value'] ?? '' }} </div>
                    <div class="mb-2">{{ $company_country['value'] ?? '' }}</div>
                    <div class="mb-1">Mobile : {!!  mobile_link($company_phone['value']) ?? '-'  !!}</div>
                    <div class="mb-1">Email   : {!!  email_link($company_email['value']) ?? ''  !!}</div>



                </div>

                <div class="col-sm-6 text-right pb-2">

                    <div class="mb-1">Invoice Number :
                        &nbsp;
                        <strong class="font-weight-semibold">
                            {{ $invoice->invoice_number }}
                        </strong>
                    </div>
                    <div>Invoice Date :
                        <strong class="font-weight-semibold"> {{ $invoice->invoice_date->format('m/d/Y') }} </strong>
                    </div>

                </div>
            </div>

            <hr class="mb-3">

            <div class="row">
                <div class="col">

                    <div class="font-weight-bold mb-2">Invoice To:</div>
                    @foreach($invoice->recipients['recipient'] as $recipient)
                        <div> {{ $recipient['email'] }} </div>
                    @endforeach

                </div>
            </div>


            <div class="row mt-3">
                <div class="col">
                    <div class="font-weight-bold mb-2">Project Info:</div>
                    <div> Name : {{ $invoice->project->name }} </div>
                    <div> Start Date : {{ $invoice->project_start_date->format('m/d/Y') }} </div>
                    <div> Primary Contact : {{ $invoice->project->primaryContact->first_name }} {{ $invoice->project->primaryContact->last_name }} ({!! email_link($invoice->project->primaryContact->email) !!}) </div>
                </div>
            </div>


            <div class="mt-4">
                <table class="table table-bordered">
                    <tr>
                        <td> Description</td>
                        @if($invoice->qty_type != 'amount')
                            <td>
                                @if($invoice->qty_type == 'quantity')
                                    Quantity
                                @elseif($invoice->qty_type == 'hours')
                                    Hours
                                @endif
                            </td>
                            <td>
                                @if($invoice->qty_type == 'quantity')
                                    Price
                                @elseif($invoice->qty_type == 'hours')
                                    Rate
                                @endif
                            </td>
                            <td> Amount </td>
                        @else
                            <td> Amount</td>
                        @endif


                    </tr>
                    @foreach($invoice->details as $detail)
                        <tr>
                            <td>
                                {{ $detail->name }}
                                @if($detail->description)
                                    <div class="text-muted small"> {{ $detail->description }} </div>
                                @endif
                            </td>

                            @if($invoice->qty_type != 'amount')
                                <td>
                                    {{ $detail->qty_count }}
                                </td>
                                <td>
                                    {{ format_money($detail->qty_value, $company_currency) }}
                                </td>
                                <td> {{ format_money($detail->sub_total, $company_currency) }} </td>
                            @else
                                <td> {{ format_money($detail->sub_total, $company_currency) }} </td>
                                <td> {{ $detail->tax ? format_money($detail->tax, $company_currency) : '-' }} </td>
                            @endif
                        </tr>
                    @endforeach
                </table>

                <div class="row">

                    <div class="offset-6 col">
                        <table class="table table-bordered">
                            <tr>
                                <td> Sub Total </td>
                                <td> {{ format_money($invoice->total_amount, $company_currency) }} </td>
                            </tr>
                            @if(!empty($invoice->tax))
                                <tr>
                                    <td> Tax </td>
                                    <td> {{ format_money($invoice->tax_amount, $company_currency) }} </td>
                                </tr>
                            @endif
                            @if(!empty($invoice->discount))
                                <tr>
                                    <td> Discount </td>
                                    <td> {{ format_money($invoice->discount_amount, $company_currency) }} </td>
                                </tr>
                            @endif
                            @if(!empty($invoice->custom_fee_amount))
                                <tr>
                                    <td> {{ $invoice->custom_fee_name }} </td>
                                    <td> {{ format_money($invoice->custom_fee_amount, $company_currency) }} </td>
                                </tr>
                            @endif
                            <tr>
                                <td> Total </td>
                                <td> {{ format_money($invoice->final_amount, $company_currency) }} </td>
                            </tr>
                        </table>
                    </div>
                </div>


            </div>


            <div class="mb-4">
                <strong>Note: </strong>
                <p class="mt-1">{{ $invoice->recipient_notes ?? '-' }}</p>
            </div>

            <div class="mb-4">
                <strong>Terms and Conditions:</strong>
                <p class="mt-1">{{ $invoice->term_conditions ?? '-' }}</p>

            </div>

        </div>

    </div>

@stop