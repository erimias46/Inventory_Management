<?php
$redirect_link = "../../../";
$side_link = "../../../";
include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';

// Enable error reporting for debugging
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['size_type'])) {
    $sizeType = intval($_POST['size_type']);

    $sql = "SELECT * FROM jeansdb WHERE type = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $sizeType);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "<div>No sizes found for the selected type.</div>";
        exit;
    }

    while ($row = $result->fetch_assoc()) {
        $size = htmlspecialchars($row['size']);
        $sizeId = htmlspecialchars($row['id']);
        echo "
            <div class='flex items-center mb-2 justify-around'>
                <label class='text-gray-800 text-sm font-medium flex-1'>$size</label>
                <input type='hidden' name='size_ids[]' value='$sizeId'>
                <input type='hidden' name='sizes[]' value='$size'>
                <input type='number' min='0' name='quantities[]' value='0' step='1' 
                       class='form-input flex-1 ml-4 border border-gray-300 p-2 rounded-md text-gray-800 quantity-input' 
                       placeholder='Quantity for size $size'>
            </div>
        ";
    }

    echo "
        <div class='mt-4'>
            <label class='text-gray-800 text-sm font-medium'>Total:</label>
            <span id='total-quantity' class='text-black-800 text-sm font-bold'>0</span>
        </div>
    ";
} else {
    echo "<div>Invalid request.</div>";
}
?>
