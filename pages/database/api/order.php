<?php
include("../../../include/db.php");

include("../../../include/mdb.php");


    $order_desc = ''; // Initialize variable to hold the printable HTML content


        $type = $_GET['type'];
        $primary_key = $type . '_id' ;
        $update_id = $_POST[$primary_key];

        if($type=='design'){
            $primary_key='digital_id';
        }
        $sql = "SELECT * FROM $type WHERE $primary_key = $update_id";
        $result = mysqli_query($con, $sql);
        $row = mysqli_fetch_assoc($result);

        if ($type == 'book') {
            $common_var = json_decode($row['common_var'], true);

            $cover_input = json_decode($row['cover_input'], true);

            $page_input = json_decode($row['page_input'], true);

            $total_output = json_decode($row['total_output'], true);
            $types=$row['types'];

            $customer = $common_var['customer'];
            $job_type = $common_var['job_type'];
            $size = $common_var['size'];
            $required_quantity = $common_var['required_quantity'];

            if($types=='cover'){

            $cover_paper_id = $cover_input['cover_paper_id'];
            $cover_plate_id = $cover_input['cover_plate_id'];


            $cover_on_plate = $cover_input['cover_on_plate'];
            $cover_print_color = $cover_input['cover_print_color'];
            $cover_waste_paper = $cover_input['cover_waste_paper'];
            $cover_lamination_type = $cover_input['cover_lamination_type'];
            $cover_print_side = $cover_input['cover_print_side'];

            $sql = "SELECT * FROM paper WHERE paper_id = $cover_paper_id";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($result);
            $cover_paper_type = $row['paper_type'];

            $sql = "SELECT * FROM plate WHERE plate_id = $cover_plate_id";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($result);
            $cover_plate_type = $row['plate_name'];


            if ($cover_lamination_type == "none") {
                $cover_lamination_type = "None";
                $total_cover_care_lamination = 0;
            } else {

                $sql = "SELECT * FROM laminationdb WHERE lam_id = $cover_lamination_type";
                $result = mysqli_query($con, $sql);
                $row = mysqli_fetch_assoc($result);
                $cover_lamination_type = $row['lam_type'];
                $cover_care_lamination = $row['care_lamination'];


                $total_cover_care_lamination = $cover_care_lamination * $required_quantity;
            }

        }

            $page_paper_id_a = $page_input['page_paper_id_a'];
            $page_plate_id_a = $page_input['page_plate_id_a'];
            $page_on_plate_a = $page_input['page_on_plate_a'];
            $page_print_color_a = $page_input['page_print_color_a'];


            $page_lamination_type_a = $page_input['page_lam_type_a'];

            $sql = "SELECT * FROM paper WHERE paper_id = $page_paper_id_a";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($result);
            $page_paper_type_a = $row['paper_type'];


            $sql = "SELECT * FROM plate WHERE plate_id = $page_plate_id_a";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($result);
            $page_plate_type_a = $row['plate_name'];

            if ($page_lamination_type_a == "none") {
                $page_lamination_type_a = "None";
                $total_page_care_lamination_a = 0;
            } else {

                $sql = "SELECT * FROM laminationdb WHERE lam_id = $page_lamination_type_a";
                $result = mysqli_query($con, $sql);
                $row = mysqli_fetch_assoc($result);
                $page_lamination_type_a = $row['lam_type'];
                $page_care_lamination_a = $row['care_lamination'];

                $total_page_care_lamination_a = $page_care_lamination_a * $required_quantity;
            }


        


            $page_paper_id_b = $page_input['page_paper_id_b'];
            $page_plate_id_b = $page_input['page_plate_id_b'];
            $page_on_plate_b = $page_input['page_on_plate_b'];
            $page_print_color_b = $page_input['page_print_color_b'];

            $page_lamination_type_b = $page_input['page_lam_type_b'];

            $sql = "SELECT * FROM paper WHERE paper_id = $page_paper_id_b";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($result);
            $page_paper_type_b = $row['paper_type'];

            $sql = "SELECT * FROM plate WHERE plate_id = $page_plate_id_b";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($result);
            $page_plate_type_b = $row['plate_name'];


            if ($page_lamination_type_b == "none") {
                $page_lamination_type_b = "None";
                $total_page_care_lamination_b = 0;
            } else {
                $sql = "SELECT * FROM laminationdb WHERE lam_id = $page_lamination_type_b";
                $result = mysqli_query($con, $sql);
                $row = mysqli_fetch_assoc($result);
                $page_lamination_type_b = $row['lam_type'];
                $page_care_lamination_b = $row['care_lamination'];

                $total_page_care_lamination_b = $page_care_lamination_b * $required_quantity;
            }


            $page_paper_id_c = $page_input['page_paper_id_c'];
            $page_plate_id_c = $page_input['page_plate_id_c'];
            $page_on_plate_c = $page_input['page_on_plate_c'];
            $page_print_color_c = $page_input['page_print_color_c'];

            $page_lamination_type_c = $page_input['page_lam_type_c'];

            $sql = "SELECT * FROM paper WHERE paper_id = $page_paper_id_c";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($result);
            $page_paper_type_c = $row['paper_type'];

            $sql = "SELECT * FROM plate WHERE plate_id = $page_plate_id_c";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($result);
            $page_plate_type_c = $row['plate_name'];

            if ($page_lamination_type_c == "none") {
                $page_lamination_type_c = "None";
                $total_page_care_lamination_c = 0;
            } else {

                $sql = "SELECT * FROM laminationdb WHERE lam_id = $page_lamination_type_c";
                $result = mysqli_query($con, $sql);
                $row = mysqli_fetch_assoc($result);
                $page_lamination_type_c = $row['lam_type'];

                $page_care_lamination_c = $row['care_lamination'];

                $total_page_care_lamination_c = $page_care_lamination_c * $required_quantity;
            }


            $page_paper_id_d = $page_input['page_paper_id_d'];
            $page_plate_id_d = $page_input['page_plate_id_d'];
            $page_on_plate_d = $page_input['page_on_plate_d'];
            $page_print_color_d = $page_input['page_print_color_d'];

            $page_lamination_type_d = $page_input['page_lam_type_d'];

            $sql = "SELECT * FROM paper WHERE paper_id = $page_paper_id_d";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($result);
            $page_paper_type_d = $row['paper_type'];

            $sql = "SELECT * FROM plate WHERE plate_id = $page_plate_id_d";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($result);
            $page_plate_type_d = $row['plate_name'];

            if ($page_lamination_type_d == "none") {
                $page_lamination_type_d = "None";
                $total_page_care_lamination_d = 0;
            } else {

                $sql = "SELECT * FROM laminationdb WHERE lam_id = $page_lamination_type_d";
                $result = mysqli_query($con, $sql);
                $row = mysqli_fetch_assoc($result);
                $page_lamination_type_d = $row['lam_type'];
                $page_care_lamination_d = $row['care_lamination'];

                $total_page_care_lamination_d = $page_care_lamination_d * $required_quantity;
            }


            
$order_desc = "Job Details for $type\n";
$order_desc .= "Customer: $customer\n";
$order_desc .= "Job Type: $job_type\n";
$order_desc .= "Size: $size\n";
$order_desc .= "Required Quantity: $required_quantity\n\n";

if($types=='cover'){

$order_desc .= "Cover Details\n";
$order_desc .= "Paper Type: $cover_paper_type\n";
$order_desc .= "Plate Type: $cover_plate_type\n";
$order_desc .= "On Plate: $cover_on_plate\n";
$order_desc .= "Print Color: $cover_print_color\n";
$order_desc .= "Waste Paper: $cover_waste_paper\n";
$order_desc .= "Lamination Type: $cover_lamination_type\n";
$order_desc .= "Print Side: $cover_print_side\n";
$order_desc .= "Total Care Lamination: $total_cover_care_lamination\n\n";

$order_desc .= "----------------------------------------\n";

}

$order_desc .= "Page A Details\n";
$order_desc .= "Paper Type: $page_paper_type_a\n";
$order_desc .= "Plate Type: $page_plate_type_a\n";
$order_desc .= "On Plate: $page_on_plate_a\n";
$order_desc .= "Print Color: $page_print_color_a\n";
$order_desc .= "Lamination Type: $page_lamination_type_a\n";
$order_desc .= "Total Care Lamination: $total_page_care_lamination_a\n\n";

$order_desc .= "Page B Details\n";
$order_desc .= "Paper Type: $page_paper_type_b\n";
$order_desc .= "Plate Type: $page_plate_type_b\n";
$order_desc .= "On Plate: $page_on_plate_b\n";
$order_desc .= "Print Color: $page_print_color_b\n";
$order_desc .= "Lamination Type: $page_lamination_type_b\n";
$order_desc .= "Total Care Lamination: $total_page_care_lamination_b\n\n";

$order_desc .= "Page C Details\n";
$order_desc .= "Paper Type: $page_paper_type_c\n";
$order_desc .= "Plate Type: $page_plate_type_c\n";
$order_desc .= "On Plate: $page_on_plate_c\n";
$order_desc .= "Print Color: $page_print_color_c\n";
$order_desc .= "Lamination Type: $page_lamination_type_c\n";
$order_desc .= "Total Care Lamination: $total_page_care_lamination_c\n\n";

$order_desc .= "Page D Details\n";
$order_desc .= "Paper Type: $page_paper_type_d\n";
$order_desc .= "Plate Type: $page_plate_type_d\n";
$order_desc .= "On Plate: $page_on_plate_d\n";
$order_desc .= "Print Color: $page_print_color_d\n";
$order_desc .= "Lamination Type: $page_lamination_type_d\n";
$order_desc .= "Total Care Lamination: $total_page_care_lamination_d\n\n";

$order_desc .= "----------------------------------------\n";

    
        
        } else if ($type == 'brocher') {
    $customer = $row['customer'];
    $job_type = $row['job_type'];
    $size = $row['size'];
    $required_quantity = $row['required_quantity'];
    $unit_price = $row['unit_price'];
    $total_price = $row['total_price'];
    $total_price_vat = $row['total_price_vat'] ?? $total_price * $row['vat'];
    $lamination_type = $row['lam_type'];
    $paper_id = $row['paper_id'];
    $required_paper = $row['required_paper_full'];
    $machine_run = $row['machine_run'];
    $print_side = $row['print_side'];
    $plate_id = $row['plate_id'];
    $fold = $row['fold'];
    $on_plate = $row['on_plate'];
    $sql = "SELECT * FROM paper WHERE paper_id = $paper_id";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);
    $paper_type = $row['paper_type'];



