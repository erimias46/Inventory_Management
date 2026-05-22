# Mobile integration tests (live MAMP)

Requires:

1. MAMP running (`http://127.0.0.1:8888/stock/api/v1/index.php`)
2. `php tests/fixtures/setup_test_shop.php`
3. Booted iOS simulator or device (`flutter devices`)

## Run

```bash
cd mobile_app

# API-only (fast, ~30s)
flutter test integration_test/api_smoke_test.dart integration_test/api_repositories_test.dart

# POS checkout (cart + bank dropdown + sale)
flutter test integration_test/pos_checkout_test.dart -d <device_id>

# Everything
flutter test integration_test/ -d <device_id>
```

Or from repo root:

```bash
RUN_INTEGRATION=1 RUN_UI_INTEGRATION=1 ./tests/run_all.sh
```

## Files

| File | Tests |
|------|--------|
| `api_smoke_test.dart` | Login, dashboard overview, banks dedupe, product types |
| `api_repositories_test.dart` | Products, sales, admin, inventory, ops, users APIs |
| `pos_checkout_test.dart` | Add to cart, checkout sheet, CBE dropdown, delivery reason, complete sale |
| `sales_modules_test.dart` | Sales tabs, search, delivery, verify, logs |
| `admin_modules_test.dart` | Dashboard KPIs, inventory, users, admin shell |
| `app_flow_test.dart` | Login → dashboard → POS |
| `screens_navigation_test.dart` | All product types, add product, admin tools |
| `support/test_harness.dart` | Shared login + POS helpers |
