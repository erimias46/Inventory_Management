<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/tests/_load_env.php';
require_once dirname(__DIR__) . '/include/db_master.php';
require_once dirname(__DIR__) . '/include/helpers.php';
require_once dirname(__DIR__) . '/api/v1/Response.php';
require_once dirname(__DIR__) . '/services/mobile/AuthService.php';
require_once dirname(__DIR__) . '/services/mobile/DashboardService.php';
require_once dirname(__DIR__) . '/services/mobile/SaleService.php';
require_once dirname(__DIR__) . '/services/mobile/ProductService.php';
require_once __DIR__ . '/Unit/TestDatabase.php';

tests_load_env();
