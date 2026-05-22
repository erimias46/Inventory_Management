<?php
/**
 * Returns a connection to the stock_master database.
 * Used by: superadmin panel, login page (shop list), shop provisioning.
 */
function stock_master_connect(): ?mysqli
{
    $con = mysqli_connect('localhost', 'root', 'root', 'stock_master');
    if (!$con) {
        return null;
    }
    mysqli_query($con, "SET SESSION sql_mode = 'ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'");
    mysqli_set_charset($con, 'utf8mb4');
    return $con;
}
