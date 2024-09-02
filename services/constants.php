<?php
include_once('database.php');

class ConstantsService {
    private $conn;

    function __construct() {
        $conn = DatabaseService::getConnection();
    }

    public static function getAllConstants() {
        $result = $conn->query("SELECT * FROM d_constants");
        return $conn->fetch_all();
    }
}