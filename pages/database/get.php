<?php
include("data.php");
include("../../../include/db.php");

$type = $_GET['type'];
validType($type);

if ($type == 'design') $primary_key = 'digital_id';
else $primary_key = $type . "_id";

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $id = $_GET['id'];
        $item = getResource($type, $id);
        echo json_encode($item);
        break;
    case 'POST':
        $id = $_POST[$primary_key];
        unset($_POST[$primary_key]);
        $common_var_keys = array("customer", "job_type", "constant_cost", "required_quantity", "profit_margin", "vat", "bind_id", "folding_price", "type", "digital_print", "size", "commitonprice", "page_machine_run", "cover_machine_run");
        $total_output_keys = array("total_cost", "profit_margin", "total_price", "total_price_vat", "unit_price", "unit_price_vat");
        $page_input_keys = array("page_paper_id_a", "page_plate_id_a", "page_per_a1_a", "page_print_cost_a", "number_of_page_a", "page_on_plate_a", "page_print_color_a", "page_lam_type_a", "page_paper_id_b", "page_plate_id_b", "page_per_a1_b", "page_print_cost_b", "number_of_page_b", "page_on_plate_b", "page_print_color_b", "page_lam_type_b", "page_paper_id_c", "page_plate_id_c", "page_per_a1_c", "page_print_cost_c", "number_of_page_c", "page_on_plate_c", "page_print_color_c", "page_lam_type_c", "page_paper_id_d", "page_plate_id_d", "page_per_a1_d", "page_print_cost_d", "number_of_page_d", "page_on_plate_d", "page_print_color_d", "page_lam_type_d");
        $cover_input_keys = array("cover_paper_id", "cover_plate_id", "cover_page_per_a1", "cover_print_cost", "cover_on_plate", "cover_print_color", "cover_waste_paper", "cover_lamination_type", "cover_print_side");
        $cover_output_keys = array("cover_machine_run", "cover_printing_price", "cover_required_paper", "cover_plate_price", "cover_paper_cost", "cover_lamination_price", "cover_total_cost");
        $page_output_keys = array("page_machine_run", "page_printing_price", "page_required_paper", "page_plate_price", "page_paper_cost", "page_lamination_price", "page_number_of_plate", "page_waste_paper_total", "page_total_required_paper", "page_total_cost", "folding_total_price");

        if ($type == 'book') {
            $common_var = array_filter($_POST, function ($key) use ($common_var_keys) {
                return in_array($key, $common_var_keys);
            }, ARRAY_FILTER_USE_KEY);

            $total_output = array_filter($_POST, function ($key) use ($total_output_keys) {
                return in_array($key, $total_output_keys);
            }, ARRAY_FILTER_USE_KEY);

            $page_input = array_filter($_POST, function ($key) use ($page_input_keys) {
                return in_array($key, $page_input_keys);
            }, ARRAY_FILTER_USE_KEY);

            $cover_input = array_filter($_POST, function ($key) use ($cover_input_keys) {
                return in_array($key, $cover_input_keys);
            }, ARRAY_FILTER_USE_KEY);

            $cover_output = array_filter($_POST, function ($key) use ($cover_output_keys) {
                return in_array($key, $cover_output_keys);
            }, ARRAY_FILTER_USE_KEY);

            $page_output = array_filter($_POST, function ($key) use ($page_output_keys) {
                return in_array($key, $page_output_keys);
            }, ARRAY_FILTER_USE_KEY);

            $data = array(
                'date' => $_POST['date'],
                'common_var' => json_encode($common_var),
                'total_output' => json_encode($total_output),
                'page_input' => json_encode($page_input),
                'cover_input' => json_encode($cover_input),
                'cover_output' => json_encode($cover_output),
                'page_output' => json_encode($page_output),
            );
        } else {
            $data = $_POST;
        }

        error_log(json_encode($data));

        $updates = join(', ', array_map(function ($val, $key) {
            return "$key = '$val'";
        }, $data, array_keys($data)));

        $sql = "UPDATE $type SET $updates WHERE $primary_key = '$id'";
        $qry = mysqli_query($con, $sql);

        if (!$qry) {
            http_response_code(500);
            die();
        }
        break;
}
