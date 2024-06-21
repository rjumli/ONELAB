<!doctype html>
<html lang="en">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <style>
        html * {
            font-family:Arial, Helvetica, sans-serif;
        }
        table,
        td,
        th {
            border: .5px solid black;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            padding: 3px;
            vertical-align: top;
        }

        td {
            padding: 3px;
            /* vertical-align: top; */
            /* text-align: center; */
        }
        input[type=checkbox] {
            transform: scale(.7);
        }
         .a {
            width: 55px; 
            height: 55px;
         }
         label {
        display: block;
        padding-left: 15px;
        text-indent: -15px;
        }
        input {
        width: 13px;
        height: 13px;
        padding: 0;
        margin:0;
        vertical-align: bottom;
        position: relative;
        top: -5px;
        left: 7px;
        *overflow: hidden;
        
        }
        .spec_table td {
margin-left: -20px;
font-size: .9em;
line-height: 1.1em;
border-top: none !important;
}

.page-break {
  page-break-after: always;
}
input[type=checkbox] { display: inline; }
input[type=checkbox]:before { font-family: DejaVu Sans; }
label {
  display: inline-block;
}

    </style>
    </head>

    <body>
        <?php 
            $configuration = json_encode($configuration); 
            $configuration = json_decode($configuration, true);   
            
        ?>

        <div style="font-family:Arial;">
            <img src="{{ asset('images/logo-sm.png') }}" alt="tag" style="position: absolute; top: -4; left: 90; width: 60px; height: 60px;">
            <center style="font-size: 9px; margin-bottom: 0px;">Republic of the Philippines</center>
            <center style="font-size: 10px; margin-bottom: 0px; text-transform: uppercase; font-weight: bold;">{{$configuration['name']}}</center>
            <center style="font-size: 11px; margin-bottom: 0px; font-weight: bold; color: #4290f5;">REGIONAL STANDARDS AND TESTING LABORATORIES</center>
            <center style="font-size: 9px;">Pettit Baracks, Zamboanga City | (062) 991-1024</center>
            <hr style="color: #4290f5; margin-top: 15px;"/>
            <br/>
            
            <center style="font-size: 13px; color:#000; font-weight: bold; padding: 2px; margin-top: -10px;">TEST REPORT</center>
            
            <table style="border: .5px solid black; font-size: 11px; margin-top: 0px;">
                <tbody>
                    <tr>
                        <td width="30%">TSR Number</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Date Submitted</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Date Analyzed</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Sample Submitted</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Sample Descriptions</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Submitted by</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Address</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
            <!-- <table style="border: hidden; font-size: 10px; margin-top: 0px;">
                <tbody>
                    <tr>
                        <td style="border-right-style: hidden; border-bottom-style: hidden;" width="30%">TSR Number</td>
                        <td style="border-bottom-style: hidden;">:</td>
                    </tr>
                    <tr>
                        <td style="border-right-style: hidden; border-bottom-style: hidden;">Date Submitted</td>
                        <td style="border-bottom-style: hidden;">:</td>
                    </tr>
                    <tr>
                        <td style="border-right-style: hidden; border-bottom-style: hidden;">Date Analyzed</td>
                        <td style="border-bottom-style: hidden;">:</td>
                    </tr>
                    <tr>
                        <td style="border-right-style: hidden; border-bottom-style: hidden;">Sample Submitted</td>
                        <td style="border-bottom-style: hidden;">:</td>
                    </tr>
                    <tr>
                        <td style="border-right-style: hidden; border-bottom-style: hidden;">Sample Descriptions</td>
                        <td style="border-bottom-style: hidden;">:</td>
                    </tr>
                </tbody>
            </table> -->
            <h6 style="font-size: 11px; margin-top: 12px;">CHEMICAL / MECHANICAL TEST RESULT(S):</h6>
            <table style="border: 1px solid black; margin-top: -22px;">
                <thead style="background-color:#edf1f5; padding: 5px; font-size: 11px;">
                    <tr>    
                        <th width="30%">PARAMETER</th>
                        <th width="20%">RESULT</th>
                        <th width="50%">METHOD</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="font-size: 9px;" colspan="3"><center>***** NOTHING FOLLOWS *****</center></td>
                    </tr>
                </tbody>
            </table>
            <h6 style="font-size: 11px; margin-top: 12px;">REMARKS:</h6>
            <ul style="font-size: 10px; margin-top: -20px; margin-left: -15px;"> 
                <li>This report is based on the samples received by this office and should not be used for advertising purposes or sales promotion nor as a basis for tariff or customs classification of imported commodity.</li>
                <li>Information in italics is provided by the customer.</li>
                <li>This report is not valid without dry seal and QR code.</li>
                <li>This report shall not be reproduced except in full, without the written approval of the laboratory.</li>
            </ul>
        </div>

    </body>
</html>

