<?php
$redirect_link = "../../";
$side_link = "../../";

include $redirect_link . 'partials/main.php';
include_once $redirect_link . 'include/db.php';
$current_date = date('Y-m-d');

$initial_directory = 'files/';
if (isset($_GET['file'])) {
    $files_directory = $_GET['file'] . "/";
} else {
    $files_directory = 'files/';
}
$current_directory = $files_directory;
$results = glob(str_replace(['[', ']', "\f[", "\f]"], ["\f[", "\f]", '[[]', '[]]'], ($current_directory ? $current_directory : $initial_directory)) . '*');
$directory_first = true;
if ($directory_first) {
    usort($results, function ($a, $b) {
        $a_is_dir = is_dir($a);
        $b_is_dir = is_dir($b);
        if ($a_is_dir === $b_is_dir) {
            return strnatcasecmp($a, $b);
        } else if ($a_is_dir && !$b_is_dir) {
            return -1;
        } else if (!$a_is_dir && $b_is_dir) {
            return 1;
        }
    });
}
function convert_filesize($bytes, $precision = 2)
{
    $units = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);
    return round($bytes, $precision) . ' ' . $units[$pow];
}
function get_filetype_icon($filetype)
{
    if (is_dir($filetype)) {
        return '<i class="mgc_folder_3_line text-lg me-3"></i>';
    } else if (preg_match('/image\/*/', mime_content_type($filetype))) {
        return '<i class="mgc_pic_line text-lg me-3"></i>';
    } else if (preg_match('/video\/*/', mime_content_type($filetype))) {
        return '<i class="mgc_video_line text-lg me-3"></i>';
    } else if (preg_match('/audio\/*/', mime_content_type($filetype))) {
        return '<i class="mgc_audio_tape_line text-lg me-3"></i>';
    }
    return '<i class="mgc_file_line text-lg me-3"></i>';
}

?>

<head>
    <?php
    $title = 'File Manager';
    include $redirect_link . 'partials/title-meta.php'; ?>

    <?php include $redirect_link . 'partials/head-css.php'; ?>


    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Include jQuery and Select2 JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>

