<?php
include_once __DIR__ . '/database.php';

class ConstantsService
{
    public static function getAllConstants(): array
    {
        $conn = DatabaseService::getConnection();
        $result = $conn->query('SELECT * FROM d_constants');
        if (!$result) {
            return [];
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