if($lamination_type=="none"){
    $lamination_type="None";
    $total_care_lamination=0;
}else{
    $sql = "SELECT * from laminationdb where lam_id = $lamination_type";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);
    $lamination_type = $row['lam_type'];
    $care_lamination = $row['care_lamination'];

    $total_care_lamination = $care_lamination * $required_quantity;
}

    $sql = "SELECT * from plate where plate_id = $plate_id";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);
    $plate_type = $row['plate_name'];


   // $total_care_lamination = $care_lamination * $required_quantity;


    $order_desc = "Job Details for $type\n";
    $order_desc .= "Customer: $customer\n";
    $order_desc .= "Job Type: $job_type\n";
    $order_desc .= "Size: $size\n";
    $order_desc .= "Required Quantity: $required_quantity\n";
    $order_desc .= "Required Paper: $required_paper\n";
    $order_desc .= "Lamination Type: $lamination_type\n";
    $order_desc .= "Total Care Lamination: $total_care_lamination\n";
    $order_desc .= "Plate Type: $plate_type\n";
    $order_desc .= "Paper Type: $paper_type\n";
    $order_desc .= "Machine Run: $machine_run\n";
    $order_desc .= "Print Side: $print_side\n";
    $order_desc .= "Fold: $fold\n";
    $order_desc .= "On Plate: $on_plate\n";
    $order_desc .= "----------------------------\n";  // Separator for each job



} else if ($type == 'bag') {
    $customer = $row['customer'];
    $job_type = $row['job_type'];
    $size = $row['size'];
    $required_quantity = $row['required_quantity'];
    $unit_price = $row['unit_price'];
    $total_price = $row['total_price'];
    $total_price_vat = $row['total_price_vat'] ?? $total_price * $row['vat'];
    $lamination_type = $row['lam_type'];
    $paper_id = $row['paper_type'];
    $required_paper = $row['required_paper'];
    $machine_run = $row['machine_run'];
   // $print_side = $row['print_side'];
    $plate_id = $row['plate_type'];
    $fold = $row['folding_count'];
    $on_plate = $row['on_plate'];
    $bind_id=$row['bind_type'];
    $item_per_page=$row['item_per_page'];
    $page_waste=$row['page_waste'];


    $sql = "SELECT * FROM paper WHERE paper_id = $paper_id";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);
    $paper_type = $row['paper_type'];




    

    $sql = "SELECT * from plate where plate_id = $plate_id";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);
    $plate_type = $row['plate_name'];


    $sql="SELECT * from bind where bind_id=$bind_id";
    $result=mysqli_query($con,$sql);
    $row=mysqli_fetch_assoc($result);
    $bind_type=$row['bind_type'];


    if($lamination_type=="none"){
        $lamination_type="None";
        $total_care_lamination=0;
    }else{


    $sql = "SELECT * from laminationdb where lam_id = $lamination_type";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);
    $lamination_type = $row['lam_type'];
    $care_lamination = $row['care_lamination'];


    $total_care_lamination = $care_lamination * $required_quantity;
    }


    $order_desc = "Job Details for $type\n";
    $order_desc .= "Customer: $customer\n";
    $order_desc .= "Job Type: $job_type\n";
    $order_desc .= "Size: $size\n";
    $order_desc .= "Required Quantity: $required_quantity\n";
    $order_desc .= "Required Paper: $required_paper\n";
    $order_desc .= "Lamination Type: $lamination_type\n";
    $order_desc .= "Total Care Lamination: $total_care_lamination\n";
    $order_desc .= "Plate Type: $plate_type\n";
    $order_desc .= "Paper Type: $paper_type\n";
    $order_desc .= "Machine Run: $machine_run\n";
  
    $order_desc .= "Fold: $fold\n";
    $order_desc .= "On Plate: $on_plate\n";
    $order_desc .= "Bind Type: $bind_type\n";
    $order_desc .= "Item Per Page: $item_per_page\n";
    $order_desc .= "Page Waste: $page_waste\n";
    $order_desc .= "----------------------------\n";  // Separator for each job



}



