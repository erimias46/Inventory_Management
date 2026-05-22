<?php

declare(strict_types=1);

/**
 * CLI: php tests/fixtures/setup_test_shop.php
 * Creates stock_test shop DB + master registry + seed data for automated tests.
 */

$root = dirname(__DIR__, 2);
require_once $root . '/tests/_load_env.php';
require_once $root . '/tools/schema.php';
require_once $root . '/include/helpers.php';

$env = tests_load_env();
$host = $env['mysql_host'];
$user = $env['mysql_user'];
$pass = $env['mysql_pass'];
$shopDb = $env['shop_db'];
$shopSlug = $env['shop_slug'];

$rootCon = mysqli_connect($host, $user, $pass);
if (!$rootCon) {
    fwrite(STDERR, "MySQL connect failed: " . mysqli_connect_error() . PHP_EOL);
    exit(1);
}
mysqli_query($rootCon, "SET SESSION sql_mode = 'ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'");

echo "Setting up test environment...\n";

/* stock_master */
mysqli_query($rootCon, "CREATE DATABASE IF NOT EXISTS `stock_master` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
mysqli_select_db($rootCon, 'stock_master');

mysqli_query($rootCon, "CREATE TABLE IF NOT EXISTS `shops` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(200) NOT NULL,
  `slug` VARCHAR(100) NOT NULL UNIQUE,
  `db_name` VARCHAR(100) NOT NULL UNIQUE,
  `admin_username` VARCHAR(100) NOT NULL DEFAULT 'masteradmin',
  `plan` ENUM('trial','basic','pro') DEFAULT 'trial',
  `active` TINYINT(1) DEFAULT 1,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

mysqli_query($rootCon, "CREATE TABLE IF NOT EXISTS `master_api_tokens` (
  `token_hash` VARCHAR(64) NOT NULL,
  `shop_db` VARCHAR(100) NOT NULL DEFAULT 'stock',
  `expires_at` DATETIME NOT NULL,
  PRIMARY KEY (`token_hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$slugEsc = mysqli_real_escape_string($rootCon, $shopSlug);
$dbEsc = mysqli_real_escape_string($rootCon, $shopDb);
mysqli_query($rootCon, "DELETE FROM shops WHERE slug='$slugEsc'");
mysqli_query($rootCon, "INSERT INTO shops (name, slug, db_name, admin_username, plan, active)
  VALUES ('Test Shop', '$slugEsc', '$dbEsc', 'masteradmin', 'pro', 1)");
echo "✓ Registered shop $shopSlug → $shopDb in stock_master\n";

/* shop database */
mysqli_query($rootCon, "DROP DATABASE IF EXISTS `$shopDb`");
mysqli_query($rootCon, "CREATE DATABASE `$shopDb` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
mysqli_select_db($rootCon, $shopDb);

foreach (stock_schema_sql() as $sql) {
    if (!mysqli_query($rootCon, $sql)) {
        fwrite(STDERR, "Schema error: " . mysqli_error($rootCon) . "\nSQL: $sql\n");
        exit(1);
    }
}

mysqli_query($rootCon, "CREATE TABLE IF NOT EXISTS `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_name` varchar(255) DEFAULT NULL,
  `product_type` varchar(100) DEFAULT NULL,
  `size` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `price` double DEFAULT NULL,
  `quantity` double DEFAULT NULL,
  `warehouse` int DEFAULT NULL,
  `image` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `source_table` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

mysqli_query($rootCon, "DROP TABLE IF EXISTS `bankdb`");
mysqli_query($rootCon, "CREATE TABLE `bankdb` (
  `id` int NOT NULL AUTO_INCREMENT,
  `bankname` varchar(255) NOT NULL,
  `accountnumber` varchar(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

foreach (stock_schema_seed_categories() as $sql) {
    mysqli_query($rootCon, $sql);
}

/* users */
$allModules = json_encode(array_fill_keys([
    'viewjeans', 'viewshoes', 'fullsale', 'allsale', 'searchproduct', 'deliverysale',
    'verifyproducts', 'logsale', 'user', 'constant', 'backup', 'email', 'custview',
    'salejeans', 'saleshoes', 'refundsalejeans', 'refundsaleshoes', 'deletesalejeans',
    'exchangesalejeans', 'addjeans', 'addshoes',
], 1), JSON_THROW_ON_ERROR);

$limitedModules = json_encode(['viewjeans' => 1], JSON_THROW_ON_ERROR);

mysqli_query($rootCon, "INSERT INTO user (user_name, password, previledge, module) VALUES
  ('masteradmin', 'admin', 'admin', '$allModules'),
  ('testuser', 'testpass', 'user', '$limitedModules')");

mysqli_query($rootCon, "INSERT INTO bankdb (bankname, accountnumber) VALUES ('CBE', '100001')");

/* jeans lookup tables + product */
mysqli_query($rootCon, "INSERT INTO jeansdb (size) VALUES ('M'), ('L')");
mysqli_query($rootCon, "INSERT INTO jeans_type_db (type) VALUES ('Standard')");
mysqli_query($rootCon, "INSERT INTO jeans (jeans_name, size, size_id, type, type_id, image, price, quantity, buy_price, active)
  VALUES ('Test Jean', 'M', 1, 'Standard', 1, '', 100, 10, 50, 1)");

mysqli_query($rootCon, "INSERT INTO shoesdb (size) VALUES ('40')");
mysqli_query($rootCon, "INSERT INTO shoe_type_db (type) VALUES ('Flat')");
mysqli_query($rootCon, "INSERT INTO shoes (shoes_name, size, size_id, type, type_id, image, price, quantity, buy_price, active)
  VALUES ('Test Shoe', '40', 1, 'Flat', 1, '', 200, 5, 120, 1)");

mysqli_query($rootCon, "INSERT INTO products (product_name, product_type, size, type, price, quantity, source_table)
  VALUES ('Test Jean', 'jeans', 'M', 'Standard', 100, 10, 'jeans'),
         ('Test Shoe', 'shoes', '40', 'Flat', 200, 5, 'shoes')");

echo "✓ Database $shopDb created and seeded\n";
echo "  Users: masteradmin / admin, testuser / testpass (limited)\n";
echo "  Products: Test Jean (jeans M), Test Shoe (shoes 40)\n";
echo "Done.\n";
