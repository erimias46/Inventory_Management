<?php

$current_date = date('Y-m-d');

include('../db.php');




if (isset($_POST['generate_performa'])) {
    $to = $_POST['to'];
    $subject = $_POST['subject'];
    $from = $_POST['from'];
    $emai_body = htmlspecialchars($_POST['email_body']);
    $email_body=$_POST['email_body'];
    $to_city = $_POST['to_city'];
    $to_comp_add = $_POST['to_comp_add'];


    if (empty($_POST['salut'])) {
        $salut = "Best Regards,";
    } else {
        $salut = $_POST['salut'];
    }





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


    $ref="EDP/".$referenceNumber;


    $sql = "INSERT INTO email_log (`to`, `subject`, `email_body`, `from`, `to_city`, `to_comp_add`, `salut`, `ref`) 
VALUES ('$to', '$subject', '$email_body', '$from', '$to_city', '$to_comp_add', '$salut', '$ref')";
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
        <title>Email</title>
        <link rel="stylesheet" href="assets/css/style.css">
        <style>
            .header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                width: 100%;
            }

            .header img {
                max-width: 100%;
                height: auto;
            }

            .header .date {
                margin-left: auto;
                font-size: 1em;
                font-weight: bold;
            }

            .aligned-text {
                text-align: justify;
                margin-bottom: 10px;
            }
        </style>
    </head>

    <body>
        <div class="tm_container">
            <div class="tm_invoice_wrap">
                <div class="tm_invoice tm_style1" id="tm_download_section">
                    <div class="tm_invoice_in">
                        <div class="tm_invoice_head">
                            <div class="header">
                                <img src="logo1.png" width="300px" height="100px" alt="Logo">
                                <div class="date">
                                    <div class="tm_invoice_info_list " style="margin-top: 90px;  ">
                                        <p class="tm_invoice_number tm_m0"><b class="tm_primary_color">Date: </b><b><?= $current_date ?> </b></p>
                                        <p class="tm_invoice_date tm_m0 "><b class="tm_primary_color">Ref.No.</b>EDP/<?= $referenceNumber ?></p>

                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                    <div class="tm_invoice_info tm_mb20">
                        <div class="tm_invoice_seperator"></div>

                    </div>
                    <div class="tm_invoice_head  tm_mb20">
                        <div class="tm_invoice_left">

                            <p>
                                <br>
                                To: <?= $to ?>
                                <br />

                                <?= $to_comp_add ?>
                                <br />
                                <?= $to_city ?>

                            </p>
                        </div>
                        <div class="tm_invoice_right tm_text_right">








                        </div>
                    </div>

                    <p class="tm_mb2"><b class="tm_primary_color"> Subject: <?= $subject ?></b></p>

                    <div class="tm_padd_5_20 aligned-text">
                        <!-- <p class="tm_mb5"><b class="tm_primary_color"><?= $subject ?></b></p> -->

                        <p><?= nl2br(rtrim($emai_body, '=')); ?></p>

                    </div><!-- .tm_note -->
                    <div class="tm_text_right" style="margin-top: 10px;  ">
                        <p style="margin-bottom: 0"><?php echo $salut ?> <br /> <?= $from ?></p>
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
        <title>Email</title>
        <link rel="stylesheet" href="assets/css/style.css">
    </head>

    <body>
        <div class="tm_container">
            Please submit a form.
        </div>
    </body>

    </html>

<?php }
