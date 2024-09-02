<?php
include '../../include/db.php';

if (isset($_GET['order']) && isset($_GET['update'])) {
    $update_ids = explode(',', $_GET['update']);
    $printableContent = ''; // Initialize variable to hold the printable HTML content

    foreach ($update_ids as $update_id) {
        $type = $_GET['type'];
        $primary_key = empty($_GET['primary_key']) ? $type . '_id' : $_GET['primary_key'];
        $sql = "SELECT * FROM $type WHERE $primary_key = $update_id";
        $result = mysqli_query($con, $sql);
        $row = mysqli_fetch_assoc($result);

        if ($type == 'book') {
            $common_var = json_decode($row['common_var'], true);


            $cover_input=json_decode($row['cover_input'],true);

           
           $page_input=json_decode($row['page_input'],true);
           
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

            


            if($cover_lamination_type=="none"){
                $cover_lamination_type="None";
                $total_cover_care_lamination=0;
            }
            else{

            $sql = "SELECT * FROM laminationdb WHERE lam_id = $cover_lamination_type";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($result);
            $cover_lamination_type = $row['lam_type'];
            $cover_care_lamination = $row['care_lamination'];


            $total_cover_care_lamination = $cover_care_lamination * $required_quantity;}

            }


            if($types=='digital'){
                $digital_machine_run=$row['digital_machine_run'];
                $digital_print_side=$row['digital_print_side'];
                $digital_lamination_type=$cover_input['digital_lamination_type'];
                $page_type=$cover_input['unitbanner_type'];


                if($digital_lamination_type=="none"){
                    $digital_lamination_type="None";
                    $total_digital_care_lamination=0;
                }
                else{

                $sql = "SELECT * FROM laminationdb WHERE lam_id = $digital_lamination_type";
                $result = mysqli_query($con, $sql);
                $row = mysqli_fetch_assoc($result);
                $digital_lamination_type = $row['lam_type'];
                $digital_care_lamination = $row['care_lamination'];

                $total_digital_care_lamination = $digital_care_lamination * $required_quantity;
                }

                $sql = "SELECT * from pagedb where page_id = $page_type";
                $result = mysqli_query($con, $sql);
                $row = mysqli_fetch_assoc($result);
                $digital_page_type = $row['page_type'];

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

            if($page_lamination_type_a=="none"){
                $page_lamination_type_a="None";
                $total_page_care_lamination_a=0;
            }
            else{

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


            if($page_lamination_type_b=="none"){
                $page_lamination_type_b="None";
                $total_page_care_lamination_b=0;
            }
            else{
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

            if($page_lamination_type_c=="none"){
                $page_lamination_type_c="None";
                $total_page_care_lamination_c=0;
            }
            else{

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

            if($page_lamination_type_d=="none"){
                $page_lamination_type_d="None";
                $total_page_care_lamination_d=0;
            }
            else{

            $sql = "SELECT * FROM laminationdb WHERE lam_id = $page_lamination_type_d";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($result);
            $page_lamination_type_d = $row['lam_type'];
            $page_care_lamination_d = $row['care_lamination'];

            $total_page_care_lamination_d = $page_care_lamination_d * $required_quantity;

            }

            







          
        } else if($type == 'brocher') {
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
            $machine_run=$row['machine_run'];
            $print_side=$row['print_side'];
            $plate_id=$row['plate_id'];
            $fold=$row['fold'];
            $on_plate=$row['on_plate'];
            $sql="SELECT * FROM paper WHERE paper_id = $paper_id";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($result);
            $paper_type = $row['paper_type'];



            

            if($lamination_type=="none"){
                $lamination_type="None";
                $total_care_lamination=0;
            }
            else{
            $sql="SELECT * from laminationdb where lam_id = $lamination_type";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($result);
            $lamination_type = $row['lam_type'];
            $care_lamination = $row['care_lamination'];
                $total_care_lamination = $care_lamination * $required_quantity;
            }

            $sql="SELECT * from plate where plate_id = $plate_id";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($result);
            $plate_type = $row['plate_name'];


           



            




           
        }elseif($type=='digital'){
            $customer = $row['customer'];
            $job_type = $row['job_type'];
            $size = $row['size'];
            $required_quantity = $row['required_quantity'];
            
        }

        elseif($type=="otherdigital"){
            $customer = $row['customer'];
            $job_type = $row['job_type'];
            $size = $row['size'];
            $required_quantity = $row['required_quantity'];
            $machine_run=$row['machine_run'];
            $unit_price = $row['unit_price'];

            $sql="SELECT * from unitdigital where unitdigital_id = $unit_price";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($result);
            $unitdigital_name = $row['unitdigital_name'];


            $total_amount=$required_quantity*$unit_price;
           
        }

        elseif($type=='banner'){
            $customer = $row['customer'];
            $job_type = $row['job_type'];
            $size = $row['size'];
            $required_quantity = $row['required_quantity'];
            $unitbanner_type = $row['unitbanner_type'];
            $width = $row['width'];
            $lengths = $row['lengths'];
            $kare = $row['kare'];
            $totalkare = $row['totalkare'];

            $sql="SELECT * from unitbanner where unitbanner_id = $unitbanner_type";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($result);
            $unitbanner_name = $row['unitbanner_name'];



            
        }

        elseif($type=='design'){
            $customer = $row['customer'];
            $job_type = $row['job_type'];
           
            $required_quantity = $row['required_quantity'];
            $designtype=$row['designtype'];
            $numberofpages=$row['number_of_pages'];
           $sql="SELECT * from unitdesign where unitdesign_id = $designtype";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($result);
            $unitdesign_name = $row['unitdesign_name'];
           
            
        }

        elseif($type=='single_page'){
            $customer = $row['customer'];
            $job_type = $row['job_type'];
           
            $required_quantity = $row['required_quantity'];
            $print_side=$row['print_side'];
            $machine_run=$row['machine_run'];
            $page_id=$row['page_id'];

            $sql="SELECT * from pagedb where page_id = $page_id";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($result);
            $page_type = $row['page_type'];
        
           
           
        }

        elseif($type=='multi_page'){
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
            $lam_type=$row['lam_type'];
            $machine_run=$row['machine_run'];
            $bind_id=$row['bind_id'];


            $sql="SELECT * from pagedb where page_id = $page_id_a";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($result);
            $page_type_a = $row['page_type'];

            $sql="SELECT * from pagedb where page_id = $page_id_b";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($result);
            $page_type_b = $row['page_type'];

            $sql="SELECT * from pagedb where page_id = $page_id_c";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($result);
            $page_type_c = $row['page_type'];

            $sql="SELECT * from pagedb where page_id = $page_id_d";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($result);
            $page_type_d = $row['page_type'];

            $sql="SELECT * from laminationdb where lam_id = $lam_type";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($result);
            $lamination_type = $row['lam_type'];
            $care_lamination = $row['care_lamination'];

            $total_care_lamination = $care_lamination * $required_quantity;

            $sql="SELECT * from bind where bind_id = $bind_id";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($result);
            $bind_type = $row['bind_type'];
           
        } elseif ($type == 'bag') {
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
            $bind_id = $row['bind_type'];
            $item_per_page = $row['item_per_page'];
            $page_waste = $row['page_waste'];


            $sql = "SELECT * FROM paper WHERE paper_id = $paper_id";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($result);
            $paper_type = $row['paper_type'];






            $sql = "SELECT * from plate where plate_id = $plate_id";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($result);
            $plate_type = $row['plate_name'];


            $sql = "SELECT * from bind where bind_id=$bind_id";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_assoc($result);
            $bind_type = $row['bind_type'];


            if ($lamination_type == "none") {
                $lamination_type = "None";
                $total_care_lamination = 0;
            } else {


                $sql = "SELECT * from laminationdb where lam_id = $lamination_type";
                $result = mysqli_query($con, $sql);
                $row = mysqli_fetch_assoc($result);
                $lamination_type = $row['lam_type'];
                $care_lamination = $row['care_lamination'];


                $total_care_lamination = $care_lamination * $required_quantity;
            }

              // Separator for each job



        }
        
        
        else{

            $customer = $row['customer'];
            $job_type = $row['job_type'];
            $size = $row['size'];
            $required_quantity = $row['required_quantity'];
            $unit_price = $row['unit_price'];
            $total_price = $row['total_price'];
            $total_price_vat = $row['total_price_vat'] ?? $total_price * $row['vat'];

        }

        // Append to the printable content



        if($type=='brocher'){
            $printableContent .= "
            <div class='printable-section'>
                <h2>Job Details for $type</h2>
                <p><strong>Customer:</strong> $customer</p>
                <p><strong>Job Type:</strong> $job_type</p>
                <p><strong>Size:</strong> $size</p>
                <p><strong>Required Quantity:</strong> $required_quantity</p>
                <p><strong>Required Paper:</strong> $required_paper</p>
                <p><strong>Lamination Type:</strong> $lamination_type</p>
                <p><strong>Total Care Lamination:</strong> $total_care_lamination</p>
                <p><strong>Plate Type:</strong> $plate_type</p>
                <p><strong>Paper Type:</strong> $paper_type</p>
                <p><strong>Machine Run:</strong> $machine_run</p>
                <p><strong>Print Side:</strong> $print_side</p>
                <p><strong>Fold:</strong> $fold</p>
                <p><strong>On Plate:</strong> $on_plate</p>


                <hr>
            </div>";
        }


        elseif($type=='bag'){
            $printableContent .= "
            <div class='printable-section'>
                <h2>Job Details for Bag</h2>
                <p><strong>Customer:</strong> $customer</p>
                <p><strong>Job Type:</strong> $job_type</p>
                <p><strong>Size:</strong> $size</p>
                <p><strong>Required Quantity:</strong> $required_quantity</p>
                <p><strong>Unit Price:</strong> $unit_price</p>
                <p><strong>Total Price:</strong> $total_price</p>
                <p><strong>Total Price with VAT:</strong> $total_price_vat</p>
                <p><strong>Lamination Type:</strong> $lamination_type</p>
                <p><strong>Paper Type:</strong> $paper_type</p>
                <p><strong>Machine Run:</strong> $machine_run</p>
                <p><strong>Plate Type:</strong> $plate_type</p>
                <p><strong>Fold:</strong> $fold</p>
                <p><strong>On Plate:</strong> $on_plate</p>
                <p><strong>Bind Type:</strong> $bind_type</p>
                <p><strong>Item Per Page:</strong> $item_per_page</p>
                <p><strong>Page Waste:</strong> $page_waste</p>
                <hr>
            </div>";
        } elseif ($type == 'book') {
            $printableContent .= "
    <div class='printable-section'>
        <h2>Job Details for $type</h2>
        <p><strong>Customer:</strong> $customer</p>
        <p><strong>Job Type:</strong> $job_type</p>
        <p><strong>Size:</strong> $size</p>
        <p><strong>Required Quantity:</strong> $required_quantity</p>";

            // Cover Details if $types == "cover"
            if ($types == "cover") {
                $printableContent .= "
        <h3>Cover Details</h3>
        <p><strong>Paper Type:</strong> $cover_paper_type</p>
        <p><strong>Plate Type:</strong> $cover_plate_type</p>
        <p><strong>On Plate:</strong> $cover_on_plate</p>
        <p><strong>Print Color:</strong> $cover_print_color</p>
        <p><strong>Waste Paper:</strong> $cover_waste_paper</p>
        <p><strong>Lamination Type:</strong> $cover_lamination_type</p>
        <p><strong>Print Side:</strong> $cover_print_side</p>
        <p><strong>Total Care Lamination:</strong> $total_cover_care_lamination</p>";
            }


            if ($types == "digital") {
                $printableContent .= "
        <h3>Digital Details</h3>
        <p><strong>Machine Run:</strong> $digital_machine_run</p>
        <p><strong>Print Side:</strong> $digital_print_side</p>
        <p><strong>Lamination Type:</strong> $digital_lamination_type</p>
        <p><strong>Page Type:</strong> $digital_page_type</p>
        <p><strong>Total Care Lamination:</strong> $total_digital_care_lamination</p>";
            }

            $printableContent .= "
        <h3>Page A Details</h3>
        <p><strong>Paper Type:</strong> $page_paper_type_a</p>
        <p><strong>Plate Type:</strong> $page_plate_type_a</p>
        <p><strong>On Plate:</strong> $page_on_plate_a</p>
        <p><strong>Print Color:</strong> $page_print_color_a</p>
        <p><strong>Lamination Type:</strong> $page_lamination_type_a</p>
        <p><strong>Total Care Lamination:</strong> $total_page_care_lamination_a</p>

        <h3>Page B Details</h3>
        <p><strong>Paper Type:</strong> $page_paper_type_b</p>
        <p><strong>Plate Type:</strong> $page_plate_type_b</p>
        <p><strong>On Plate:</strong> $page_on_plate_b</p>
        <p><strong>Print Color:</strong> $page_print_color_b</p>
        <p><strong>Lamination Type:</strong> $page_lamination_type_b</p>
        <p><strong>Total Care Lamination:</strong> $total_page_care_lamination_b</p>

        <h3>Page C Details</h3>
        <p><strong>Paper Type:</strong> $page_paper_type_c</p>
        <p><strong>Plate Type:</strong> $page_plate_type_c</p>
        <p><strong>On Plate:</strong> $page_on_plate_c</p>
        <p><strong>Print Color:</strong> $page_print_color_c</p>
        <p><strong>Lamination Type:</strong> $page_lamination_type_c</p>
        <p><strong>Total Care Lamination:</strong> $total_page_care_lamination_c</p>

        <h3>Page D Details</h3>
        <p><strong>Paper Type:</strong> $page_paper_type_d</p>
        <p><strong>Plate Type:</strong> $page_plate_type_d</p>
        <p><strong>On Plate:</strong> $page_on_plate_d</p>
        <p><strong>Print Color:</strong> $page_print_color_d</p>
        <p><strong>Lamination Type:</strong> $page_lamination_type_d</p>
        <p><strong>Total Care Lamination:</strong> $total_page_care_lamination_d</p>
        
        <hr>
    </div>";
        }

        elseif($type=='digital'){

            $printableContent .= "
            <div class='printable-section'>
                <h2>Job Details for Manual</h2>
                <p><strong>Customer:</strong> $customer</p>
                <p><strong>Job Type:</strong> $job_type</p>
                <p><strong>Size:</strong> $size</p>
                <p><strong>Required Quantity:</strong> $required_quantity</p>

                <hr>
            </div>";
        }

        elseif($type=='otherdigital'){

            $printableContent .= "
            <div class='printable-section'>
                <h2>Job Details for Digital</h2>
                <p><strong>Customer:</strong> $customer</p>
                <p><strong>Job Type:</strong> $job_type</p>
                <p><strong>Size:</strong> $size</p>
                <p><strong>Required Quantity:</strong> $required_quantity</p>
                <p><strong>Unit Digital Type:</strong> $unitdigital_name</p>
               
                <hr>

            </div>";

        }

        elseif($type=='banner'){
            $printableContent .= "
            <div class='printable-section'>
                <h2>Job Details for Banner</h2>
                <p><strong>Customer:</strong> $customer</p>
                <p><strong>Job Type:</strong> $job_type</p>
                <p><strong>Size:</strong> $size</p>
                <p><strong>Required Quantity:</strong> $required_quantity</p>
                <p><strong>Unit Banner Type:</strong> $unitbanner_name</p>
                <p><strong>Width:</strong> $width</p>
                <p><strong>Length:</strong> $lengths</p>
                <p><strong>Kare:</strong> $kare</p>
                <p><strong>Total Kare:</strong> $totalkare</p>
                <hr>
            </div>";

        }

        elseif($type=='design'){
            $printableContent .= "
            <div class='printable-section'>
                <h2>Job Details for Design</h2>
                <p><strong>Customer:</strong> $customer</p>
                <p><strong>Job Type:</strong> $job_type</p>
                <p><strong>Required Quantity:</strong> $required_quantity</p>
                <p><strong>Unit Design Type:</strong> $unitdesign_name</p>
                <p><strong>Number of Pages:</strong> $numberofpages</p>
                <hr>
            </div>";

        }


        elseif($type=='single_page'){
            $printableContent .= "
            <div class='printable-section'>
                <h2>Job Details for Single Page</h2>
                <p><strong>Customer:</strong> $customer</p>
                <p><strong>Job Type:</strong> $job_type</p>
                <p><strong>Print Side:</strong> $print_side</p>
                <p><strong>Machine Run:</strong> $machine_run</p>
                <p><strong>Page Type:</strong> $page_type</p>
                <p><strong>Required Quantity:</strong> $required_quantity</p>
                <hr>
            </div>";

        }

        elseif($type=='multi_page'){
            $printableContent .= "
            <div class='printable-section'>
                <h2>Job Details for Multi Page</h2>
                <p><strong>Customer:</strong> $customer</p>
                <p><strong>Job Type:</strong> $job_type</p>
                <p><strong>Size:</strong> $size</p>
                <p><strong>Required Quantity:</strong> $required_quantity</p>
                <p><strong>Page Type A:</strong> $page_type_a</p>
                <p><strong>Page Type B:</strong> $page_type_b</p>
                <p><strong>Page Type C:</strong> $page_type_c</p>
                <p><strong>Page Type D:</strong> $page_type_d</p>
                <p><strong>Lamination Type:</strong> $lamination_type</p>
                <p><strong>Total Care Lamination:</strong> $total_care_lamination</p>
                <p><strong>Machine Run:</strong> $machine_run</p>
                <p><strong>Bind Type:</strong> $bind_type</p>
                <hr>
            </div>";
        }




            else{
                $printableContent .= "
                <div class='printable-section'>
                    <h2>Job Details for $type</h2>
                    <p><strong>Customer:</strong> $customer</p>
                    <p><strong>Job Type:</strong> $job_type</p>
                    <p><strong>Size:</strong> $size</p>
                    <p><strong>Required Quantity:</strong> $required_quantity</p>
                    <p><strong>Unit Price:</strong> $unit_price</p>
                    <p><strong>Total Price:</strong> $total_price</p>
                    <p><strong>Total Price with VAT:</strong> $total_price_vat</p>
                    <hr>
                </div>";
            }
        }

    // Check if there is printable content and output it as a printable page
    if (!empty($printableContent)) {
        echo "
        <html>
        <head>
            <title>Printable Job Details</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                .printable-section { margin-bottom: 20px; }
                h2 { color: #333; }
                p { margin: 5px 0; }
                hr { border: 0; border-top: 1px solid #ccc; margin: 20px 0; }
            </style>
        </head>
        <body>
            $printableContent
            <button onclick='window.print()'>Print this page</button>
        </body>
        </html>";
    } else {
        http_response_code(400);
        echo "No data to display.";
    }
} else {
    http_response_code(400);
    echo "Update must not be empty";
}
