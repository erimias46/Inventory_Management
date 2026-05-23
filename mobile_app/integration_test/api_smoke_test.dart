import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:yurostock_mobile/core/config/api_config.dart';
import 'package:yurostock_mobile/core/providers/app_providers.dart';

import 'support/binding.dart';
import 'support/live_config.dart';

/// Live API smoke — requires MAMP + `php tests/fixtures/setup_test_shop.php`
///
/// Run with integration flag:
///   RUN_INTEGRATION=1 ./tests/run_all.sh
/// Or:
///   cd mobile_app && flutter test integration_test
void main() {
  configureIntegrationTestBinding();

  test('login and load dashboard overview via API', () async {
    await ApiConfig.setBaseUrl(LiveTestConfig.apiBase);

    final container = ProviderContainer();
    addTearDown(container.dispose);

    final auth = container.read(authRepositoryProvider);
    final admin = container.read(adminRepositoryProvider);
    final sales = container.read(salesRepositoryProvider);
    final ops = container.read(opsRepositoryProvider);

    try {
      final appUser = await auth.login(
        LiveTestConfig.user,
        LiveTestConfig.pass,
        shopSlug: LiveTestConfig.shopSlug,
      );
      expect(appUser.userName, LiveTestConfig.user);

      final overview = await admin.dashboardOverview(period: '30');
      expect(overview['kpis'], isNotNull);
      expect(overview['today'], isNotNull);

      final banks = await sales.banks();
      expect(banks.where((b) => b == 'CBE').length, lessThanOrEqualTo(1));

      final types = await ops.allProductTypes();
      expect(types, isA<List<Map<String, dynamic>>>());
    } catch (e) {
      fail(
        'Live API smoke failed (${LiveTestConfig.apiBase}): $e\n'
        'Ensure MAMP is running and run: php tests/fixtures/setup_test_shop.php',
      );
    }
  });
}