elseif ($type == 'digital') {
    $customer = $row['customer'];
    $job_type = $row['job_type'];
    $size = $row['size'];
    $required_quantity = $row['required_quantity'];


    $order_desc = "Job Details for Manual\n";
    $order_desc .= "Customer: $customer\n";
    $order_desc .= "Job Type: $job_type\n";
    $order_desc .= "Size: $size\n";
    $order_desc .= "Required Quantity: $required_quantity\n";
    $order_desc .= "----------------------------------------\n";

} elseif ($type == "otherdigital") {
    $customer = $row['customer'];
    $job_type = $row['job_type'];
    $size = $row['size'];
    $required_quantity = $row['required_quantity'];
    $machine_run = $row['machine_run'];
    $unit_price = $row['unit_price'];

    $sql = "SELECT * from unitdigital where unitdigital_id = $unit_price";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);
    $unitdigital_name = $row['unitdigital_name'];
    $total_amount = $required_quantity * $unit_price;



    $order_desc = "Job Details for Digital\n";
    $order_desc .= "Customer: $customer\n";
    $order_desc .= "Job Type: $job_type\n";
    $order_desc .= "Size: $size\n";
    $order_desc .= "Required Quantity: $required_quantity\n";
    $order_desc .= "Unit Digital Type: $unitdigital_name\n";
    $order_desc .= "----------------------------------------\n";

} elseif ($type == 'banner') {
    $customer = $row['customer'];
    $job_type = $row['job_type'];
    $size = $row['size'];
    $required_quantity = $row['required_quantity'];
    $unitbanner_type = $row['unitbanner_type'];
    $width = $row['width'];
    $lengths = $row['lengths'];
    $kare = $row['kare'];
    $totalkare = $row['totalkare'];

    $sql = "SELECT * from unitbanner where unitbanner_id = $unitbanner_type";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);
    $unitbanner_name = $row['unitbanner_name'];



    $order_desc = "Job Details for Banner\n";
    $order_desc .= "Customer: $customer\n";
    $order_desc .= "Job Type: $job_type\n";
    $order_desc .= "Size: $size\n";
    $order_desc .= "Required Quantity: $required_quantity\n";
    $order_desc .= "Unit Banner Type: $unitbanner_name\n";
    $order_desc .= "Width: $width\n";
    $order_desc .= "Length: $lengths\n";
    $order_desc .= "Kare: $kare\n";
    $order_desc .= "Total Kare: $totalkare\n";
    $order_desc .= "----------------------------------------\n";

} elseif ($type == 'design') {
    $customer = $row['customer'];
    $job_type = $row['job_type'];

    $required_quantity = $row['required_quantity'];
    $designtype = $row['designtype'];
    $numberofpages = $row['number_of_pages'];
    $sql = "SELECT * from unitdesign where unitdesign_id = $designtype";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);
    $unitdesign_name = $row['unitdesign_name'];



    $order_desc = "Job Details for Design\n";
    $order_desc .= "Customer: $customer\n";
    $order_desc .= "Job Type: $job_type\n";
    $order_desc .= "Required Quantity: $required_quantity\n";
    $order_desc .= "Unit Design Type: $unitdesign_name\n";
    $order_desc .= "Number of Pages: $numberofpages\n";
    $order_desc .= "----------------------------------------\n";




} elseif ($type == 'single_page') {
    $customer = $row['customer'];
    $job_type = $row['job_type'];

    $required_quantity = $row['required_quantity'];
    $print_side = $row['print_side'];
    $machine_run = $row['machine_run'];
    $page_id = $row['page_id'];

    $sql = "SELECT * from pagedb where page_id = $page_id";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);
    $page_type = $row['page_type'];



    $order_desc = "Job Details for Single Page\n";
    $order_desc .= "Customer: $customer\n";
    $order_desc .= "Job Type: $job_type\n";
    $order_desc .= "Print Side: $print_side\n";
    $order_desc .= "Machine Run: $machine_run\n";
    $order_desc .= "Page Type: $page_type\n";
    $order_desc .= "Required Quantity: $required_quantity\n";
    $order_desc .= "----------------------------------------\n";




} elseif ($type == 'multi_page') {
    $customer = $row['customer'];
    $job_type = $row['job_type'];
    $size = $row['size'];
    $required_quantity = $row['required_quantity'];

    $page_id_a = $row['page_id_a'];
    $page_id_b = $row['page_id_b'];
    $page_id_c = $row['page_id_c'];
    $page_id_d = $row['page_id_d'];
    $nopage_a = $row['nopage_a'];
    $nopage_b = $row['nopage_b'];
    $nopage_c = $row['nopage_c'];
    $nopage_d = $row['nopage_d'];
    $lam_type = $row['lam_type'];
    $machine_run = $row['machine_run'];
    $bind_id = $row['bind_id'];


    $sql = "SELECT * from pagedb where page_id = $page_id_a";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);
    $page_type_a = $row['page_type'];

    $sql = "SELECT * from pagedb where page_id = $page_id_b";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);
    $page_type_b = $row['page_type'];

    $sql = "SELECT * from pagedb where page_id = $page_id_c";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);
    $page_type_c = $row['page_type'];

    $sql = "SELECT * from pagedb where page_id = $page_id_d";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);
    $page_type_d = $row['page_type'];

    $sql = "SELECT * from laminationdb where lam_id = $lam_type";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);
    $lamination_type = $row['lam_type'];
    $care_lamination = $row['care_lamination'];

    $total_care_lamination = $care_lamination * $required_quantity;

    $sql = "SELECT * from bind where bind_id = $bind_id";
    $result = mysqli_query($con, $sql);
    $row = mysqli_fetch_assoc($result);
    $bind_type = $row['bind_type'];



    $order_desc = "Job Details for Multi Page\n";
$order_desc .= "Customer: $customer\n";
$order_desc .= "Job Type: $job_type\n";
$order_desc .= "Size: $size\n";
$order_desc .= "Required Quantity: $required_quantity\n";
$order_desc .= "Page Type A: $page_type_a\n";
$order_desc .= "Page Type B: $page_type_b\n";
$order_desc .= "Page Type C: $page_type_c\n";
$order_desc .= "Page Type D: $page_type_d\n";
$order_desc .= "Lamination Type: $lamination_type\n";
$order_desc .= "Total Care Lamination: $total_care_lamination\n";
$order_desc .= "Machine Run: $machine_run\n";
$order_desc .= "Bind Type: $bind_type\n";
$order_desc .= "----------------------------------------\n";
}
        







        // Append to the printable content
        $order_desc;
 
