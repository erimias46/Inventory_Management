<?php

$current_date = date('Y-m-d');
include('../db.php');

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
  } else {
    return 'Number out of range';
  }
}


function number_to_words($number)
{
  $integer_part = floor($number);
  $fractional_part = round(($number - $integer_part) * 100);

  $integer_text = int_to_text($integer_part);
  $fractional_text = $fractional_part > 0 ? ' and ' . int_to_text($fractional_part) . ' Cents' : '';

  return $integer_text . $fractional_text;
}


if (isset($_POST['generate_performa'])) {
  $vat_reg = $_POST['vat_reg'];
  $tin = $_POST['tin'];
  $invoice_to = $_POST['invoice_to'];
  $client = $_POST['client'];
  //$quote_number = $_POST['quote_number'];
  $quote_sender = $_POST['quote_sender'];
  $requested_by = $_POST['requested_by'];
  $phone = $_POST['phone'];
  $item_name = $_POST['item_name'];
  $item_qty = $_POST['item_qty'];
  $item_price = $_POST['item_price'];
  $vat = $_POST['vat'];
  $valid = $_POST['valid'];

  $date = date('Y-m-d');

  $items_html = '';
  $total_price = 0;



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

  for ($i = 0; $i < count($item_name); $i++) {
    $total = $item_qty[$i] * $item_price[$i];
    $total_price += $total;
    $items_html .= "
            <tr>
                <td class='tm_width_3'>
                    {$item_name[$i]}
                </td>
                 <td class='tm_width_1'>{$item_price[$i]}</td>
                <td class='tm_width_2'>{$item_qty[$i]}</td>
               
                <td class='tm_width_2 tm_text_right'>$total</td>
            </tr>
        ";
  }





  $total_vat = $vat / 100 * $total_price;
  $grand_total = $total_price + $total_vat;
  $total_in_words = number_to_words($grand_total);



  for ($i = 0; $i < count($item_name); $i++) {
    $total = $item_qty[$i] * $item_price[$i];
    $total_price += $total;

    $items[] = [
      'name' => $item_name[$i],
      'quantity' => $item_qty[$i],
      'price' => $item_price[$i],
      'total' => $total
    ];
  }

  $items_json = json_encode($items);



  $sql = "INSERT INTO price_quote_log (vat_number, tin_number, customer, quote_sender, requested_by, phone_no, items, ref, vat, valid, grand_total,address) 
        VALUES ('$vat_reg', '$tin', '$client', '$quote_sender', '$requested_by', '$phone', '$items_json', '$ref', '$vat', '$valid', '$grand_total', '$invoice_to')";
  $result = mysqli_query($con, $sql);




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
    <title><?php echo $client . '_ ' . $date; ?> </title>
    <link rel="stylesheet" href="assets/css/style.css">
  </head>

  <body>
    <div class="tm_container">
      <div class="tm_invoice_wrap">
        <div class="tm_invoice tm_style1" id="tm_download_section">
          <div class="tm_invoice_in">
            <div class="tm_invoice_head tm_align_center tm_mb20">
              <div class="tm_invoice_left">
                <div><img src="logo1.png" alt="Logo" style="width:300px; height:100px"></div>
              </div>
              <div class="tm_invoice_right tm_text_right">
                <div class="tm_primary_color tm_f40 tm_text_uppercase">Price Quote</div>

                <div class="tm_invoice_info tm_mb20">
                  <div class="tm_invoice_seperator"></div>
                  <div class="tm_invoice_info_list" style="margin-top: 50px;  ">
                    <p class="tm_invoice_number tm_m0">VAT Registration No: <b class="tm_primary_color"><?= $vat_reg ?></b></p>
                    <p class="tm_invoice_date tm_m0">Tin No.: <b class="tm_primary_color"><?= $tin ?></b></p>

                  </div>
                </div>
              </div>
            </div>
            <div class="tm_invoice_head tm_mb10">
              <div class="tm_invoice_left">
                <p class="tm_mb2"><b class="tm_primary_color">Invoice To: <?= $client ?></b></p>
                <p>
                  Address: <b><?= $invoice_to ?></b><br>

                  Requested By: <b><?= $requested_by ?></b><br>
                  Delivery Date: <b>As per ToR</b>

                </p>
              </div>
              <div class="tm_invoice_right tm_text_right">

                <p>
                  Date: <b><?= $current_date ?></b><br>
                  Quote No.: <b><?= $ref ?></b><br>
                  Quote Sender: <b><?= $quote_sender ?></b><br>

                  Phone: <b><?= $phone ?></b>
                </p>
              </div>
            </div>
            <div class="tm_table tm_style1 tm_mb30">
              <div class="tm_round_border">
                <div class="tm_table_responsive">
                  <table>
                    <thead>
                      <tr>
                        <th class="tm_width_4 tm_semi_bold tm_primary_color tm_gray_bg">Item</th>
                        <th class="tm_width_2 tm_semi_bold tm_primary_color tm_gray_bg">Unit Price
                        </th>
                        <th class="tm_width_1 tm_semi_bold tm_primary_color tm_gray_bg">Quantity
                        </th>
                        <th class="tm_width_2 tm_semi_bold tm_primary_color tm_gray_bg tm_text_right">
                          Total</th>
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
                  <p class="tm_mb2">Amount in Words:<br><b class="tm_primary_color"><?= $total_in_words ?></b></p>
                </div>
                <div class="tm_right_footer">
                  <table>
                    <tbody>
                      <tr>
                        <td class="tm_width_3 tm_primary_color tm_border_none tm_bold">Total</td>
                        <td class="tm_width_3 tm_primary_color tm_text_right tm_border_none tm_bold">
                          <?= $total_price ?>
                        </td>
                      </tr>
                      <tr>
                        <td class="tm_width_3 tm_primary_color tm_border_none tm_pt0">VAT <span class="tm_ternary_color">(<?= $vat ?>%)</span></td>
                        <td class="tm_width_3 tm_primary_color tm_text_right tm_border_none tm_pt0">
                          <?= $total_vat ?>
                        </td>
                      </tr>
                      <tr class="tm_border_top tm_border_bottom">
                        <td class="tm_width_3 tm_border_top_0 tm_bold tm_f16 tm_primary_color">Grand
                          Total </td>
                        <td class="tm_width_3 tm_border_top_0 tm_bold tm_f16 tm_primary_color tm_text_right">
                          <?= $grand_total ?>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="tm_padd_15_20 tm_round_border">
              <p class="tm_mb5"><b class="tm_primary_color">Terms & Conditions:</b></p>
              <ul class="tm_m0 tm_note_list">
                <li>Prices are subject to change as additional information is supplied. Changes to
                  <b>Specification</b> and <b>Quantity</b> will subject price to change. This estimate is
                  <b>valid for <?= $valid ?> days</b> from the date indicated.
                </li>
                <li>100%[<b>Br <?= $grand_total ?></b>] will be invoiced at project delivery</li>
              </ul>
            </div><!-- .tm_note -->
            <div style="margin-top: 10px; padding: 15px; " class="tm_round_border">
              <p style="margin-bottom: 0">Thank you for opportunity to submit this estimate. We look forward
                to working with you.</p>
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
