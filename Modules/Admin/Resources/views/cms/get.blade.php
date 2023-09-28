<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:400,600,700,800,900|Nunito:400,600,700,800,900&display=swap" rel="stylesheet">
    <title>CMS</title>

</head>

<body style="background-color: #f7f7f7; height: 100%; margin: 0; padding: 0; width: 100%">
    <center>
        <table align="center" border="0" cellpadding="0" cellspacing="0" height="100%" id="bodyTable" style="border-collapse: collapse;background-color: #f7f7f7; height: 100%; margin: 0; padding: 0; width:100%" width="100%">
            <tr>
                <td align="center" id="bodyCell" style="border-top: 0;height: 100%; margin: 0; padding: 0; width: 100%" valign="top">

                    <table border="0" cellpadding="0" cellspacing="0" class="templateContainer" style="border-collapse: collapse; max-width: 600px; background:#f9f9f9;border: 0" width="100%">
                        
                       
                        <tr>
                            <td id="templateBody" style="background-color: #fff; border-top: 0; border-bottom: 0; padding: 30px 30px" valign="top">
                                <table border="0" cellpadding="0" cellspacing="0" class="mcnTextBlock" width="100%">
                                    <tbody class="mcnTextBlockOuter">
                                        <tr>
                                            <td class="mcnTextBlockInner" valign="top">
                                                <table align="left" border="0" cellpadding="0" cellspacing="0" class="mcnTextContentContainer" style="border-collapse: collapse;min-width:100%;" width="100%">
                                                    <tbody>
                                                        <tr>
                                                            <td class="mcnTextContent" style='word-break: break-word; color: #2a2a2a;font-size: 16px; line-height: 150%; text-align: left; padding:9px 18px;' valign="top"> {{$data->title}}
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td class="mcnTextContent" style='word-break: break-word; color: #2a2a2a;font-size: 16px; line-height: 150%; text-align: left; padding:9px 18px;' valign="top">
                                                            {!!$data->body!!}
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
                       
                    </table>
                </td>
            </tr>
        </table>
    </center>
</body>

</html>