<?php

if(isset($_POST['num1']) && isset($_POST['num2'])) {
    $num1 = $_POST['num1'];
    $num2 = $_POST['num2'];
    $result = $num1 * $num2;
    echo "Total Price : $result";
}

?>