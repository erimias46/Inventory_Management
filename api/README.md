# Yurostock REST API v1

Mobile-ready JSON API for the Yurostock inventory system.

## Base URL

- Local (MAMP, no mod_rewrite): `http://127.0.0.1:8888/stock/api/v1/index.php`
- Health: `GET .../index.php/health`

## Authentication

```http
POST /auth/login
Content-Type: application/json

{"username":"masteradmin","password":"admin"}
```

Response:

```json
{"ok":true,"data":{"token":"...","user":{"id":25,"user_name":"masteradmin",...}}}
```

Use header on protected routes:

```http
Authorization: Bearer <token>
```

## Key endpoints

| Method | Path | Description |
|--------|------|-------------|
| GET | /products/types | Product categories |
| GET | /products/names?type=jeans | Product names |
| GET | /products/sizes?type=&name= | Sizes and quantities |
| POST | /sales/multi | Create multi-line sale |
| GET | /sales | List active sales |
| GET | /sales/{type}/{id} | Sale detail |
| POST | /sales/{type}/{id}/refund | Refund sale |
| GET | /delivery | Pending deliveries |
| GET | /verify/queue?type=wig | Verify queue |
| POST | /verify/{type}/{id}/approve | Approve verify item |
| GET | /dashboard/summary?period=30 | Admin KPIs |
| GET | /inventory/{type} | Stock list |

Permissions mirror web `user.module` JSON flags.
