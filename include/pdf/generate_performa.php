<?php
require_once __DIR__ . '/../../vendor/autoload.php';

$defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
$fontDirs = $defaultConfig['fontDir'];

$defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
$fontData = $defaultFontConfig['fontdata'];

$mpdf = new \Mpdf\Mpdf([
    'fontDir' => array_merge($fontDirs, [ __DIR__ . '/font' ]),
    'fontdata' => $fontData + [ // lowercase letters only in font key
        'opensans' => [
            'R' => 'OpenSans-Regular.ttf',
            'B' => 'OpenSans-Bold.ttf',
            'L' => 'OpenSans-Light.ttf',
            'I' => 'OpenSans-Italic.ttf',
        ]
    ],
    'default_font' => 'opensans'
]);

$current_date = date('Y-m-d');

function int_to_text($num) {
    $ones = array('Zero', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine');
    $tens = array('Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen');
    $twenties = array('Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety');
    if ($num < 10) {
        return $ones[$num];
    } elseif ($num < 20) {
        return $tens[$num-10];
    } elseif ($num < 100) {
        return $twenties[$num/10-2] . ($num%10==0 ? '' : ' ' . $ones[$num%10]);
    } elseif ($num < 1000) {
        return $ones[$num/100] . ' Hundred' . ($num%100==0 ? '' : ' and ' . int_to_text($num%100));
    } elseif ($num < 1000000) {
        return int_to_text($num/1000) . ' Thousand' . ($num%1000==0 ? '' : ' ' . int_to_text($num%1000));
    } else {
        return 'Number out of range';
    }
}


if (isset($_POST['generate_performa'])) {
    $vat_reg = $_POST['vat_reg'];
    $tin = $_POST['tin'];
    $payment_to = $_POST['payment_to'];
    $client = $_POST['client'];
    $quote_number = $_POST['quote_number'];
    $quote_sender = $_POST['quote_sender'];
    $requested_by = $_POST['requested_by'];
    $phone = $_POST['phone'];
    $item_name = $_POST['item_name'];
    $item_qty = $_POST['item_qty'];
    $item_price = $_POST['item_price'];

    $html = <<<EOF
        <style>
            table, tr, td {
                padding: 10px;
            }

            table {
                width: 100%;
            }
        </style>
        <table>
            <tbody>
                <thead>
                    <tr>
                        <td rowspan="3" colspan="2">
                            <img src="logo.jpg" width="200px"/> 
                        </td>
                        <td style="font-size: 32px; font-weight: light; text-align: right">
                            Price Quote
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: right">
                            +251 913 121 812<br>
                            <a href="mailto:infooelias@gmail.com">infooelias@gmail.com</a>
                        </td>
                    </tr>
                    <tr style="text-align: right">
                        <td style="display: flex">
                            <div style="border-right: 1px solid dodgerblue;">
                                <span style="font-weight: 200">VAT REGISTRATION NO.</span><br>
                                $vat_reg
                            </div>
                            <div style="border-right: 1px solid dodgerblue; ">
                                <span style="font-weight: 200">TIN NUMBER</span><br>
                                $tin
                            </div>
                            <div style="">
                                <span style="font-weight: 200">PAYMENT TO</span><br>
                                $payment_to
                            </div>
                        </td>
                    </tr>
                </thead>
            </tbody>
        </table>
    EOF;

    $items_html = '';
    $total_price = 0;

    for ($i=0; $i < count($item_name); $i++) { 
        $total = $item_qty[$i] * $item_price[$i];
        $total_price += $total;
        $items_html .= "
                <tr>
                    <td>
                        {$item_name[$i]}
                    </td>
                    <td>{$item_qty[$i]}</td>
                    <td>{$item_price[$i]}</td>
                    <td>$total</td>
                </tr>
        ";
    }

    $total_vat = 0.15 * $total_price;
    $grand_total = $total_price + $total_vat;
    $total_in_words = int_to_text($grand_total);

    $html .= <<<EOF
    <div>
        <table style="padding-bottom: 15px">
            <tr>
                <td style="width: 70%; "></td>
                <td style="border-bottom: 1px solid #ccc">Date: <b>$current_date</b> </td>
            </tr>
            <tr >
                <td style="width: 70%; border-bottom: 1px solid #ccc; ">Client: <b>$client</b></td>
                <td style="border-bottom: 1px solid #ccc">Quote No.: <b>$quote_number</b> </td>
            </tr>
            <tr >
                <td style="width: 70%; border-bottom: 1px solid #ccc; ">Requested By: <b>$requested_by</b></td>
                <td style="border-bottom: 1px solid #ccc">Quote Sender: <b>$quote_sender</b> </td>
            </tr>
            <tr >
                <td style="width: 70%; border-bottom: 1px solid #ccc; ">Phone: <b>$phone</b></td>
                <td style="border-bottom: 1px solid #ccc">Delivery Date: <b>As per ToR</b> </td>
            </tr>
        </table>

        <style>
            table.main {
                border-top: 2pt solid #bbddff;
                border-bottom: 3pt solid #bbddff;
            }

            table.main th, table.main td {
                border-top: 1pt solid #ccc;
                text-align: left;
            }

            table.main th {
                font-weight: normal;
            }

            h4 {
                color: dodgerblue;
            }
        </style>

        <table class="main">
            <tr>
                <th style="border: none; font-weight: bold; color: dodgerblue" colspan="4">Requirements</th>
            </tr>
            <tr>
                <th style="width: 30%">Specifications</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Total Price</th>
            </tr>
            <tbody>
                $items_html
            </tbody>
            <tfoot>
                <tr>
                    <td rowspan="3" colspan="2"></td>
                    <td><b>Total</b></td>
                    <td><b>$total_price</b></td>
                </tr>
                <tr>
                    <td><b>15% VAT</b></td>
                    <td>$total_vat</td>
                </tr>
                <tr>
                    <td><b>Grand Total</b></td>
                    <td><b>$grand_total</b></td>
                </tr>
                <tr>
                    <td colspan="4">
                    Amount in Words: <b>$total_in_words</b>
                    </td>
                </tr>
            </tfoot>
        </table>
        <div>
            <h4>Please Read...</h4>
            <p>Prices are subject to change as additional information is supplied. Changes to <b>Specification</b> and <b>Quantity</b> will subject price to change. This estimate is <b>valid for 30 days</b> from the date indicated.</p>
            <h4>Payment Term:</h4>
            <p>100%[<b>Br $grand_total</b>] will be invoiced at project delivery</p>
            <p>Thank you for opportunity to submit this estimate. We look forward to working with you.</p>
        </div>
    </div>
    EOF;

    $mpdf->WriteHTML($html);
    $mpdf->Output();
}