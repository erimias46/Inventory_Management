import 'package:flutter_test/flutter_test.dart';
import 'package:integration_test/integration_test.dart';
import 'package:yurostock_mobile/core/providers/app_providers.dart';

import 'support/test_harness.dart';

/// Deep API coverage through mobile repositories (no UI).
void main() {
  IntegrationTestWidgetsFlutterBinding.ensureInitialized();

  group('Repository API coverage', () {
    testWidgets('products endpoints', (tester) async {
      final container = await IntegrationHarness.requireLogin();
      addTearDown(container.dispose);
      final sales = container.read(salesRepositoryProvider);
      final types = await sales.productTypes();
      expect(types, isNotEmpty);

      final search = await sales.searchProducts('jeans', q: 'Test');
      expect(search, isNotEmpty);

      final all = await sales.searchAllProducts(q: 'Test');
      expect(all, isA<List>());

      final multi = await sales.searchMulti('jeans', ['M', 'L']);
      expect(multi, isA<List>());

      final sizes = await sales.productSizes('jeans', 'Test Jean');
      expect(sizes['sizes'], isA<List>());

      final price = await sales.productPrice('jeans', 'Test Jean', 'M');
      expect(price, greaterThan(0));
    });

    testWidgets('sales endpoints', (tester) async {
      final container = await IntegrationHarness.requireLogin();
      addTearDown(container.dispose);
      final sales = container.read(salesRepositoryProvider);
      final list = await sales.listSales();
      expect(list, isA<List<Map<String, dynamic>>>());

      final logs = await sales.multiSaleLogs(limit: 5);
      expect(logs, isA<List>());

      final deliveries = await sales.listDeliveries();
      expect(deliveries, isA<List>());
    });

    testWidgets('admin dashboard endpoints', (tester) async {
      final container = await IntegrationHarness.requireLogin();
      addTearDown(container.dispose);
      final admin = container.read(adminRepositoryProvider);
      final overview = await admin.dashboardOverview(period: '30');
      expect(overview['kpis'], isNotNull);
      expect(overview['today'], isNotNull);
      expect(overview['by_category'], isNotNull);

      final daily = await admin.dailySales(month: DateTime.now().month, year: DateTime.now().year);
      expect(daily['series'], isA<List>());
    });

    testWidgets('inventory and ops endpoints', (tester) async {
      final container = await IntegrationHarness.requireLogin();
      addTearDown(container.dispose);
      final admin = container.read(adminRepositoryProvider);
      final ops = container.read(opsRepositoryProvider);

      final inv = await admin.inventory('jeans');
      expect(inv, isNotEmpty);

      final types = await admin.inventoryTypes('jeans');
      expect(types, isA<List>());

      final refunds = await ops.refundLogs('jeans');
      expect(refunds, isA<List>());

      final exchanges = await ops.exchangeLogs('jeans');
      expect(exchanges, isA<List>());

      final productTypes = await ops.allProductTypes();
      expect(productTypes, isA<List>());

      final settings = await ops.settings();
      expect(settings, isA<Map>());
    });

    testWidgets('users and banks', (tester) async {
      final container = await IntegrationHarness.requireLogin();
      addTearDown(container.dispose);
      final admin = container.read(adminRepositoryProvider);
      final sales = container.read(salesRepositoryProvider);

      final users = await admin.users();
      expect(users, isNotEmpty);

      final keys = await admin.userModuleKeys();
      expect(keys, isNotEmpty);

      final banks = await sales.banks();
      expect(banks.where((b) => b == 'CBE').length, lessThanOrEqualTo(1));
    });

    testWidgets('verify queue', (tester) async {
      final container = await IntegrationHarness.requireLogin();
      addTearDown(container.dispose);
      final sales = container.read(salesRepositoryProvider);
      final queue = await sales.verifyQueue('jeans');
      expect(queue, isA<List>());
    });
  });
}
