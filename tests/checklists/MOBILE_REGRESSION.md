# Mobile regression checklist

Device/simulator pointed at MAMP API (`http://127.0.0.1:8888/stock/api/v1/index.php` or `10.0.2.2` on Android emulator).

## Settings

- [ ] Save API URL and test connection — fully connected
- [ ] My profile — update name/password
- [ ] Store settings (masteradmin only)

## Login

- [ ] Shop picker when multiple shops exist
- [ ] Wrong password shows error
- [ ] Shop name appears in sales/admin app bar after login

## POS

- [ ] Category chips match shop categories (Settings → Categories on web)
- [ ] Shop checkout completes
- [ ] Delivery checkout requires delivery person
- [ ] Category Sales (More) lists all categories

## Sales tab

- [ ] All sales list and category filters
- [ ] Sale detail — refund, exchange, delete, edit (if permitted)

## Search

- [ ] All categories search tab
- [ ] Multi-size search tab

## More tab

- [ ] Sale log, multi sale logs, products in, all product types
- [ ] Delivery, verify stock
- [ ] Refund logs link (if module)

## Admin

- [ ] Dashboard — today, KPIs, activity, chart, categories, banks, top products, stock, monthly
- [ ] Inventory categories grid
- [ ] Users list / add / edit / delete
- [ ] Operations hub — backup, constants, export, etc.

## Permissions

- [ ] Log in as `testuser` — restricted tabs hidden (no user admin, etc.)

## Cross-check with web

- [ ] Sale created on web appears in mobile All Sales
- [ ] Sale created on mobile appears on web

**Tester:** _______________ **Date:** _______________ **Build:** _______________
