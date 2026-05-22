import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:integration_test/integration_test.dart';
import 'package:yurostock_mobile/core/config/api_config.dart';
import 'package:yurostock_mobile/core/providers/app_providers.dart';

/// Live API smoke — requires MAMP + `php tests/fixtures/setup_test_shop.php`
///
/// Run with integration flag:
///   RUN_INTEGRATION=1 ./tests/run_all.sh
/// Or:
///   cd mobile_app && flutter test integration_test
void main() {
  IntegrationTestWidgetsFlutterBinding.ensureInitialized();

  const apiBase = String.fromEnvironment(
    'TEST_API_BASE',
    defaultValue: ApiConfig.iosBase,
  );
  const user = String.fromEnvironment('TEST_USER', defaultValue: 'masteradmin');
  const pass = String.fromEnvironment('TEST_PASS', defaultValue: 'admin');
  const shopSlug = String.fromEnvironment('TEST_SHOP_SLUG', defaultValue: 'testshop');

  test('login and load dashboard overview via API', () async {
    await ApiConfig.setBaseUrl(apiBase);

    final container = ProviderContainer();
    addTearDown(container.dispose);

    final auth = container.read(authRepositoryProvider);
    final admin = container.read(adminRepositoryProvider);

    try {
      final appUser = await auth.login(user, pass, shopSlug: shopSlug);
      expect(appUser.userName, user);

      final overview = await admin.dashboardOverview(period: '30');
      expect(overview['kpis'], isNotNull);
      expect(overview['today'], isNotNull);
    } catch (e) {
      fail(
        'Live API smoke failed ($apiBase): $e\n'
        'Ensure MAMP is running and run: php tests/fixtures/setup_test_shop.php',
      );
    }
  });
}
