<?php
include_once("database.php");

class AuthService
{
    public static function checkUser($username, $password){
        $conn = DatabaseService::getConnection();
        $result = $conn->query("SELECT * FROM user WHERE user_name = '$username'");
        while ($row = $result->fetch_assoc()) {
            return $row['user_name'] == $username && $row['password'] == $password;
        }
        return false;
    }
}