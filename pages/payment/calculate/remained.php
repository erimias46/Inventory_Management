<?php

if(isset($_POST['num1']) && isset($_POST['num2']) && isset($_POST['num3'])) {
    $num1 = $_POST['num1'];
    $num2 = $_POST['num2'];
    $num3 = $_POST['num3'];
    $sum = $num1 * $num2;
    $result = $sum - $num3;
    echo "Remedial : $result";
}

?>