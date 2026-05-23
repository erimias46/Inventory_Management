#!/usr/bin/env bash
# Run full local test suite. Exit non-zero on any failure.
set -euo pipefail

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
cd "$ROOT"

echo "==> Yurostock test suite"
echo ""

PHP_BIN="${PHP_BIN:-php}"
if ! command -v "$PHP_BIN" &>/dev/null; then
  for candidate in \
    /Applications/MAMP/bin/php/php8.3.28/bin/php \
    /Applications/MAMP/bin/php/php8.4.15/bin/php \
    /Applications/MAMP/bin/php/php7.4.33/bin/php \
    /usr/local/bin/php; do
    if [ -x "$candidate" ]; then
      PHP_BIN="$candidate"
      break
    fi
  done
fi

if ! command -v "$PHP_BIN" &>/dev/null; then
  echo "ERROR: php not found. Set PHP_BIN or install MAMP PHP."
  exit 1
fi

echo "Using PHP: $PHP_BIN"
echo ""

echo "==> 1/4 Test database setup"
"$PHP_BIN" tests/fixtures/setup_test_shop.php
echo ""

if [ ! -f phpunit.phar ]; then
  echo "==> Downloading PHPUnit phar"
  curl -sL https://phar.phpunit.de/phpunit-10.phar -o phpunit.phar
  chmod +x phpunit.phar
fi

echo "==> 2/4 API integration tests"
"$PHP_BIN" tests/api/run.php
echo ""

if [ -f phpunit.phar ]; then
  echo "==> 3/4 PHP unit tests"
  "$PHP_BIN" phpunit.phar -c phpunit.xml
  echo ""
else
  echo "==> 3/4 PHP unit tests SKIPPED (phpunit.phar missing)"
  echo ""
fi

echo "==> 4/4 Flutter unit + widget tests"
if command -v flutter &>/dev/null; then
  (cd mobile_app && flutter test test/)
  echo ""
  if [ -d mobile_app/integration_test ] && [ "${RUN_INTEGRATION:-0}" = "1" ]; then
    if [ "${RUN_UI_INTEGRATION:-0}" = "1" ]; then
      echo "==> 6/6 Flutter UI integration (one file at a time — use mobile_app/run_tests.sh)"
      (cd mobile_app && RUN_INTEGRATION=1 RUN_UI_INTEGRATION=1 ./run_tests.sh)
    else
      echo "==> 5/5 Flutter API integration (live MAMP)"
      (cd mobile_app && RUN_INTEGRATION=1 ./run_tests.sh)
      echo "Tip: RUN_UI_INTEGRATION=1 RUN_INTEGRATION=1 ./tests/run_all.sh for full on-device UI flows"
    fi
  else
    echo "Tip: RUN_INTEGRATION=1 ./tests/run_all.sh for mobile API integration against MAMP"
  fi
else
  echo "WARN: flutter not in PATH — skip Dart tests"
fi

echo ""
echo "All automated suites passed."
echo "Complete manual checklists:"
echo "  - tests/checklists/WEB_REGRESSION.md"
echo "  - tests/checklists/MOBILE_REGRESSION.md"
