<?php
$redirect_link = "../../../";
$side_link = "../../../";
include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';



if (isset($_POST['size_type'])) {
    $sizeType = $_POST['size_type'];
    $sql = "SELECT * FROM jeansdb WHERE type = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $sizeType);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $size = $row['size'];
        $sizeId = $row['id'];
        echo "
            <div class='flex items-center mb-2 justify-around'>
                <label class='text-gray-800 text-sm font-medium flex-1'>$size</label>
                <input type='hidden' name='size_ids[]' value='$sizeId'>
                <input type='hidden' name='sizes[]' value='$size'>
                <input type='number' min='0' name='quantities[]' value='0' step='1' class='form-input flex-1 ml-4 border border-gray-300 p-2 rounded-md text-gray-800' placeholder='Quantity for size $size'>
            </div>
        ";
    }
}

?>