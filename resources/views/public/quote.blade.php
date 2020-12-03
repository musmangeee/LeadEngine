@extends('layouts.public')
@section('content')

    <div class="card">
        <div class="card-body p-5">


            @if($quote->status == \App\Models\Quote::STATUS_ACCEPTED)
                <div class="alert alert-success mb-4">
                    <strong> You have accepted the quote. </strong>
                    <p> We will reach out to you with next steps soon! If you have any questions, please contact us at <a href="mailto:{{ $company_email['value'] }}"> {{ $company_email['value'] }}</a>.</p>
                </div>
            @elseif($quote->status == \App\Models\Quote::STATUS_REJECTED && empty($quote->reject_reason))
                <div class="alert alert-danger mb-4">
                    <strong> You have rejected the quote. </strong>

                    <form method="post" action="{{ route('public.save-quote',$quote->quote_number) }}">
                        @csrf
                        <div class="form-group">
                            <label for="reject_reason" class="form-label"> Reason for Rejection </label>
                            <textarea class="form-control {{ $errors->has('reject_reason') ? 'is-invalid' : '' }}" name="reject_reason" id="" cols="30" rows="5"></textarea>
                            @if($errors->has('reject_reason'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('reject_reason') }}
                                </div>
                            @endif
                        </div>

                        <button type="submit" name="result" value="rejected" class="btn btn-danger ml-2"> Submit</button>
                    </form>

                </div>



            @elseif($quote->status == \App\Models\Quote::STATUS_REJECTED && !empty($quote->reject_reason))

                <div class="alert alert-danger mb-4">
                    <strong> You have rejected the quote. </strong>
                </div>
            @endif

            <div class="row mt-4">


                <div class="col-sm-6 pb-2">

                    @if(!empty($logo->value))
                        <img style="width: 140px; height: auto;" src="/media/{{ $logo->value }}" alt="">
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

                    <div class="mb-1">Quote Number :
                        &nbsp;
                        <strong class="font-weight-semibold">
                            {{ $quote->quote_number }}
                        </strong>
                    </div>
                    <div>Quote Date :
                        <strong class="font-weight-semibold"> {{ $quote->quote_date->format('m/d/Y') }} </strong>
                    </div>

                </div>
            </div>

            <hr class="mb-3">

            <div class="row">
                <div class="col-sm-6 mb-4">

                    <div class="font-weight-bold mb-2">Invoice To:</div>
                    @foreach($quote->recipients['recipient'] as $recipient)
                        <div> {{ $recipient['email'] }} </div>
                    @endforeach

                </div>
            </div>


            <div>
                <table class="table table-bordered">
                    <tr>
                        <td> Description</td>
                        @if($quote->qty_type != 'amount')
                            <td>
                                @if($quote->qty_type == 'quantity')
                                    Quantity
                                @elseif($quote->qty_type == 'hours')
                                    Hours
                                @endif
                            </td>
                            <td>
                                @if($quote->qty_type == 'quantity')
                                    Price
                                @elseif($quote->qty_type == 'hours')
                                    Rate
                                @endif
                            </td>
                            <td> Amount </td>
                        @else
                            <td> Amount</td>
                        @endif


                    </tr>
                    @foreach($quote->details as $detail)
                        <tr>
                            <td>
                                {{ $detail->name }}
                                @if($detail->description)
                                    <div class="text-muted small"> {{ $detail->description }} </div>
                                @endif
                            </td>

                            @if($quote->qty_type != 'amount')
                                <td>
                                    {{ $detail->qty_count }}
                                </td>
                                <td>
                                    {{ format_money($detail->qty_value, $company_currency) }}
                                </td>
                                <td> {{ format_money($detail->sub_total, $company_currency) }} </td>
                            @else
                                <td> {{ format_money($detail->sub_total, $company_currency) }} </td>
                            @endif
                        </tr>
                    @endforeach
                </table>

                <div class="row">

                    <div class="offset-6 col">
                        <table class="table table-bordered">
                            <tr>
                                <td> Sub Total </td>
                                <td> {{ format_money($quote->total_amount, $company_currency) }} </td>
                            </tr>
                            @if(!empty($quote->tax))
                                <tr>
                                    <td> Tax </td>
                                    <td> {{ format_money($quote->tax_amount, $company_currency) }} </td>
                                </tr>
                            @endif
                            @if(!empty($quote->discount))
                                <tr>
                                    <td> Discount </td>
                                    <td> {{ format_money($quote->discount_amount, $company_currency) }} </td>
                                </tr>
                            @endif
                            @if(!empty($quote->custom_fee_amount))
                                <tr>
                                    <td> {{ $quote->custom_fee_name }} </td>
                                    <td> {{ format_money($quote->custom_fee_amount, $company_currency) }} </td>
                                </tr>
                            @endif
                            <tr>
                                <td> Total </td>
                                <td> {{ format_money($quote->final_amount, $company_currency) }} </td>
                            </tr>
                        </table>
                    </div>
                </div>


            </div>


            <div class="mb-4">
                <strong>Note: </strong>
                <p class="mt-1">{{ $quote->recipient_notes ?? '-' }}</p>
            </div>

            <div class="mb-4">
                <strong>Terms and Conditions:</strong>
                <p class="mt-1">{{ $quote->term_conditions ?? '-' }}</p>

            </div>

        </div>
        @if($quote->status === 'sent')
            <div class="card-footer text-center">
                <form method="post" action="{{ route('public.save-quote',$quote->quote_number) }}">
                    @csrf
                    <button type="submit" name="result" value="accepted" class="btn btn-success"> Accept Quote </button>
                    <button type="submit" name="result" value="rejected" class="btn btn-danger ml-2"> Reject Quote</button>
                </form>

            </div>
        @endif

    </div>

@stop