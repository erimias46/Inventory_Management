# Web regression checklist

Run on **test shop** (`testshop` / `stock_test`) or staging before production release. Check each box.

## Auth and multi-shop

- [ ] `login.php` — shop list loads from `stock_master`
- [ ] Login shop A vs shop B shows different inventory
- [ ] `pages/superadmin/` — list/create/edit shops (if used)

## Dashboard (`index2.php`)

- [ ] Profit / Earnings / Quantity / Sales cards respond to period dropdowns
- [ ] Daily sales chart — change month and year
- [ ] Store activity: in-store, delivery, exchange, refund counts
- [ ] Bank transactions summary
- [ ] Top 10 best sellers list
- [ ] Stock summary + recent products
- [ ] Monthly table matches year filter
- [ ] Totals roughly match `GET /dashboard/overview?period=30` for same shop

## Sales (`pages/sale/`)

- [ ] Multi-sale (`multi.php`) — shop checkout completes
- [ ] Multi-sale — delivery requires deliverer/reason
- [ ] Per-category sale pages (jeans, shoes, …)
- [ ] Refund, exchange, delete, edit sale
- [ ] Sale log, products in log, multi sale logs

## Admin

- [ ] Users CRUD + module permissions (`pages/account/user3.php`)
- [ ] Categories settings (`pages/settings/categories.php`)
- [ ] Backup, export, constants, email subscribers

## Data integrity

- [ ] Stock decreases after active sale
- [ ] Refund restores stock where applicable
- [ ] Exchange creates linked sale record

**Tester:** _______________ **Date:** _______________