<body>

    <!-- Begin page -->
    <div class="flex wrapper">

        <?php include $redirect_link . 'partials/menu.php'; ?>

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="page-content">

            <?php include $redirect_link . 'partials/topbar.php'; ?>

            <main class="flex-grow p-6">
                <div class="card">
                    <div class="card-header">
                        <div class="flex justify-between items-center">
                            <h4 class="text-slate-900 dark:text-slate-200 text-lg font-medium"><?= ucfirst($title) ?></h4>
                            <button type="button" class="btn bg-info text-white" data-fc-type="modal" data-fc-target="upload">
                                <i class="mgc_upload_3_line text-base me-4"></i>
                                Upload File
                            </button>
                        </div>
                    </div>
                    
                    <div class="px-6 py-3">
                        <a class="text-lg hover:text-primary" href="index.php">
                            <?= $current_directory ?>
                        </a>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <div class="min-w-full inline-block align-middle">
                                <div class="overflow-hidden">
                                    <table id=" zero_config " class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead>
                                            <tr class="text-left">
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>File type</th>
                                                <th> Date</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php

                                            $sql="SELECT * FROM file";
                                            $result = mysqli_query($con, $sql);
                                            $number = 1;
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                $file_name = $row['file_name'];
                                                $extension = $row['extension'];
                                                $date = $row['date'];
                                                
                                            ?>
                                                <tr class="odd:bg-white text-left even:bg-gray-100 dark:odd:bg-slate-700 dark:even:bg-slate-800">
                                                    
                                                    <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?= $number++ ?>
                                                    </td>
                                                    <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?= $file_name ?>
                                                    </td>
                                                    <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?= $extension ?>
                                                    </td>
                                                    <td class="px-2 py-2.5 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-200">
                                                        <?= $date ?>
                                                    </td>
                                                    
                                                </tr>
                                            <?php


                                            }
                                           
                                            ?>

                                                
                                                <!-- Edit modal -->
                                                
                                            <?php ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <div id="upload" class="w-full h-full fixed top-0 left-0 z-50 transition-all duration-500 hidden">
                <div class="fc-modal-open:mt-7 fc-modal-open:opacity-100 fc-modal-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto bg-white border shadow-sm rounded-md dark:bg-slate-800 dark:border-gray-700">
                    <div class="flex justify-between items-center py-2.5 px-4 border-b dark:border-gray-700">
                        <h3 class="font-medium text-gray-800 dark:text-white text-lg">Upload File</h3>
                        <button class="inline-flex flex-shrink-0 justify-center items-center h-8 w-8 dark:text-gray-200" data-fc-dismiss type="button">
                            <span class="material-symbols-rounded">close</span>
                        </button>
                    </div>
                    <form action="index.php?directory=<?= $files_directory; ?>" method="POST" enctype="multipart/form-data" id="file-form">
                        <div class="px-4 py-8 overflow-y-auto">
                            <div class="mb-3">
                                <input type="file" name="file" id="file-upload" hidden />
                                <label for="file-upload" class="flex flex-row items-center px-3 py-2 rounded border dark:border-slate-700">
                                    <i class="mgc_upload_3_line me-3 text-xl"></i>
                                    <span id="file-label">Select File to Upload</span>
                                </label>
                            </div>
                            <div class="mb-3">
                                <label class="text-gray-800 text-sm font-medium inline-block mb-2">Job Number</label>
                                <select type="text" name="job_number" class="search-select" id="job_number" required>
                                    <?php
                                    $sql = "SELECT * FROM payment ORDER BY job_number DESC";
                                    $result = mysqli_query($con, $sql);
                                    while ($row = mysqli_fetch_assoc($result)) {
                                    ?>
                                        <option value="<?php echo $row['job_number'] ?>">
                                            <?php echo $row['job_number']; ?>
                                        </option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="text-gray-800 text-sm font-medium inline-block mb-2">Client</label>
                                <input type="text" id="client" name="client" class="form-input" required>
                            </div>
                            <div class="mb-3">
                                <label class="text-gray-800 text-sm font-medium inline-block mb-2">Date</label>
                                <input type="date" id="date" name="date" class="form-input" required>
                            </div>
                            <div class="mb-3">
                                <label class="text-gray-800 text-sm font-medium inline-block mb-2">Quantity</label>
                                <input type="text" id="quantity" name="quantity" class="form-input" required>
                            </div>
                            <div class="mb-3">
                                <label class="text-gray-800 text-sm font-medium inline-block mb-2">Employee</label>
                                <input type="text" id="employee" name="employee" class="form-input" required>
                            </div>

                            <div class="mb-3">
                                <label class="text-gray-800 text-sm font-medium inline-block mb-2">Size</label>
                                <input type="text" id="size" name="size" class="form-input" required>
                            </div>

                        </div>
                        <div class="flex justify-end items-center gap-4 p-4 border-t dark:border-slate-700">
                            <button class="py-2 px-5 inline-flex justify-center items-center gap-2 rounded dark:text-gray-200 border dark:border-slate-700 font-medium hover:bg-slate-100 hover:dark:bg-slate-700 transition-all" data-fc-dismiss type="button">Close</button>
                            <button name="upload" type="submit" class="btn bg-success text-white">Upload</button>
                        </div>
                    </form>
                </div>
            </div>

            <?php include $redirect_link . 'partials/footer.php'; ?>
        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->

    </div>

    <?php include $redirect_link . 'partials/customizer.php'; ?>

    <?php include $redirect_link . 'partials/footer-scripts.php'; ?>


    <script>
        $(document).ready(function() {
            $('#job_number').change(function() {
                var jobNumber = $(this).val();

                $.ajax({
                    url: 'fetch_job_details.php',
                    type: 'POST',
                    data: {
                        job_number: jobNumber
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status == 'success') {
                            $('#client').val(response.data.client);
                            $('#date').val(response.data.date);
                            $('#quantity').val(response.data.quantity);
                            $('#employee').val(response.data.employee_name);
                            $('#size').val(response.data.size);
                        } else {
                            alert('Failed to fetch job details.');
                        }
                    }
                });
            });
        });
    </script>




    <script>
        document.getElementById('file-upload').addEventListener('change', function() {
            let file = $('#file-form input[name=file]').val();
            if (file) {
                console.log(file, $('#file-label'))
                $('#file-label').html(file).css('color', 'green')
            }
        });
        const saveFile = async (blob, suggestedName) => {
            const supportsFileSystemAccess =
                'showSaveFilePicker' in window &&
                (() => {
                    try {
                        return window.self === window.top;
                    } catch {
                        return false;
                    }
                })();
            // If the File System Access API is supported…
            if (supportsFileSystemAccess) {
                try {
                    // Show the file save dialog.
                    const handle = await showSaveFilePicker({
                        suggestedName,
                    });
                    // Write the blob to the file.
                    const writable = await handle.createWritable();
                    await writable.write(blob);
                    await writable.close();
                    return;
                } catch (err) {
                    // Fail silently if the user has simply canceled the dialog.
                    if (err.name !== 'AbortError') {
                        console.error(err.name, err.message);
                        return;
                    }
                }
            }
            // Fallback if the File System Access API is not supported…
            // Create the blob URL.
            const blobURL = URL.createObjectURL(blob);
            // Create the `<a download>` element and append it invisibly.
            const a = document.createElement('a');
            a.href = blobURL;
            a.download = suggestedName;
            a.style.display = 'none';
            document.body.append(a);
            // Programmatically click the element.
            a.click();
            // Revoke the blob URL and remove the element.
            setTimeout(() => {
                URL.revokeObjectURL(blobURL);
                a.remove();
            }, 1000);
        };


        document.getElementById('file-form').addEventListener('submit', function(event) {
            event.preventDefault();
            const file = document.querySelector('input[name=file]').files[0]
            if (!file) {
                alert('file must be selected');
                return;
            }
            const client = document.querySelector('input[name=client]').value
            const employee = document.querySelector('input[name=employee]').value
            const quantity = document.querySelector('input[name=quantity]').value
            const date = document.querySelector('input[name=date]').value
            const jobNumber = document.querySelector('select[name=job_number]').value;
            const size = document.querySelector('input[name=size]').value;

            const originalFileName = file.name;
            const extension = originalFileName.split('.').pop();
            const originalFileNameWithoutExtension = originalFileName.substring(0, originalFileName.lastIndexOf('.'));

            // const jobNumber = document.querySelector('input[name=job_number]').value
            // const currDate = new Date().toJSON().slice(0, 10)
            const filename = [jobNumber, client, employee, originalFileNameWithoutExtension, size, quantity, date].join('_') + '.' + file.name.split('.').pop()
            console.log(filename)
            saveFile(file, filename)
                .then(() => {
                    // Send an AJAX request to insert into the database
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', 'insert_file.php', true);
                    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                            window.location = 'action.php?status=success&redirect=index.php';
                        } else if (xhr.readyState === 4) {
                            window.location = 'action.php?status=error&redirect=index.php';
                        }
                    };
                    const params = `file_name=${encodeURIComponent(filename)}&extension=${encodeURIComponent(extension)}&date=${encodeURIComponent(date)}`;
                    xhr.send(params);
                })
                .catch(() => window.location = 'action.php?status=error&redirect=index.php');
        });
    </script>
