<?php
$redirect_link = "../../";
$side_link = "../../";
include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';

$id = $_GET['id'];
$type = $_GET['type'];

// Query to get the JSON data from the `brocher_group` table
$sql = "SELECT * FROM brocher_group WHERE id = '$id' AND type = '$type'";
$result = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($result);

$id_b=$row['id'];

$data = json_decode($row['data'], true); // Decode the JSON to get the array of IDs
?>
<!DOCTYPE html>
<html>

<head>
    <?php include $redirect_link . 'partials/title-meta.php'; ?>
    <?php include $redirect_link . 'partials/head-css.php'; ?>
</head>

<body>
    <!-- Begin page -->
    <div class="flex wrapper">
        <?php include $redirect_link . 'partials/menu.php'; ?>
        <div class="page-content">

            <?php include $redirect_link . 'partials/topbar.php'; ?>
            <main class="flex-grow p-6">

                <div class="card">
                    <div class="card mt-3">
                        <div class="p-6">
                            <div class="overflow-x-auto">
                                <div class="min-w-full inline-block align-middle">
                                    <div class="overflow-hidden">
                                        <table id="zero_config" class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                            <thead>
                                                <tr>
                                                    <th><input type="checkbox" id="select-all"></th>
                                                    <th>#</th>
                                                    <th>ID</th>
                                                    <?php
                                                    // Get the columns from the specified type table
                                                    $columns_query = "SHOW COLUMNS FROM $type";
                                                    $columns_result = mysqli_query($con, $columns_query);
                                                    while ($column = mysqli_fetch_assoc($columns_result)) {
                                                        // Change snake_case to Capitalized string for column names
                                                        $field_name = ucfirst(str_replace('_', ' ', $column['Field']));
                                                    ?>
                                                        <th class="p-2.5 text-left text-xs font-medium text-gray-500 uppercase">
                                                            <?= $field_name ?>
                                                        </th>
                                                    <?php } ?>
                                                    <th>Action</th> <!-- New column for the delete button -->
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $i = 0;
                                                foreach ($data as $item_id) {
                                                    $i++;
                                                    if ($type == 'design') {
                                                        $primary_key = 'digital_id';
                                                    } else {
                                                        $primary_key = $type . '_id';
                                                    }

                                                    // Query the specific type's table to get the details
                                                    $item_sql = "SELECT * FROM $type WHERE $primary_key = '$item_id'";
                                                    $item_result = mysqli_query($con, $item_sql);
                                                    $item_row = mysqli_fetch_assoc($item_result);
                                                ?>
                                                    <tr class="odd:bg-white even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800">
                                                        <td><input type="checkbox" class="item-checkbox" data-item-id="<?php echo $item_id; ?>"></td>
                                                        <td><?php echo $i; ?></td>
                                                        <td><?php echo $item_id; ?></td>
                                                        <?php
                                                        foreach ($item_row as $value) {
                                                        ?>
                                                            <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                                <?= $value ?>
                                                            </td>
                                                        <?php
                                                        }
                                                        ?>
                                                        <td>
                                                            <button class="delete-btn btn bg-danger/25 text-danger hover:bg-danger hover:text-white btn-sm rounded-full" data-item-id="<?php echo $item_id; ?>">Delete</button>
                                                        </td>
                                                    </tr>
                                                <?php
                                                }
                                                ?>
                                            </tbody>

                                        </table>
                                    </div>
                                </div>
                            </div>
                            <button id="generate-btn" class="btn bg-success/25 text-success hover:bg-primary hover:text-white btn-sm rounded-full">Generate</button>
                        </div>
                    </div>
                </div>

                <?php include $redirect_link . 'partials/footer.php'; ?>
            </main>
        </div>
    </div>

    <?php include $redirect_link . 'partials/customizer.php'; ?>
    <?php include $redirect_link . 'partials/footer-scripts.php'; ?>
</body>

</html>


<script>
    document.querySelectorAll('.delete-btn').forEach(function(button) {
        button.addEventListener('click', function() {
            const itemId = this.dataset.itemId;

            if (confirm('Are you sure you want to delete this item?')) {
                // Debug: Log the item ID to be deleted
                console.log("Deleting item with ID:", itemId);

                fetch('delete_item.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            id: "<?php echo $id_b; ?>",
                            type: "<?php echo $type; ?>",
                            item_id: itemId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log("Server response:", data); // Debug: Log the server response
                        if (data.success) {
                            alert('Item deleted successfully!');
                            location.reload(); // Reload the page to update the table
                        } else {
                            alert('Failed to delete item: ' + data.error);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error); // Debug: Log any errors
                    });
            }
        });
    });


    document.getElementById('generate-btn').addEventListener('click', function() {
        let selectedItems = [];
        document.querySelectorAll('.item-checkbox:checked').forEach(function(checkbox) {
            selectedItems.push(checkbox.dataset.itemId);
        });

        if (selectedItems.length > 0) {
            // Send the selected IDs and any other necessary data to the server
            fetch('group_generate.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        ids: selectedItems,
                        type: "<?php echo $type; ?>"
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Data generated successfully!');
                    } else {
                        alert('Failed to generate data: ' + data.error);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        } else {
            alert('No items selected!');
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const rows = document.querySelectorAll('tr[data-href]');
        rows.forEach(row => {
            row.addEventListener('click', function(e) {
                if (!e.target.closest('#del-btn')) {
                    window.location.href = row.dataset.href;
                }
            });
        });
    });
</script>