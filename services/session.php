<?php
// Initialize the session
require_once('auth.php');

session_start();

function checkAuth($username, $password)
{
    if (AuthService::checkUser($username, $password)) {
        setSesstion($username);
        return true;
    } else {
        return "Email is not valid";
    }
}

function setSesstion($username)
{
    $_SESSION["user"] = true;
    $_SESSION["username"] = $username;
}

function logoutSesstion()
{
    session_destroy();
}

