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
                        <td valign="top" width="600" style="width:600px;">
                        <![endif]-->
                        <table align="left" border="0" cellpadding="0" cellspacing="0" style="max-width:100%; min-width:100%;" width="100%" class="mcnTextContentContainer">
                            <tbody><tr>

                                <td valign="top" class="mcnTextContent" style="padding-top:0; padding-right:18px; padding-bottom:9px; padding-left:18px;">
                                    <p>{{ $contentMessage }}</p>
                                    <p>&nbsp;</p>
                                    <p>Regards, <br>{{$currentUser->first_name}} {{$currentUser->last_name}}</p>
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
