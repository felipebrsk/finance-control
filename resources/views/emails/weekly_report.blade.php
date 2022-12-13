<!-- TODO: finish report email | use a better logo -->

<div style="max-height: 1281px; max-width: 1521px; margin: 0 auto;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout:fixed;background-color:#F9F9F9;"
        id="bodyTable">
        <tbody>
            <tr>
                <td align="center" valign="top" style="padding-right:10px;padding-left:10px;" id="bodyCell">
                    <!--[if (gte mso 9)|(IE)]><table align="center" border="0" cellspacing="0" cellpadding="0" style="width:600px;" width="600"><tr><td align="center" valign="top"><![endif]-->

                    <!-- Email Wrapper Header Open //-->
                    <table border="0" cellpadding="0" cellspacing="0" style="max-width:600px;" width="100%">
                        <tbody>
                            <tr>
                                <td align="center" valign="top">
                                    <!-- Content Table Open // -->
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <tbody>
                                            <tr>
                                                <td align="center" valign="middle"
                                                    style="padding-top:40px;padding-bottom:40px">
                                                    <!-- Logo and Link // -->
                                                    <a href="{{ env('APP_URL') }}" style="text-decoration:none;">
                                                        <img src="{{ Storage::temporaryUrl('statics/logo.png', now()->addHours(2)) }}"
                                                            alt="" width="200">
                                                    </a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <!-- Content Table Close // -->
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <!-- Email Wrapper Header Close //-->

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
                                                <td height="3"
                                                    style="background-color:#62c1bf;font-size:1px;line-height:3px;">
                                                    &nbsp;</td>
                                            </tr>

                                            <tr>
                                                <td align="center" valign="top"
                                                    style="padding-bottom:5px;padding-left:20px;padding-right:20px;padding-top:40px;">
                                                    <!-- Main Title Text // -->
                                                    <h2
                                                        style="color:#000000; font-family:'Poppins', Helvetica, Arial, sans-serif; font-size:28px; font-weight:500; font-style:normal; letter-spacing:normal; line-height:36px; text-transform:none; text-align:center; padding:0; margin:0">
                                                        Esse é o seu relatório semanal!
                                                    </h2>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td align="center" valign="top"
                                                    style="padding-bottom:10px;padding-left:20px;padding-right:20px;">
                                                    <!-- Sub Title Text // -->
                                                    <h4
                                                        style="color:#999999; font-family:'Poppins', Helvetica, Arial, sans-serif; font-size:16px; font-weight:500; font-style:normal; letter-spacing:normal; line-height:24px; text-transform:none; text-align:center; padding:0; margin:0">
                                                        Confira os seus gastos na semana em: {{ $space->name }}
                                                    </h4>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td align="center" valign="top"
                                                    style="padding-left:20px;padding-right:20px;" ui-sortable">

                                                    <table border="0" cellpadding="0" cellspacing="0"
                                                        width="100%">
                                                        <tbody>
                                                            <tr>
                                                                <td align="center" valign="top">
                                                                    <img src="https://www.pngmart.com/files/7/Spend-PNG-Transparent-Picture.png"
                                                                        alt="" width="60" border="0"
                                                                        style="height:auto;width:100%;max-width:60px;">
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>

                                                    <table border="0" cellpadding="0" cellspacing="0"
                                                        width="100%">
                                                        <tbody>
                                                            <tr>
                                                                <td align="center" valign="top"
                                                                    style="padding-bottom:10px;">
                                                                    <!-- Medium Title Text // -->
                                                                    <p
                                                                        style="color:#000000; font-family:'Poppins', Helvetica, Arial, sans-serif; font-size:18px; font-weight:500; font-style:normal; letter-spacing:normal; line-height:26px; text-transform:none; text-align:center; padding:0; margin:0">
                                                                        Gastou
                                                                    </p>
                                                                </td>
                                                            </tr>

                                                            <tr>
                                                                <td align="center" valign="top"
                                                                    style="padding-bottom:20px;">
                                                                    <!-- Description Text// -->
                                                                    <p
                                                                        style="color:#666666; font-family:'Open Sans', Helvetica, Arial, sans-serif; font-size:14px; font-weight:400; font-style:normal; letter-spacing:normal; line-height:22px; text-transform:none; text-align:center; padding:0; margin:0">
                                                                        {{ formatCurrency($totalSpent, $space->currency->iso) }}
                                                                    </p>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>

                                                    @if ($highestSpent)
                                                        <table border="0" cellpadding="0" cellspacing="0"
                                                            width="100%">
                                                            <tbody>
                                                                <tr>
                                                                    <td align="center" valign="top"
                                                                        style="padding-bottom:10px;">
                                                                        <!-- Medium Title Text // -->
                                                                        <p
                                                                            style="color:#000000; font-family:'Poppins', Helvetica, Arial, sans-serif; font-size:18px; font-weight:500; font-style:normal; letter-spacing:normal; line-height:26px; text-transform:none; text-align:center; padding:0; margin:0">
                                                                            Seu maior gasto foi em
                                                                            {{ $highestSpent->category->name }}
                                                                        </p>
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td align="center" valign="top"
                                                                        style="padding-bottom:20px;">
                                                                        <!-- Description Text// -->
                                                                        <p
                                                                            style="color:#666666; font-family:'Open Sans', Helvetica, Arial, sans-serif; font-size:14px; font-weight:400; font-style:normal; letter-spacing:normal; line-height:22px; text-transform:none; text-align:center; padding:0; margin:0">
                                                                            {{ formatCurrency($highestSpent->amount, $space->currency->iso) }}
                                                                        </p>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    @endif

                                                    <table border="0" cellpadding="0" cellspacing="0"
                                                        width="100%">
                                                        <tbody>
                                                            <tr>
                                                                <td align="center" valign="top"
                                                                    style="padding-top:20px;padding-bottom:20px;">

                                                                    <!-- Button Table // -->
                                                                    <table align="center" border="0"
                                                                        cellpadding="0" cellspacing="0">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td align="center"
                                                                                    style="background-color:#003CE5;padding-top:12px;padding-bottom:12px;padding-left:35px;padding-right:35px;border-radius:50px">
                                                                                    <!-- Button Link // -->
                                                                                    <a href="#"
                                                                                        style="color:#FFFFFF; font-family:'Poppins', Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; font-style:normal;letter-spacing:1px; line-height:20px; text-transform:uppercase; text-decoration:none; display:block">
                                                                                        Mais detalhes
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

                    <!-- Email Wrapper Footer Open // -->
                    <table border="0" cellpadding="0" cellspacing="0" style="max-width:600px;" width="100%">
                        <tbody>
                            <tr>
                                <td align="center" valign="top">
                                    <!-- Content Table Open// -->
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <tbody>
                                            <tr>
                                                <td align="center" valign="top"
                                                    style="padding-top:10px;padding-bottom:10px;padding-left:10px;padding-right:10px;">
                                                    <!-- Social Links (Facebook)// -->
                                                    <a href="#facebook-link" style="display:inline-block;">
                                                        <img src="http://weekly.grapestheme.com/notify/img/social/light/facebook.png"
                                                            alt="" width="40" border="0"
                                                            style="height:auto; width:100%; max-width:40px; margin-left:2px; margin-right:2px">
                                                    </a>
                                                    <!-- Social Links (Twitter)// -->
                                                    <a href="#twitter-link" style="display:inline-block;">
                                                        <img src="http://weekly.grapestheme.com/notify/img/social/light/twitter.png"
                                                            alt="" width="40" border="0"
                                                            style="height:auto; width:100%; max-width:40px; margin-left:2px; margin-right:2px">
                                                    </a>
                                                    <!-- Social Links (Pintrest)// -->
                                                    <a href="#pintrest-link" style="display:inline-block;">
                                                        <img src="http://weekly.grapestheme.com/notify/img/social/light/pintrest.png"
                                                            alt="" width="40" border="0"
                                                            style="height:auto; width:100%; max-width:40px; margin-left:2px; margin-right:2px">
                                                    </a>
                                                    <!-- Social Links (Instagram)// -->
                                                    <a href="#instagram-link" style="display:inline-block;">
                                                        <img src="http://weekly.grapestheme.com/notify/img/social/light/instagram.png"
                                                            alt="" width="40" border="0"
                                                            style="height:auto; width:100%; max-width:40px; margin-left:2px; margin-right:2px">
                                                    </a>
                                                    <!-- Social Links (Linkdin)// -->
                                                    <a href="#linkdin-link" style="display:inline-block;">
                                                        <img src="http://weekly.grapestheme.com/notify/img/social/light/linkdin.png"
                                                            alt="" width="40" border="0"
                                                            style="height:auto; width:100%; max-width:40px; margin-left:2px; margin-right:2px">
                                                    </a>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td align="center" valign="top" style="padding: 10px 10px 5px;">
                                                    <!-- Brand Information // -->
                                                    <p
                                                        style="color:#777777; font-family:'Open Sans', Helvetica, Arial, sans-serif; font-size:12px; font-weight:400; font-style:normal; letter-spacing:normal; line-height:20px; text-transform:none; text-align:center; padding:0; margin:0;">
                                                        ©&nbsp;Finance Control
                                                    </p>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td align="center" valign="top"
                                                    style="padding-top:0px;padding-bottom:10px;padding-left:10px;padding-right:10px;">
                                                    <!-- Information of NewsLetter (Subscribe Info)// -->
                                                    <p
                                                        style="color:#777777; font-family:'Open Sans', Helvetica, Arial, sans-serif; font-size:12px; font-weight:400; font-style:normal; letter-spacing:normal; line-height:20px; text-transform:none; text-align:center; padding:0; margin:0;">
                                                        Se tiver alguma dúvida, por favor, nos contate <a
                                                            href="#"
                                                            style="color:#777777;text-decoration:underline;">support@mail.com.</a><br>
                                                        <a href="#"
                                                            style="color:#777777;text-decoration:underline;">Parar</a>
                                                        de receber relatórios
                                                        semanais.
                                                    </p>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td align="center" valign="top"
                                                    style="padding-top:10px;padding-bottom:10px;padding-left:10px;padding-right:10px;">
                                                    <!-- App Links (Anroid)// -->
                                                    <a href="#Play-Store-Link" style="display:inline-block;"store">
                                                        <img src="http://weekly.grapestheme.com/notify/img/app/play-store.png"
                                                            alt="" width="120" border="0"
                                                            style="height:auto;margin:5px;width:100%;max-width:120px;">
                                                    </a>
                                                    <!-- App Links (IOs)// -->
                                                    <a href="#App-Store-Link" style="display:inline-block;"store">
                                                        <img src="http://weekly.grapestheme.com/notify/img/app/app-store.png"
                                                            alt="" width="120" border="0"
                                                            style="height:auto;margin:5px;width:100%;max-width:120px;">
                                                    </a>
                                                </td>
                                            </tr>

                                            <!-- Space -->
                                            <tr>
                                                <td height="30" style="font-size:1px;line-height:1px;">&nbsp;
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <!-- Content Table Close// -->
                                </td>
                            </tr>

                            <!-- Space -->
                            <tr>
                                <td height="30" style="font-size:1px;line-height:1px;">&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                    <!-- Email Wrapper Footer Close // -->

                    <!--[if (gte mso 9)|(IE)]></td></tr></table><![endif]-->
                </td>
            </tr>
        </tbody>
    </table>
</div>
