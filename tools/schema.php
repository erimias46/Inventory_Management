<?php
/**
 * Returns the SQL statements to initialize a brand-new shop database.
 * Call stock_schema_sql() to get an array of SQL strings to execute in order.
 */

function stock_schema_sql(): array
{
    $sqls = [];

    /* ── user ──────────────────────────────────────────────────────── */
    $sqls[] = "CREATE TABLE IF NOT EXISTS `user` (
  `user_id`    INT NOT NULL AUTO_INCREMENT,
  `user_name`  VARCHAR(100) NOT NULL,
  `password`   VARCHAR(100) NOT NULL,
  `previledge` VARCHAR(100) NOT NULL,
  `module`     LONGTEXT     NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

    /* ── API tokens ─────────────────────────────────────────────────── */
    $sqls[] = "CREATE TABLE IF NOT EXISTS `user_api_token` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `token_hash` VARCHAR(64) NOT NULL,
  `expires_at` DATETIME NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_token_hash (token_hash),
  INDEX idx_user_id (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

    /* ── app_settings ───────────────────────────────────────────────── */
    $sqls[] = "CREATE TABLE IF NOT EXISTS `app_settings` (
  `key`   VARCHAR(100) NOT NULL,
  `value` TEXT         NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

    /* ── categories ─────────────────────────────────────────────────── */
    $sqls[] = "CREATE TABLE IF NOT EXISTS `categories` (
  `id`            INT AUTO_INCREMENT PRIMARY KEY,
  `slug`          VARCHAR(50)  NOT NULL UNIQUE,
  `label`         VARCHAR(100) NOT NULL,
  `icon`          VARCHAR(100) NOT NULL DEFAULT 'fas fa-box',
  `sort_order`    INT NOT NULL DEFAULT 0,
  `enabled`       TINYINT(1)   NOT NULL DEFAULT 1,
  `default_image` VARCHAR(200) NOT NULL DEFAULT '',
  `page_folder`   VARCHAR(100) NOT NULL DEFAULT 'category',
  `file_prefix`   VARCHAR(100) NOT NULL DEFAULT '',
  `add_file`      VARCHAR(200) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

    /* ── d_constants ────────────────────────────────────────────────── */
    $sqls[] = "CREATE TABLE IF NOT EXISTS `d_constants` (
  `id`   INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `db`   VARCHAR(255) NOT NULL,
  `date` VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

    /* ── bank / bankdb ──────────────────────────────────────────────── */
    $sqls[] = "CREATE TABLE IF NOT EXISTS `bank` (
  `id`      INT AUTO_INCREMENT PRIMARY KEY,
  `bank_name` VARCHAR(255) NOT NULL,
  `amount`  DOUBLE NOT NULL DEFAULT 0,
  `date`    DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

    $sqls[] = "CREATE TABLE IF NOT EXISTS `bankdb` (
  `id`   INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

    /* ── customer ───────────────────────────────────────────────────── */
    $sqls[] = "CREATE TABLE IF NOT EXISTS `customer` (
  `customer_id` int NOT NULL AUTO_INCREMENT,
  `customer_name` varchar(100) NOT NULL,
  `tin_number` varchar(100) NOT NULL DEFAULT '',
  `phone_number` varchar(100) NOT NULL DEFAULT '',
  `management_id` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

    /* ── subscribers ────────────────────────────────────────────────── */
    $sqls[] = "CREATE TABLE IF NOT EXISTS `subscribers` (
  `id`    INT AUTO_INCREMENT PRIMARY KEY,
  `email` VARCHAR(255) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

    /* ── multi_sale ─────────────────────────────────────────────────── */
    $sqls[] = "CREATE TABLE IF NOT EXISTS `multi_sale` (
  `id`         INT AUTO_INCREMENT PRIMARY KEY,
  `sale_data`  LONGTEXT NOT NULL,
  `user_id`    INT NOT NULL,
  `total`      DOUBLE NOT NULL DEFAULT 0,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

    /* ── Per-category tables (7 standard categories) ────────────────── */
    $categories = [
        ['jeans',     'jeans_name',     'jeansdb',     'jeans_type_db'],
        ['shoes',     'shoes_name',     'shoesdb',     'shoe_type_db'],
        ['top',       'top_name',       'topdb',       'top_type_db'],
        ['complete',  'complete_name',  'completedb',  'complete_type_db'],
        ['accessory', 'accessory_name', 'accessorydb', 'accessory_type_db'],
        ['wig',       'wig_name',       'wigdb',       'wig_type_db'],
        ['cosmetics', 'cosmetics_name', 'cosmeticsdb', 'cosmetics_type_db'],
    ];

    foreach ($categories as [$cat, $name_col, $sizedb, $typedb]) {
        /* Main inventory table */
        $sqls[] = "CREATE TABLE IF NOT EXISTS `$cat` (
  `id`         INT NOT NULL AUTO_INCREMENT,
  `{$name_col}` VARCHAR(255) NOT NULL,
  `size`       VARCHAR(255) NOT NULL,
  `size_id`    INT NOT NULL,
  `type`       VARCHAR(255) NOT NULL,
  `type_id`    INT NOT NULL,
  `image`      TEXT NOT NULL,
  `price`      DOUBLE NOT NULL,
  `quantity`   DOUBLE NOT NULL,
  `warehouse`  INT DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active`     INT NOT NULL DEFAULT 1,
  `error`      TINYINT NOT NULL DEFAULT 0,
  `buy_price`  DOUBLE DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

        /* Sales table — jeans uses legacy names 'sales' / 'sales_log' / 'delivery' */
        $sales_tbl   = ($cat === 'jeans') ? 'sales'     : "{$cat}_sales";
        $log_tbl     = ($cat === 'jeans') ? 'sales_log' : "{$cat}_sales_log";
        $deliver_tbl = ($cat === 'jeans') ? 'delivery'  : "{$cat}_delivery";

        $sqls[] = "CREATE TABLE IF NOT EXISTS `$sales_tbl` (
  `sales_id`   INT NOT NULL AUTO_INCREMENT,
  `sales_date` DATE NOT NULL,
  `update_date` DATE NOT NULL,
  `{$name_col}` VARCHAR(255) NOT NULL,
  `{$cat}_id`  INT NOT NULL,
  `size`       VARCHAR(255) NOT NULL,
  `size_id`    INT NOT NULL,
  `price`      DOUBLE NOT NULL,
  `method`     VARCHAR(255) NOT NULL,
  `cash`       DOUBLE NOT NULL,
  `bank`       DOUBLE NOT NULL,
  `quantity`   INT NOT NULL,
  `user_id`    INT NOT NULL,
  `bank_id`    INT DEFAULT NULL,
  `bank_name`  VARCHAR(255) DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status`     VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`sales_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

        $sqls[] = "CREATE TABLE IF NOT EXISTS `$log_tbl` (
  `sales_id`    INT NOT NULL AUTO_INCREMENT,
  `sales_date`  DATE NOT NULL,
  `update_date` DATE NOT NULL,
  `{$name_col}` VARCHAR(255) NOT NULL,
  `{$cat}_id`   INT NOT NULL,
  `size`        VARCHAR(255) NOT NULL,
  `size_id`     INT NOT NULL,
  `price`       DOUBLE NOT NULL,
  `method`      VARCHAR(255) NOT NULL,
  `cash`        DOUBLE NOT NULL,
  `bank`        DOUBLE NOT NULL,
  `quantity`    INT NOT NULL,
  `user_id`     INT NOT NULL,
  `bank_id`     INT DEFAULT NULL,
  `bank_name`   VARCHAR(255) DEFAULT NULL,
  `created_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status`      VARCHAR(255) DEFAULT NULL,
  `error`       TINYINT DEFAULT 0,
  `error_reason` VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`sales_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

        $sqls[] = "CREATE TABLE IF NOT EXISTS `$deliver_tbl` (
  `sales_id`   INT NOT NULL AUTO_INCREMENT,
  `sales_date` DATE NOT NULL,
  `update_date` DATE NOT NULL,
  `{$name_col}` VARCHAR(255) NOT NULL,
  `{$cat}_id`  INT NOT NULL,
  `size`       VARCHAR(255) NOT NULL,
  `size_id`    INT NOT NULL,
  `price`      DOUBLE NOT NULL,
  `method`     VARCHAR(255) NOT NULL,
  `cash`       DOUBLE NOT NULL,
  `bank`       DOUBLE NOT NULL,
  `quantity`   INT NOT NULL,
  `user_id`    INT NOT NULL,
  `bank_id`    INT DEFAULT NULL,
  `bank_name`  VARCHAR(255) DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status`     VARCHAR(255) DEFAULT NULL,
  `verifiy`    INT NOT NULL DEFAULT 0,
  `reason`     VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`sales_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

        $sqls[] = "CREATE TABLE IF NOT EXISTS `{$cat}_verify` (
  `id`         INT NOT NULL AUTO_INCREMENT,
  `{$name_col}` VARCHAR(255) NOT NULL,
  `size`       VARCHAR(255) NOT NULL,
  `size_id`    INT NOT NULL,
  `type`       VARCHAR(255) NOT NULL,
  `type_id`    INT NOT NULL,
  `image`      TEXT NOT NULL,
  `price`      DOUBLE NOT NULL,
  `quantity`   DOUBLE NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active`     INT NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

        /* jeans has 2 size tables (jeansdb and jeansdb2) */
        if ($cat === 'jeans') {
            $sqls[] = "CREATE TABLE IF NOT EXISTS `jeansdb` (
  `id`   INT AUTO_INCREMENT PRIMARY KEY,
  `size` VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
            $sqls[] = "CREATE TABLE IF NOT EXISTS `jeansdb2` (
  `id`   INT AUTO_INCREMENT PRIMARY KEY,
  `size` VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        } else {
            $sqls[] = "CREATE TABLE IF NOT EXISTS `$sizedb` (
  `id`   INT AUTO_INCREMENT PRIMARY KEY,
  `size` VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        }

        $sqls[] = "CREATE TABLE IF NOT EXISTS `$typedb` (
  `id`   INT AUTO_INCREMENT PRIMARY KEY,
  `type` VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

        $sqls[] = "CREATE TABLE IF NOT EXISTS `exchange_$cat` (
  `id`         INT AUTO_INCREMENT PRIMARY KEY,
  `{$name_col}` VARCHAR(255) NOT NULL,
  `size`       VARCHAR(255) NOT NULL,
  `quantity`   INT NOT NULL,
  `user_id`    INT NOT NULL,
  `reason`     VARCHAR(500) DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
    }

    return $sqls;
}

function stock_schema_seed_categories(): array
{
    return [
        "INSERT IGNORE INTO categories (slug,label,icon,sort_order,enabled,default_image,page_folder,file_prefix,add_file)
         VALUES
         ('jeans','Jeans','fas fa-scroll',1,1,'defaultjeans.jpg','price_calculator','jeans','add_single_jeans.php'),
         ('shoes','Shoes','fas fa-shoe-prints',2,1,'defaultshoes.jpg','shoe','shoes','add_shoes.php'),
         ('top','Top','fas fa-tshirt',3,1,'defaulttop.jpg','top','top','add_top.php'),
         ('complete','Complete','fas fa-box-open',4,1,'defaultcomplete.jpg','complete','complete','add_complete.php'),
         ('accessory','Accessory','fas fa-gem',5,1,'defaultaccessory.jpg','accessory','accessory','add_accessory.php'),
         ('wig','Wig','fas fa-hat-wizard',6,1,'defaultwig.jpg','wig','wig','add_wig.php'),
         ('cosmetics','Cosmetics','fas fa-spa',7,1,'defaultcosmetics.jpg','cosmetics','cosmetics','add_cosmetics.php')",

        "INSERT IGNORE INTO d_constants (name, db, date) VALUES
         ('Jeans Size','jeansdb',NOW()),
         ('Jeans Size 2','jeansdb2',NOW()),
         ('Shoes','shoesdb',NOW()),
         ('Shoes Type','shoe_type_db',NOW()),
         ('Top','topdb',NOW()),
         ('Top Type','top_type_db',NOW()),
         ('Complete','completedb',NOW()),
         ('Complete Type','complete_type_db',NOW()),
         ('Accessory','accessorydb',NOW()),
         ('Accessory Type','accessory_type_db',NOW()),
         ('Wig','wigdb',NOW()),
         ('Wig Type','wig_type_db',NOW()),
         ('Cosmetics','cosmeticsdb',NOW()),
         ('Cosmetics Type','cosmetics_type_db',NOW())",

        "INSERT IGNORE INTO app_settings (`key`,`value`) VALUES
         ('company_name','My Shop'),
         ('store_name','Stock Hub'),
         ('currency','ETB'),
         ('currency_symbol','Br'),
         ('mod_jeans','1'),
         ('mod_shoes','1'),
         ('mod_top','1'),
         ('mod_complete','1'),
         ('mod_accessory','1'),
         ('mod_wig','1'),
         ('mod_cosmetics','1')",
    ];
}
