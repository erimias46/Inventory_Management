<!-- Plugin Js -->


<script src="<?php echo $redirect_link ?>assets/libs/simplebar/simplebar.min.js"></script>
<script src="<?php echo $redirect_link ?>assets/libs/feather-icons/feather.min.js"></script>
<script src="<?php echo $redirect_link ?>assets/libs/@frostui/tailwindcss/frostui.js"></script>

<!-- App Js -->
<script src="<?php echo $redirect_link ?>assets/js/app.js"></script>
<script src="<?php echo $redirect_link; ?>assets/libs/sweetalert/dist/js/sweetalert.min.js"></script>

<!-- Datatable -->
<link href="https://cdn.datatables.net/v/dt/jq-3.7.0/dt-1.13.6/datatables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/v/dt/jq-3.7.0/dt-1.13.6/datatables.min.js"></script>

<!-- Select js -->
<script src="<?= $redirect_link ?>assets/libs/nice-select2/js/nice-select2.js"></script>
<script src="<?= $redirect_link ?>assets/js/pages/form-select.js"></script>

<!-- sweet alert dark mode -->
<style>
    html[data-mode=dark] .swal-title, 
    html[data-mode=dark] .swal-text {
        color: var(--dt-html-background);
    } 

    .swal-icon--success:after, .swal-icon--success:before, .swal-icon--success__hide-corners {
        background-color:  rgb(31 41 55 / var(--tw-bg-opacity));
    }

    html[data-mode=dark] select {
        background-color: rgb(31 41 55 / var(--tw-bg-opacity));
    } 

    .selectize, .search-select {
        width: 100% !important;
    }

    .nice-select .nice-select-search {
        background-color: transparent;
    }

    .page-content {
        overflow: auto;
    }

</style>

<script>
$(document).ready(function() {
    $('#zero_config').DataTable();
    document.querySelectorAll('#del-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault()
            const href = e.currentTarget.getAttribute('href')
            console.log(href)
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will not be able to recover this data!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                console.log(willDelete)
                if (willDelete) {
                    document.location.href = href;
                }
            })
        })
    });
})
</script>

<?php

if(isset($_GET['success']) || isset($_GET['status']) && $_GET['status'] == 'success') {
    ?>
<script>
swal({
    title: 'Success',
    text: 'performed action successfully',
    icon: 'success'
}).then(() => window.history.replaceState({}, document.title, window.location.href.split('?')[0]))
</script>
<?php
} else if (isset($_GET['error']) || isset($_GET['status']) && $_GET['status'] == 'error') {
    ?>
<script>
swal({
    title: 'Error occured',
    text: 'unknown error occured',
    icon: 'error'
}).then(() => window.history.replaceState({}, document.title, window.location.href.split('?')[0]))

</script>
<?php
}