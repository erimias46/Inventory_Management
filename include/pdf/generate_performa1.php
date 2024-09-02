<?php

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
    $vat = $_POST['vat'];

    $items_html = '';
    $total_price = 0;

    for ($i=0; $i < count($item_name); $i++) { 
        $total = $item_qty[$i] * $item_price[$i];
        $total_price += $total;
        $items_html .= "
            <tr>
                <td class='tm_width_3'>
                    {$item_name[$i]}
                </td>
                <td class='tm_width_2'>{$item_qty[$i]}</td>
                <td class='tm_width_1'>{$item_price[$i]}</td>
                <td class='tm_width_2 tm_text_right'>$total</td>
            </tr>
        ";
    }

    $total_vat = $vat / 100 * $total_price;
    $grand_total = $total_price + $total_vat;
    $total_in_words = int_to_text($grand_total);

    ?>

        <!DOCTYPE html>
        <html class="no-js" lang="en">

        <head>
        <!-- Meta Tags -->
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="Laralink">
        <!-- Site Title -->
        <title>Price Quote</title>
        <link rel="stylesheet" href="assets/css/style.css">
        </head>

        <body>
        <div>
            <div class="tm_container">
            <div class="tm_invoice_wrap">
            <div class="tm_invoice tm_style1" id="tm_download_section">
                <div class="tm_invoice_in">
                <div class="tm_invoice_head tm_align_center tm_mb20">
                    <div class="tm_invoice_left">
                    <div class="tm_logo"><img src="logo.jpg" width="200px" alt="Logo"></div>
                    </div>
                    <div class="tm_invoice_right tm_text_right">
                    <div class="tm_primary_color tm_f50 tm_text_uppercase">Price Quote</div>
                    </div>
                </div>
                <div class="tm_invoice_info tm_mb20">
                    <div class="tm_invoice_seperator tm_gray_bg"></div>
                        <div class="tm_invoice_info_list">
                            <p class="tm_invoice_number tm_m0">VAT Registration No: <b class="tm_primary_color"><?= $vat_reg ?></b></p>
                            <p class="tm_invoice_date tm_m0">Tin No.: <b class="tm_primary_color"><?= $tin ?></b></p>
                            <p class="tm_invoice_date tm_m0">Payment to: <b class="tm_primary_color"><?= $payment_to ?></b></p>
                        </div>
                    </div>
                    <div class="tm_invoice_head tm_mb10">
                        <div class="tm_invoice_left">
                            <p class="tm_mb2"><b class="tm_primary_color">Invoice To:</b></p>
                            <p>
                                <br>
                                Client: <b><?= $client ?></b><br>
                                Requested By: <b><?= $requested_by ?></b><br>
                                Phone: <b><?= $phone ?></b>
                            </p>
                        </div>
                    <div class="tm_invoice_right tm_text_right">
                    <p class="tm_mb2"><b class="tm_primary_color">Pay To:</b></p>
                    <p>
                        Date: <b><?= $current_date ?></b><br>
                        Quote No.: <b><?= $quote_number ?></b><br>
                        Quote Sender: <b><?= $quote_sender ?></b><br>
                        Delivery Date: <b>As per ToR</b>
                    </p>
                    </div>
                </div>
                <div class="tm_table tm_style1 tm_mb30">
                    <div class="tm_round_border">
                    <div class="tm_table_responsive">
                        <table>
                        <thead>
                            <tr>
                            <th class="tm_width_3 tm_semi_bold tm_primary_color tm_gray_bg">Item</th>
                            <th class="tm_width_2 tm_semi_bold tm_primary_color tm_gray_bg">Unit Price</th>
                            <th class="tm_width_1 tm_semi_bold tm_primary_color tm_gray_bg">Quantity</th>
                            <th class="tm_width_2 tm_semi_bold tm_primary_color tm_gray_bg tm_text_right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?= $items_html ?>
                        </tbody>
                        </table>
                    </div>
                    </div>
                    <div class="tm_invoice_footer">
                    <div class="tm_left_footer">
                        <p class="tm_mb2">Amount in Words:<b class="tm_primary_color"><?= $total_in_words ?></b></p>
                    </div>
                    <div class="tm_right_footer">
                        <table>
                        <tbody>
                            <tr>
                            <td class="tm_width_3 tm_primary_color tm_border_none tm_bold">Total</td>
                            <td class="tm_width_3 tm_primary_color tm_text_right tm_border_none tm_bold"><?= $total_price ?></td>
                            </tr>
                            <tr>
                            <td class="tm_width_3 tm_primary_color tm_border_none tm_pt0">VAT <span class="tm_ternary_color">(<?= $vat ?>%)</span></td>
                            <td class="tm_width_3 tm_primary_color tm_text_right tm_border_none tm_pt0">+<?= $total_vat ?></td>
                            </tr>
                            <tr class="tm_border_top tm_border_bottom">
                            <td class="tm_width_3 tm_border_top_0 tm_bold tm_f16 tm_primary_color">Grand Total	</td>
                            <td class="tm_width_3 tm_border_top_0 tm_bold tm_f16 tm_primary_color tm_text_right"><?= $grand_total ?></td>
                            </tr>
                        </tbody>
                        </table>
                    </div>
                    </div>
                </div>
                <div class="tm_padd_15_20 tm_round_border">
                    <p class="tm_mb5"><b class="tm_primary_color">Terms & Conditions:</b></p>
                    <ul class="tm_m0 tm_note_list">
                    <li>Prices are subject to change as additional information is supplied. Changes to <b>Specification</b> and <b>Quantity</b> will subject price to change. This estimate is <b>valid for 30 days</b> from the date indicated.</li>
                    <li>100%[<b>Br <?= $grand_total ?></b>] will be invoiced at project delivery</li>
                    </ul>
                </div><!-- .tm_note -->
                <div class="tm_padd_15_20 tm_round_border">
                    <p>Thank you for opportunity to submit this estimate. We look forward to working with you.</p>
                </div>
                </div>
            </div>
            </div>
        </div>
        </body>
        </html>
<?php }