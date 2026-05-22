import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:integration_test/integration_test.dart';
import 'package:yurostock_mobile/core/router/app_router.dart';
import 'package:yurostock_mobile/features/admin/dashboard_screen.dart';
import 'package:yurostock_mobile/features/admin/inventory_list_screen.dart';
import 'package:yurostock_mobile/features/admin/users_screen.dart';
import 'package:yurostock_mobile/features/admin/customers_screen.dart';
import 'package:yurostock_mobile/features/admin/categories_screen.dart';

import 'support/test_harness.dart';

void main() {
  IntegrationTestWidgetsFlutterBinding.ensureInitialized();

  group('Admin modules', () {
    testWidgets('dashboard shows KPI cards from live API', (tester) async {
      final container = await IntegrationHarness.requireLogin();
      addTearDown(container.dispose);

      await tester.pumpWidget(
        UncontrolledProviderScope(
          container: container,
          child: const MaterialApp(home: DashboardScreen()),
        ),
      );
      await tester.pumpAndSettle(const Duration(seconds: 15));

      expect(find.text('Profit'), findsWidgets);
      expect(find.text('Earnings'), findsWidgets);
      IntegrationHarness.assertNoFrameworkError(tester);
    });

    testWidgets('dashboard period chips switch without crash', (tester) async {
      final container = await IntegrationHarness.requireLogin();
      addTearDown(container.dispose);

      await tester.pumpWidget(
        UncontrolledProviderScope(
          container: container,
          child: const MaterialApp(home: DashboardScreen()),
        ),
      );
      await tester.pumpAndSettle(const Duration(seconds: 12));

      for (final label in ['7d', '30d', 'Today']) {
        if (find.text(label).evaluate().isNotEmpty) {
          await tester.tap(find.text(label).first);
          await tester.pumpAndSettle(const Duration(seconds: 8));
        }
      }
      IntegrationHarness.assertNoFrameworkError(tester);
    });

    testWidgets('inventory jeans lists Test Jean', (tester) async {
      final container = await IntegrationHarness.requireLogin();
      addTearDown(container.dispose);

      await tester.pumpWidget(
        UncontrolledProviderScope(
          container: container,
          child: const MaterialApp(home: InventoryListScreen(type: 'jeans')),
        ),
      );
      await tester.pumpAndSettle(const Duration(seconds: 12));
      expect(find.textContaining('Test Jean'), findsWidgets);
      IntegrationHarness.assertNoFrameworkError(tester);
    });

    testWidgets('inventory shoes lists Test Shoe', (tester) async {
      final container = await IntegrationHarness.requireLogin();
      addTearDown(container.dispose);

      await tester.pumpWidget(
        UncontrolledProviderScope(
          container: container,
          child: const MaterialApp(home: InventoryListScreen(type: 'shoes')),
        ),
      );
      await tester.pumpAndSettle(const Duration(seconds: 12));
      expect(find.textContaining('Test Shoe'), findsWidgets);
      IntegrationHarness.assertNoFrameworkError(tester);
    });

    testWidgets('users screen loads staff list', (tester) async {
      final container = await IntegrationHarness.requireLogin();
      addTearDown(container.dispose);

      await tester.pumpWidget(
        UncontrolledProviderScope(
          container: container,
          child: const MaterialApp(home: UsersScreen()),
        ),
      );
      await tester.pumpAndSettle(const Duration(seconds: 10));
      expect(find.textContaining('masteradmin'), findsWidgets);
      IntegrationHarness.assertNoFrameworkError(tester);
    });

    testWidgets('customers screen loads', (tester) async {
      final container = await IntegrationHarness.requireLogin();
      addTearDown(container.dispose);

      await tester.pumpWidget(
        UncontrolledProviderScope(
          container: container,
          child: const MaterialApp(home: CustomersScreen()),
        ),
      );
      await tester.pumpAndSettle(const Duration(seconds: 10));
      IntegrationHarness.assertNoFrameworkError(tester);
    });

    testWidgets('categories screen loads', (tester) async {
      final container = await IntegrationHarness.requireLogin();
      addTearDown(container.dispose);

      await tester.pumpWidget(
        UncontrolledProviderScope(
          container: container,
          child: const MaterialApp(home: CategoriesScreen()),
        ),
      );
      await tester.pumpAndSettle(const Duration(seconds: 10));
      IntegrationHarness.assertNoFrameworkError(tester);
    });

    testWidgets('admin shell tabs: Dashboard, Inventory, More', (tester) async {
      final container = await IntegrationHarness.requireLogin();
      addTearDown(container.dispose);

      await IntegrationHarness.pumpApp(tester, container);
      expect(find.text('Dashboard'), findsWidgets);

      await tester.tap(find.text('Inventory'));
      await tester.pumpAndSettle(const Duration(seconds: 8));

      await tester.tap(find.text('More'));
      await tester.pumpAndSettle(const Duration(seconds: 5));
      expect(find.text('Operations'), findsOneWidget);
      IntegrationHarness.assertNoFrameworkError(tester);
    });

    testWidgets('admin tools route loads', (tester) async {
      final container = await IntegrationHarness.requireLogin();
      addTearDown(container.dispose);

      final router = container.read(routerProvider);
      await tester.pumpWidget(
        UncontrolledProviderScope(
          container: container,
          child: MaterialApp.router(routerConfig: router),
        ),
      );
      await tester.pumpAndSettle(const Duration(seconds: 6));
      router.go('/admin/tools');
      await tester.pumpAndSettle(const Duration(seconds: 8));
      IntegrationHarness.assertNoFrameworkError(tester);
    });
  });
}
