<!-- TODO finish email buttons -->

@extends('emails.template')

@php
    $lastPasswordActivity = $user
        ->activities()
        ->whereAction('password.changed')
        ->latest('updated_at');
@endphp

@section('content')
    <!-- Email Wrapper Body Open // -->
    <table border="0" cellpadding="0" cellspacing="0" style="max-width:600px;" width="100%">
        <tbody>
            <tr>
                <td align="center" valign="top">

                    <!-- Table Card Open // -->
                    <table border="0" cellpadding="0" cellspacing="0"
                        style="background-color:#FFFFFF;border-color:#E5E5E5;border-style:solid;border-width:0 1px 1px 1px;"
                        width="100%">

                        <tbody>
                            <tr>
                                <!-- Header Top Border // -->
                                <td height="3" style="background-color:#c21d1d;font-size:1px;line-height:3px;">
                                    &nbsp;</td>
                            </tr>

                            <tr>
                                <td align="center" valign="top"
                                    style="padding-bottom:5px;padding-left:20px;padding-right:20px;padding-top:40px;">
                                    <!-- Main Title Text // -->
                                    <h2
                                        style="color:#000000; font-family:'Poppins', Helvetica, Arial, sans-serif; font-size:28px; font-weight:500; font-style:normal; letter-spacing:normal; line-height:36px; text-transform:none; text-align:center; padding:0; margin:0">
                                        Olá, {{ Str::before($user->name, ' ') }}.
                                    </h2>
                                </td>
                            </tr>

                            @if ($lastPasswordActivity->exists())
                                @php
                                    $activityUpdatedAt = $lastPasswordActivity->value('updated_at');
                                @endphp
                                <tr>
                                    <td align="center" valign="top"
                                        style="padding-bottom:10px;padding-left:20px;padding-right:20px;">
                                        <!-- Sub Title Text // -->
                                        <h4
                                            style="color:#999999; font-family:'Poppins', Helvetica, Arial, sans-serif; font-size:16px; font-weight:500; font-style:normal; letter-spacing:normal; line-height:24px; text-transform:none; text-align:center; padding:0; margin:0">
                                            Sua senha foi alterada às {{ $activityUpdatedAt->format('H:i') }} do dia
                                            {{ $activityUpdatedAt->format('d-m-Y') }}!
                                        </h4>
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td align="center" valign="top"
                                        style="padding-bottom:10px;padding-left:20px;padding-right:20px;">
                                        <!-- Sub Title Text // -->
                                        <h4
                                            style="color:#999999; font-family:'Poppins', Helvetica, Arial, sans-serif; font-size:16px; font-weight:500; font-style:normal; letter-spacing:normal; line-height:24px; text-transform:none; text-align:center; padding:0; margin:0">
                                            Sua senha foi alterada!
                                        </h4>
                                    </td>
                                </tr>
                            @endif

                            <tr>
                                <td align="center" valign="top" style="padding-left:20px;padding-right:20px;"
                                    ui-sortable">

                                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <tbody>
                                            <tr>
                                                <td align="center" valign="top">
                                                    <img src="https://www.freeiconspng.com/thumbs/alert-icon/alert-icon-red-11.png"
                                                        alt="" width="60" border="0"
                                                        style="height:auto;width:100%;max-width:60px;">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <tbody>
                                            <tr>
                                                <td align="center" valign="top" style="padding-top:20px;">
                                                    <!-- Description Text// -->
                                                    <p
                                                        style="color:#666666; font-family:'Open Sans', Helvetica, Arial, sans-serif; font-size:14px; font-weight:400; font-style:normal; letter-spacing:normal; line-height:22px; text-transform:none; text-align:center; padding:0; margin:0">
                                                        Caso não tenha sido você, clique no botão abaixo para recuperá-la.
                                                    </p>
                                                    <p
                                                        style="color:#666666; font-family:'Open Sans', Helvetica, Arial, sans-serif; font-size:14px; font-weight:400; font-style:normal; letter-spacing:normal; line-height:22px; text-transform:none; text-align:center; padding:0; margin:0">
                                                        Se necessário, entre em contato conosco no rodapé do email!
                                                    </p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <tbody>
                                            <tr>
                                                <td align="center" valign="top"
                                                    style="padding-top:20px;padding-bottom:20px;">

                                                    <!-- Button Table // -->
                                                    <table align="center" border="0" cellpadding="0" cellspacing="0">
                                                        <tbody>
                                                            <tr>
                                                                <td align="center"
                                                                    style="background-color:#e50f00;padding-top:12px;padding-bottom:12px;padding-left:35px;padding-right:35px;border-radius:50px">
                                                                    <!-- Button Link // -->
                                                                    <a href="#"
                                                                        style="color:#FFFFFF; font-family:'Poppins', Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; font-style:normal;letter-spacing:1px; line-height:20px; text-transform:uppercase; text-decoration:none; display:block">
                                                                        Alterar senha
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>

                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                </td>
                            </tr>

                            <tr>
                                <td height="20" style="font-size:1px;line-height:1px;">&nbsp;
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <!-- Table Card Close// -->

                    <!-- Space -->
                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tbody>
                            <tr>
                                <td height="30" style="font-size:1px;line-height:1px;">&nbsp;
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </td>
            </tr>
        </tbody>
    </table>
    <!-- Email Wrapper Body Close // -->
@endsection
