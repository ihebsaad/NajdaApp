<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<style type="text/css" media="all">
        /* Take care of image borders and formatting, client hacks */
        img { max-width: 600px; outline: none; text-decoration: none; -ms-interpolation-mode: bicubic;}
        a img { border: none; }
        table { border-collapse: collapse !important;}
        #outlook a { padding:0; }
        .ReadMsgBody { width: 75%; }
        .ExternalClass { width: 75%; }
        .backgroundTable { margin: 0 auto; padding: 0; width: 75% !important; }
        table td { border-collapse: collapse; }
        .ExternalClass * { line-height: 115%; }
        .container-for-gmail-android { min-width: 380px; }


        /* General styling */
        * {
            font-family: Helvetica, Arial, sans-serif;
        }

        body {
            -webkit-font-smoothing: antialiased;
            -webkit-text-size-adjust: none;
           /* max-width: 450px !important;*/
            margin: 0 !important;
          /*  height: 650px;*/
            color: #676767;
        }


        td {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 14px;
            color: #777777;
            text-align: center;
            line-height: 21px;
        }

        a {
            color: #676767;
            text-decoration: none !important;
        }

        .pull-left {
            text-align: left;
        }

        .pull-right {
            text-align: right;
        }

        .header-lg,
        .header-md,
        .header-sm {
            font-size: 26px;
            font-weight: 700;
            line-height: normal;
            padding: 30px 0 0;
            color: #4d4d4d;
        }

        .header-md {
            font-size: 24px;
        }

        .header-sm {
            padding: 5px 0;
            font-size: 18px;
            line-height: 1.3;
        }

        .content-padding {
            padding: 20px 0 30px;
        }

        .mobile-header-padding-right {
            width: 290px;
            text-align: right;
            padding-left: 10px;
        }

        .mobile-header-padding-left {
            width: 290px;
            text-align: left;
            padding-left: 10px;
        }

        .free-text {
            width: 75% !important;
            padding: 10px 60px 0px;
        }

        .block-rounded {
            border-radius: 5px;
            border: 1px solid #e5e5e5;
            vertical-align: top;
        }

        .button {
            padding: 55px 0 0;
        }

        .info-block {
            padding: 0 20px;
            width: 260px;
        }

        .mini-block-container {
            padding: 30px 50px;
            width: 60px;
        }

        .mini-block {
            background-color: #ffffff;
            width: 380px;
            border: 1px solid #cccccc;
            border-radius: 5px;
          /*  padding: 60px 75px;*/
            padding: 20px 20px 20px 20px;
        }

        .block-rounded {
            width: 240px;
        }

        .info-img {
            width: 258px;
            border-radius: 5px 5px 0 0;
        }

        .force-width-img {
            width: 160px;
            height: 1px !important;
        }

        .force-width-full {
            width: 400px;
            height: 1px !important;
        }

        .user-img img {
            width: 82px;
            border-radius: 5px;
            border: 1px solid #cccccc;
        }

        .user-img {
            width: 92px;
            text-align: left;
        }

        .user-msg {
            width: 236px;
            font-size: 14px;
            text-align: left;
            font-style: italic;
        }

        .code-block {
            padding: 10px 0;
            border: 1px solid #cccccc;
            color: #4d4d4d;
            font-weight: bold;
            font-size: 18px;
            text-align: center;
        }

        .force-width-gmail {
            min-width:600px;
            height: 0px !important;
            line-height: 1px !important;
            font-size: 1px !important;
        }

        .button-width {
            width: 228px;
        }

    </style>

    <style type="text/css" media="all">
        @import url(http://fonts.googleapis.com/css?family=Oxygen:400,700);
    </style>

    <style type="text/css" media="all">
        @media screen {
            /* Thanks Outlook 2013! */
            * {
                font-family: 'Oxygen', 'Helvetica Neue', 'Arial', 'sans-serif' !important;
            }
        }
    </style>


<body bgcolor="#f7f7f7" >
<table align="center"  class="container-for-gmail-android" width="75%">
    <tr>
        <td align="left"   width="100%" style="background-color:#e7e7e7;padding-left:20px;padding-right:20px;">
            <center>
                 <table    width="100%" bgcolor="grey" style="background-color:transparent">
                    <tr>
                        <td width="100%" height="80"   style="text-align: center; vertical-align:middle;">

                            <center>
                                <table  width="400" class="w320">
                                    <tr>
                                        <td class="pull-left mobile-header-padding-left" style="vertical-align: middle;">
                                            <label style="font-size:16px;color:#4fc1e9;">Emetteur:</label><label style="font-size:16px;color:#000000!important"> {{ $entree->emetteur   }}</label>
                                        </td>
                                        <td class="pull-right mobile-header-padding-right" >
                                            <label style="font-size:16px;color:#4fc1e9;">Date:</label><label style="font-size:16px;color: #000000 !important;"> <?php echo  date('d/m/Y H:i', strtotime( $entree->reception  )) ; ?></label> </td>

                                        </td>
                                    </tr>
                                </table>
                            </center>
                         </td>
                    </tr>
                </table>
            </center>
        </td>
    </tr>
    <tr>
        <td align="center"  width="100%" heigh="100%" style="background-color: #f7f7f7;" class="content-padding">
            <center>
                <table  width="100%" >
                    <tr>
                        <td class="header-lg">
                            <span style="color:#fd9883"> Sujet : {{$entree->sujet}}</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="free-text">

                            @if (!empty($entree->dossier))
                                 <b>REF Dossier: {{ $entree->dossier   }}</b>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="mini-block-container">
                            <table    width="100%"  style="border-collapse:separate !important;">
                                <tr>
                                    <td class="mini-block">
                                        <table   width="100%">
                                            <tr>
                                                <td >
                                                <!--<td class="code-block">-->
                                                    <?php $contenu=$entree['contenu'];
                                                    echo $contenu;?>
                                                </td>
                                            </tr>


                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </center>
        </td>
    </tr>
    <!--
    <tr>
    <td align="center"   width="75%" style="background-color: #ffffff;  border-top: 1px solid #e5e5e5; border-bottom: 1px solid #e5e5e5;">
        <center>
            <table  width="300" class="w320">
                <tr>
                    <td class="content-padding">
                        <table width="75%">
                            <tr>
                                <td class="header-md">
                                 </td>
                            </tr>
                        </table>
                    </td>
                </tr>

            </table>
        </center>
    </td>
    </tr><!--
    <tr>
        <td align="center"  width="75%" style="background-color: #f7f7f7; height: 70px;">
            <center>
                <table   width="300" class="w320">
                    <tr>
                        <td style="padding: 25px 0 25px;color:#fd9883;font-weight:bold;"><I>
                            <a href="http://www.najda-assistance.com/">Najda Assistance</a><br>
                            Tel :+216 36 00 36 00 <br>
                            Fax :+216 36 00 36 00<br>
                            Email :contact@najda-assistance.com</I>
                        </td>
                    </tr>
                </table>
            </center>
        </td>
    </tr>-->
</table>
</body>
