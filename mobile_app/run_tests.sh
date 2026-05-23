#!/usr/bin/env bash
# Run mobile tests — copy/paste-safe (no comment lines for zsh).
set -euo pipefail

cd "$(dirname "$0")"

DEVICE="${FLUTTER_DEVICE:-}"
if [ -z "$DEVICE" ]; then
  DEVICE=$(flutter devices 2>/dev/null | awk '/simulator \(simulator\)|mobile\)/{print $NF; exit}' | tr -d '()' || true)
fi
DEVICE_ARGS=()
if [ -n "$DEVICE" ]; then
  DEVICE_ARGS=(-d "$DEVICE")
  echo "Device: $DEVICE"
fi

DART_DEFINES=(
  --dart-define=TEST_API_BASE="${TEST_API_BASE:-http://127.0.0.1:8888/stock/api/v1/index.php}"
  --dart-define=TEST_USER="${TEST_USER:-masteradmin}"
  --dart-define=TEST_PASS="${TEST_PASS:-admin}"
  --dart-define=TEST_SHOP_SLUG="${TEST_SHOP_SLUG:-testshop}"
)

run_integration_file() {
  local file="$1"
  echo ""
  echo "==> integration: $file"
  flutter test "$file" "${DEVICE_ARGS[@]}" "${DART_DEFINES[@]}" --timeout=5m
}

echo "==> Flutter unit + widget tests"
flutter test test/

if [ "${RUN_INTEGRATION:-0}" != "1" ]; then
  echo ""
  echo "Skipping integration (set RUN_INTEGRATION=1 to include live API/UI tests)."
  exit 0
fi

echo ""
echo "==> Flutter API integration (no full app rebuild per file when possible)"
run_integration_file integration_test/api_smoke_test.dart
run_integration_file integration_test/api_repositories_test.dart

if [ "${RUN_UI_INTEGRATION:-0}" = "1" ]; then
  echo ""
  echo "==> Flutter UI integration (one file at a time — avoids Xcode concurrent build)"
  for f in \
    integration_test/pos_checkout_test.dart \
    integration_test/screens_navigation_test.dart \
    integration_test/sales_modules_test.dart \
    integration_test/admin_modules_test.dart \
    integration_test/app_flow_test.dart; do
    run_integration_file "$f"
  done
else
  echo ""
  echo "Tip: RUN_UI_INTEGRATION=1 RUN_INTEGRATION=1 ./run_tests.sh for simulator UI tests"
fi

echo ""
echo "All requested mobile tests passed."
