import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:integration_test/integration_test.dart';
import 'package:yurostock_mobile/core/router/app_router.dart';
import 'package:yurostock_mobile/features/sales/all_sales_screen.dart';
import 'package:yurostock_mobile/features/sales/multi_search_screen.dart';
import 'package:yurostock_mobile/features/sales/delivery_screen.dart';
import 'package:yurostock_mobile/features/sales/verify_screen.dart';
import 'package:yurostock_mobile/features/sales/sales_logs_screen.dart';
import 'package:yurostock_mobile/features/sales/sale_item_log_screen.dart';
import 'package:yurostock_mobile/features/sales/products_in_log_screen.dart';

import 'support/test_harness.dart';

void main() {
  IntegrationTestWidgetsFlutterBinding.ensureInitialized();

  group('Sales modules', () {
    testWidgets('sales shell tabs: POS, Sales list, Search', (tester) async {
      final container = await IntegrationHarness.requireLogin();
      addTearDown(container.dispose);

      await IntegrationHarness.goToSalesPos(tester, container);
      expect(find.text('Point of Sale'), findsWidgets);

      await IntegrationHarness.openSalesTab(tester, 'Sales');
      expect(tester.takeException(), isNull);

      await IntegrationHarness.openSalesTab(tester, 'Search');
      expect(find.text('All categories'), findsOneWidget);
      expect(find.text('Multi-size'), findsOneWidget);
    });

    testWidgets('AllSalesScreen loads from API', (tester) async {
      final container = await IntegrationHarness.requireLogin();
      addTearDown(container.dispose);

      await tester.pumpWidget(
        UncontrolledProviderScope(
          container: container,
          child: const MaterialApp(home: AllSalesScreen()),
        ),
      );
      await tester.pumpAndSettle(const Duration(seconds: 12));
      IntegrationHarness.assertNoFrameworkError(tester);
    });

    testWidgets('MultiSearchScreen search-all tab', (tester) async {
      final container = await IntegrationHarness.requireLogin();
      addTearDown(container.dispose);

      await tester.pumpWidget(
        UncontrolledProviderScope(
          container: container,
          child: const MaterialApp(home: MultiSearchScreen()),
        ),
      );
      await tester.pumpAndSettle(const Duration(seconds: 10));

      await tester.enterText(find.byType(TextField).first, 'Test');
      await tester.tap(find.text('Go'));
      await tester.pumpAndSettle(const Duration(seconds: 10));
      IntegrationHarness.assertNoFrameworkError(tester);
    });

    testWidgets('MultiSearchScreen multi-size tab', (tester) async {
      final container = await IntegrationHarness.requireLogin();
      addTearDown(container.dispose);

      await tester.pumpWidget(
        UncontrolledProviderScope(
          container: container,
          child: const MaterialApp(home: MultiSearchScreen()),
        ),
      );
      await tester.pumpAndSettle(const Duration(seconds: 8));
      await tester.tap(find.text('Multi-size'));
      await tester.pumpAndSettle();

      await tester.enterText(find.byType(TextField).first, 'M');
      await tester.tap(find.text('Find products with all sizes'));
      await tester.pumpAndSettle(const Duration(seconds: 10));
      IntegrationHarness.assertNoFrameworkError(tester);
    });

    testWidgets('DeliveryScreen loads', (tester) async {
      final container = await IntegrationHarness.requireLogin();
      addTearDown(container.dispose);

      await tester.pumpWidget(
        UncontrolledProviderScope(
          container: container,
          child: const MaterialApp(home: DeliveryScreen()),
        ),
      );
      await tester.pumpAndSettle(const Duration(seconds: 10));
      IntegrationHarness.assertNoFrameworkError(tester);
    });

    testWidgets('VerifyScreen jeans queue loads', (tester) async {
      final container = await IntegrationHarness.requireLogin();
      addTearDown(container.dispose);

      await tester.pumpWidget(
        UncontrolledProviderScope(
          container: container,
          child: const MaterialApp(home: VerifyScreen()),
        ),
      );
      await tester.pumpAndSettle(const Duration(seconds: 10));
      IntegrationHarness.assertNoFrameworkError(tester);
    });

    testWidgets('Sales logs screens load', (tester) async {
      final container = await IntegrationHarness.requireLogin();
      addTearDown(container.dispose);

      for (final screen in [
        const SalesLogsScreen(),
        const SaleItemLogScreen(),
        const ProductsInLogScreen(),
      ]) {
        await tester.pumpWidget(
          UncontrolledProviderScope(
            container: container,
            child: MaterialApp(home: screen),
          ),
        );
        await tester.pumpAndSettle(const Duration(seconds: 10));
        IntegrationHarness.assertNoFrameworkError(tester);
      }
    });

    testWidgets('category POS route jeans', (tester) async {
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
      router.go('/sales/category/jeans');
      await tester.pumpAndSettle(const Duration(seconds: 15));
      expect(find.textContaining('Jean'), findsWidgets);
      IntegrationHarness.assertNoFrameworkError(tester);
    });
  });
}
