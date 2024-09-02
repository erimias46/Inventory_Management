<?php
require_once('tcpdf.php');
include '../db.php';
$current_date = date('Y-m-d');

function int_to_text($num) {
    $ones = array('Zero', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine');
    $tens = array('Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen');
    $twenties = array('Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety');

    if ($num < 10) {
        return $ones[$num];
    } elseif ($num < 20) {
        return $tens[$num - 10];
    } elseif ($num < 100) {
        return $twenties[(int)($num / 10) - 2] . ($num % 10 == 0 ? '' : ' ' . $ones[$num % 10]);
    } elseif ($num < 1000) {
        return $ones[(int)($num / 100)] . ' Hundred' . ($num % 100 == 0 ? '' : ' and ' . int_to_text($num % 100));
    } elseif ($num < 1000000) {
        return int_to_text((int)($num / 1000)) . ' Thousand' . ($num % 1000 == 0 ? '' : ' ' . int_to_text($num % 1000));
    } elseif ($num < 1000000000) {
        return int_to_text((int)($num / 1000000)) . ' Million' . ($num % 1000000 == 0 ? '' : ' ' . int_to_text($num % 1000000));
    } elseif ($num < 1000000000000) {
        return int_to_text((int)($num / 1000000000)) . ' Billion' . ($num % 1000000000 == 0 ? '' : ' ' . int_to_text($num % 1000000000));
    } elseif ($num < 1000000000000000) {
        return int_to_text((int)($num / 1000000000000)) . ' Trillion' . ($num % 1000000000000 == 0 ? '' : ' ' . int_to_text($num % 1000000000000));
    } else {
        return 'Number out of range';
    }
}


// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetMargins(3, 3, 3);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);
// $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->setFontSubsetting(true);
$fontname = TCPDF_FONTS::addTTFfont('ubuntu.ttf', 'TrueTypeUnicode', '', 96);
$fontbold = TCPDF_FONTS::addTTFfont('ubuntuB.ttf', 'TrueTypeUnicode', '', 96);
$pdf->SetFont($fontname, '', 10);
$pdf->AddPage();

if (isset($_POST['update'])) {
    $html = 
        '
            <style>
                table, tr, td {
                    padding: 10px;
                }
            </style>
            <table style="background-color: #fff; color: #fff">
                <tbody>
                    <thead>
                        <tr>
                            <td><img src="Untitled-2-01.png"/></td>
                        </tr>
                    </thead>
                </tbody>
            </table>
        ';
    $html .= '
            <table>
                <tbody>
                    <thead>
                        <tr>
                            <td align="right">
                                Date:'.date('d-m-Y').'
                            </td>
                        </tr>
                        <tr>
                            <td align="right">
                                Ref.No:'."1258".'
                            </td>
                        </tr>
                        <tr>
                            <td align="left">
                                <h1 style="color: #FF0000">Proforma Invoice</h1>
                            </td>
                        </tr>
                        <tr style="font-weight:bold">
                            <td align="left" >';
                                foreach ($_POST['update'] as $update_id) {
                                    $sql = "SELECT * FROM generate WHERE generate_id = $update_id";
                                    $result = mysqli_query($con, $sql);
                                    $row = mysqli_fetch_assoc($result);
                                    $customer = $row['customer'];
                                }
    $html .= '
                                To : '.$customer.'
                            </td>
                        </tr>	           
                    </thead>
                </tbody>
            </table>
        ';
    $html .= '
            <div class="box-body table-responsive no-padding">
                <table>
                    <thead>
                        <tr style="font-weight:bold; padding: 1px;">
                            <th style="width:55%">Job Description</th>
                            <th style="width:10%">Size</th>
                            <th style="width:10%">Unit Price</th>
                            <th style="width:10%">Quantity</th>
                            <th style="width:20%">Total</th>
                        </tr>
                    </thead>
                <tbody>
            <thead>
        ';

        $tot = 0;
        $ttnvt =0;
        $vat =0;

        foreach ($_POST['update'] as $update_id) {
            $sql = "SELECT * FROM generate WHERE generate_id = $update_id AND types != 'design'";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($result);
            $customer = $row['customer'];
            $job_description = $row['job_description'];
            $size = $row['size'];
            $unit_price = $row['unit_price'];
            $quantity = $row['quantity'];
            $total_price = $row['total_price'];

            $ttnvt = $ttnvt + $total_price;
            $vat = $ttnvt * 0.15;
            $tot = $vat + $ttnvt;

            $html .= '
                <tr>
                    <td style="border-bottom: 1px solid #222; width:55%">'.$job_description.'</td>
                    <td style="border-bottom: 1px solid #222; width:10%">'.$size.'</td>
                    <td style="border-bottom: 1px solid #222; width:10%">'.$unit_price.'</td>
                    <td style="border-bottom: 1px solid #222; width:10%">'.$quantity.'</td>
                    <td style="border-bottom: 1px solid #222; width:20%">'.$total_price.'</td>
                </tr>
            ';
        }

    $html .='
            </thead>
        </tbody>
    </table>
    ';


    $design_html = '';
    foreach ($_POST['update'] as $update_id) {
        $sql = "SELECT * FROM generate WHERE generate_id = $update_id AND types = 'design'";
        $result = mysqli_query($con, $sql);
        $row = mysqli_fetch_assoc($result);
        if ($row) {
            $customer = $row['customer'];
            $size = $row['size'];
            $unit_price = $row['unit_price'];
            $quantity = $row['quantity'];
            $total_price = $row['total_price'];
            
            $ttnvt = $ttnvt + $total_price;
            $vat = $ttnvt * 0.15;
            $tot = $vat + $ttnvt;
            
            $design_html .= '
            <tr>
                <td style="border-bottom: 1px solid #222; width:55%">'.$customer.'</td>
                <td style="border-bottom: 1px solid #222; width:10%">'.$size.'</td>
                <td style="border-bottom: 1px solid #222; width:10%">'.$unit_price.'</td>
                <td style="border-bottom: 1px solid #222; width:10%">'.$quantity.'</td>
                <td style="border-bottom: 1px solid #222; width:20%">'.$total_price.'</td>
            </tr>
            ';
        }
    }

    if ($design_html != '') {
        $html .= <<<EOF
                <div class="box-body table-responsive no-padding">
                    <table>
                        <thead>
                            <tr style="font-weight:bold; padding: 1px;">
                                <th style="width:55%">Job Description</th>
                                <th style="width:10%">Size</th>
                                <th style="width:10%">Price per page</th>
                                <th style="width:10%">No. of Pages</th>
                                <th style="width:20%">Total</th>
                            </tr>
                        </thead>
                    <tbody>
                        $design_html
                    </tbody>
                </table>
            EOF;
    }

    $html .= '
            <table>
                <tbody>
                    <thead>                    
                        <tr>
                            <td align="right" colspan="5"><strong>Total : '.number_format($ttnvt, 2).' </strong></td>
                        </tr>
                        <tr>
                            <td align="right" colspan="5"><strong>Vat : '.number_format($vat, 2).' </strong></td>
                        </tr>
                        <tr>
                            <td align="right" colspan="5"><strong>Total Inc : '.number_format($tot, 2).' </strong></td>
                        </tr>
                        <tr>
                            <td align="left" colspan="10">NB  :    '.int_to_text($tot).' Birr</td>
                        </tr>
                    </thead>
                </tbody>
            </table>
        </div>
        ';

    $time = time();
    $pdf_name = "$time.pdf";
    $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
    ob_end_flush();
    $pdf->Output(dirname(__FILE__).'/invoice/'.$pdf_name.'', 'F');

    if ($pdf) {
        foreach ($_POST['update'] as $update_id) {
            $remove_verify = "DELETE FROM `generate` WHERE generate_id = $update_id";
            $result_remove = mysqli_query($con, $remove_verify);
            if ($result_remove) {
                echo "<script>window.location.href='../../pages/generate.php?status=success&file=$pdf_name';</script>";
            } else {
                echo "<script>window.location.href='../../pages/generate.php?status=error';</script>";
            }
        }
    } else {
        echo "<script>window.location.href='../../pages/generate.php?status=error';</script>";
    }
} else if (isset($_POST['generate_performa'])) {
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
        <table style="background-color: #fff; color: #000; padding: 20px; margin-right: 10px; width: 100%">
            <tbody>
                <thead>
                    <tr>
                        <td rowspan="2" colspan="2">
                            <img src="logo.jpg" width="300px"/> 
                        </td>
                        <td style="font-size: 32px; font-weight: 200; text-align: right">
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
                        <td style="border-right: 1px solid dodgerblue;">
                            <span style="font-weight: 200">VAT REGISTRATION NO.</span><br>
                            $vat_reg
                        </td>
                        <td style="border-right: 1px solid dodgerblue; ">
                            <span style="font-weight: 200">TIN NUMBER</span><br>
                            $tin
                        </td>
                        <td style="">
                            <span style="font-weight: 200">PAYMENT TO</span><br>
                            $payment_to
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

    $time = time();
    $pdf_name = "performa_$time.pdf";
    $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
    // $pdf->writeHTML($html, true, false, true, false, '');
    ob_end_flush();
    $file = dirname(__FILE__).'/invoice/'.$pdf_name.'';
    $pdf->Output($file, 'F');
    header('Content-type: application/pdf'); 
    header('Content-Disposition: inline; filename="' . $file . '"'); 
    header('Content-Transfer-Encoding: binary'); 
    header('Accept-Ranges: bytes'); 
    header("Content-Length: " . filesize($file));
    readfile($file);
} else {
    echo "<script>window.location.href='../../pages/generate.php';</script>";
}

?>