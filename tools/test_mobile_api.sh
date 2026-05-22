#!/bin/bash
# Quick integration test: API -> MySQL
BASE="${1:-http://127.0.0.1:8888/stock/api/v1/index.php}"

echo "=== Health ==="
curl -s "$BASE/health" | head -c 120
echo ""

echo "=== Login ==="
LOGIN=$(curl -s -X POST "$BASE/auth/login" -H "Content-Type: application/json" -d '{"username":"masteradmin","password":"admin"}')
echo "$LOGIN" | head -c 200
echo ""
TOKEN=$(echo "$LOGIN" | sed -n 's/.*"token":"\([^"]*\)".*/\1/p')
if [ -z "$TOKEN" ]; then echo "LOGIN FAILED"; exit 1; fi
AUTH="Authorization: Bearer $TOKEN"

test_ep() {
  local name="$1"
  local url="$2"
  local extra="${3:-}"
  local code
  code=$(curl -s -o /tmp/api_out.json -w "%{http_code}" $extra "$url")
  local ok=$(head -c 80 /tmp/api_out.json)
  echo "[$code] $name -> $ok"
}

test_ep "products/types" "$BASE/products/types"
test_ep "products/search" "$BASE/products/search?type=jeans" -H "$AUTH"
test_ep "sales list" "$BASE/sales" -H "$AUTH"
test_ep "banks" "$BASE/banks" -H "$AUTH"
test_ep "delivery" "$BASE/delivery" -H "$AUTH"
test_ep "verify queue" "$BASE/verify/queue?type=jeans" -H "$AUTH"
test_ep "dashboard" "$BASE/dashboard/summary?period=30" -H "$AUTH"
test_ep "inventory jeans" "$BASE/inventory/jeans" -H "$AUTH"
test_ep "users" "$BASE/users" -H "$AUTH"
test_ep "customers" "$BASE/customers/manage" -H "$AUTH"

echo "=== Done ==="