</body>

</html>


<?php
// Navigate to directory or download file
if (isset($_GET['file'])) {
    // If the file is a directory
    if (is_dir($_GET['file'])) {
        // Update the current directory
        $current_directory = $_GET['file'] . '/';
    } else {
        // Download file
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($_GET['file']) . '"');
        readfile($_GET['file']);
        exit;
    }
}

if (isset($_POST['add_folder'])) {
    $basePath = $_POST['directory'];
    $folder_name = $_POST['folder_name'];
    $folderPath = $basePath . $folder_name;
    if (!is_dir($folderPath)) {
        if (mkdir($folderPath)) {
            echo "<script>window.location = 'action.php?status=success&redirect=index.php'; </script>";
        } else {
            echo "<script>window.location = 'action.php?status=error&redirect=index.php'; </script>";
        }
    }
}

if (isset($_GET['directory'])) {
    if (!empty($_FILES['file']['name'])) {
        error_log(json_encode($_FILES));
        $uploadFilepath = $_POST['file_path'];
        $client = $_POST['client'];
        $jobNumber = $_POST['job_number'];
        $filename = implode('_', array($client, $jobNumber, $current_date));
        $targetFile = $uploadFilepath . $filename;
        if (move_uploaded_file($_FILES['file']['tmp_name'], $targetFile)) {
            echo "<script>window.location = 'action.php?status=success&redirect=index.php'; </script>";
        } else {
            echo "<script>window.location = 'action.php?status=error&redirect=index.php'; </script>";
        }
    }
}

if (isset($_POST['rename'])) {
    $oldName = $_POST['old_name'];
    $newName = $_POST['file_name'];
    $filePath = $current_directory . $oldName;
    $newFilePath = $current_directory . $newName;

    if (rename($filePath, $newFilePath)) {
        echo "<script>window.location = 'action.php?status=success&redirect=index.php'; </script>";
    } else {
        echo "<script>window.location = 'action.php?status=error&redirect=index.php'; </script>";
    }
}

?>