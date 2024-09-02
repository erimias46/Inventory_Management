<?php
include("../../../include/db.php");

function validType($type) {
    $types = array('book', 'brocher', 'digital', 'otherdigital', 'banner', 'design','multi_page','single_page','banner_out','bag');
    if (!in_array($type, $types)) {
        http_response_code(400);
        echo "invalid type";
        die();
    }
}

function getPrimaryKey($type) {
    return ($type == 'design') ? 'digital_id' : $type . "_id";
}

function getResource($type, $id) {
    include("../../../include/db.php");
    $primary_key = getPrimaryKey($type);
    $result = mysqli_query($con, "SELECT * FROM $type WHERE $primary_key = $id");
    $item = mysqli_fetch_assoc($result);
    if ($type == 'book') {
        $item = array_merge($item, json_decode($item['common_var'], true), json_decode($item['total_output'], true), json_decode($item['cover_input'], true), json_decode($item['page_input'], true), json_decode($item['cover_output'], true), json_decode($item['page_output'], true));
        unset($item['common_var'], $item['total_output'], $item['cover_input'], $item['page_input'], $item['cover_output'], $item['page_output']);
    }
    return $item;
}
