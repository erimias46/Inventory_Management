<?php

if(isset($_POST['num1']) && isset($_POST['num2'])) {
    $num1 = $_POST['num1'];
    $num2 = $_POST['num2'];
    $result = $num1 * $num2;
    $result_vat = $result * 1.15;
    echo "Total Price : $result_vat";
}

?>