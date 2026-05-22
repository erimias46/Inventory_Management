# Yurostock test suite

Local-only automated and manual tests for the PHP API, web app, and Flutter mobile client.

## Quick start

```bash
# 1. One-time: create test shop database (MAMP MySQL running)
php tests/fixtures/setup_test_shop.php

# 2. Copy environment (optional overrides)
cp tests/env.example tests/.env.test

# 3. Run everything
./tests/run_all.sh
```

## Environment variables

| Variable | Default | Purpose |
|----------|---------|---------|
| `TEST_API_BASE` | `http://127.0.0.1:8888/stock/api/v1/index.php` | REST API entry |
| `TEST_MYSQL_HOST` | `localhost` | MySQL host |
| `TEST_MYSQL_USER` | `root` | MySQL user |
| `TEST_MYSQL_PASS` | `root` | MySQL password |
| `TEST_SHOP_DB` | `stock_test` | Isolated shop database |
| `TEST_SHOP_SLUG` | `testshop` | Shop slug in `stock_master` |
| `TEST_USER` | `masteradmin` | Full-access API user |
| `TEST_PASS` | `admin` | Password (plain text, matches web) |
| `TEST_LIMITED_USER` | `testuser` | Restricted user for 403 tests |
| `TEST_LIMITED_PASS` | `testpass` | Limited user password |

Do not point tests at production data. Use `stock_test` only.

## Suites

| Command | What it runs |
|---------|----------------|
| `php tests/fixtures/setup_test_shop.php` | Creates `stock_master` shop row + `stock_test` schema/seed |
| `php tests/api/run.php` | HTTP integration tests (~98 checks) including `flows.php` (sale lifecycle, refund, exchange, CRUD, validation) |
| `vendor/bin/phpunit` | PHP unit tests (`tests/Unit/`) |
| `cd mobile_app && flutter test test/` | Dart unit + widget tests (23) |
| `RUN_INTEGRATION=1 ./tests/run_all.sh` | PHP/API + `api_smoke_test` + `api_repositories_test` |
| `RUN_UI_INTEGRATION=1 RUN_INTEGRATION=1 ./tests/run_all.sh` | Full mobile `integration_test/` on simulator (~35 tests) |
| `cd mobile_app && flutter test integration_test/ -d <device>` | All integration tests (see `mobile_app/integration_test/README.md`) |

## Manual regression

- [checklists/WEB_REGRESSION.md](checklists/WEB_REGRESSION.md)
- [checklists/MOBILE_REGRESSION.md](checklists/MOBILE_REGRESSION.md)

## Pre-release gate

1. `setup_test_shop.php` succeeds  
2. `./tests/run_all.sh` exits 0  
3. Both checklists signed off on `testshop`
