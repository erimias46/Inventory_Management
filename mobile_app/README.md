# Yurostock Mobile

Flutter app for Yurostock inventory and sales, connected to the PHP REST API.

## Setup

1. Start MAMP and ensure the `stock` database is running.
2. Verify API health: `http://127.0.0.1:8888/stock/api/v1/index.php/health`
3. In the app, open **API Settings** and set the base URL:
   - Android emulator: `http://10.0.2.2:8888/stock/api/v1/index.php`
   - iOS simulator: `http://127.0.0.1:8888/stock/api/v1/index.php`
   - Production: `https://inventory.yurostock.com/api/v1`

## Run

```bash
cd mobile_app
flutter pub get
flutter run
```

## Login

Use the same credentials as the web app (e.g. `masteradmin` / `admin` or `sales` / `123` from backup SQL).

## Features

- **Sales**: multi-sale POS, all sales, search, delivery, verify queue
- **Admin**: dashboard charts, category inventory, users, customers
