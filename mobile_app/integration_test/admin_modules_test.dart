import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:yurostock_mobile/core/router/app_router.dart';
import 'package:yurostock_mobile/features/admin/dashboard_screen.dart';
import 'package:yurostock_mobile/features/admin/inventory_list_screen.dart';
import 'package:yurostock_mobile/features/admin/users_screen.dart';
import 'package:yurostock_mobile/features/admin/customers_screen.dart';
import 'package:yurostock_mobile/features/admin/categories_screen.dart';

import 'support/binding.dart';
import 'support/test_harness.dart';

void main() {
  configureIntegrationTestBinding();

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
      await IntegrationHarness.waitUntil(tester, () => find.text('Profit').evaluate().isNotEmpty);

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
      await IntegrationHarness.waitForScreenLoad(tester);

      for (final label in ['7d', '30d', 'Today']) {
        if (find.text(label).evaluate().isNotEmpty) {
          await tester.tap(find.text(label).first);
          await IntegrationHarness.settle(tester);
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
      await IntegrationHarness.waitUntil(
        tester,
        () => find.textContaining('Test Jean').evaluate().isNotEmpty,
      );
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
      await IntegrationHarness.waitUntil(
        tester,
        () => find.textContaining('Test Shoe').evaluate().isNotEmpty,
      );
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
      await IntegrationHarness.waitUntil(
        tester,
        () => find.textContaining('masteradmin').evaluate().isNotEmpty,
      );
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
      await IntegrationHarness.waitForScreenLoad(tester);
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
      await IntegrationHarness.waitForScreenLoad(tester);
      IntegrationHarness.assertNoFrameworkError(tester);
    });

    testWidgets('admin shell tabs: Dashboard, Inventory, More', (tester) async {
      final container = await IntegrationHarness.requireLogin();
      addTearDown(container.dispose);

      await IntegrationHarness.pumpApp(tester, container);
      expect(find.text('Dashboard'), findsWidgets);

      await tester.tap(find.text('Inventory'));
      await IntegrationHarness.settle(tester);

      await tester.tap(find.text('More'));
      await IntegrationHarness.settle(tester, cap: const Duration(seconds: 5));
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
      await IntegrationHarness.settle(tester, cap: const Duration(seconds: 10));
      router.go('/admin/tools');
      await IntegrationHarness.settle(tester, cap: const Duration(seconds: 10));
      IntegrationHarness.assertNoFrameworkError(tester);
    });
  });
}
