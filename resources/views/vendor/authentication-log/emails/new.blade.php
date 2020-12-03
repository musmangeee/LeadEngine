@extends('layouts.email')
@section('content')


    <tr>
        <td valign="top" id="templateUpperBody"><table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnTextBlock" style="min-width:100%;">
                <tbody class="mcnTextBlockOuter">
                <tr>
                    <td valign="top" class="mcnTextBlockInner" style="padding-top:9px;">
                        <!--[if mso]>
                        <table align="left" border="0" cellspacing="0" cellpadding="0" width="100%" style="width:100%;">
                            <tr>
                        <![endif]-->

                        <!--[if mso]>
                        <td valign="top" width="600" style="width:600px; padding: 10px">
                        <![endif]-->
                        <table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:100%; min-width:100%;" width="100%" class="mcnTextContentContainer">
                            <tbody><tr>

                                <td valign="top" class="mcnTextContent" style="padding-top:0; padding-right:18px; padding-bottom:9px; padding-left:18px;">

                                    <h2>Hello!</h2>
                                    <br />
                                    Your {{ config('app.name') }} account logged in from a new device.
                                    <br /><br />

                                    <b>Account:</b> {{ $account->email }}<br>
                                    <b>Time:</b> {{ $time->toCookieString() }}<br>
                                    <b>IP Address:</b> {{ $ipAddress }}<br>
                                    <b>Browser:</b> {{ $browser }}

                                    <br /><br />
                                    If this was you, you can ignore this alert. If you suspect any suspicious activity on your account, please change your password.
                                    <br /><br />
                                    Regards,<br>{{ config('app.name') }}
                                    &nbsp;
                                </td>
                            </tr>
                            </tbody></table>

                        <!--[if mso]>
                        </td>
                        <![endif]-->

                        <!--[if mso]>
                        </tr>
                        </table>
                        <![endif]-->
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>


@stop
