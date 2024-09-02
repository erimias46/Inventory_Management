<?php
include '../db.php';
$current_date = date('Y-m-d');

function int_to_text($num)
{
    $ones = array('Zero', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine');
    $tens = array('Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen');
    $twenties = array('Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety');
    if ($num < 10) {
        return $ones[$num];
    } elseif ($num < 20) {
        return $tens[$num - 10];
    } elseif ($num < 100) {
        return $twenties[$num / 10 - 2] . ($num % 10 == 0 ? '' : ' ' . $ones[$num % 10]);
    } elseif ($num < 1000) {
        return $ones[$num / 100] . ' Hundred' . ($num % 100 == 0 ? '' : ' and ' . int_to_text($num % 100));
    } elseif ($num < 1000000) {
        return int_to_text($num / 1000) . ' Thousand' . ($num % 1000 == 0 ? '' : ' ' . int_to_text($num % 1000));
    } elseif ($num < 1000000000) {
        return int_to_text($num / 1000000) . ' Million' . ($num % 1000000 == 0 ? '' : ' ' . int_to_text($num % 1000000));
    } elseif ($num < 1000000000000) {
        return int_to_text($num / 1000000000) . ' Billion' . ($num % 1000000000 == 0 ? '' : ' ' . int_to_text($num % 1000000000));
    } else {
        return 'Number out of range';
    }
}

function number_to_words($number)
{
    $integer_part = floor($number);
    $fractional_part = round(($number - $integer_part) * 100); // Handle cents

    $integer_text = int_to_text($integer_part);
    $fractional_text = int_to_text($fractional_part);

    if ($fractional_part > 0) {
        return $integer_text . ' and ' . $fractional_text . ' Cents';
    } else {
        return $integer_text;
    }
}

if (isset($_POST['update'])) {
    $tot = 0;
    $ttnvt = 0;
    $vat = 0;
    $advance = 0;
    $remainder = 0;
    $total_remainder = 0;
    $total_advance = 0;


    $counterFile = 'counter.txt';
    if (file_exists($counterFile)) {
        // Read the current counter value
        $counter = file_get_contents($counterFile);
        // Increment the counter by 1
        $counter++;
    } else {
        // If the file doesn't exist, start the counter at 1
        $counter = 1;
    }

    // Save the new counter value back to the file
    file_put_contents($counterFile, $counter);

    // Create the unique reference number
    $referenceNumber = str_pad($counter, 6, '0', STR_PAD_LEFT);

    // Use $referenceNumber as needed


    $ref = "EDP/" . $referenceNumber;

    $html = '';
    foreach ($_POST['update'] as $update_id) {
        $sql = "SELECT * FROM deliver WHERE generate_id = $update_id AND types != 'design'";
        $result = mysqli_query($con, $sql);
        $row = mysqli_fetch_assoc($result);
        if ($row == null)
            continue;
        $customer = $row['customer'];
        $size = $row['size'];
        $unit_price = $row['unit_price'];
        $quantity = $row['quantity'];
        $total_price = $row['total_price'];
        $job_desc = $row['job_description'];
        $advance = $row['advance'];
        $remainder = $row['remainder'];
        $date = $row['created_at'];



        $ttnvt = $ttnvt + $total_price;
        $vat = $ttnvt * 0.15;
        $tot = $vat + $ttnvt;

        $total_remainder += $remainder;
        $total_advance += $advance;

        // $remainder=$tot-$advance;

        $html .= "
            <tr>
                <td class='tm_width_3'>$job_desc</td>
                <td class='tm_width_1'>$size</td>
                <td class='tm_width_1'>$unit_price</td>
                <td class='tm_width_1'>$quantity</td>
                <td class='tm_width_1'>$date</td>

                <td class='tm_width_1 tm_text_right'>" . number_format($total_price, 2) . "</td>
            </tr>
        ";
    }


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
        <title>Generated pdf</title>
        <link rel="stylesheet" href="assets/css/style.css">
    </head>

    <body>
        <div class="tm_container">
            <div class="tm_invoice_wrap">
                <div class="tm_invoice tm_style1" id="tm_download_section">
                    <div class="tm_invoice_in">
                        <div class="tm_invoice_head tm_align_center tm_mb20" style="margin-right: 60px;">
                            <div class="tm_invoice_left">
                                <div>
                                    <div class="tm_mb40"><img src="logo1.png" style="width:300px; height:100px; " alt="Logo"></div>
                                    <p class="tm_invoice_date tm_m0">To: <b class="tm_primary_color"><?= $customer ?></b></p>
                                </div>
                            </div>
                            <div class="tm_invoice_right tm_text_right">
                                <div class="tm_primary_color tm_f26 tm_text_uppercase">Delivery Form</div>


                                <br />
                                <br />


                                <div class="tm_invoice_info tm_mb20">
                                    <div class="tm_invoice_seperator">

                                    </div>
                                    <div class="tm_invoice_info_list">

                                        <?php
                                        foreach ($_POST['update'] as $update_id) {
                                            $sql = "SELECT * FROM deliver WHERE generate_id = $update_id";
                                            $result = mysqli_query($con, $sql);
                                            $row = mysqli_fetch_assoc($result);
                                            if ($row == null)
                                                continue;
                                            $customer = $row['customer'];
                                        }
                                        ?>


                                        <p class="tm_invoice_date tm_m0">Ref. No.: <b class="tm_primary_color"><?= $ref  ?></b></p>

                                    </div>


                                </div>


                            </div>
                        </div>

                        <div class="tm_table tm_style1 tm_mb40">
                            <div class="tm_round_border">
                                <div class="tm_table_responsive">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th class="tm_width_4 tm_semi_bold tm_primary_color tm_gray_bg">Job Desc.
                                                </th>
                                                <th class="tm_width_1 tm_semi_bold tm_primary_color tm_gray_bg">Size</th>
                                                <th class="tm_width_1 tm_semi_bold tm_primary_color tm_gray_bg">Unit Price
                                                </th>
                                                <th class="tm_width_1 tm_semi_bold tm_primary_color tm_gray_bg">Quantity
                                                </th>
                                                <th class="tm_width_2 tm_semi_bold tm_primary_color tm_gray_bg">Date
                                                </th>

                                                <th class="tm_width_1 tm_semi_bold tm_primary_color tm_gray_bg tm_text_right">
                                                    Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?= $html ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?php
                        $design_html = '';
                        foreach ($_POST['update'] as $update_id) {
                            $sql = "SELECT * FROM remainder WHERE generate_id = $update_id AND types = 'design'";
                            $result = mysqli_query($con, $sql);
                            $row = mysqli_fetch_assoc($result);
                            if ($row) {
                                $customer = $row['customer'];
                                $size = $row['size'];
                                $unit_price = $row['unit_price'];
                                $quantity = $row['quantity'];
                                $total_price = $row['total_price'];
                                $job_desc = $row['job_description'];
                                $date = $row['created_at'];

                                $ttnvt = $ttnvt + $total_price;
                                $vat = $ttnvt * 0.15;
                                $tot = $vat + $ttnvt;

                                $design_html .= "
                        <tr>
                            <td class='tm_width_2'>$job_desc</td>
                            <td class='tm_width_1'>$size</td>
                            <td class='tm_width_1'>$unit_price</td>

                            <td class='tm_width_1'>$quantity</td>
                            <td class='tm_width_1'>$date</td>

                            <td class='tm_width_1 tm_text_right'>" . number_format($total_price, 2) . " </td>
                        </tr>
                    ";
                            }
                        }

                        if ($design_html != '') {
                            echo <<<EOF
                    <div class="tm_table tm_style1 tm_mb30">
                        <div class="tm_round_border">
                            <div class="tm_table_responsive">
                                <table>
                                    <thead>
                                        <tr>
                                            <th class="tm_width_2 tm_semi_bold tm_primary_color tm_gray_bg">Job Desc.</th>
                                            <th class="tm_width_1 tm_semi_bold tm_primary_color tm_gray_bg">Size</th>
                                            <th class="tm_width_2 tm_semi_bold tm_primary_color tm_gray_bg">Price per page</th>
                                            <th class="tm_width_2 tm_semi_bold tm_primary_color tm_gray_bg">No. of Pages</th>
                                            <th class="tm_width_1 tm_semi_bold tm_primary_color tm_gray_bg tm_text_right">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        $design_html
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    EOF;
                        }
                        ?>

                        <div class="tm_invoice_footer">
                            <div class="tm_left_footer">
                                <p class="tm_mb2">Amount in Words:<br><b class="tm_primary_color"><?= number_to_words($tot) ?></b></p>
                            </div>
                            <div class="tm_right_footer">
                                <table>
                                    <tbody>
                                        <tr>
                                            <td class="tm_width_3 tm_primary_color tm_border_none tm_bold">Total</td>
                                            <td class="tm_width_3 tm_primary_color tm_text_right tm_border_none tm_bold">
                                                <?= number_format($ttnvt, 2) ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="tm_width_3 tm_primary_color tm_border_none tm_pt0">VAT </td>
                                            <td class="tm_width_3 tm_primary_color tm_text_right tm_border_none tm_pt0">
                                                +<?= number_format($vat, 2) ?></td>
                                        </tr>
                                        <tr class="tm_border_top tm_border_bottom">
                                            <td class="tm_width_3 tm_border_top_0 tm_bold tm_f16 tm_primary_color">Grand
                                                Total </td>
                                            <td class="tm_width_3 tm_border_top_0 tm_bold tm_f16 tm_primary_color tm_text_right">
                                                <?= number_format($tot, 2) ?>
                                            </td>
                                        </tr>




                                        <tr class="tm_border_top tm_border_bottom">
                                            <td class="tm_width_3 tm_border_top_0 tm_bold tm_f16 tm_primary_color">Signiture
                                            </td>
                                            <td class="tm_width_3 tm_border_top_0 tm_bold tm_f16 tm_primary_color tm_text_right">

                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="tm_invoice_btns tm_hide_print">
                <a href="javascript:window.print()" class="tm_invoice_btn tm_color1">
                    <span class="tm_btn_icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512">
                            <path d="M384 368h24a40.12 40.12 0 0040-40V168a40.12 40.12 0 00-40-40H104a40.12 40.12 0 00-40 40v160a40.12 40.12 0 0040 40h24" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32" />
                            <rect x="128" y="240" width="256" height="208" rx="24.32" ry="24.32" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32" />
                            <path d="M384 128v-24a40.12 40.12 0 00-40-40H168a40.12 40.12 0 00-40 40v24" fill="none" stroke="currentColor" stroke-linejoin="round" stroke-width="32" />
                            <circle cx="392" cy="184" r="24" fill='currentColor' />
                        </svg>
                    </span>
                    <span class="tm_btn_text">Print</span>
                </a>
                <button id="tm_download_btn" class="tm_invoice_btn tm_color2">
                    <span class="tm_btn_icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="ionicon" viewBox="0 0 512 512">
                            <path d="M320 336h76c55 0 100-21.21 100-75.6s-53-73.47-96-75.6C391.11 99.74 329 48 256 48c-69 0-113.44 45.79-128 91.2-60 5.7-112 35.88-112 98.4S70 336 136 336h56M192 400.1l64 63.9 64-63.9M256 224v224.03" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="32" />
                        </svg>
                    </span>
                    <span class="tm_btn_text">Download</span>
                </button>
            </div>
        </div>
        </div>
        <script src="assets/js/jquery.min.js"></script>
        <script src="assets/js/jspdf.min.js"></script>
        <script src="assets/js/html2canvas.min.js"></script>
        <script src="assets/js/main.js"></script>
    </body>

    </html>

    <?php
    foreach ($_POST['update'] as $update_id) {
        $remove_verify = "DELETE FROM `deliver` WHERE generate_id = $update_id";
        $result_remove = mysqli_query($con, $remove_verify);
    }
} else {
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
        <div class="tm_container">
            Please submit a form.
        </div>
    </body>

    </html>

<?php }
