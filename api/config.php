<?php

return [
    'api_secret' => getenv('STOCK_API_SECRET') ?: 'yurostock-mobile-api-change-in-production',
    'token_ttl_days' => 30,
    'app_base_url' => getenv('STOCK_APP_BASE_URL') ?: 'http://localhost:8888/stock',
    'cors_origin' => getenv('STOCK_CORS_ORIGIN') ?: '*',
];
