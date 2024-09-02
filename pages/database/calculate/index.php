<?php
if (isset($_POST['unitPrice'], $_POST['qty'], $_POST['advance'], $_POST['vat'])) {
    $unitPrice = $_POST['unitPrice'];
    $qty = $_POST['qty'];
    $advance = $_POST['advance'];
    $vat = $_POST['vat'];

    $total = $unitPrice * $qty;
    $total_vat = $total * (1 + $vat / 100);
    $remedial = $total_vat- $advance;
   // $remedial_vat = $remedial * (1 + $vat / 100);
    echo json_encode(array('total' => number_format($total_vat, 2), 'remaining' => number_format($remedial, 2)));
}

?>