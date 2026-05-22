import 'package:yurostock_mobile/core/config/api_config.dart';

/// Shared env for live MAMP integration tests (see tests/env.example).
class LiveTestConfig {
  static const apiBase = String.fromEnvironment(
    'TEST_API_BASE',
    defaultValue: ApiConfig.iosBase,
  );
  static const user = String.fromEnvironment('TEST_USER', defaultValue: 'masteradmin');
  static const pass = String.fromEnvironment('TEST_PASS', defaultValue: 'admin');
  static const shopSlug = String.fromEnvironment('TEST_SHOP_SLUG', defaultValue: 'testshop');
}
