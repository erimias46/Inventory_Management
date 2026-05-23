# Mobile integration tests (live MAMP)

Requires:

1. MAMP running (`http://127.0.0.1:8888/stock/api/v1/index.php`)
2. `php tests/fixtures/setup_test_shop.php`
3. Booted iOS simulator or device (`flutter devices`)

## Run

Prefer the script (runs one file at a time, 5m timeout, avoids zsh paste issues):

```bash
cd mobile_app
./run_tests.sh                                    # unit/widget only
RUN_INTEGRATION=1 ./run_tests.sh                  # + API integration
RUN_INTEGRATION=1 RUN_UI_INTEGRATION=1 ./run_tests.sh   # + simulator UI
```

Or from repo root:

```bash
RUN_INTEGRATION=1 RUN_UI_INTEGRATION=1 ./tests/run_all.sh
```

Do **not** run `flutter test integration_test/` as a single command — it starts concurrent Xcode builds and can hang for hours.

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
