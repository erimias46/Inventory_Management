<?php
$redirect_link = "../";
$side_link = "../";
include $redirect_link . 'include/db.php';
$current_date = date('Y-m-d');
?>

<div class="page-wrapper">
    <div class="container-fluid">
        <div class="content-wrapper">
            <section class="content">
                <div class="row">
                    <div class="col-lg-6">
                        <h4 class="card-title">
                            Job Status </h4>
                    </div>
                    <div class="col-lg-6">
                        <div class="float-end">
                            <a class="btn btn-success btn-rounded" href="/pages/export.php?type=payment"><i
                                    class="fas fa-upload"></i> Export</a>
                        </div>
                    </div>
                </div>
                <br>

                <div class="row">
                    <form method="GET" class="d-flex align-items-center justify-content-center">
                        <div class="me-3"> Choose Status </div>
                        <select name="status" class="form-select bg-white" style="max-width: 15ch"
                            onchange="form.submit()">
                            <option value="">All</option>
                            <?php
                                    $result = mysqli_query($con, "SELECT DISTINCT(status) FROM payment WHERE status != ''");
                                    while($row = mysqli_fetch_assoc($result)) {
                                ?>
                            <option value="<?= $row['status'] ?>"
                                <?= $_GET['status'] == $row['status'] ? 'selected':'' ?>><?= $row['status'] ?></option>
                            <?php } ?>
                        </select>
                    </form>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <div class="table-responsive">
                                    <table id="zero_config"
                                        class="table border table-striped table-bordered text-nowrap"
                                        style="width:100%">
                                        <thead>
                                            <tr>
                                                <td><input type="checkbox" name="checkAll" id="checkAll"></td>
                                                <th>ID</th>
                                                <th>Job ID</th>
                                                <th>Customer Name</th>
                                                <th>Job Description</th>
                                                <th>Total</th>
                                                <th>Status</th>
                                                <th>Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $filter_status = empty($_GET['status']) ? '': "WHERE status = '{$_GET['status']}'";
                                                $sql = "SELECT * FROM payment $filter_status ORDER BY payment_id DESC";
                                                $result = mysqli_query($con, $sql);
                                                while ($row = mysqli_fetch_assoc($result)) {
                                                ?>
                                            <tr>
                                                <td><input class="box" type="checkbox" name="update[]"
                                                        value="<?php echo $row['payment_id']; ?>"></td>
                                                <td><?php echo $row['payment_id'] ?></td>
                                                <td><?php echo $row['job_number'] ?></td>
                                                <td><?php echo $row['client'] ?></td>
                                                <td><?php echo $row['job_description'] ?></td>
                                                <td><?php echo $row['total'] ?></td>
                                                <td><?php echo $row['status'] ?></td>
                                                <td><?php echo $row['date'] ?></td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button class="btn btn-outline-secondary dropdown-toggle"
                                                            type="button" data-bs-toggle="dropdown"
                                                            aria-expanded="false">
                                                            Action
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li><a class="dropdown-item"
                                                                    href="status1.php?id=<?php echo $row['payment_id'] ?>&data=start">Start</a>
                                                            </li>
                                                            <li><a class="dropdown-item"
                                                                    href="status1.php?id=<?php echo $row['payment_id'] ?>&data=progress">On
                                                                    Progress</a></li>
                                                            <li><a class="dropdown-item"
                                                                    href="status1.php?id=<?php echo $row['payment_id'] ?>&data=complete">Completed</a>
                                                            </li>
                                                            <li><a class="dropdown-item"
                                                                    href="status1.php?id=<?php echo $row['payment_id'] ?>&data=delivered">Delivered</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php
                                                }
                                                ?>
                                        </tbody>
                                    </table>
                                </div>
                                <button class="btn btn-success m-2" name="generate" id="generate">Generate</button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#generate').click(function(e) {
        e.preventDefault();
        const checkboxes = $('table#zero_config').find('[type="checkbox"]:checked.box')
        const update = $.map(checkboxes, c => c.value)
        $.post("", {
                generate: '',
                update
            },
            function(data, status) {
                console.log(data, status)
                if (status == 'success') swal("Great!", 'successfully generated', "success");
                else swal("Oops!", 'unknown error occurred', "error");
                checkboxes.prop('checked', false)
            }
        );
    });
});
</script>

<?php
include '../include/footer.php';

if (isset($_POST['generate']) && isset($_POST['update'])) {
    foreach ($_POST['update'] as $check0) {
        $sql = "SELECT * FROM payment WHERE payment_id = '$check0'";
        $result = mysqli_query($con, $sql);
        $row = mysqli_fetch_assoc($result);
        $client = $row['client'];
        $job_description = $row['job_description'];
        $size = $row['size'];
        $total = $row['total'];
        $quantity = $row['quantity'];
        $unit_price = $row['unit_price'];

        $generate_insert = "INSERT INTO generate(customer, job_description, size, quantity, total_price, unit_price, price_vat, types) 
            VALUES ('$client', '$job_description', '$size', '$quantity', '$total', '$unit_price', 'Test', 'Test')";
        $result_generate = mysqli_query($con, $generate_insert);
        if ($result_generate) {
            http_response_code(200);
        } else {
            http_response_code(500);
        }
    }
} else {
    http_response_code(400);
    echo "update must not be empty";
}

?>