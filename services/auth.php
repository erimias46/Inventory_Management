<?php
include_once __DIR__ . '/database.php';

class AuthService
{
    public static function checkUser(string $username, string $password): bool
    {
        $conn = DatabaseService::getConnection();
        $username = $conn->real_escape_string($username);
        $result = $conn->query("SELECT user_name, password FROM user WHERE user_name = '$username' LIMIT 1");
        if (!$result || $result->num_rows === 0) {
            return false;
        }
        $row = $result->fetch_assoc();
        return $row['user_name'] === $username && $row['password'] === $password;
    }
}
